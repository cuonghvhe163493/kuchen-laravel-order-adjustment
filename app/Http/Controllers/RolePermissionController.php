<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RolePermissionController extends Controller
{
    /**
     * Hiển thị Ma trận Phân quyền (RBAC Matrix) & Danh sách Tài khoản lưu trong Database
     */
    public function index(Request $request): View
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy('module');
        $users = User::with('roles')->orderBy('id', 'asc')->get();

        return view('roles.index', [
            'roles' => $roles,
            'permissionsByModule' => $permissions,
            'users' => $users,
        ]);
    }
}
