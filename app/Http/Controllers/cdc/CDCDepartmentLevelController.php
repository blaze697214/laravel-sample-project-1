<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\CurriculumYears;
use App\Models\Levels;
use App\Models\Department;
use App\Models\DepartmentLevelDetail;
use Illuminate\Http\Request;

class CDCDepartmentLevelController extends Controller
{
    //
    public function index($schemeId)
    {

        $departments = Department::all();

        /*
        Check if department configuration exists
        */

        $configured = DepartmentLevelDetail::whereHas(
            'level',
            function ($q) use ($schemeId) {

                $q->where('curriculum_year_id', $schemeId);

            }
        )
            ->pluck('department_id')
            ->unique()
            ->toArray();

        return view(
            'cdc.schemes.department_levels.index',
            compact('departments', 'configured', 'schemeId')
        );

    }

    public function create($schemeId, $departmentId)
    {

        $department = Department::findOrFail($departmentId);

        $scheme = CurriculumYears::findOrFail($schemeId);

        $levels = Levels::where('curriculum_year_id', $schemeId)
            ->orderByRaw('order_no = 0')
            ->orderBy('order_no')
            ->get();

        $existing = DepartmentLevelDetail::where('department_id', $departmentId)
            ->whereIn('level_id', $levels->pluck('id'))
            ->get()
            ->keyBy('level_id');

        return view(
            'cdc.schemes.department_levels.create',
            compact(
                'department',
                'scheme',
                'levels',
                'existing'
            ));

    }

    public function store(Request $request, $schemeId, $departmentId)
    {

        $scheme = CurriculumYears::findOrFail($schemeId);

        $totalCredits = 0;
        $totalMarks = 0;

        foreach ($request->input('levels') as $levelId) {

            $level = Levels::findOrFail($levelId);

            if ($level->is_audit) {

                $credits = 0;
                $marks = 0;

            } else {

                $credits = $request->credits[$levelId] ?? 0;
                $marks = $request->marks[$levelId] ?? 0;

            }

            if (! $level->is_audit) {

                $totalCredits += $credits;
                $totalMarks += $marks;

            }

            $offered = $request->courses_offered[$levelId] ?? 0;

            $compulsory = $request->compulsory[$levelId] ?? 0;
            $elective = $request->elective[$levelId] ?? 0;

            $toBeCompleted = $compulsory + $elective;

            if ($offered < $toBeCompleted) {

                return back()->withErrors([
                    'offered' => $level->name.
                    ': Course offered must be > Course to be completed(compulsory+elective)',
                ])->withInput();

            }

        }

        /* Validate grand totals */

        if ($totalCredits != $scheme->total_credits) {

            return back()->withErrors([
                'credits' => 'Total credits must equal '.$scheme->total_credits,
            ])->withInput();

        }

        if ($totalMarks != $scheme->total_marks) {

            return back()->withErrors([
                'marks' => 'Total marks must equal '.$scheme->total_marks,
            ])->withInput();

        }

        /* Save data */

        foreach ($request->levels as $levelId) {

            DepartmentLevelDetail::updateOrCreate(

                [
                    'department_id' => $departmentId,
                    'level_id' => $levelId,
                ],

                [
                    'courses_offered' => $request->courses_offered[$levelId],
                    'compulsory_to_complete' => $request->compulsory[$levelId],
                    'elective_to_complete' => $request->elective[$levelId] ?? 0,
                    'th_hrs' => $request->th[$levelId] ?? 0,
                    'tu_hrs' => $request->tu[$levelId] ?? 0,
                    'pr_hrs' => $request->pr[$levelId] ?? 0,    
                    'credits' => $request->credits[$levelId] ?? 0,
                    'marks' => $request->marks[$levelId] ?? 0,
                    'is_configured' => 1,
                ]

            );

        }

        return redirect()->route(
            'cdc.schemes.departmentLevels.preview',
            [$schemeId, $departmentId]
        );

    }

    public function preview($schemeId, $departmentId)
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

        return view(
            'cdc.schemes.department_levels.preview',
            compact('department', 'rows', 'totals', 'auditRow', 'grand', 'schemeId')
        );

    }

    public function finalize($schemeId, $departmentId)
    {

        DepartmentLevelDetail::where('department_id', $departmentId)
            ->update([
                'is_configured' => 1,
            ]);

        return redirect()->route(
            'cdc.schemes.departmentLevels.index',
            $schemeId
        )->with('success', 'Department configuration saved');

    }
}
