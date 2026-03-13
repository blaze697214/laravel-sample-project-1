<?php

namespace App\Http\Controllers\cdc;

use App\Http\Controllers\Controller;
use App\Models\Courses;
use App\Models\CurriculumYears;
use App\Models\Department;
use App\Models\Levels;
use Illuminate\Http\Request;

class CDCCourseController extends Controller
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

        $programmeDepartments = Department::where('type', 'programme')->get();
        $serviceDepartments = Department::where('type', 'service')->get();

        $courses = Courses::where('curriculum_year_id', $schemeId)
            ->with('departments')
            ->get()
            ->groupBy('level_id');

        return view(
            'cdc.schemes.courses',
            compact('scheme', 'levels', 'programmeDepartments', 'serviceDepartments', 'courses')
        );

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $schemeId)
    {

        $request->validate([
            'title' => 'required',
            'abbrevation' => 'required',
            'level_id' => 'required|exists:levels,id',
            'offered' => 'required|exists:departments,id',
        ]);

        $course = Courses::create([

            'title' => $request->input('title'),
            'abbrevation' => $request->input('abbrevation'),
            'curriculum_year_id' => $schemeId,
            'level_id' => $request->input('level_id'),
            'owner_department_id' => $request->input('owner_department_id'),

        ]);

        $data = [];

        if ($request->input('offered')) {

            foreach ($request->input('offered') as $departmentId) {

                $data[$departmentId] = [
                    'is_elective' => isset($request->input('elective')[$departmentId]),
                ];

            }

            $course->departments()->attach($data);

        }

        return back()->with('success', 'Course created');

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
    public function update(Request $request, $courseId)
    {

        $request->validate([
            'title' => 'required',
            'abbrevation' => 'required',
        ]);

        $course = Courses::findOrFail($courseId);

        $course->update([
            'title' => $request->input('title'),
            'abbrevation' => $request->input('abbrevation'),
        ]);

        return back()->with('success', 'Course updated');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($courseId)
    {

        $course = Courses::findOrFail($courseId);

        /* remove pivot records first */
        $course->departments()->detach();

        $course->delete();

        return back()->with('success', 'Course deleted');

    }

    public function next($schemeId)
    {

        $courseCount = Courses::where('curriculum_year_id', $schemeId)->count();

        if ($courseCount == 0) {

            return back()->withErrors([
                'courses' => 'Please add at least one course before saving.',
            ]);

        }

        return redirect()->route(
            'cdc.schemes.departmentLevels.index',
            $schemeId
        );

    }
}
