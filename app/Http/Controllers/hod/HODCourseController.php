<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\CourseDetail;
use App\Models\CurriculumYears;
use App\Models\DepartmentCourse;
use App\Models\DepartmentLevelDetail;
use App\Models\ElectiveGroup;
use App\Models\Levels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HODCourseController extends Controller
{
    public function index()
    {
        $department = Auth::user()->department;
        $activeScheme = CurriculumYears::where('is_active', true)->first();
        $levels = Levels::where('curriculum_year_id', $activeScheme->id)->orderByRaw('is_audit = 1')->orderBy('order_no')->get();
        $levels->transform(function ($level) use ($department) {

            $coursesOffered = DepartmentLevelDetail::where('department_id', $department->id)
                ->where('level_id', $level->id)
                ->value('courses_offered');

            /*
            |--------------------------------------------------------------------------
            | Courses added by CDC
            |--------------------------------------------------------------------------
            */

            $coursesAdded = DepartmentCourse::where('department_id', $department->id)
                ->whereHas('course', function ($q) use ($level) {

                    $q->where('level_id', $level->id);

                })
                ->count();

            /*
            |--------------------------------------------------------------------------
            | Courses configured by HOD
            |--------------------------------------------------------------------------
            */

            $configured = CourseDetail::whereHas('departmentCourse.course', function ($q) use ($department, $level) {

                $q->where('level_id', $level->id)
                    ->whereHas('departmentCourses', function ($sub) use ($department) {

                        $sub->where('department_id', $department->id);

                    });

            })
                ->where('is_configured', true)
                ->count();

            $level->courses_offered = $coursesOffered ?? 0;
            $level->courses_added = $coursesAdded;
            $level->configured = $configured;

            /*
            |--------------------------------------------------------------------------
            | Determine status
            |--------------------------------------------------------------------------
            */

            if ($coursesAdded < $coursesOffered) {

                $level->status = 'missing';

            } elseif ($configured < $coursesOffered) {

                $level->status = 'not_configured';

            } else {

                $level->status = 'configured';

            }

            return $level;
        });

        return view('hod.courses.index', compact('department', 'activeScheme', 'levels'));
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
                'levelDetails',
                'activeScheme'
            )
        );
    }

    public function store(Request $request, $levelId)
    {

        $department = Auth::user()->department;

        $level = Levels::findOrFail($levelId);

        /*
        |--------------------------------------------------------------------------
        | Validate basic input
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'course_code' => 'required|array',

            'th_hrs' => 'required|array',
            'tu_hrs' => 'required|array',
            'pr_hrs' => 'required|array',

            'credits' => 'required|array',
            'th_paper_hrs' => 'required|array',

            'th_marks' => 'required|array',
            'test_marks' => 'required|array',
            'pr_marks' => 'required|array',
            'or_marks' => 'required|array',
            'tw_marks' => 'required|array',

        ]);

        if ($level->is_audit) {
            $request->merge([
                'th_marks' => array_fill_keys(array_keys($request->course_code), 0),
                'test_marks' => array_fill_keys(array_keys($request->course_code), 0),
                'pr_marks' => array_fill_keys(array_keys($request->course_code), 0),
                'or_marks' => array_fill_keys(array_keys($request->course_code), 0),
                'tw_marks' => array_fill_keys(array_keys($request->course_code), 0),
            ]);
        }

        /*
|--------------------------------------------------------------------------
| Get elective groups for this level
|--------------------------------------------------------------------------
*/

        $groups = ElectiveGroup::where('department_id', $department->id)
            ->where('level_id', $levelId)
            ->with('courses')
            ->get();

        /* collect elective department_course ids */

        $electiveDcIds = [];

        foreach ($groups as $group) {

            foreach ($group->courses as $course) {

                $dc = $course->departmentCourses
                    ->where('department_id', $department->id)
                    ->first();

                if ($dc) {
                    $electiveDcIds[] = $dc->id;
                }

            }
        }

        /*
|--------------------------------------------------------------------------
| Calculate totals (handle elective groups)
|--------------------------------------------------------------------------
*/

        $totalTH = 0;
        $totalTU = 0;
        $totalPR = 0;

        $totalCredits = 0;

        $totalMarks = 0;

        /* -----------------------------
           Compulsory courses
        ------------------------------*/

        foreach ($request->th_hrs as $dcId => $th) {

            if (! in_array($dcId, $electiveDcIds)) {

                $totalTH += $request->th_hrs[$dcId] ?? 0;
                $totalTU += $request->tu_hrs[$dcId] ?? 0;
                $totalPR += $request->pr_hrs[$dcId] ?? 0;

                $totalCredits += $request->credits[$dcId] ?? 0;

                $totalMarks +=
                    ($request->th_marks[$dcId] ?? 0) +
                    ($request->test_marks[$dcId] ?? 0) +
                    ($request->pr_marks[$dcId] ?? 0) +
                    ($request->or_marks[$dcId] ?? 0) +
                    ($request->tw_marks[$dcId] ?? 0);
            }

        }

        /* -----------------------------
           Elective groups
        ------------------------------*/

        foreach ($groups as $group) {

            $course = $group->courses->first();

            $dc = $course->departmentCourses
                ->where('department_id', $department->id)
                ->first();

            if (! $dc) {
                continue;
            }

            $dcId = $dc->id;

            $multiplier = $group->min_select_count;

            $totalTH += ($request->th_hrs[$dcId] ?? 0) * $multiplier;
            $totalTU += ($request->tu_hrs[$dcId] ?? 0) * $multiplier;
            $totalPR += ($request->pr_hrs[$dcId] ?? 0) * $multiplier;

            $totalCredits += ($request->credits[$dcId] ?? 0) * $multiplier;

            $totalMarks += (
                ($request->th_marks[$dcId] ?? 0) +
                ($request->test_marks[$dcId] ?? 0) +
                ($request->pr_marks[$dcId] ?? 0) +
                ($request->or_marks[$dcId] ?? 0) +
                ($request->tw_marks[$dcId] ?? 0)
            ) * $multiplier;

        }

        /*
        |--------------------------------------------------------------------------
        | Get required totals from department_level_details
        |--------------------------------------------------------------------------
        */

        $levelDetails = DepartmentLevelDetail::where(
            'department_id',
            $department->id
        )
            ->where('level_id', $levelId)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Validate totals
        |--------------------------------------------------------------------------
        */

        if ($totalTH != $levelDetails->th_hrs) {

            return back()->withErrors([
                'th_hrs' => 'Total TH hours must be '.$levelDetails->th_hrs,
            ])->withInput();

        }

        if ($totalTU != $levelDetails->tu_hrs) {

            return back()->withErrors([
                'tu_hrs' => 'Total TU hours must be '.$levelDetails->tu_hrs,
            ])->withInput();

        }

        if ($totalPR != $levelDetails->pr_hrs) {

            return back()->withErrors([
                'pr_hrs' => 'Total PR hours must be '.$levelDetails->pr_hrs,
            ])->withInput();

        }

        if ($totalCredits != $levelDetails->credits) {

            return back()->withErrors([
                'credits' => 'Total credits must be '.$levelDetails->credits,
            ])->withInput();

        }

        if ($totalMarks != $levelDetails->marks) {

            return back()->withErrors([
                'marks' => 'Total marks must be '.$levelDetails->marks,
            ])->withInput();

        }

        /*
        |--------------------------------------------------------------------------
        | Prepare course data for preview
        |--------------------------------------------------------------------------
        */

        $courses = DepartmentCourse::whereIn(
            'id',
            array_keys($request->course_code)
        )
            ->with('course')
            ->get();

        $rows = [];
        $scheme = CurriculumYears::findOrFail($request->scheme_id);
        $year = substr($scheme->year_start, -2);

        foreach ($request->course_code as $dcId => $code) {

            CourseDetail::updateOrCreate(

                [
                    'department_course_id' => $dcId,
                ],

                [
                    'course_code' => $year.$level->order_no.$request->course_code[$dcId] ?? 0,

                    'th_hrs' => $request->th_hrs[$dcId] ?? 0,
                    'tu_hrs' => $request->tu_hrs[$dcId] ?? 0,
                    'pr_hrs' => $request->pr_hrs[$dcId] ?? 0,

                    'credits' => $request->credits[$dcId] ?? 0,
                    'th_paper_hrs' => $request->th_paper_hrs[$dcId] ?? 0,

                    'th_marks' => $request->th_marks[$dcId] ?? 0,
                    'test_marks' => $request->test_marks[$dcId] ?? 0,
                    'pr_marks' => $request->pr_marks[$dcId] ?? 0,
                    'or_marks' => $request->or_marks[$dcId] ?? 0,
                    'tw_marks' => $request->tw_marks[$dcId] ?? 0,

                    'created_by' => Auth::id(),
                    'is_configured' => true,
                ]

            );

        }

        return redirect()->route('hod.courses.preview', $levelId);

    }

    public function preview($levelId)
    {
        $department = Auth::user()->department;

        $level = Levels::findOrFail($levelId);

        $compulsoryCourses = DepartmentCourse::where('department_id', $department->id)
            ->where('is_elective', false)
            ->whereHas('course', function ($q) use ($levelId) {

                $q->where('level_id', $levelId);

            })
            ->with(['course', 'courseDetails'])
            ->get();

        $electiveGroups = ElectiveGroup::where('department_id', $department->id)
            ->where('level_id', $levelId)
            ->with([
                'courses.departmentCourses.courseDetails',
                'courses',
            ])
            ->get();

        $auditDetails = DepartmentLevelDetail::where(
            'department_id',
            Auth::user()->department_id
        )
            ->where('level_id', $level->id)
            ->first();

        return view(
            'hod.courses.preview',
            compact(
                'level',
                'department',
                'compulsoryCourses',
                'electiveGroups',
                'auditDetails'
            )
        );
    }

    public function finalize($levelId)
    {

        $department = Auth::user()->department;

        DepartmentLevelDetail::where(
            'department_id',
            $department->id
        )
            ->where('level_id', $levelId)
            ->update([
                'is_configured' => true,
            ]);

        return redirect()
            ->route('hod.courses')
            ->with('success', 'Level configured successfully');

    }
}
