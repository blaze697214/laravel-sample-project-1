<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\CourseDetail;
use App\Models\Courses;
use App\Models\CurriculumYears;
use App\Models\DepartmentCourse;
use App\Models\ElectiveGroup;
use App\Models\Levels;
use App\Models\SemesterPlacement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HODSemesterPlacementController extends Controller
{
    public function index()
    {
        $department = Auth::user()->department;

        $scheme = CurriculumYears::where('is_active', true)->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Get configured levels (only levels with course details)
        |--------------------------------------------------------------------------
        */

        $configuredLevels = CourseDetail::whereHas('departmentCourse', function ($q) use ($department) {

            $q->where('department_id', $department->id);

        })
            ->with('departmentCourse.course')
            ->get()
            ->pluck('departmentCourse.course.level_id')
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Detect number of academic years from levels
        |--------------------------------------------------------------------------
        */

        $years = ['First', 'Second', 'Third'];

        /*
        |--------------------------------------------------------------------------
        | Get semester placements
        |--------------------------------------------------------------------------
        */

        $placements = SemesterPlacement::where('department_id', $department->id)
            ->where('curriculum_year_id', $scheme->id)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Count placements per semester
        |--------------------------------------------------------------------------
        */

        $placementCounts = [];

        foreach ($placements as $placement) {

            $year = $placement->academic_year_no;
            $semester = $placement->semester_type;

            if (! isset($placementCounts[$year])) {
                $placementCounts[$year] = [
                    'odd' => 0,
                    'even' => 0,
                ];
            }

            $placementCounts[$year][$semester]++;

        }

        /*
        |--------------------------------------------------------------------------
        | Check if placement complete
        |--------------------------------------------------------------------------
        */

        $totalPlaceables = DepartmentCourse::where('department_id', $department->id)
            ->count()
            +
            ElectiveGroup::where('department_id', $department->id)
                ->count();

        $placedCount = $placements->count();

        $isComplete = ($placedCount >= $totalPlaceables);

        return view(
            'hod.semester_placement.index',
            compact(
                'department',
                'scheme',
                'years',
                'placementCounts',
                'isComplete'
            )
        );
    }

    public function configure()
    {
        $department = Auth::user()->department;

        $scheme = CurriculumYears::where('is_active', true)->firstOrFail();

        $years = ['First', 'Second', 'Third'];

        $courses = DepartmentCourse::where('department_id', $department->id)
            ->where('is_elective', false)
            ->with('course')
            ->get();

        $electiveGroups = ElectiveGroup::where('department_id', $department->id)
            ->get();

        $placements = SemesterPlacement::where('department_id', $department->id)
            ->where('curriculum_year_id', $scheme->id)
            ->get()
            ->map(function ($p) {

                return [
                    'year' => $p->academic_year_no,
                    'semester' => $p->semester_type,
                    'type' => $p->placeable_type == Courses::class ? 'courses' : 'elective_group',
                    'id' => $p->placeable_id,
                ];

            });
        // ->orderBy('academic_year_no')
        // ->orderBy('semester_type');

        return view(
            'hod.semester_placement.configure',
            compact(
                'department',
                'scheme',
                'years',
                'courses',
                'electiveGroups',
                'placements'
            )
        );
    }

    public function store(Request $request)
    {

        $department = Auth::user()->department;

        $scheme = CurriculumYears::where('is_active', true)->firstOrFail();

        $placements = json_decode($request->placements, true);

        if (! $placements) {

            return back()->withErrors('No placements submitted.');

        }
        if (empty($placements)) {
            return back()->withErrors('No placements submitted. Empty');
        }

        /*
        ------------------------------------------------
        Prevent duplicate placements
        ------------------------------------------------
        */

        $seen = [];

        foreach ($placements as $p) {

            $key = $p['type'].'_'.$p['id'];

            if (isset($seen[$key])) {

                return back()->withErrors(
                    'Duplicate placement detected.'
                );

            }

            $seen[$key] = true;

        }

        /*
        ------------------------------------------------
        Validate placement count
        ------------------------------------------------
        */

        $totalCourses = DepartmentCourse::where('department_id', $department->id)
            ->where('is_elective', false)
            ->count();

        $totalGroups = ElectiveGroup::where('department_id', $department->id)
            ->count();

        $requiredPlacements = $totalCourses + $totalGroups;

        if (count($placements) !== $requiredPlacements) {

            return back()->withErrors(
                'All courses and elective groups must be placed.'
            );

        }

        /*
        ------------------------------------------------
        Delete old placements
        ------------------------------------------------
        */

        SemesterPlacement::where('department_id', $department->id)
            ->where('curriculum_year_id', $scheme->id)
            ->delete();

        /*
        ------------------------------------------------
        Insert placements
        ------------------------------------------------
        */

        foreach ($placements as $p) {

            SemesterPlacement::create([

                'department_id' => $department->id,

                'curriculum_year_id' => $scheme->id,

                'academic_year_no' => $p['year'],

                'semester_type' => $p['semester'],

                'placeable_type' => $p['type'] === 'course'
                    ? Courses::class
                    : ElectiveGroup::class,

                'placeable_id' => $p['id'],

            ]);

        }

        /*
        ------------------------------------------------
        Redirect
        ------------------------------------------------
        */

        return redirect()
            ->route('hod.semesterPlacement.preview')
            ->with('success', 'Semester placements saved successfully.');

    }
}
