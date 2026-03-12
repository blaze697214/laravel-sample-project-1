<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\CurriculumYears;
use App\Models\Programme;
use App\Models\ProgrammeLevelDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CDCSchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $schemes = CurriculumYears::orderBy('year_start', 'desc')->get();

        return view(
            'cdc.schemes.manage',
            compact('schemes')
        );

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cdc.schemes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $scheme = CurriculumYears::create([

            'year_start' => $request->input('year_start'),

            'year_end' => $request->input('year_start') + 3,

            'total_credits' => $request->input('total_credits'),

            'total_marks' => $request->input('total_marks'),

            'created_by' => Auth::id(),

            'is_active' => 0,

            'is_locked' => 0,

        ]);

        return redirect()->route(
            'cdc.schemes.levels.create',
            $scheme->id
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function editIndex()
    {

        $schemes = CurriculumYears::orderBy('year_start', 'desc')->get();

        return view('cdc.schemes.edit', compact('schemes'));

    }

    public function edit($schemeId)
    {

        return redirect()
            ->route('cdc.schemes.levels.create', $schemeId);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function toggleActive($schemeId)
    {

        $scheme = CurriculumYears::findOrFail($schemeId);

        if ($scheme->is_locked) {

            return back()->withErrors([
                'scheme' => 'Locked scheme cannot be activated',
            ]);

        }

        /* deactivate other schemes */

        CurriculumYears::where('is_active', 1)->update([
            'is_active' => 0,
        ]);

        /* activate selected */

        $scheme->update([
            'is_active' => ! $scheme->is_active,
        ]);

        return back()->with('success', 'Active scheme updated');

    }

    public function toggleLock($schemeId)
    {

        $scheme = CurriculumYears::findOrFail($schemeId);

        /* if locking, deactivate */

        if (! $scheme->is_locked) {

            $scheme->update([
                'is_locked' => 1,
                'is_active' => 0,
            ]);

        } else {

            $scheme->update([
                'is_locked' => 0,
            ]);

        }

        return back()->with('success', 'Scheme lock status updated');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($schemeId)
    {

        $scheme = CurriculumYears::findOrFail($schemeId);

        if ($scheme->is_locked) {

            return back()->withErrors([
                'scheme' => 'Locked scheme cannot be deleted',
            ]);

        }

        $scheme->delete();

        return back()->with('success', 'Scheme deleted');

    }

    public function finalize($schemeId)
    {

        $scheme = CurriculumYears::findOrFail($schemeId);

        /*
        Optional safety check:
        Ensure all programmes configured
        */

        $programmes = Programme::count();

        $configured = ProgrammeLevelDetail::whereHas('level', function ($q) use ($schemeId) {
            $q->where('curriculum_year_id', $schemeId);
        })
            ->distinct('programme_id')
            ->count('programme_id');

        if ($configured < $programmes) {

            return back()->withErrors([
                'scheme' => 'All programmes must be configured before saving the scheme.',
            ]);

        }

        /* mark scheme finalized */

        $scheme->update([
            'is_locked' => 1,
        ]);

        return redirect()
            ->route('cdc.schemes.create')
            ->with('success', 'Scheme created successfully');

    }
}
