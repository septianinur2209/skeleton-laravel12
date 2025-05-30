<?php

namespace App\Repositories\v1\API\Setting;

use App\Http\Resources\Setting\UserResource;
use App\Models\User;
use App\Traits\MainTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class UserRepository
{
    use MainTrait;

    public function __construct(
        protected User $model
    ){}

    
    public function query($request)
    {

        $data = $this->model->query();

        if ($request->type == 'dropdown') {

            $data->selectRaw("TRIM($request->column) AS $request->column")->distinct();

        } else {

            $data->select(
                'id',
                'name',
                'email',
                'picture',
            );

        }

        return $data;

    }

    
    public function whereData($request)
    {
        
        $filteredData = $this->query($request);

        try {

            $search = $request->search;
            
            if (isset($search) && ($request->type != 'dropdown')) {

                $filteredData = $filteredData->where(function ($query) use ($search) {

                    $query->where('name', 'ilike', '%' . $search . '%')
                        ->orWhere('email', 'ilike', '%' . $search . '%');

                });
                
            }


            $name = $request->name;

            if ($name != null && $name != "") {

                if ($request->type == 'dropdown' && $request->column == 'name') {

                    $filteredData = $filteredData->where('name', 'ilike', '%' . $request->name . '%');

                } else {

                    $filteredData = $filteredData->whereRaw("TRIM(name) = ?", [trim($name)]);

                }

            }


            $email = $request->email;

            if ($email != null && $email != "") {

                if ($request->type == 'dropdown' && $request->column == 'email') {

                    $filteredData = $filteredData->where('email', 'ilike', '%' . $request->email . '%');

                } else {

                    $filteredData = $filteredData->whereRaw("TRIM(email) = ?", [trim($email)]);

                }

            }


            $status = $request->status;

            if ($status != null) {

                $filteredData = $filteredData->where('status', $status);
                
            }


            if ($request->type != 'dropdown') {
                
                $allowedSorts = $this->model->getFillable();

                @[$filterField, $operator] = explode(',', end(explode('|', $request->order)));

                if (request()->routeIs('setting.user.download')) {
                    
                    $filteredData =  $filteredData->orderBy('created_at', 'asc');

                } else {

                    if ($filterField && $operator && $operator != "null" && in_array($filterField, $allowedSorts)) {
                        
                        $filteredData = $filteredData->orderBy($filterField, $operator);

                    } else {
                        
                        $filteredData =  $filteredData->orderBy('created_at', 'asc');

                    }

                }

            } else if ($request->type == 'dropdown' || $request->type == 'filter') {

                $filteredData =  $filteredData->orderBy($request->column, 'asc');

            }

        } catch (\Exception $e) {

            Log::info('Setting - User - whereData: ' . $e->getMessage());

            return [
                500,
                [
                    'message'   => 'Failed to process data. Error: ' . $e->getMessage()
                ]
            ];

        }

        return [
            200,
            [
                'data'  => $filteredData
            ]
        ];

    }


    public function countedFilter($request)
    {

        list($code, $data) = $this->whereData($request);

        return $data['data']->select('id')->get();

    }


    public function show($request)
    {
        try {
            
            list($code, $data)      = $this->whereData($request);

            if ($request->type == 'dropdown') {
                
                $response = $data['data']->limit(10)->get();

                if (count($response) <= 0) {

                    return [
                        404,
                        [
                            'message'   => 'Data not available.'
                        ]
                    ];

                }

            } else {

                if ($request['length'] != -1) {
                    
                    $result = $data['data']
                        ->offset($request['start'] ?? 0)
                        ->limit($request['length'] ?? 10);

                }
                
                $result = $result->get();

                $response = array(
                    'search'          => $request->search,
                    'data'            => UserResource::collection($result) ?? [],
                    'params'          => $request->all(),
                    'recordsTotal'    => $result->count(),
                    'recordsFiltered' => count($this->countedFilter($request)) ?? 0,
                );

            }

        } catch (\Exception $e) {

            Log::info('Setting - User - show: ' . $e->getMessage());

            return [
                500,
                [
                    'message'   => 'Failed to process data. Error: ' . $e->getMessage()
                ]
            ];

        }
        
        return [
            200,
            [
                'message'   => 'Success to Get Data.',
                'data'      => $response
            ]
        ];
    }


    public function showId($id)
    {
        try {
            $data = $this->model->find($id);

            if (!$data) {

                return [
                    404,
                    [
                        'message'   => 'Data not available.'
                    ]
                ];

            }

        } catch (\Exception $e) {

            Log::info('Setting - User - showId: ' . $e->getMessage());

            return [
                500,
                [
                    'message'   => 'Failed to process data. Error: ' . $e->getMessage()
                ]
            ];

        }
        
        return [
            200,
            [
                'message' => 'Success to Get Data by ID.',
                'data' => new UserResource($data),
            ]
        ];
    }

    
    public function insert($request)
    {

        DB::beginTransaction();

        try {

            $input = [];

            if ($request->has('name')) {
                $input['name'] = $request->name;
            }

            if ($request->has('email')) {
                $input['email'] = $request->email;
            }

            if ($request->has('password')) {
                $input['password'] = bcrypt($request->password);
            }else{
                $input['password'] = bcrypt('password');
            }

            if ($request->hasFile('picture') && $request->file('picture')->isValid()) {

                $file = $request->file('picture');
                $extension = $file->getClientOriginalExtension();

                $filename = Str::uuid()->toString() . '.' . $extension;

                $path = $file->storeAs('avatars', $filename, 'public');

                $input['picture'] = $path;

            }

            if(count($input) > 0) {
                $data = $this->model->create($input);
            }

        } catch (\Exception $e) {

            Log::info('Setting - User - insert: ' . $e->getMessage());
            
            DB::rollBack();
            
            return [
                500,
                [
                    'message'   => 'Failed to process data. Error: ' . $e->getMessage()
                ]
            ];

        }

        DB::commit();

        return [
            200,
            [
                'message'   => 'Success to Insert Data.',
                'payload'   => new UserResource($data)
            ]
        ];

    }

    
    final public function update($request, $id)
    {

        DB::beginTransaction();

        try {
            
            $input = [];
            $data = $this->model->find($id);

            if (!$data) {
                
                return [
                    404,
                    [
                        'message'   => 'Data not available.'
                    ]
                ];

            }

            if ($request->has('name')) {
                $input['name'] = $request->name;
            }

            if ($request->has('email')) {
                $input['email'] = $request->email;
            }

            if ($request->has('password')) {
                $input['password'] = bcrypt($request->password);
            }

            if ($request->hasFile('picture') && $request->file('picture')->isValid()) {

                $file = $request->file('picture');
                $extension = $file->getClientOriginalExtension();

                if (!empty($data->picture) && Storage::exists($data->picture)) {
                    Storage::delete($data->picture);
                }

                $filename = Str::uuid()->toString() . '.' . $extension;

                $path = $file->storeAs('avatars', $filename, 'public');

                $input['picture'] = $path;

            }

            if(count($input) > 0) {
                $data->update($input);
            }

        } catch (\Exception $e) {

            Log::info('Setting - User - update: ' . $e->getMessage());
            
            DB::rollBack();

            return [
                500,
                [
                    'message'   => 'Failed to process data. Error: ' . $e->getMessage()
                ]
            ];

        }
        
        DB::commit();

        return [
            200,
            [
                'message' => 'Success to Update Data.',
                'payload'   => new UserResource($data)
            ]
        ];

    }

    
    final public function updateStatus($request, $id)
    {

        DB::beginTransaction();

        try {
            
            $data = $this->model->find($id);

            if (!$data) {

                return [
                    404,
                    [
                        'message'   => 'Data not available.'
                    ]
                ];

            }

            
            $data->update(
                [
                    'status' => $request->status
                ]
            );

        } catch (\Exception $e) {

            Log::info('Setting - User - updateStatus: ' . $e->getMessage());
            
            DB::rollBack();

            return [
                500,
                [
                    'message'   => 'Failed to process data. Error: ' . $e->getMessage()
                ]
            ];

        }
        
        DB::commit();

        return [
            200,
            [
                'message'   => 'Success to Update Status Data.',
                'payload'   => new UserResource($data)
            ]
        ];

    }

    final public function delete($id)
    {

        DB::beginTransaction();

        try {
            
            $data = $this->model->find($id);

            if (!$data) {

                return [
                    404,
                    [
                        'message'   => 'Data not available.'
                    ]
                ];

            }

            $data->delete();

        } catch (\Exception $e) {

            Log::info('Setting - User - delete: ' . $e->getMessage());

            DB::rollBack();

            return [
                500,
                [
                    'message'   => 'Failed to process data. Error: ' . $e->getMessage()
                ]
            ];

        }
        
        DB::commit();

        return [
            200,
            [
                'message'   => 'Success to Delete Data.'
            ]
        ];

    }

    
    public function download($request)
    {
        
        list($code, $data)= $this->whereData($request);

        try {
            
            function filterGenerator($resultData)
            {

                $index = 1;
                
                foreach ($resultData->cursor() as $item) {

                    $item->no = $index++;
                    yield $item;

                }

            }

            
            return (new FastExcel(filterGenerator($data['data'])))->download('Setting-User.xlsx', function ($item) {
                
                return [
                    'No.'               => $item->no,
                    'Name'              => $item->name,
                    'Email'             => $item->email,
                    'Status'            => $item->status ? 'active' : 'inactive',
                    'Created At'        => $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '',
                    'Created By'        => $item->created_by,
                    'Updated At'        => $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : '',
                    'Updated By'        => $item->updated_by,
                ];

            });

        } catch (\Exception $e) {

            Log::info('Setting - User - download: ' . $e->getMessage());

            throw $e;

        }

    }
}
