<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Courses;
use App\Models\Programme;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashBoardController extends Controller
{
    public function dashboard()
    {

        $totalUsers = User::count();
        $totalProgrammes = Programme::count();
        $totalCourses = Courses::count();

        $totalFaculty = User::whereHas('roles', function($q){
            $q->where('name','faculty');
        })->count();

        $recentUsers = User::latest()->take(5)->get();

        $programmeFaculty = Programme::withCount(['users as faculty_count' => function($q){
            $q->whereHas('roles', function($q2){
                $q2->where('name','faculty');
            });
        }])->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProgrammes',
            'totalCourses',
            'totalFaculty',
            'recentUsers',
            'programmeFaculty'
        ));
    }
}
