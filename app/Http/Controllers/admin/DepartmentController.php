<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    //
    public function index()
    {
        $departments = Department::all();

        return view('admin.department',compact('departments'));
    }

    public function store(Request $request)
    {
        Department::create([
            'name'=>$request->input('name'),
            'abbrevation'=>$request->input('abbrevation'),
            'type'=>$request->input('type')
        ]);

        return redirect()->back();
    }

    public function update(Request $request,$id)
    {
        $department = Department::find($id);

        $department->update([
            'name'=>$request->input('name'),
            'abbrevation'=>$request->input('abbrevation')
        ]);

        return redirect()->back();
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);

        // prevent deletion if users exist
        if ($department->users()->exists()) {
            return back()->with('error','Department has assigned users');
        }

        $department->delete();

        return back()->with('success','Department deleted successfully');
    }
}
