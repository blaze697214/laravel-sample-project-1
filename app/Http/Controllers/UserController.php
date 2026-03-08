<?php

namespace App\Http\Controllers;

use App\Models\Programme;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();

        $programmes = Programme::all();

        $users = User::with(['roles', 'programme'])
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['cdc', 'hod']);
            })
            ->get();

        return view('admin.users', compact(
            'roles',
            'programmes',
            'users'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create([

            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'programme_id' => $request->input('programme_id'),
            'created_by' => Auth::id(),

        ]);

        $user->roles()->attach($request->input('role_id'));

        return back()->with('success', 'User created');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([

            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'programme_id' => $request->input('programme_id'),

        ]);

        return back()->with('success', 'User updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return back()->with('success', 'User deleted');
    }
}
