<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\EmailTemplate;
use App\Models\EmailTemplateDetails;
use App\Models\Trip;
use App\Models\EmailCategory;
use App\Models\TripTravelingMember;

use Illuminate\View\View;
class EmailTemplateController extends Controller
{
    //
    public function index(): View
    {
        $user = Auth::guard('masteradmins')->user();
        $EmailTemplate = EmailTemplate::with('emailcategory')->where('id', $user->users_id)->get();
        return view('masteradmin.emailtemplate.index', compact('EmailTemplate'));
    }
    public function create(): View
    {
        $user = Auth::guard('masteradmins')->user();
        if($user->users_id && $user->role_id ==0 ){
            $emailcategory = EmailCategory::where('email_cat_status', 1)->get();
        }else{
            $emailcategory = EmailCategory::where('email_cat_status', 1)->where('id', $user->users_id)->get();
        }
        return view('masteradmin.emailtemplate.add_email_template', compact(
            'emailcategory'
        ));
    }

    public function store(Request $request): RedirectResponse
{
     //dd($request->all());
    // Get the authenticated user (master admin in this case)
    $user = Auth::guard('masteradmins')->user();
    // dd($user);
    $dynamicId = $user->users_id; // Use the user ID to dynamically set the table name

    // Validate the request data
    $validatedData = $request->validate([
        'category' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'email_text' => 'required|string',
    ], [
        'category.required' => 'Category is required',
        'email_text.required' => 'Email content is required',
    ]);
    // dd($validatedData);
    $emailTemplate = new EmailTemplate();
    $tableName = $emailTemplate->getTable();
    $uniqueId = $this->GenerateUniqueRandomString($table = $tableName, $column = "email_tid", $chars = 6);

   // dd($uniqueId);


    $emailTemplate->id= $dynamicId;
    $emailTemplate->email_tid= $uniqueId;
    $emailTemplate->category = $validatedData['category'];
    $emailTemplate->title = $validatedData['title'];
    $emailTemplate->email_text = $validatedData['email_text'];

    // Save the email template to the dynamically set table
    $emailTemplate->save();
    \MasterLogActivity::addToLog('Email Template Created.');
    // Log the creation of the email template (optional)
    // \MasterLogActivity::addToLog('Email Template Created by Master Admin.');

    // Redirect to the index page with a success message
    return redirect()->route('masteradmin.emailtemplate.index')
        ->with('success', 'Email Template created successfully.');
}
public function edit($email_tid): View
{
    // dd($email_tid);
    $user = Auth::guard('masteradmins')->user();
    if($user->users_id && $user->role_id ==0 ){
        $categories = EmailCategory::where('email_cat_status', 1)->get();
    }else{
        $categories = EmailCategory::where('email_cat_status', 1)->where('id', $user->users_id)->get();
    }
    $emailTemplate = EmailTemplate::where('email_tid', $email_tid)->firstOrFail();
    return view('masteradmin.emailtemplate.edit', compact('emailTemplate','categories'));
}

// Update the email template
public function update(Request $request, $email_tid): RedirectResponse
{
    $validatedData = $request->validate([
        'category' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'email_text' => 'required|string',
    ], [
        'category.required' => 'Category is required',
        'email_text.required' => 'Email content is required',
    ]);

    $emailTemplate = EmailTemplate::where('email_tid', $email_tid)->firstOrFail();
    $emailTemplate->category = $validatedData['category'];
    $emailTemplate->email_text = $validatedData['email_text'];
    $emailTemplate->where('email_tid', $email_tid)->update($validatedData);
    \MasterLogActivity::addToLog('Email Template Updated.');
    return redirect()->route('masteradmin.emailtemplate.index')->with('success', 'Email Template updated successfully.');
}

// Delete an email template
public function destroy($email_tid): RedirectResponse
{
    $emailTemplate = EmailTemplate::where('email_tid', $email_tid)->firstOrFail();
    $emailTemplate->where('email_tid', $email_tid)->delete();
    \MasterLogActivity::addToLog('Email Template Deleted.');
    return redirect()->route('masteradmin.emailtemplate.index')->with('success', 'Email Template deleted successfully.');
}
public function EmailTemplate(): View
{
    $user = Auth::guard('masteradmins')->user();
    $EmailTemplate = EmailTemplateDetails::with(['email_category','lead_traveler'])->where(['id' => $user->users_id])->get();
    
    // dd($EmailTemplate);
    return view('masteradmin.emailtemplate.details', compact('EmailTemplate'));
}

public function add_emailtemplate(): View
{
    $user = Auth::guard('masteradmins')->user();
    $EmailTemplate = EmailTemplateDetails::where(['id' => $user->users_id])->get();
    $user = Auth::guard('masteradmins')->user();
    $categories = EmailCategory::get();
    $travellers = TripTravelingMember::select('trtm_id','trtm_first_name')->where('trtm_status', 1)
    ->where('lead_status',1)->distinct()->get();
    
    // dd($travellers);
    return view('masteradmin.emailtemplate.add_email', compact('categories' ,'travellers'));
}

public function fetchEmailText(Request $request)
{
    // Get the selected category from the AJAX request
    $category = $request->input('category');

    // Query the database to fetch the email template with the given category
    $emailTemplate = EmailTemplate::where('category', $category)->first();

    // Check if a matching email template is found
    if ($emailTemplate) {
        // Return the email text as JSON response
        return response()->json(['email_text' => $emailTemplate->email_text]);
    }

    // If no template is found, return an empty email_text
    return response()->json(['email_text' => '']);
}


// public function fetchTravellerDetails(Request $request)
// {
//     $travellerId = $request->tr_id;
    
//     // Fetch the traveller details from the DB based on the traveller_id
//     $traveller = Trip::find($travellerId);

//     // Return traveller details
//     return response()->json([
//         'client_name' => $traveller->tr_traveler_name,
//         'phone_number' => $traveller->tr_phone_number,
//         'booking_number' => $traveller->tr_booking_number,
//         // Add any other fields you want to use in the template
//     ]);
// }
public function fetchTravellerDetails(Request $request)
{
    // dD($request->all());
    // $traveller_id = $request->input('tr_id');
    // dd($traveller_id);

    // Fetch traveller by id
    $traveller = TripTravelingMember::select('trtm_id','trtm_first_name')->where('trtm_status', 1)
    ->where('lead_status',1)->where('trtm_id',$request->traveller_id)->firstOrFail();
    
    // $traveller = Trip::where('tr_id',$request->traveller_id)->firstOrFail();
  // dd($traveller);
    if (!$traveller) {
        // If traveller is not found, return an error response or handle it accordingly
        return response()->json(['error' => 'Traveller not found.'], 404);
    }

    // Now safely access traveller properties
    return response()->json([
        'tr_traveler_name' => $traveller->trtm_first_name,
        'tr_number' => $traveller->trtm_number,
        // 'other_details' => $traveller->other_details, // Replace with actual fields
    ]);
}
public function storeEmailTemplate(Request $request): RedirectResponse
{
//    dd($request->all());
    // Get the authenticated user (master admin in this case)
    $user = Auth::guard('masteradmins')->user();
    $dynamicId = $user->users_id; // Use the user ID to dynamically set the table name

    // Validate the request data
    $validatedData = $request->validate([
        'category_id' => 'required|string|max:255',
        'traveller_id' => 'required|string', // Ensure this matches your travellers table
        'email_text' => 'required|string',
    ], [
        'category_id.required' => 'Category is required',
        'email_text.required' => 'Email content is required',
    ]);
   
    // Create a new EmailTemplateDetails instance
    $emailTemplate = new EmailTemplateDetails();
    $tableName = $emailTemplate->getTable();

    $uniqueId = $this->GenerateUniqueRandomString($table = $tableName, $column = "emt_id", $chars = 6);
    // dd($uniqueId);
    // $uniqueId = $this->GenerateUniqueRandomString($tableName, 'emt_id', 6);
    // EmailTemplate
    // \DB::enableQueryLog();

    $emailsubject = EmailTemplate::where('category', $request->category_id)->first();
 
    // dd(\DB::getQueryLog()); 

    // dd($emailsubject);
    $subject = $emailsubject->title ?? '';

    // Set the attributes for the model
    $emailTemplate->email_subject = $subject;
    $emailTemplate->id = $dynamicId;
    $emailTemplate->emt_id = $uniqueId; // Generate a unique email template ID
    $emailTemplate->traveller_id = $validatedData['traveller_id'];
    $emailTemplate->category_id = $validatedData['category_id'];
    $emailTemplate->email_text = $validatedData['email_text'];
    $emailTemplate->status = 1; // Example of setting a default value
    $emailTemplate->emt_status = 1; // Example of setting a default value

    // Save the email template to the dynamically set table
    $emailTemplate->save();
    // dd($emailTemplate );
    // Log the creation of the email template
    \MasterLogActivity::addToLog('Email Template Created by Master Admin.');

    // Redirect to the index page with a success message
    return redirect()->route('masteradmin.emailtemplate.EmailTemplate') // Adjust this to your actual route name
        ->with('success', 'Email template saved successfully!');
}

    public function delete_emailtemplate($id)
    {
        $emailTemplate = EmailTemplateDetails::where('emt_id', $id)->firstOrFail();
        $emailTemplate->where('emt_id', $id)->delete();
        \MasterLogActivity::addToLog('Email Template is Deleted.');
        return redirect()->route('masteradmin.emailtemplate.EmailTemplate')->with('success', 'Email Template deleted successfully.');
    }

    public function view_emailtemplate($id)
    {
        $user = Auth::guard('masteradmins')->user();
        $EmailTemplate = EmailTemplateDetails::with(['email_category','lead_traveler'])->where(['emt_id' => $id])->firstOrFail();
        
        // dd($traveller);
        return view('masteradmin.emailtemplate.view_email', compact( 'EmailTemplate'));
    }







}
