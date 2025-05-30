<?php

namespace App\Traits;

use App\Models\Log\LogActivity;
use App\Models\Settings\SRole;
use App\Models\Notification;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait MainTrait
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendResponse($result, $message)
    {

        $response = [
            'code'      => 200,
            'message'   => $message,
            'status'    => true,
            'payload'   => $result
        ];

        return response()->json($response, 200);

    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendError($errorMessages, $code = 404)
    {

        $response = [
            'code'      => $code,
            'message'   => ( gettype($errorMessages) == 'array' ) ? implode('\n', $errorMessages) : $errorMessages,
            'status'    => false,
            'payload'    => null
        ];

        throw new HttpResponseException(response()->json($response, $code));

    }

    public function sendResponseData(
        int $statusCode = 200,
        mixed $message = 'Success',
        bool $isSuccess = true,
        mixed $data = null,
    ): JsonResponse {

        return response()->json(
            [
                'code'          => $statusCode,
                'message'       => $message,
                'status'        => $isSuccess,
                'payload'       => $data,
            ],
            $statusCode
        );
        
    }

    public function decodeJwtToken()
    {
        
        $request    = app()->make(Request::class);
        $jwtToken   = $request->bearerToken();

        $parts = explode('.', $jwtToken);

        if (count($parts) !== 3) {

            return  [
                400, 
                'Invalid JWT token '
            ];
            
        }

        $header = base64_decode($parts[0]);
        $payload = base64_decode($parts[1]);

        $headerJson = json_decode($header, true);
        $payloadJson = json_decode($payload, true);

        if (empty($headerJson) || empty($payloadJson)) {

            return  [
                400,
                'Invalid JWT token '
            ];

        }

        return  [
            200,
            $payloadJson['name']
        ];

    }

    public function responseArray($code, $message, $data): array
    {

        return [
            $code, 
            [
                'code'          => $code,
                'message'       => $message,
                'status'        => true,
                'payload'       => $data,
            ]
        ];

    }

    public function timestampFormat($data)
    {

        return date('Y-m-d H:i:s', strtotime($data));

    }

    public function recordLog($activity)
    {

        LogActivity::create($activity);

        return null;

    }

    public function pushNotification($notification)
    {

        Notification::create($notification);
        
        return true;

    }

    public function pushNotificationToAllAdmin($notification, $user = null) {

        if ($user == null) return false;
        
        $rolesAdmin = SRole::where('id', config('custom.SUPER_ADMIN_ROLE_ID', 1))->first();

        // send notification to all admin
        foreach($rolesAdmin->users as $admin) {

            if ($user->id != $admin->id) {

                $notification['target_user_id'] = $admin->id;
                $this->pushNotification($notification);

            }

        }

    }
}
