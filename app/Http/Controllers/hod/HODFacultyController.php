<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\CurriculumYears;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HODFacultyController extends Controller
{
    public function index()
    {
        $department = Auth::user()->department;

        $scheme = CurriculumYears::where('is_active', true)->firstOrFail();

        $faculty = User::where('department_id', $department->id)
            ->whereHas('roles', fn ($q) => $q->where('name', 'faculty'))
            ->latest()
            ->get();

        return view('hod.faculty.index', compact(
            'department',
            'faculty',
            'scheme'
        ));
    }

    public function store(Request $request)
    {
        $department = Auth::user()->department;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $department->id,
        ]);

        $role = Role::where('name', 'faculty')->first();

        $user->roles()->attach($role->id);

        return back()->with('success', 'Faculty created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return back()->with('success', 'Faculty updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return back()->with('success', 'Faculty deleted successfully.');
    }
}
