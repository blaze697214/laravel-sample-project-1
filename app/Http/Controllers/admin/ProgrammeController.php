<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    //
    public function index()
    {
        $programmes = Programme::all();

        return view('admin.programme',compact('programmes'));
    }

    public function store(Request $request)
    {
        Programme::create([
            'name'=>$request->input('name'),
            'abbrevation'=>$request->input('abbrevation')
        ]);

        return redirect()->back();
    }

    public function update(Request $request,$id)
    {
        $programme = Programme::find($id);

        $programme->update([
            'name'=>$request->input('name'),
            'abbrevation'=>$request->input('abbrevation')
        ]);

        return redirect()->back();
    }

    public function destroy($id)
    {
        $programme = Programme::findOrFail($id);

        // prevent deletion if users exist
        if ($programme->users()->exists()) {
            return back()->with('error','Programme has assigned users');
        }

        $programme->delete();

        return back()->with('success','Programme deleted successfully');
    }
}
