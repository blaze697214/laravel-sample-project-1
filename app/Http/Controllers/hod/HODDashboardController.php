<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\CourseDetail;
use App\Models\Courses;
use App\Models\CurriculumYears;
use App\Models\Department;
use App\Models\DepartmentCourse;
use App\Models\DepartmentLevelDetail;
use App\Models\Levels;
use App\Models\Syllabus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HODDashboardController extends Controller
{
    //
    public function index()
{
    $user = Auth::user();
    $department = $user->department;

    $departmentType = $department->type;

    /*
    |--------------------------------------------------------------------------
    | Programme Department Dashboard
    |--------------------------------------------------------------------------
    */

    if ($departmentType === 'programme') {

        $activeScheme = CurriculumYears::where('is_active', true)->first();

        $departmentId = $department->id;

        $coursesInScheme = DepartmentCourse::where('department_id', $departmentId)
            ->whereHas('course', function ($q) use ($activeScheme) {
                $q->where('curriculum_year_id', $activeScheme->id);
            })
            ->count();

        $configuredCourses = CourseDetail::whereHas('departmentCourse', function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId);
        })->where('is_configured', true)->count();

        $facultyCount = User::where('department_id', $departmentId)
            ->whereHas('roles', fn($q) => $q->where('name', 'faculty'))
            ->count();

        $cards = [
            'coursesInScheme' => $coursesInScheme,
            'configuredCourses' => $configuredCourses,
            'remaining' => $coursesInScheme - $configuredCourses,
            'facultyCount' => $facultyCount
        ];

        $recentFaculty = User::where('department_id', $departmentId)
            ->whereHas('roles', fn($q) => $q->where('name', 'faculty'))
            ->latest()
            ->take(5)
            ->get();

        $levels = Levels::where('curriculum_year_id', $activeScheme->id)
            ->orderByRaw('order_no=0')
            ->orderBy('order_no')
            ->get();

        $schemeProgress = [];

        foreach ($levels as $level) {

            $offered = DepartmentLevelDetail::where('department_id', $departmentId)
                ->where('level_id', $level->id)
                ->value('courses_offered');

            $configured = CourseDetail::whereHas('departmentCourse.course', function ($q) use ($level) {
                $q->where('level_id', $level->id);
            })->count();

            $schemeProgress[] = [
                'level' => $level->name,
                'offered' => $offered,
                'configured' => $configured,
                'remaining' => $offered - $configured
            ];
        }

        return view('hod.dashboard', compact(
            'departmentType',
            'department',
            'activeScheme',
            'cards',
            'recentFaculty',
            'schemeProgress'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Service Department Dashboard
    |--------------------------------------------------------------------------
    */

    $departmentId = $department->id;

    $ownedCourses = Courses::where('owner_department_id', $departmentId)->count();

    $coursesUsed = DepartmentCourse::whereHas('course', function ($q) use ($departmentId) {
        $q->where('owner_department_id', $departmentId);
    })->count();

    $syllabusCompleted = Syllabus::whereHas('departmentCourse.course', function ($q) use ($departmentId) {
        $q->where('owner_department_id', $departmentId);
    })->where('is_submitted', true)->count();

    $facultyCount = User::where('department_id', $departmentId)
        ->whereHas('roles', fn($q) => $q->where('name', 'faculty'))
        ->count();

    $cards = [
        'ownedCourses' => $ownedCourses,
        'coursesUsed' => $coursesUsed,
        'syllabusCompleted' => $syllabusCompleted,
        'facultyCount' => $facultyCount
    ];

    $ownedCoursesTable = Courses::where('owner_department_id', $departmentId)
        ->get();

    $recentUpdates = Syllabus::whereHas('departmentCourse.course', function ($q) use ($departmentId) {
        $q->where('owner_department_id', $departmentId);
    })
        ->latest()
        ->take(5)
        ->get();

    return view('hod.dashboard', compact(
        'departmentType',
        'department',
        'cards',
        'ownedCoursesTable',
        'recentUpdates'
    ));
}
}
