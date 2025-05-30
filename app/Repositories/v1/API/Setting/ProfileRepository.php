<?php

namespace App\Repositories\v1\API\Setting;

use App\Http\Resources\Setting\ProfileResource;
use App\Models\User;
use App\Traits\MainTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileRepository
{
    use MainTrait;

    protected $id;

    public function __construct(
        protected User $model, $id = null
    ){
        $this->id = $id ?? auth()->user()->id;
    }


    public function me()
    {
        try {
            $data = $this->model->find($this->id);

            if (!$data) {

                return [
                    404,
                    [
                        'message'   => 'Data not available.'
                    ]
                ];

            }

        } catch (\Exception $e) {

            Log::info('Setting - Profile - me: ' . $e->getMessage());

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
                'data' => new ProfileResource($data),
            ]
        ];
    }

    
    final public function update($request)
    {

        DB::beginTransaction();

        try {
            
            $input = [];
            $data = $this->model->find($this->id);

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
                'data' => new ProfileResource($data),
            ]
        ];

    }

    
    final public function updateProfilePhoto($request)
    {

        DB::beginTransaction();

        try {
            
            $data = $this->model->find($this->id);

            if (!$data) {
                
                return [
                    404,
                    [
                        'message'   => 'Data not available.'
                    ]
                ];

            }

            if ($request->hasFile('picture') && $request->file('picture')->isValid()) {

                $file = $request->file('picture');
                $extension = $file->getClientOriginalExtension();

                if (!empty($data->picture) && Storage::exists($data->picture)) {
                    Storage::delete($data->picture);
                }

                $filename = Str::uuid()->toString() . '.' . $extension;

                $path = $file->storeAs('avatars', $filename, 'public');

                $data->update([
                    'picture' => $path,
                ]);

            } else {
                
                return [
                    422,
                    [
                        'message' => 'No valid image uploaded.'
                    ]
                ];
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
                'data' => new ProfileResource($data),
            ]
        ];

    }

    
    final public function updatePassword($request)
    {

        DB::beginTransaction();

        try {
            
            $data = $this->model->find($this->id);

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
                    'password'  => bcrypt($request->password) 
                ]
            );

        } catch (\Exception $e) {

            Log::info('Setting - User - updatePassword: ' . $e->getMessage());
            
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
                'message' => 'Success to Update Password.',
                'data' => new ProfileResource($data),
            ]
        ];

    }
}
