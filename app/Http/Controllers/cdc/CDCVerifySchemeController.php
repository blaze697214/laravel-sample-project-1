<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\ClassAwardConfiguration;
use App\Models\CourseDetail;
use App\Models\CurriculumYears;
use App\Models\Department;
use App\Models\DepartmentCourse;
use App\Models\DepartmentLevelDetail;
use App\Models\ElectiveGroup;
use App\Models\Levels;
use App\Services\SchemeVerificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class CDCVerifySchemeController extends Controller
{
    public function index()
    {
        $schemes = CurriculumYears::orderBy('year_start', 'desc')->get();

        return view('cdc.schemes.verify.index', compact('schemes'));
    }

    public function departments($schemeId, SchemeVerificationService $service)
    {

        $departments = Department::where('type', 'programme')->get();
        $status = [];

        foreach ($departments as $department) {

            $verification = $service->verifyDepartment($schemeId, $department->id);

            $status[$department->id] = $verification['complete'];
        }

        return view(
            'cdc.schemes.verify.departments',
            compact('departments', 'schemeId', 'status')
        );

    }

    public function summary($schemeId, $departmentId, SchemeVerificationService $service)
    {

        $department = Department::findOrFail($departmentId);

        $verification = $service->verifyDepartment($schemeId, $departmentId);

        return view(
            'cdc.schemes.verify.summary',
            compact('department', 'schemeId', 'verification')
        );

    }

    public function departmentLevelsView($schemeId, $departmentId, SchemeVerificationService $service)
    {

        $verification = $service->verifyDepartment($schemeId, $departmentId);

        if (! $verification['departmentLevel']) {

            return redirect()
                ->route('cdc.schemes.verify.summary', [$schemeId, $departmentId])
                ->with('error', 'Department Level configuration has not been created yet.');

        }

        // load actual data
        $department = Department::findOrFail($departmentId);

        $rows = DepartmentLevelDetail::with('level')
            ->where('department_id', $departmentId)
            ->whereHas('level', function ($q) use ($schemeId) {
                $q->where('curriculum_year_id', $schemeId);
            })
            ->get()
            ->sortBy('level.order_no');

        // reuse your earlier logic
        $totals = [
            'courses' => 0,
            'completed' => 0,
            'compulsory' => 0,
            'elective' => 0,
            'th' => 0,
            'tu' => 0,
            'pr' => 0,
            'hours' => 0,
            'credits' => 0,
            'marks' => 0,
        ];

        $auditRow = null;

        foreach ($rows as $row) {

            $totalHours = $row->th_hrs + $row->tu_hrs + $row->pr_hrs;
            $row->total_hours = $totalHours;

            if ($row->level->is_audit) {

                $auditRow = $row;

                continue;

            }

            $totals['courses'] += $row->courses_offered;

            $totals['compulsory'] += $row->compulsory_to_complete;
            $totals['elective'] += $row->elective_to_complete;

            $completed = $row->compulsory_to_complete + $row->elective_to_complete;
            $totals['completed'] += $completed;

            $totals['th'] += $row->th_hrs;
            $totals['tu'] += $row->tu_hrs;
            $totals['pr'] += $row->pr_hrs;

            $totals['hours'] += $totalHours;

            $totals['credits'] += $row->credits;
            $totals['marks'] += $row->marks;

        }

        $grand = [
            'courses' => $totals['courses'] + ($auditRow->courses_offered ?? 0),
            'completed' => $totals['completed'] + ($auditRow->compulsory_to_complete ?? 0),
            'th' => $totals['th'] + ($auditRow->th_hrs ?? 0),
            'tu' => $totals['tu'] + ($auditRow->tu_hrs ?? 0),
            'pr' => $totals['pr'] + ($auditRow->pr_hrs ?? 0),
            'hours' => $totals['hours'] + ($auditRow->total_hours ?? 0),
            'credits' => $totals['credits'],
            'marks' => $totals['marks'],
        ];

        return view(
            'cdc.schemes.verify.department-levels',
            compact(
                'department',
                'schemeId',
                'rows',
                'totals',
                'grand',
                'auditRow'
            )
        );
    }

    public function downloadDepartmentLevelsPdf($schemeId, $departmentId)
    {

        $department = Department::findOrFail($departmentId);

        $rows = DepartmentLevelDetail::with('level')
            ->where('department_id', $departmentId)
            ->whereHas('level', function ($q) use ($schemeId) {
                $q->where('curriculum_year_id', $schemeId);
            })
            ->get()
            ->sortBy('level.order_no');

        $totals = [
            'courses' => 0,
            'completed' => 0,
            'compulsory' => 0,
            'elective' => 0,
            'th' => 0,
            'tu' => 0,
            'pr' => 0,
            'hours' => 0,
            'credits' => 0,
            'marks' => 0,
        ];

        $auditRow = null;

        foreach ($rows as $row) {

            $totalHours = $row->th_hrs + $row->tu_hrs + $row->pr_hrs;
            $row->total_hours = $totalHours;

            if ($row->level->is_audit) {

                $auditRow = $row;

                continue;

            }

            $totals['courses'] += $row->courses_offered;

            $totals['compulsory'] += $row->compulsory_to_complete;
            $totals['elective'] += $row->elective_to_complete;

            $completed = $row->compulsory_to_complete + $row->elective_to_complete;
            $totals['completed'] += $completed;

            $totals['th'] += $row->th_hrs;
            $totals['tu'] += $row->tu_hrs;
            $totals['pr'] += $row->pr_hrs;

            $totals['hours'] += $totalHours;

            $totals['credits'] += $row->credits;
            $totals['marks'] += $row->marks;

        }

        $grand = [
            'courses' => $totals['courses'] + ($auditRow->courses_offered ?? 0),
            'completed' => $totals['completed'] + ($auditRow->compulsory_to_complete ?? 0),
            'th' => $totals['th'] + ($auditRow->th_hrs ?? 0),
            'tu' => $totals['tu'] + ($auditRow->tu_hrs ?? 0),
            'pr' => $totals['pr'] + ($auditRow->pr_hrs ?? 0),
            'hours' => $totals['hours'] + ($auditRow->total_hours ?? 0),
            'credits' => $totals['credits'],
            'marks' => $totals['marks'],
        ];

        $pdf = Pdf::loadView(
            'pdf.department-levels',
            compact(
                'department',
                'schemeId',
                'rows',
                'totals',
                'grand',
                'auditRow'
            ));

        return $pdf->download('department_structure.pdf');
    }

    public function courseDetails($schemeId, $departmentId)
    {

        $department = Department::findOrFail($departmentId);

        $scheme = CurriculumYears::findOrFail($schemeId);

        $levels = Levels::where('curriculum_year_id', $schemeId)
            ->orderByRaw('is_audit = 1')
            ->orderBy('order_no')
            ->get();

        $levels->transform(function ($level) use ($department) {

            /*
            |--------------------------------------------------------------------------
            | Courses added for this level
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

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            if ($coursesAdded > 0 && $configured == $coursesAdded) {

                $level->status = 'configured';

            } else {

                $level->status = 'missing';

            }

            return $level;

        });

        return view(
            'cdc.schemes.verify.course_details.levels',
            compact(
                'scheme',
                'department',
                'levels'
            )
        );

    }

    public function showCourseDetails($schemeId, $departmentId, $levelId)
    {

        $department = Department::findOrFail($departmentId);

        $scheme = CurriculumYears::findOrFail($schemeId);

        $level = Levels::findOrFail($levelId);

        /*
        |--------------------------------------------------------------------------
        | Compulsory Courses
        |--------------------------------------------------------------------------
        */

        $compulsoryCourses = DepartmentCourse::where('department_id', $department->id)
            ->where('is_elective', false)
            ->whereHas('course', function ($q) use ($levelId) {

                $q->where('level_id', $levelId);

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
            ->with([
                'courses.departmentCourses.courseDetails',
                'courses',
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Audit Level Details
        |--------------------------------------------------------------------------
        */

        $auditDetails = DepartmentLevelDetail::where(
            'department_id', $department->id
        )
            ->where('level_id', $levelId)
            ->first();

        return view(
            'cdc.schemes.verify.course_details.show',
            compact(
                'scheme',
                'department',
                'level',
                'compulsoryCourses',
                'electiveGroups',
                'auditDetails'
            )
        );

    }

    public function downloadCourseDetails($schemeId, $departmentId, $levelId)
    {

        $department = Department::findOrFail($departmentId);
        $scheme = CurriculumYears::findOrFail($schemeId);
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

        $pdf = Pdf::loadView(
            'pdf.course-details',
            compact(
                'scheme',
                'department',
                'level',
                'compulsoryCourses',
                'electiveGroups',
                'auditDetails'
            )
        );

        return $pdf->download('course-details.pdf');
    }

    public function classAward($schemeId, $departmentId)
    {

        $department = Department::findOrFail($departmentId);

        $scheme = CurriculumYears::findOrFail($schemeId);

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
        | Totals
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
            'cdc.schemes.verify.class-award',
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

    public function downloadClassAward($schemeId, $departmentId)
    {

        $department = Department::findOrFail($departmentId);

        $scheme = CurriculumYears::findOrFail($schemeId);

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
        | Totals
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

        /*
        |--------------------------------------------------------------------------
        | Render PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'pdf.class-award',
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

        return $pdf->download(
            'class_award_'.$department->abbrevation.'.pdf'
        );

    }
}
