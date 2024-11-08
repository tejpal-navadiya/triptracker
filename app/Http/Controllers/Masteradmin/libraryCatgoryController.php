<?php

namespace App\Http\Controllers\masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LibraryCategory;


class libraryCatgoryController extends Controller
{

    public function index(){


        $user = Auth::guard('masteradmins')->user();

        $library_category = LibraryCategory::all();


        return view('masteradmin.library.category_index',compact('library_category'));
    }
    
    public function create(){

        return view('masteradmin.library.category_create');
    }
    

    public function store(Request $request){

    //   echo '<pre>';
    //   dump($request->all());
    //   echo '</pre>';
    
    // dd($request->all());

    $user = Auth::guard('masteradmins')->user();

     $dynamicId = $user->users_id;

     $validatedData = $request->validate([
         'lib_cat_status' => 'required|string',
         'lib_cat_name' => 'required|string',
    
     ], [
         'lib_cat_status.required' => 'Status  is required',
         'lib_cat_name.required' => 'Name is required',
     
     ]);

     $library = new LibraryCategory();
     $tableName = $library->getTable();
     $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "lib_cat_id", $chars = 6);
    
     $library->lib_cat_id = $uniqueId1;

     $library->id = $user->users_id;

     $library->lib_cat_status = $validatedData['lib_cat_status'];
     $library->lib_cat_name = $validatedData['lib_cat_name'];
    
     $library->save();

     \MasterLogActivity::addToLog('Master Admin Library Category Created Created.');


     return redirect()->route('library_category.index')->with('success', 'Library Category created successfully.');

    }

    public function edit($id)
    {
        $Library_categories = LibraryCategory::where('lib_cat_id', $id)->firstOrFail();

        $Lib_status = LibraryCategory::all(); 

        return view('masteradmin.library.category_edit', compact('Library_categories','Lib_status'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::guard('masteradmins')->user();
    
        $library = LibraryCategory::where('lib_cat_id', $id)->firstOrFail();
    
        $validatedData = $request->validate([
            'lib_cat_name' => 'required|string',
            'lib_cat_status' => 'required|string',
        ], [
            'lib_cat_name.required' => 'Name is required',
            'lib_cat_status.required' => 'Status is required',
        ]);
    
        // Update the library record
        $library->update($validatedData);
    
        \MasterLogActivity::addToLog('Master Admin Library Category Updated.');
    
        return redirect()->route('library_category.index')->with('success', 'Library Category Updated successfully.');
    }
    

public function destroy($id)
{
    $user = Auth::guard('masteradmins')->user();

    $library = LibraryCategory::where('lib_cat_id', $id)->firstOrFail();

    $library->where('lib_cat_id', $id)->delete();

    \MasterLogActivity::addToLog('Master Admin Library Category Deleted.');

    return redirect()->route(route: 'library_category.index')->with('success', 'Library Category deleted successfully');
}


    
}
