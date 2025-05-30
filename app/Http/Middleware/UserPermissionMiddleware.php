<?php

namespace App\Http\Middleware;

use App\Models\Master\MMenu;
use App\Models\Settings\SMenuAccess;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        $user = Auth::user();

        if (!$user) return $this->unauthorizedResponse();

        $currentPath = $this->parsePermission($request->route()->getName());

        $check_menu = MMenu::where('menu', $currentPath['group'])->first();

        if (!$check_menu) return $this->forbiddenResponse('Menu not Registered');

        $has_access = SMenuAccess::where('menu_id', $check_menu->id)
            ->where('role_id', $user->userRole->role->id)
            ->first();

        if (!$has_access) return $this->forbiddenResponse('You do not have permission to access this page');

        if (!$has_access->{$currentPath['action']}) return $this->forbiddenResponse('You do not have permission to access this page');
        
        return $next($request);

    }

    function parsePermission($permission)
    {
        $exceptions = [
            'update-status' => 'edit',
            'show-id'       => 'show',
            'download'      => 'show'
        ];

        $parts = explode('.', $permission);

        $count = count($parts);

        if ($count === 2) {

            return [
                'group'     => $parts[0],
                'action'    => array_key_exists($parts[1], $exceptions) ? $exceptions[$parts[1]] : $parts[1],
            ];

        } elseif ($count >= 3) {
            
            $group = implode('.', array_slice($parts, 0, $count - 1));

            $action = $parts[$count - 1];
            $action = array_key_exists($action, $exceptions) ? $exceptions[$action] : $action;
            
            return [
                'group'     => $group,
                'action'    => $action,
            ];

        }

        return [
            'group' => null,
            'action' => null,
        ];

    }

    /**
     * Return 401 Unauthorized response.
     */
    private function unauthorizedResponse()
    {

        return response()->json(
            [
                'code'      => 401,
                'message'   => 'Unauthorized',
                'status'    => false,
            ],
            401
        );

    }

    /**
     * Return 403 Forbidden response with custom message.
     */
    private function forbiddenResponse(string $message = "You do not have permission to access this page")
    {

        return response()->json(
            [
                'code'      => 403,
                'message'   => "Forbidden - $message",
                'status'    => false,
            ],
            403
        );

    }
}
