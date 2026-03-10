<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\CurriculumYears;
use App\Models\Programme;
use App\Services\SchemeVerificationService;

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

    // public function programmeLevels($schemeId, $programmeId, SchemeVerificationService $service)
    // {

    //     $verification = $service->verifyProgramme($schemeId, $programmeId);

    //     if (! $verification['programmeLevel']) {

    //         return redirect()
    //             ->route('cdc.schemes.verify.summary', [$schemeId, $programmeId])
    //             ->with('error', 'Programme Level configuration has not been created yet.');

    //     }

    //     // load actual data
    // }
}
