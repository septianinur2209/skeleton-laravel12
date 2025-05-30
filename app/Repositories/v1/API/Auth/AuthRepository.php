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

    public function login($request)
    {
        
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $find = User::where('email', $request->email)->first();

        if (!$find) {
            return [
                404,
                [
                    "message"   => "User Not Found"
                ]
            ];
        };

        if (! $token = auth()->attempt($credentials)) {

            return [
                401,
                [
                    "message"   => "Unauthorized"
                ]
            ];

        }

        return $this->respondWithToken($token, $find);
    }

    public function me()
    {
        
        return [
            200, 
            [
                'message'   => 'Success',
                'data'      => auth()->user(),
            ]
        ];
    }

    public function logout()
    {
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

    public function register($request)
    {
        try {

            User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => bcrypt($request->password),
            ]);

            return [
                200,
                [
                    "message"   => "Success",
                    "data"      => []
                ]
            ];
        } catch (Exception $e) {

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

    public function sendResetLinkEmail($request)
    {

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {

            return [
                200, 
                [
                    'message'   => 'Success'
                ]
            ];

        } else {

            return [
                500,
                [
                    "message"   => 'Failed to send email',
                    "data"      => []
                ]
            ];
        }

    }
    
    public function resetPassword($request)
    {

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {

            return [
                200, 
                [
                    'message'   => 'Success'
                ]
            ];

        } else {

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
