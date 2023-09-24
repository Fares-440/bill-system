<?php

namespace App\Http\Controllers;

use App\Sections;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SectionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:الاقسام', ['only' => ['index']]);
        $this->middleware('permission:اضافة قسم', ['only' => ['store']]);
        $this->middleware('permission:تعديل قسم', ['only' => ['update']]);
        $this->middleware('permission:حذف قسم', ['only' => ['delete']]);
    }
    public function index()
    {
        $sections = Sections::all();
        return view('sections.sections', compact('sections'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'section_name' => 'required|unique:sections|max:255',
            'description' => 'required',
        ], [
            'section_name.required' => 'يرجى ادخال اسم القسم',
            'section_name.unique' => 'اسم القسم مسجل مسبقاً',
            'description.required' => 'يرجى ادخال الملاحظة',
        ]);

        $sections = new Sections();
        $sections->section_name = $request->input('section_name');
        $sections->description = $request->input('description');
        $sections->created_by = (Auth::user()->name);
        $result = Sections::where('section_name', '=', $request->input('section_name'))->first();
        if (!is_null($result)) {
            Session::flash('Error', 'خطأ القسم مسجل مسبقاً');
            return redirect(route('sections'));
        } else {
            $sections->save();
            Session::flash('Add', 'تم اضافة القسم بنجاح');
            return redirect(route('sections'));
        }
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'id' => 'required',
            'section_name' => 'required|unique:sections,section_name|max:255,'.$id,
            'description' => 'required',
        ], [
            'section_name.required' => 'يرجى ادخال اسم القسم',
            'section_name.unique' => 'اسم القسم مسجل مسبقاً',
            'description.required' => 'يرجى ادخال الملاحظة',
        ]);
        $sections = Sections::find($request->input('id'));
        $sections->update([
            'section_name' => $request->section_name,
            'description' => $request->description
        ]);
        Session::flash('edit', 'تم تعديل القسم بنجاح');
        return redirect(route('sections'));
    }

    public function delete(Request $request)
    {
        Sections::find($request->id)->delete();
        Session::flash('delete', 'تم حذف القسم بنجاح');
        return redirect(route('sections'));
    }
}
