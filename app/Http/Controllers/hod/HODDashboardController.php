<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\Courses;
use App\Models\CurriculumYears;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HODDashboardController extends Controller
{
    //
    public function index()
    {

        $programmeId = Auth::user()->programme_id;

        $activeScheme = CurriculumYears::where('is_active',1)->first();

        if(!$activeScheme){
            abort(404,'No active scheme found');
        }


        // total courses in scheme
        $coursesInScheme = Courses::where('curriculum_year_id',$activeScheme->id)->count();


        // configured courses (placeholder for later table)
        $configuredCourses = 0;


        $remainingCourses = $coursesInScheme - $configuredCourses;


        // faculty count
        $facultyCount = User::where('programme_id',$programmeId)
            ->whereHas('roles', fn($q)=>$q->where('name','faculty'))
            ->count();


        // recent faculty
        $recentFaculty = User::where('programme_id',$programmeId)
            ->whereHas('roles', fn($q)=>$q->where('name','faculty'))
            ->latest()
            ->take(5)
            ->get();


        return view('hod.dashboard',[
            'programmeId'=>$programmeId,
            'scheme'=>$activeScheme,
            'coursesInScheme'=>$coursesInScheme,
            'configuredCourses'=>$configuredCourses,
            'remainingCourses'=>$remainingCourses,
            'facultyCount'=>$facultyCount,
            'recentFaculty'=>$recentFaculty
        ]);

    }
}
