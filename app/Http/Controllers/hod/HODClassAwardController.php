<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\ClassAwardConfiguration;
use App\Models\CurriculumYears;
use App\Models\DepartmentCourse;
use App\Models\ElectiveGroup;
use App\Models\Levels;
use Illuminate\Support\Facades\Auth;

class HODClassAwardController extends Controller
{
    public function index()
    {

        $department = Auth::user()->department;

        $scheme = CurriculumYears::where('is_active', true)->first();
        $activeScheme = CurriculumYears::where('is_active', true)->first();

        $levels = Levels::where('curriculum_year_id', $activeScheme->id)->orderByRaw('is_audit = 1')->orderBy('order_no')->get();

        /*
        |--------------------------------------------------------------------------
        | Compulsory Courses
        |--------------------------------------------------------------------------
        */

        $compulsoryCourses = DepartmentCourse::where('department_id', $department->id)
            ->where('is_elective', false)
            ->whereHas('course', function ($q) use ($scheme) {

                $q->where('curriculum_year_id', $scheme->id);

            })
            ->with('course')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Elective Groups
        |--------------------------------------------------------------------------
        */

        $electiveGroups = ElectiveGroup::where('department_id', $department->id)
            ->with('courses')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Existing Configuration (if already saved)
        |--------------------------------------------------------------------------
        */

        $config = ClassAwardConfiguration::where(
            'department_id',
            $department->id
        )
            ->where('curriculum_year_id', $scheme->id)
            ->with(['compulsoryCourses', 'electiveGroups'])
            ->first();

        return view(
            'hod.class_award.index',
            compact(
                'department',
                'scheme',
                'levels',
                'compulsoryCourses',
                'electiveGroups',
                'config'
            )
        );

    }
}
