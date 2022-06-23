<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return view('dashboard.role.createPermission');
    }

    public function create()
    {
        //
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|regex:/^[a-zA-Z .]+$/'
        ]);
        Permission::create([
            'name' => $request->name,
            'guard_name' => 'sanctum'
        ]);
        return redirect()->back();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
