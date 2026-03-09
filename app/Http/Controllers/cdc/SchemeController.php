<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\CurriculumYears;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchemeController extends Controller
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
