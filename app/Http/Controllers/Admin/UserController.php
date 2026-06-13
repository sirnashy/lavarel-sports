<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_admin'] = $request->boolean('is_admin', false);
        $data['is_active'] = $request->boolean('is_active', true);
        $user->update($data);

        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles', []));
        }

        ActivityLog::record('updated', 'Updated user: ' . $user->name, $user);
        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        ActivityLog::record('deleted', 'Deleted user: ' . $user->name, $user);
        $user->delete();
        return back()->with('success', 'User deleted.');
    }
}