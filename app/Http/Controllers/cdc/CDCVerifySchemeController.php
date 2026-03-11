<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\CurriculumYears;
use App\Models\Programme;
use App\Models\ProgrammeLevelDetail;
use App\Services\SchemeVerificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;

class CDCVerifySchemeController extends Controller
{
    public function index()
    {
        $schemes = CurriculumYears::orderBy('year_start', 'desc')->get();

        return view('cdc.schemes.verify.index', compact('schemes'));
    }

    public function programmes($schemeId, SchemeVerificationService $service)
    {

        $programmes = Programme::all();

        $status = [];

        foreach ($programmes as $programme) {

            $verification = $service->verifyProgramme($schemeId, $programme->id);

            $status[$programme->id] = $verification['complete'];
        }

        return view(
            'cdc.schemes.verify.programmes',
            compact('programmes', 'schemeId', 'status')
        );

    }

    public function summary($schemeId, $programmeId, SchemeVerificationService $service)
    {

        $programme = Programme::findOrFail($programmeId);

        $verification = $service->verifyProgramme($schemeId, $programmeId);

        return view(
            'cdc.schemes.verify.summary',
            compact('programme', 'schemeId', 'verification')
        );

    }

    public function programmeLevelsView($schemeId, $programmeId, SchemeVerificationService $service)
    {

        $verification = $service->verifyProgramme($schemeId, $programmeId);

        if (! $verification['programmeLevel']) {

            return redirect()
                ->route('cdc.schemes.verify.summary', [$schemeId, $programmeId])
                ->with('error', 'Programme Level configuration has not been created yet.');

        }

        // load actual data
        $programme = Programme::findOrFail($programmeId);

        $rows = ProgrammeLevelDetail::with('level')
            ->where('programme_id', $programmeId)
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
            'cdc.schemes.verify.programme-levels',
            compact(
                'programme',
                'schemeId',
                'rows',
                'totals',
                'grand',
                'auditRow'
            )
        );
    }

    public function downloadProgrammeLevelsPdf($schemeId, $programmeId)
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

        $pdf = Pdf::loadView(
            'cdc.schemes.verify.programme-levels-pdf',
            compact(
                'programme',
                'schemeId',
                'rows',
                'totals',
                'grand',
                'auditRow'
            ));

        return $pdf->download('programme_structure.pdf');
    }

    public function downloadProgrammeLevelsWord($schemeId, $programmeId)
    {

        $programme = Programme::findOrFail($programmeId);

        $rows = ProgrammeLevelDetail::with('level')
            ->where('programme_id', $programmeId)
            ->whereHas('level', function ($q) use ($schemeId) {
                $q->where('curriculum_year_id', $schemeId);
            })
            ->get()
            ->sortBy('level.order_no');

        $phpWord = new PhpWord;

        $section = $phpWord->addSection();

        $section->addText(
            'PROGRAMME - '.strtoupper($programme->name)
        );

        $table = $section->addTable();

        foreach ($rows as $row) {

            $table->addRow();

            $table->addCell()->addText($row->level->name);

            $table->addCell()->addText($row->courses_offered);

            $table->addCell()->addText($row->credits);

        }

        $file = storage_path('programme_structure.docx');

        $phpWord->save($file, 'Word2007');

        return response()->download($file);

    }
}
