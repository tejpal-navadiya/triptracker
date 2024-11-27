<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\EmailsTemplates;
use App\Models\EmailCategories;

class EmailsTemplatesController extends Controller
{
    //
    public function index(): View
    {
        $EmailTemplate = EmailsTemplates::with('emailcategory')->get();
       // dd($EmailTemplate);
        return view('superadmin.email_template.index', compact('EmailTemplate'));
    }
    public function create()
    {
        $emailcategory = EmailCategories::where('email_cat_status', 1)->get();
        
        return view('superadmin.email_template.create', compact(
            'emailcategory'
        ));

    }
    
    public function store(Request $request)
    {
    // dd($request->all());
        $validatedData = $request->validate([
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'email_text' => 'required|string',
        ], [
            'category.required' => 'Category is required',
            'email_text.required' => 'Email content is required',
        ]);
    
        $emailTemplate = new EmailsTemplates();
        
        $emailTemplate->category = $validatedData['category'];
        $emailTemplate->title = $validatedData['title'];
        $emailTemplate->email_text = $validatedData['email_text'];
    
        $emailTemplate->save();

        \LogActivity::addToLog('Admin Email Template is Created.');
    
        return redirect()->route('emails-templates.index')
            ->with('success', 'Email Template created successfully.');
    }

    public function edit($email_tid): View
    {
        $categories = EmailCategories::where('email_cat_status', 1)->get();
        
        $emailTemplate = EmailsTemplates::where('email_tid', $email_tid)->firstOrFail();

        return view('superadmin.email_template.edit', compact('emailTemplate','categories'));
    }

    public function update(Request $request, $email_tid)
    {
        $validatedData = $request->validate([
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'email_text' => 'required|string',
        ], [
            'category.required' => 'Category is required',
            'email_text.required' => 'Email content is required',
        ]);

        $emailTemplate = EmailsTemplates::where('email_tid', $email_tid)->firstOrFail();
        $emailTemplate->category = $validatedData['category'];
        $emailTemplate->email_text = $validatedData['email_text'];
        $emailTemplate->where('email_tid', $email_tid)->update($validatedData);

        \LogActivity::addToLog('Admin Email Template is Updated.');

        return redirect()->route('emails-templates.index')->with('success', 'Email Template updated successfully.');
    }

    public function destroy($email_tid)
    {
        $emailTemplate = EmailsTemplates::where('email_tid', $email_tid)->firstOrFail();
        $emailTemplate->where('email_tid', $email_tid)->delete();
        \LogActivity::addToLog('Email Template Deleted.');
        return redirect()->route('emails-templates.index')->with('success', 'Email Template deleted successfully.');
    }

}
