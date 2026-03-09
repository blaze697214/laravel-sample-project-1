<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\CurriculumYears;
use App\Models\Levels;
use Illuminate\Http\Request;

class CDCLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($schemeId)
    {

        $scheme = CurriculumYears::findOrFail($schemeId);

        $levels = Levels::where('curriculum_year_id', $schemeId)
            ->orderByRaw('order_no = 0')
            ->orderBy('order_no')
            ->get();

        return view('cdc.schemes.levels', compact(
            'scheme',
            'levels'
        ));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $schemeId)
    {

        $isAudit = $request->has('is_audit');

        if ($isAudit) {

            // check if audit level already exists
            $exists = Levels::where('curriculum_year_id', $schemeId)
                ->where('is_audit', 1)
                ->exists();

            if ($exists) {
                return back()->with('error', 'Audit level already exists');
            }

            $order = 0;

        } else {

            $order = Levels::where('curriculum_year_id', $schemeId)
                ->where('is_audit', 0)
                ->count() + 1;

        }

        Levels::create([

            'name' => $request->input('name'),
            'order_no' => $order,
            'is_audit' => $isAudit,
            'curriculum_year_id' => $schemeId,

        ]);

        return back()->with('success', 'Level added');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $levelId)
    {

        $request->validate([
            'name' => 'required',
        ]);

        $level = Levels::findOrFail($levelId);

        $level->update([
            'name' => $request->input('name'),
        ]);

        return back()->with('success', 'Level updated');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($levelId)
    {

        Levels::findOrFail($levelId)->delete();

        return back()->with('success', 'Level deleted');

    }
}
