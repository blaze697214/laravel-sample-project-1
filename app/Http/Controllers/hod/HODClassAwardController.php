<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\ClassAwardConfiguration;
use App\Models\CourseDetail;
use App\Models\CurriculumYears;
use App\Models\DepartmentCourse;
use App\Models\ElectiveGroup;
use App\Models\Levels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HODClassAwardController extends Controller
{
    public function index()
    {

        $department = Auth::user()->department;

        $scheme = CurriculumYears::where('is_active', true)->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Levels that have configured course_details
        |--------------------------------------------------------------------------
        */

        $configuredLevelIds = CourseDetail::whereHas('departmentCourse', function ($q) use ($department) {

            $q->where('department_id', $department->id);

        })
            ->where('is_configured', true)
            ->pluck('department_course_id');

        /*
        |--------------------------------------------------------------------------
        | Convert department_course_ids → level_ids
        |--------------------------------------------------------------------------
        */

        $configuredLevels = DepartmentCourse::whereIn('id', $configuredLevelIds)
            ->with('course')
            ->get()
            ->pluck('course.level_id')
            ->unique();

        /*
        |--------------------------------------------------------------------------
        | Get Levels of Active Scheme
        |--------------------------------------------------------------------------
        */

        $levels = Levels::where('curriculum_year_id', $scheme->id)
            ->where('is_audit', false)
            ->whereIn('id', $configuredLevels)
            ->orderBy('order_no')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Compulsory Courses grouped by Level
        |--------------------------------------------------------------------------
        */

        $compulsoryCourses = DepartmentCourse::where('department_id', $department->id)
            ->where('is_elective', false)
            ->whereHas('course', function ($q) use ($scheme, $configuredLevels) {

                $q->where('curriculum_year_id', $scheme->id)
                    ->whereIn('level_id', $configuredLevels);

            })
            ->with(['course', 'course.levels'])
            ->get()
            ->groupBy(function ($dc) {

                return $dc->course->level_id ?? ' ';

            });

        /*
        |--------------------------------------------------------------------------
        | Elective Groups
        |--------------------------------------------------------------------------
        */

        $electiveGroups = ElectiveGroup::where('department_id', $department->id)
            ->whereIn('level_id', $configuredLevels)
            ->with('courses')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Existing Class Award Configuration
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

    public function store(Request $request)
    {

        $department = Auth::user()->department;

        $scheme = CurriculumYears::where('is_active', true)->first();

        $request->validate([

            'total_class_award_courses' => 'required|integer|min:1',
            'compulsory_courses' => 'array',
            'elective_groups' => 'array',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Calculate selected course count
        |--------------------------------------------------------------------------
        */

        $compulsoryCount = count($request->compulsory_courses ?? []);

        $electiveGroups = ElectiveGroup::whereIn(
            'id',
            $request->elective_groups ?? []
        )->get();

        $electiveCount = $electiveGroups->sum('min_select_count');

        $selectedTotal = $compulsoryCount + $electiveCount;

        /*
        |--------------------------------------------------------------------------
        | Validate total
        |--------------------------------------------------------------------------
        */

        if ($selectedTotal != $request->total_class_award_courses) {

            return back()
                ->withErrors([
                    'total_class_award_courses' => "Selected courses ($selectedTotal) must equal total class award courses",
                ])
                ->withInput();

        }

        /*
        |--------------------------------------------------------------------------
        | Save configuration
        |--------------------------------------------------------------------------
        */

        $config = ClassAwardConfiguration::updateOrCreate(

            [
                'department_id' => $department->id,
                'curriculum_year_id' => $scheme->id,
            ],

            [
                'total_class_award_courses' => $request->total_class_award_courses,
                'created_by' => Auth::id(),
            ]

        );

        /*
        |--------------------------------------------------------------------------
        | Sync pivot tables
        |--------------------------------------------------------------------------
        */

        $config->compulsoryCourses()->sync(
            $request->compulsory_courses ?? []
        );

        $config->electiveGroups()->sync(
            $request->elective_groups ?? []
        );

        /*
        |--------------------------------------------------------------------------
        | Redirect to preview
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'hod.classAward.preview'
        )->with('success', 'Class award configuration saved');

    }

    public function preview()
    {
        $department = Auth::user()->department;

        $scheme = CurriculumYears::where('is_active', true)->first();

        $config = ClassAwardConfiguration::where('department_id', $department->id)
            ->where('curriculum_year_id', $scheme->id)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Compulsory Courses
        |--------------------------------------------------------------------------
        */

        $compulsoryCourses = DepartmentCourse::where('department_id', $department->id)
            ->whereIn('course_id', $config->compulsoryCourses->pluck('id'))
            ->with(['course', 'courseDetails'])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Elective Groups
        |--------------------------------------------------------------------------
        */

        $electiveGroups = ElectiveGroup::whereIn(
            'id',
            $config->electiveGroups->pluck('id')
        )
            ->with([
                'courses.departmentCourses.courseDetails',
                'courses',
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Totals (for footer)
        |--------------------------------------------------------------------------
        */

        $totalCredits = 0;
        $totalMarks = 0;
        $totalTH = 0;
        $totalTU = 0;
        $totalPR = 0;

        foreach ($compulsoryCourses as $dc) {

            $details = $dc->courseDetails;

            if (! $details) {
                continue;
            }

            $totalCredits += $details->credits;

            $totalTH += $details->th_hrs;
            $totalTU += $details->tu_hrs;
            $totalPR += $details->pr_hrs;

            $totalMarks +=
                $details->th_marks +
                $details->test_marks +
                $details->pr_marks +
                $details->or_marks +
                $details->tw_marks;
        }

        return view(
            'hod.class_award.preview',
            compact(
                'department',
                'scheme',
                'config',
                'compulsoryCourses',
                'electiveGroups',
                'totalCredits',
                'totalMarks',
                'totalTH',
                'totalTU',
                'totalPR'
            )
        );
    }
}
