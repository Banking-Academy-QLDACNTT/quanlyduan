<?php

namespace App\Http\Middleware;

use App\Models\AccountPermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $permissionIds = null, $actions =  null)
    {
        
        $account_id = Auth::guard('admins')->id();

        // Chuyển chuỗi permissionId thành mảng
        $permissionIdsArray = explode(',', $permissionIds);

        // Chuyển chuỗi actions thành mảng (vd: "can_view,can_edit" -> ['can_view', 'can_edit'])
        $actionsArray = explode(',', $actions);

        foreach ($permissionIdsArray as $permissionId) {
            // Lấy quyền của người dùng với từng permissionId
            $permission = AccountPermission::where('id', $account_id)
                ->where('permissionId', $permissionId)
                ->first();

            if (!$permission) {
                abort(403, 'Bạn không có quyền truy cập.');
            }

            // Kiểm tra từng quyền
            foreach ($actionsArray as $action) {
                if (empty($permission->$action)) {
                    abort(403, "Bạn không có quyền thực hiện hành động: $action đối với PermissionId: $permissionId.");
                }
            }
        }

        return $next($request);
            
        }
}
