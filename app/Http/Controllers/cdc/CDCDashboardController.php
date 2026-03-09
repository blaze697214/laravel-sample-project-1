<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\CurriculumYears;
use App\Models\User;

class CDCDashboardController extends Controller
{
    public function dashboard()
    {

        $totalSchemes = CurriculumYears::count();

        $activeScheme = CurriculumYears::where('is_active', 1)
            ->first();

        $activeScheme = $activeScheme
                        ? $activeScheme->year_start.'-'.$activeScheme->year_end
                        : null;

        $totalHods = User::whereHas('roles', function ($q) {
            $q->where('name', 'hod');
        })->count();

        $totalFaculty = User::whereHas('roles', function ($q) {
            $q->where('name', 'faculty');
        })->count();

        $schemes = CurriculumYears::latest()->get();

        $recentHods = User::with('programme')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'hod');
            })
            ->latest()
            ->take(5)
            ->get();

        return view('cdc.dashboard', compact(
            'totalSchemes',
            'activeScheme',
            'totalHods',
            'totalFaculty',
            'schemes',
            'recentHods'
        ));
    }
}
