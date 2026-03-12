<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CDCUserController extends Controller
{
    public function hodIndex()
    {

        $hodUsers = User::whereHas('roles', function ($q) {
            $q->where('name', 'hod');
        })
            ->with('department')
            ->get();

        $departments = Department::all();

        return view('cdc.users.hod.index', [
            'hodUsers' => $hodUsers,
            'departments' => $departments,
        ]);

    }

    public function storeHod(Request $request)
    {

        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email',

            'department_id' => 'required|exists:departments,id',

            'password' => 'required|min:5',

        ]);

        $user = User::create([

            'name' => $request->input('name'),

            'email' => $request->input('email'),

            'department_id' => $request->input('department_id'),

            'password' => Hash::make($request->input('password')),

        ]);

        $role = Role::where('name', 'hod')->first();

        $user->roles()->attach($role->id);

        return back()->with('success', 'HOD created successfully');

    }

    public function updateHod(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email,'.$user->id,

        ]);

        $user->update([

            'name' => $request->input('name'),

            'email' => $request->input('email'),

        ]);

        if ($request->filled('password')) {

            $user->update([
                'password' => Hash::make($request->input('password')),
            ]);

        }

        return redirect()
            ->route('cdc.users.hod')
            ->with('success', 'HOD updated successfully');

    }

    public function destroyHod($id)
    {

        $user = User::findOrFail($id);

        $user->roles()->detach();

        $user->delete();

        return back()->with('success', 'HOD deleted successfully');

    }

    public function facultyIndex(Request $request)
    {

        $departments = Department::all();

        $selectedDepartment = null;
        $facultyUsers = collect();
        $hodUsers = collect();

        if ($request->department) {

            $selectedDepartment = Department::find($request->department);

            $facultyUsers = User::where('department_id', $request->department)
                ->whereHas('roles', fn ($q) => $q->where('name', 'faculty'))
                ->get();

            $hodUsers = User::where('department_id', $request->department)
                ->whereHas('roles', fn ($q) => $q->where('name', 'hod'))
                ->get();

        }

        return view('cdc.users.faculty.index', [
            'departments' => $departments,
            'selectedDepartment' => $selectedDepartment,
            'facultyUsers' => $facultyUsers,
            'hodUsers' => $hodUsers,
        ]);

    }

    public function storeFaculty(Request $request)
    {

        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email',

            'department_id' => 'required|exists:departments,id',

            'password' => 'required|min:6',

        ]);

        $user = User::create([

            'name' => $request->input('name'),

            'email' => $request->input('email'),

            'department_id' => $request->input('department_id'),

            'password' => Hash::make($request->input('password')),

        ]);

        $role = Role::where('name', 'faculty')->first();

        $user->roles()->attach($role->id);

        return redirect()
            ->route('cdc.users.faculty', ['department' => $request->department_id])
            ->with('success', 'Faculty created successfully');
    }

    public function updateFaculty(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email,'.$user->id,

        ]);

        $user->update([

            'name' => $request->input('name'),

            'email' => $request->input('email'),

            'department_id' => $request->input('department_id'),

        ]);

        if ($request->filled('password')) {

            $user->update([
                'password' => Hash::make($request->input('password')),
            ]);

        }

        return redirect()
            ->route('cdc.users.faculty', ['department' => $request->department_id])
            ->with('success', 'Faculty updated successfully');

    }

    public function destroyFaculty(Request $request,$id)
    {

        $user = User::findOrFail($id);

        $user->roles()->detach();

        $user->delete();

        return redirect()
            ->route('cdc.users.faculty', ['department' => $request->department_id])
            ->with('success', 'Faculty created successfully');

    }
}
