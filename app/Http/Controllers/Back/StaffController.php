<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = Admin::all();

        return view('admin.staff.index', compact('staffs'));
    }

    public function create()
    {
        return view('admin.staff.create', ['roles' => Role::all()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|min:8',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        $validated['password'] = $request->password ? Hash::make($request->password) : null;
        Admin::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Staff Created successfully',
            'url' => route('admin.staffs.index'),
        ]);
    }

    public function edit(Admin $staff)
    {
        return view('admin.staff.edit', ['staff' => $staff, 'roles' => Role::all()]);
    }

    public function update(Request $request, Admin $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$staff->id}",
            'phone' => 'required|string|min:8',
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($request->password) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $staff->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Staff updated successfully',
            'url' => route('admin.staffs.index'),
        ]);
    }

    public function destroy(Admin $staff)
    {
        if ($staff->type === 'super') {
            return back()->withError('You cannot delete a super admin');
        }

        $staff->delete();

        return redirect()->route('admin.staffs.index')->withSuccess('Staff deleted successfully');
    }

    public function roles()
    {
        return view('admin.staff.roles.index', ['roles' => Role::all()]);
    }

    public function create_role()
    {
        return view('admin.staff.roles.create');
    }

    public function store_role(Request $request)
    {
        $request->validate(['name' => 'required|string', 'permissions' => 'required|array']);

        Role::create([
            'name' => $request->name,
            'permissions' => json_encode($request->permissions),
        ]);

        return redirect()->route('admin.roles.index')->withSuccess('Role created successfully');
    }

    public function edit_role(Role $role)
    {
        return view('admin.staff.roles.edit', compact('role'));
    }

    public function update_role(Request $request, Role $role)
    {
        $request->validate(['name' => 'required|string', 'permissions' => 'required|array']);

        $role->update([
            'name' => $request->name,
            'permissions' => json_encode($request->permissions),
        ]);

        return redirect()->route('admin.roles.index')->withSuccess('Role updated successfully');
    }

    public function destroy_role(Role $role)
    {
        $role->delete();

        return redirect()->route('admin.roles.index')->withSuccess('Role deleted successfully');
    }
}
