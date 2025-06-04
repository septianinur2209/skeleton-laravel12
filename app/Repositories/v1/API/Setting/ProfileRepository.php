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

    // Constructor accepts the User model and an optional ID,
    // if ID is not provided, use the currently authenticated user's ID
    public function __construct(
        protected User $model, $id = null
    ){
        $this->id = $id ?? user()->id;
    }


    // Retrieve the profile data of the user by ID
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

            // Log the error in case of exception
            Log::info('Setting - Profile - me: ' . $e->getMessage());

            return [
                500,
                [
                    'message'   => 'Failed to process data. Error: ' . $e->getMessage()
                ]
            ];

        }
        
        // Return data wrapped in ProfileResource if found successfully
        return [
            200,
            [
                'message' => 'Success to Get Data by ID.',
                'data' => new ProfileResource($data),
            ]
        ];
    }

    
    // Update user profile data (name, email, password, picture)
    final public function update($request)
    {

        // Begin a database transaction to keep data consistent
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

            // Add 'name' to input if present in request
            if ($request->has('name')) {
                $input['name'] = $request->name;
            }

            // Add 'email' to input if present in request
            if ($request->has('email')) {
                $input['email'] = $request->email;
            }

            // Hash and add 'password' to input if present in request
            if ($request->has('password')) {
                $input['password'] = bcrypt($request->password);
            }

            // Handle picture upload if a valid file is uploaded
            if ($request->hasFile('picture') && $request->file('picture')->isValid()) {

                $file = $request->file('picture');
                $extension = $file->getClientOriginalExtension();

                // Delete old picture if exists
                if (!empty($data->picture) && Storage::exists($data->picture)) {
                    Storage::delete($data->picture);
                }

                // Generate unique filename using UUID
                $filename = Str::uuid()->toString() . '.' . $extension;

                // Store file in 'avatars' folder on public disk
                $path = $file->storeAs('avatars', $filename, 'public');

                $input['picture'] = $path;

            }

            // Update the model if there are any fields to update
            if(count($input) > 0) {

                $data->update($input);

                createLog([
                    'action'        => 'Update',
                    'modul'         => 'Profile',
                    'submodul'      => 'Update',
                    'description'   => 'Update Profile: ' . $data->name
                ]);

            }

        } catch (\Exception $e) {

            // Log the error and rollback transaction if update fails
            Log::info('Setting - User - update: ' . $e->getMessage());
            
            DB::rollBack();

            return [
                500,
                [
                    'message'   => 'Failed to process data. Error: ' . $e->getMessage()
                ]
            ];

        }
        
        // Commit the transaction on success
        DB::commit();

        return [
            200,
            [
                'message' => 'Success to Update Data.',
                'data' => new ProfileResource($data),
            ]
        ];

    }

    
    // Update profile photo only
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

            // Validate if a valid image file is uploaded
            if ($request->hasFile('picture') && $request->file('picture')->isValid()) {

                $file = $request->file('picture');
                $extension = $file->getClientOriginalExtension();

                // Delete old profile photo if exists
                if (!empty($data->picture) && Storage::exists($data->picture)) {
                    Storage::delete($data->picture);
                }

                // Create a unique filename using UUID
                $filename = Str::uuid()->toString() . '.' . $extension;

                // Store new image in 'avatars' folder
                $path = $file->storeAs('avatars', $filename, 'public');

                // Update the picture path in the database
                $data->update([
                    'picture' => $path,
                ]);

                createLog([
                    'action'        => 'Update Photo',
                    'modul'         => 'Profile',
                    'submodul'      => 'Update Photo',
                    'description'   => 'Update Photo Profile: ' . $data->name
                ]);

            } else {
                
                // Return validation error if no valid image uploaded
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

    
    // Update user password only
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

            // Hash and update the password field
            $data->update(
                [
                    'password'  => bcrypt($request->password) 
                ]
            );

            createLog([
                'action'        => 'Update Password',
                'modul'         => 'Profile',
                'submodul'      => 'Update Password',
                'description'   => 'Update Password Profile: ' . $data->name
            ]);

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
