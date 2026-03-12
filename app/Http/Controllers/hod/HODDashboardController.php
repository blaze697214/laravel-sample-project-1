<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\CourseDetail;
use App\Models\Courses;
use App\Models\CurriculumYears;
use App\Models\Department;
use App\Models\DepartmentCourse;
use App\Models\DepartmentLevelDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HODDashboardController extends Controller
{
    //
    public function index()
    {

        $departmentId = Auth::user()->department_id;

        $department = Department::findOrFail($departmentId);

        $activeScheme = CurriculumYears::where('is_active',1)->firstOrFail();


        /*
        ------------------------------------------------
        Courses in Scheme
        ------------------------------------------------
        */

        $coursesInScheme = DepartmentCourse::where('department_id',$departmentId)
            ->whereHas('course', function($q) use ($activeScheme){
                $q->where('curriculum_year_id',$activeScheme->id);
            })
            ->count();


        /*
        ------------------------------------------------
        Configured Courses
        ------------------------------------------------
        */

        $configuredCourses = CourseDetail::where('is_configured',true)
            ->whereHas('departmentCourse', function($q) use ($departmentId,$activeScheme){

                $q->where('department_id',$departmentId)
                ->whereHas('course',function($c) use ($activeScheme){
                    $c->where('curriculum_year_id',$activeScheme->id);
                });

            })
            ->count();


        $remainingCourses = $coursesInScheme - $configuredCourses;


        /*
        ------------------------------------------------
        Faculty Count
        ------------------------------------------------
        */

        $facultyCount = User::where('department_id',$departmentId)
            ->whereHas('roles', fn($q)=>$q->where('name','faculty'))
            ->count();


        /*
        ------------------------------------------------
        Recent Faculty
        ------------------------------------------------
        */

        $recentFaculty = User::where('department_id',$departmentId)
            ->whereHas('roles', fn($q)=>$q->where('name','faculty'))
            ->latest()
            ->take(5)
            ->get();


        /*
        ------------------------------------------------
        Scheme Progress per Level
        ------------------------------------------------
        */

        $levels = DepartmentLevelDetail::with('level')
            ->where('department_id',$departmentId)
            ->get();


        $schemeProgress = [];

        foreach($levels as $row){

            $levelId = $row->level_id;

            $coursesInLevel = $row->courses_offered;


            $configuredInLevel = CourseDetail::where('is_configured',true)
                ->whereHas('departmentCourse', function($q) use ($departmentId,$levelId,$activeScheme){

                    $q->where('department_id',$departmentId)
                    ->whereHas('course',function($c) use ($levelId,$activeScheme){

                        $c->where('level_id',$levelId)
                          ->where('curriculum_year_id',$activeScheme->id);

                    });

                })
                ->count();


            $schemeProgress[] = [

                'name' => $row->level->name,

                'offered' => $coursesInLevel,

                'configured' => $configuredInLevel,

                'remaining' => $coursesInLevel - $configuredInLevel

            ];

        }



        return view('hod.dashboard',[
            'department'=>$department,
            'activeScheme'=>$activeScheme,
            'coursesInScheme'=>$coursesInScheme,
            'configuredCourses'=>$configuredCourses,
            'remainingCourses'=>$remainingCourses,
            'facultyCount'=>$facultyCount,
            'recentFaculty'=>$recentFaculty,
            'schemeProgress'=>$schemeProgress
        ]);

    }

}

