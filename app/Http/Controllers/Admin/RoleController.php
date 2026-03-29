<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::withCount(['users', 'permissions'])->orderBy('name')->get();
        $selectedRole = null;
        $groupedPermissions = Permission::groupedPermissions();

        if ($request->has('role')) {
            $selectedRole = Role::with('permissions')->find($request->role);
        }

        return view('admin.roles.index', compact('roles', 'selectedRole', 'groupedPermissions'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:roles,slug',
            'description' => 'nullable|string|max:500',
        ]);

        Role::create($data);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $role->update($data);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated.');
    }

    public function destroy(Role $role)
    {
        if ($role->isBuiltIn()) {
            return back()->with('error', 'Built-in roles cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete a role that has users assigned to it.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted.');
    }

    public function updatePermissions(Request $request, Role $role)
    {
        if ($role->slug === 'superadmin') {
            return back()->with('error', 'Super Admin permissions cannot be modified.');
        }

        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($request->input('permissions', []));

        return back()->with('success', 'Permissions updated for ' . $role->name . '.');
    }
}
