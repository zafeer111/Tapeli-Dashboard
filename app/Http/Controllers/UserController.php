<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('user_management.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('user_management.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'nullable|min:10',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:8',
        ]);

        $role = Role::find($request->role_id);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->contact_number = $request->contact_number;
        $user->password = Hash::make($request->password);
        $user->save();

        $user->assignRole($role->name);
        return redirect()->route('user-management.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roleId = Role::where('name', $user->getRoleNames()->first())->first()->id ?? null;
        $roles = Role::all();
        return view('user_management.edit', compact('user', 'roles', 'roleId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'contact_number' => 'nullable|min:10',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->contact_number = $request->contact_number;
        $user->save();

        $role = Role::find($request->role_id);
        if ($role) {
            $user->syncRoles([$role->name]);
        }

        return redirect()->route('user-management.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user-management.index')->with('success', 'User deleted successfully.');
    }
}
