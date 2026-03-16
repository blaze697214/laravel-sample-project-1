<?php

namespace App\Http\Controllers\hod;

use App\Http\Controllers\Controller;
use App\Models\Courses;
use App\Models\CurriculumYears;
use App\Models\DepartmentCourse;
use App\Models\DepartmentLevelDetail;
use App\Models\ElectiveGroup;
use App\Models\Levels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HODElectiveController extends Controller
{
    public function index(Request $request)
    {

        $department = Auth::user()->department;

        $activeScheme = CurriculumYears::where('is_active', true)->first();

        /*
        |--------------------------------------------------------------------------
        | Elective Summary
        |--------------------------------------------------------------------------
        */

        $totalElectives = DepartmentCourse::where('department_id', $department->id)
            ->where('is_elective', true)
            ->count();

        $groupedElectives = ElectiveGroup::where('department_id', $department->id)
            ->withCount('courses')
            ->get()
            ->sum('courses_count');

        $remainingElectives = $totalElectives - $groupedElectives;

        /*
        |--------------------------------------------------------------------------
        | Levels
        |--------------------------------------------------------------------------
        */

        $levels = Levels::where('curriculum_year_id', $activeScheme->id)
            ->whereHas('courses.departmentCourses', function ($q) use ($department) {

                $q->where('department_id', $department->id)
                    ->where('is_elective', true);

            })
            ->orderBy('order_no')
            ->get();

        $selectedLevel = $request->level ?? $levels->first()->id ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Available Elective Courses
        |--------------------------------------------------------------------------
        */

        $availableCourses = Courses::where('curriculum_year_id', $activeScheme->id)
            ->where('level_id', $selectedLevel)
            ->whereHas('departmentCourses', function ($q) use ($department) {

                $q->where('department_id', $department->id)
                    ->where('is_elective', true);

            })
            ->whereDoesntHave('electiveGroup')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Existing Groups
        |--------------------------------------------------------------------------
        */

        $groups = ElectiveGroup::where('department_id', $department->id)
            ->with(['courses', 'level'])
            ->get();

        return view('hod.electives.index', compact(
            'totalElectives',
            'groupedElectives',
            'remainingElectives',
            'levels',
            'availableCourses',
            'groups',
            'activeScheme'
        ));
    }

    public function store(Request $request)
    {

        $department = Auth::user()->department;

        $request->validate([

            'name' => 'required|string|max:255',
            'level_id' => 'required',
            'min_select_count' => 'required|integer|min:1',
            'max_select_count' => 'required|integer|min:1',
            'courses' => 'required|array',

        ]);

        if ($request->min_select_count > $request->max_select_count) {

            return back()->withErrors([
                'min_select_count' => 'Minimum selection cannot be greater than maximum selection.',
            ])->withInput();
        }
        if ($request->max_select_count > count($request->courses)) {

            return back()->withErrors([
                'max_select_count' => 'Maximum selection cannot exceed the number of courses in the group.',
            ])->withInput();

        }
        $selectedCourses = count($request->courses);

        if ($selectedCourses != $request->max_select_count) {

            return back()->withErrors([
                'courses' => 'Number of selected courses must be equal to maximum selection.',
            ])->withInput();

        }

        $levelId = $request->input('level_id');

        /*
        |--------------------------------------------------------------------------
        | Existing min_select_count for THIS level
        |--------------------------------------------------------------------------
        */

        $existingMin = ElectiveGroup::where('department_id', $department->id)
            ->where('level_id', $levelId)
            ->sum('min_select_count');

        $newTotal = $existingMin + $request->input('min_select_count');

        /*
        |--------------------------------------------------------------------------
        | Electives required for THIS level
        |--------------------------------------------------------------------------
        */

        $requiredElectives = DepartmentLevelDetail::where('department_id', $department->id)
            ->where('level_id', $levelId)
            ->value('elective_to_complete');

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        if ($newTotal > $requiredElectives) {

            return back()->withErrors([
                'min_select_count' => 'Total minimum electives for this level cannot exceed '
                    .$requiredElectives.'.',
            ]);

        }

        $group = ElectiveGroup::create([

            'name' => $request->name,
            'level_id' => $request->level_id,
            'department_id' => $department->id,
            'min_select_count' => $request->min_select_count,
            'max_select_count' => $request->max_select_count,

        ]);

        $group->courses()->attach($request->courses);

        return redirect()
            ->route('hod.electives')
            ->with('success', 'Elective group created successfully');

    }

    public function destroy(ElectiveGroup $group)
    {

        $group->delete();

        return redirect()
            ->route('hod.electives')
            ->with('success', 'Elective group deleted');

    }
}
