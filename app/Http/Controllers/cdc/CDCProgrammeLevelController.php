<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\CurriculumYears;
use App\Models\Levels;
use App\Models\Programme;
use App\Models\ProgrammeLevelDetail;
use Illuminate\Http\Request;

class CDCProgrammeLevelController extends Controller
{
    //
    public function index($schemeId)
    {

        $programmes = Programme::all();

        /*
        Check if programme configuration exists
        */

        $configured = ProgrammeLevelDetail::whereHas(
            'level',
            function ($q) use ($schemeId) {

                $q->where('curriculum_year_id', $schemeId);

            }
        )
            ->pluck('programme_id')
            ->unique()
            ->toArray();

        return view(
            'cdc.schemes.programme_levels.index',
            compact('programmes', 'configured', 'schemeId')
        );

    }

    public function create($schemeId, $programmeId)
    {

        $programme = Programme::findOrFail($programmeId);

        $scheme = CurriculumYears::findOrFail($schemeId);

        $levels = Levels::where('curriculum_year_id', $schemeId)
            ->orderByRaw('order_no = 0')
            ->orderBy('order_no')
            ->get();

        $existing = ProgrammeLevelDetail::where('programme_id', $programmeId)
            ->whereIn('level_id', $levels->pluck('id'))
            ->get()
            ->keyBy('level_id');

        return view(
            'cdc.schemes.programme_levels.create',
            compact(
                'programme',
                'scheme',
                'levels',
                'existing'
            ));

    }

    public function store(Request $request, $schemeId, $programmeId)
    {

        $scheme = CurriculumYears::findOrFail($schemeId);

        $totalCredits = 0;
        $totalMarks = 0;
        $totalOffered = 0;
        $totalToBeCompleted = 0;

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
                ]);

            }

        }

        /* Validate grand totals */

        if ($totalCredits != $scheme->total_credits) {

            return back()->withErrors([
                'credits' => 'Total credits must equal '.$scheme->total_credits,
            ]);

        }

        if ($totalMarks != $scheme->total_marks) {

            return back()->withErrors([
                'marks' => 'Total marks must equal '.$scheme->total_marks,
            ]);

        }

        /* Save data */

        foreach ($request->levels as $levelId) {

            ProgrammeLevelDetail::updateOrCreate(

                [
                    'programme_id' => $programmeId,
                    'level_id' => $levelId,
                ],

                [
                    'courses_offered' => $request->courses_offered[$levelId],
                    'compulsory_to_complete' => $request->compulsory[$levelId],
                    'elective_to_complete' => $request->elective[$levelId],
                    'th_hrs' => $request->th[$levelId],
                    'tu_hrs' => $request->tu[$levelId],
                    'pr_hrs' => $request->pr[$levelId],
                    'credits' => $request->credits[$levelId] ?? 0,
                    'marks' => $request->marks[$levelId] ?? 0,
                    'is_configured' => 1,
                ]

            );

        }

        return redirect()->route(
            'cdc.schemes.programmeLevels.preview',
            [$schemeId, $programmeId]
        );

    }

    public function preview($schemeId, $programmeId)
    {

        $programme = Programme::findOrFail($programmeId);

        $rows = ProgrammeLevelDetail::with('level')
            ->where('programme_id', $programmeId)
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
            'cdc.schemes.programme_levels.preview',
            compact('programme', 'rows', 'totals', 'auditRow', 'grand', 'schemeId')
        );

    }

    public function finalize($schemeId, $programmeId)
    {

        ProgrammeLevelDetail::where('programme_id', $programmeId)
            ->update([
                'is_configured' => 1,
            ]);

        return redirect()->route(
            'cdc.schemes.programmeLevels.index',
            $schemeId
        )->with('success', 'Programme configuration saved');

    }
}
