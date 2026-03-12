<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\CourseDetail;
use App\Models\Courses;
use App\Models\CurriculumYears;
use App\Models\Programme;
use App\Models\ProgrammeCourse;
use App\Models\ProgrammeLevelDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HODDashboardController extends Controller
{
    //
    public function index()
    {

        $programmeId = Auth::user()->programme_id;

        $programme = Programme::findOrFail($programmeId);

        $activeScheme = CurriculumYears::where('is_active',1)->firstOrFail();


        /*
        ------------------------------------------------
        Courses in Scheme
        ------------------------------------------------
        */

        $coursesInScheme = ProgrammeCourse::where('programme_id',$programmeId)
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
            ->whereHas('programmeCourse', function($q) use ($programmeId,$activeScheme){

                $q->where('programme_id',$programmeId)
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

        $facultyCount = User::where('programme_id',$programmeId)
            ->whereHas('roles', fn($q)=>$q->where('name','faculty'))
            ->count();


        /*
        ------------------------------------------------
        Recent Faculty
        ------------------------------------------------
        */

        $recentFaculty = User::where('programme_id',$programmeId)
            ->whereHas('roles', fn($q)=>$q->where('name','faculty'))
            ->latest()
            ->take(5)
            ->get();


        /*
        ------------------------------------------------
        Scheme Progress per Level
        ------------------------------------------------
        */

        $levels = ProgrammeLevelDetail::with('level')
            ->where('programme_id',$programmeId)
            ->get();


        $schemeProgress = [];

        foreach($levels as $row){

            $levelId = $row->level_id;

            $coursesInLevel = $row->courses_offered;


            $configuredInLevel = CourseDetail::where('is_configured',true)
                ->whereHas('programmeCourse', function($q) use ($programmeId,$levelId,$activeScheme){

                    $q->where('programme_id',$programmeId)
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
            'programme'=>$programme,
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

