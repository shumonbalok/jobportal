<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
       // $this->checkPermission('role_permission.access');

        $permissions = Permission::all();
        $roles = Role::with('permissions')->get();
       // return view('dashboard.role.createRole', compact('roles', 'permissions'));
        return response()->json([
            'role_permission' => $roles,
            'permissions_all' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
//        $this->checkPermission('role_permission.create');

        $request->validate([
            'name' => 'required',
            'permissions' => 'required'
        ]);
        Role::create([
            'name' => $request->input('name'),
            'guard_name' => 'sanctum'
        ])->givePermissionTo($request->input('permissions'));

        return response()->json(['Role & Permission create Successfully']);

    }

    public function edit(Role $role)
    {
//        $this->checkPermission('role_permission.edt');

        if($role->id == 1) return back()->with('error', 'Can\'t edit this role.');

        $permissions = Permission::all();
        $roles = Role::with('permissions')->get();
        return response()->json([
            'role_permission' => $roles,
            'permissions_all' => $permissions,
        ]);
    }

    public function show(Role $role)
    {
//        $this->checkPermission('role_permission.edt');

        if($role->id == 1) return back()->with('error', 'Can\'t edit this role.');

        $permissions = Permission::all();
        $roles = Role::with('permissions')->findOrFail($role->id );
        return response()->json([
            'role_permission' => $roles,
        ]);
    }

    public function update(Request $request, Role $role)
    {
//        $this->checkPermission('role_permission.edt');

        $request->validate([
            'name' => 'required',
            'permissions' => 'required'
        ]);
        $roleApi  =  Role::findById($role->id , 'sanctum');
        if($role->id == 1) return back()->with('error', 'Can\'t edit this role.');

        $roleApi->update([
            'name' => $request->input('name')
        ]);
        $role->syncPermissions($request->input('permissions'));

        return $this->apiResponse(201, 'Role & Permission Edit Successfully');
    }

    public function destroy(Role $role)
    {
//        $this->checkPermission('role_permission.delete');

        if($role->id == 1)
        return $this->apiResponse(404, 'success', 'Role Deleted successfully.');


        $role->delete();
        return $this->apiResponse(201, 'Role Deleted successfully.');
    }

    public function roleAssign()
    {
        //$this->checkPermission('role_permission.access');


        // $roles = Role::whereKeyNot(1)->get();
        $users = User::with('roles')->whereKeyNot(1)->paginate(10);
        return response()->json([
            // 'roles' => $roles,
            'user' => $users,
        ]);

    }

    public function storeAssign(Request $request)
    {
        //$this->checkPermission('role_permission.edit');


       $user= User::findOrFail($request->user)
            ->syncRoles($request->roles );
            return $this->apiResponse(201, 'User Role  successfully.');

    }

    public function roleUser(){
        $user = auth()->user();
        $permissions = $user->getAllPermissions();
        $collection =  $permissions->map(function($permission){
            return $permission->name;
        });

        return response()->json([
            'permission' => $collection,
        ]);
    }
}
