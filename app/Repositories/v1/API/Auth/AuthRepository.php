<?php

namespace App\Repositories\v1\API\Auth;

use App\Models\User;
use App\Traits\MainTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthRepository
{
    use MainTrait;

    // Handle user login attempt with email and password
    public function login($request)
    {
        
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // Check if user exists by email
        $find = User::where('email', $request->email)->first();

        if (!$find) {
            // Return 404 if user not found
            return [
                404,
                [
                    "message"   => "User Not Found"
                ]
            ];
        };

        // Attempt to create JWT token with credentials
        if (! $token = auth()->attempt($credentials)) {

            // Return 401 if authentication fails
            return [
                401,
                [
                    "message"   => "Unauthorized"
                ]
            ];

        }

        createLog([
            'action'        => 'Login',
            'modul'         => 'Auth',
            'submodul'      => 'Login',
            'description'   => 'Login User: ' . $find->name
        ]);

        // Return successful response with JWT token
        return $this->respondWithToken($token, $find);
    }

    // Get currently authenticated user info
    public function me()
    {
        
        return [
            200, 
            [
                'message'   => 'Success',
                'data'      => user(),
            ]
        ];
    }

    // Log out user and invalidate JWT token
    public function logout()
    {

        createLog([
            'action'        => 'Logout',
            'modul'         => 'Auth',
            'submodul'      => 'Logout',
            'description'   => 'Logout User: ' . user()->name
        ]);

        auth()->logout();
        JWTAuth::invalidate(JWTAuth::parseToken());

        return [
            200, 
            [
                'message'   => 'Success Logout',
                'data'      => [],
            ]
        ];
    }

    // Register a new user with provided data
    public function register($request)
    {
        try {

            User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => bcrypt($request->password),
            ]);

            createLog([
                'action'        => 'Register',
                'modul'         => 'Auth',
                'submodul'      => 'Register',
                'description'   => 'Register User: ' . $request->name
            ]);

            // Return success response after user creation
            return [
                200,
                [
                    "message"   => "Success",
                    "data"      => []
                ]
            ];
        } catch (Exception $e) {

            // Log error and return failure response
            Log::info('Auth - Register - Error : ' . $e->getMessage());
            
            return [
                $e->getCode(),
                [
                    "message"   => $e->getMessage(),
                    "data"      => []
                ]
            ];

        }
    }

    // Prepare the response containing JWT token and metadata
    protected function respondWithToken($token, $find)
    {
        $data = [
            'access_token'  => $token,
            'token_type'    => 'bearer',
            'expires_in'    => auth()->factory()->getTTL() * 60,
            'email'         => $find->email
        ];

        return [
            200, 
            [
                'message'   => 'Success',
                'data'      => $data,
            ]
        ];

    }

    // Send a password reset link email to user
    public function sendResetLinkEmail($request)
    {

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {

            // Return success if email was sent
            return [
                200, 
                [
                    'message'   => 'Success'
                ]
            ];

        } else {

            // Return error if email sending failed
            return [
                500,
                [
                    "message"   => 'Failed to send email',
                    "data"      => []
                ]
            ];
        }

    }
    
    // Reset user password using token and new password
    public function resetPassword($request)
    {

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Update the user's password
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {

            // Return success if password reset succeeded
            return [
                200, 
                [
                    'message'   => 'Success'
                ]
            ];

        } else {

            // Return error if password reset failed
            return [
                500,
                [
                    "message"   => 'Failed to reset password',
                    "data"      => []
                ]
            ];
        }
    }
}
