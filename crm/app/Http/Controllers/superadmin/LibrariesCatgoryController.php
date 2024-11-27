<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LibrariesCatgory;

class LibrariesCatgoryController extends Controller
{
    //
    public function index()
    {

        $library_category = LibrariesCatgory::all();

        return view('superadmin.library_category.index',compact('library_category'));
    }
    public function create(){

        return view('superadmin.library_category.create');
    }
    

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'lib_cat_status' => 'required|string',
            'lib_cat_name' => 'required|string',
        
        ], [
            'lib_cat_status.required' => 'Status  is required',
            'lib_cat_name.required' => 'Name is required',
        
        ]);

        $library = new LibrariesCatgory();
        $library->lib_cat_status = $validatedData['lib_cat_status'];
        $library->lib_cat_name = $validatedData['lib_cat_name'];
        
        $library->save();

        \LogActivity::addToLog('Super Admin Library Category Created Created.');


     return redirect()->route('libraries-category.index')->with('success', 'Library Category created successfully.');

    }

    public function edit($id)
    {
        $Library_categories = LibrariesCatgory::where('lib_cat_id', $id)->firstOrFail();

        $Lib_status = LibrariesCatgory::all(); 

        return view('superadmin.library_category.edit', compact('Library_categories','Lib_status'));
    }

    public function update(Request $request, $id)
    {
    
        $library = LibrariesCatgory::where('lib_cat_id', $id)->firstOrFail();
    
        $validatedData = $request->validate([
            'lib_cat_name' => 'required|string',
            'lib_cat_status' => 'required|string',
        ], [
            'lib_cat_name.required' => 'Name is required',
            'lib_cat_status.required' => 'Status is required',
        ]);
    
        // Update the library record
        $library->where('lib_cat_id', $id)->update($validatedData);
    
        \LogActivity::addToLog('Super Admin Library Category Updated.');
    
        return redirect()->route('libraries-category.index')->with('success', 'Library Category Updated successfully.');
    }
    

    public function destroy($id)
    {

        $library = LibrariesCatgory::where('lib_cat_id', $id)->firstOrFail();

        $library->where('lib_cat_id', $id)->delete();

        \LogActivity::addToLog('Super Admin Library Category Deleted.');

        return redirect()->route(route: 'libraries-category.index')->with('success', 'Library Category deleted successfully');
    }


}
