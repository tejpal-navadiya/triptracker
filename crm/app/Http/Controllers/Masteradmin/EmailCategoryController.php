<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmailCategory;
class EmailCategoryController extends Controller
{
    //
    public function index(){


        $user = Auth::guard('masteradmins')->user();

        $email_category = EmailCategory::where([ 'id' => $user->users_id])->get();


        return view('masteradmin.emailtemplate.category_index',compact('email_category'));
    }
    
    public function create(){

        return view('masteradmin.emailtemplate.category_create');
    }
    

    public function store(Request $request){

    //   echo '<pre>';
    //   dump($request->all());
    //   echo '</pre>';
    
    // dd($request->all());

    $user = Auth::guard('masteradmins')->user();

     $dynamicId = $user->users_id;

     $validatedData = $request->validate([
         'email_cat_status' => 'required|string',
         'email_cat_name' => 'required|string',
    
     ], [
         'email_cat_status.required' => 'Status  is required',
         'email_cat_name.required' => 'Name is required',
     
     ]);

     $email = new EmailCategory();
     $tableName = $email->getTable();
     $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "email_cat_id", $chars = 6);
    
     $email->email_cat_id = $uniqueId1;

     $email->id = $user->users_id;

     $email->email_cat_status = $validatedData['email_cat_status'];
     $email->email_cat_name = $validatedData['email_cat_name'];
    
     $email->save();

     \MasterLogActivity::addToLog('Master Admin email Category Created Created.');


     return redirect()->route('email_category.index')->with('success', 'Email Category created successfully.');

    }

    public function edit($id)
    {
        $email_categories = EmailCategory::where('email_cat_id', $id)->firstOrFail();

        $email_status = EmailCategory::all(); 

        return view('masteradmin.emailtemplate.category_edit', compact('email_categories','email_status'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::guard('masteradmins')->user();
    
        $email = EmailCategory::where('email_cat_id', $id)->firstOrFail();
    
        $validatedData = $request->validate([
            'email_cat_name' => 'required|string',
            'email_cat_status' => 'required|string',
        ], [
            'email_cat_name.required' => 'Name is required',
            'email_cat_status.required' => 'Status is required',
        ]);
    
        // Update the email record
        $email->where('email_cat_id', $id)->update($validatedData);
    
        \MasterLogActivity::addToLog('Master Admin email Category Updated.');
    
        return redirect()->route('email_category.index')->with('success', 'Email Category Updated successfully.');
    }
    

public function destroy($id)
{
    $user = Auth::guard('masteradmins')->user();

    $email = EmailCategory::where('email_cat_id', $id)->firstOrFail();

    $email->where('email_cat_id', $id)->delete();

    \MasterLogActivity::addToLog('Master Admin email Category Deleted.');

    return redirect()->route('email_category.index')->with('success', 'Email Category deleted successfully');
}

}
