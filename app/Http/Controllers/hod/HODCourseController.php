<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\CourseDetail;
use App\Models\CurriculumYears;
use App\Models\DepartmentCourse;
use App\Models\DepartmentLevelDetail;
use App\Models\ElectiveGroup;
use App\Models\Levels;
use Illuminate\Support\Facades\Auth;

class HODCourseController extends Controller
{
    public function index()
    {

        $department = Auth::user()->department;

        $activeScheme = CurriculumYears::where('is_active', true)->first();

        $levels = Levels::where('curriculum_year_id', $activeScheme->id)
            ->orderByRaw('is_audit = 1')
            ->orderBy('order_no')
            ->get();

        $levels->transform(function ($level) use ($department) {

            $coursesOffered = DepartmentLevelDetail::where('department_id', $department->id)
                ->where('level_id', $level->id)
                ->value('courses_offered');

            $configured = CourseDetail::whereHas('departmentCourse.course', function ($q) use ($department, $level) {

                $q->where('level_id', $level->id)
                    ->whereHas('departmentCourses', function ($sub) use ($department) {

                        $sub->where('department_id', $department->id);

                    });

            })
                ->where('is_configured', true)
                ->count();

            $level->courses_offered = $coursesOffered ?? 0;
            $level->configured = $configured;
            $level->is_configured = ($configured == $coursesOffered && $coursesOffered > 0);

            return $level;

        });

        return view('hod.courses.index', compact(
            'department',
            'activeScheme',
            'levels'
        ));

    }

    public function configure($levelId)
    {
        $department = Auth::user()->department;

        $activeScheme = CurriculumYears::where('is_active', true)->first();

        $level = Levels::findOrFail($levelId);

        /*
        |--------------------------------------------------------------------------
        | Compulsory Courses
        |--------------------------------------------------------------------------
        */

        $compulsoryCourses = DepartmentCourse::where('department_id', $department->id)
            ->where('is_elective', false)
            ->whereHas('course', function ($q) use ($levelId, $activeScheme) {

                $q->where('level_id', $levelId)
                    ->where('curriculum_year_id', $activeScheme->id);

            })
            ->with(['course', 'courseDetails'])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Elective Groups
        |--------------------------------------------------------------------------
        */

        $electiveGroups = ElectiveGroup::where('department_id', $department->id)
            ->where('level_id', $levelId)
            ->with(['courses.departmentCourses.courseDetails'])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Level Totals
        |--------------------------------------------------------------------------
        */

        $levelDetails = DepartmentLevelDetail::where('department_id', $department->id)
            ->where('level_id', $levelId)
            ->first();

        return view(
            'hod.courses.configure',
            compact(
                'level',
                'compulsoryCourses',
                'electiveGroups',
                'levelDetails'
            )
        );
    }
}
