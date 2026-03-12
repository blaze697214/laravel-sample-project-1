<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\CurriculumYears;
use App\Models\Programme;
use App\Models\ProgrammeLevelDetail;
use Illuminate\Support\Facades\Auth;

class HODSchemeController extends Controller
{
    public function index()
    {

        $programmeId = Auth::user()->programme_id;

        $programme = Programme::findOrFail($programmeId);

        $activeScheme = CurriculumYears::where('is_active', 1)->firstOrFail();

        $rows = ProgrammeLevelDetail::with('level')
            ->where('programme_id', $programmeId)
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

            $row->total_hours = $row->th_hrs + $row->tu_hrs + $row->pr_hrs;

            if ($row->level->is_audit) {

                $auditRow = $row;

                continue;

            }

            $totals['courses'] += $row->courses_offered;

            $completed = $row->compulsory_to_complete + $row->elective_to_complete;

            $totals['completed'] += $completed;

            $totals['compulsory'] += $row->compulsory_to_complete;

            $totals['elective'] += $row->elective_to_complete;

            $totals['th'] += $row->th_hrs;

            $totals['tu'] += $row->tu_hrs;

            $totals['pr'] += $row->pr_hrs;

            $totals['hours'] += $row->total_hours;

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
            'hod.scheme.index',
            compact('programme', 'rows', 'totals', 'auditRow', 'grand')
        );

    }
}
