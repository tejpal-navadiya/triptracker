<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailCategories;

class EmailCategoriesController extends Controller
{
    //
    public function index()
    {
        $email_category = EmailCategories::all();
        return view('superadmin.email_category.index',compact('email_category'));
    }
    public function create(){

        return view('superadmin.email_category.create');
    }

    public function store(Request $request)
    {
    
         $validatedData = $request->validate([
             'email_cat_status' => 'required|string',
             'email_cat_name' => 'required|string',
        
         ], [
             'email_cat_status.required' => 'Status  is required',
             'email_cat_name.required' => 'Name is required',
         
         ]);
    
         $email = new EmailCategories();
            
         $email->email_cat_status = $validatedData['email_cat_status'];
         $email->email_cat_name = $validatedData['email_cat_name'];
        
         $email->save();
    
         \LogActivity::addToLog('Admin email Category Created Created.');
    
    
         return redirect()->route('email-categories.index')->with('success', 'Email Category created successfully.');
    
        }

        public function edit($id)
        {
            $email_categories = EmailCategories::where('email_cat_id', $id)->firstOrFail();
        
            return view('superadmin.email_category.edit', compact('email_categories'));
        }

        public function update(Request $request, $id)
        {
        
            $email = EmailCategories::where('email_cat_id', $id)->firstOrFail();
        
            $validatedData = $request->validate([
                'email_cat_name' => 'required|string',
                'email_cat_status' => 'required|string',
            ], [
                'email_cat_name.required' => 'Name is required',
                'email_cat_status.required' => 'Status is required',
            ]);
        
            // Update the email record
            $email->where('email_cat_id', $id)->update($validatedData);
        
            \LogActivity::addToLog('Admin email Category Updated.');
        
            return redirect()->route('email-categories.index')->with('success', 'Email Category Updated successfully.');
        }

        public function destroy($id)
        {

            $email = EmailCategories::where('email_cat_id', $id)->firstOrFail();

            $email->where('email_cat_id', $id)->delete();

            \LogActivity::addToLog('Admin email Category Deleted.');

            return redirect()->route('email-categories.index')->with('success', 'Email Category deleted successfully');
        }
    

}
