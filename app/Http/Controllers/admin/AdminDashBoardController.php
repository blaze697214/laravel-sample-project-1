<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Courses;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashBoardController extends Controller
{
    public function dashboard()
    {

        $totalUsers = User::count();
        $totalDepartments = Department::count();
        $totalCourses = Courses::count();

        $totalFaculty = User::whereHas('roles', function($q){
            $q->where('name','faculty');
        })->count();

        $recentUsers = User::latest()->take(5)->get();

        $departmentFaculty = Department::withCount(['users as faculty_count' => function($q){
            $q->whereHas('roles', function($q2){
                $q2->where('name','faculty');
            });
        }])->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalDepartments',
            'totalCourses',
            'totalFaculty',
            'recentUsers',
            'departmentFaculty'
        ));
    }
}
