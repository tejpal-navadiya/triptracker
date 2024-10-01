<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCertification;
use Illuminate\Support\Facades\Storage;
class UserCertificationController extends Controller
{
    //

    public function store(Request $request)
    {
        // dd($request->all());
        $user = Auth::guard('masteradmins')->user();
    
        $validatedData = $request->validate([
            'users_cert_name' => 'required|string|max:255',
            'users_cert_person_name' => 'required|string|max:255',
            'users_cert_completed_date' => 'required|string|max:255',
            'users_cert_expiration' => 'required|string|max:255',
            'users_cert_desc' => 'required|string|max:255',
            'users_cert_document' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image is nullable for updates
        ], [
            'users_cert_name.required' => 'The Certification Name field is required.',
            'users_cert_person_name.required' => 'The Person Name field is required.',
            'users_cert_completed_date.required' => 'The Date Completed field is required.',
            'users_cert_expiration.required' => 'The Expiration date field is required.',
            'users_cert_desc.required' => 'The Descriptions field is required.',
        ]);
    
        if ($request->has('users_cert_id') && $request->users_cert_id != null) {
            //update
            $userCertification = UserCertification::where('users_cert_id', $request->users_cert_id)->first();
    
            if (!$userCertification) {
                return response()->json(['error' => 'Record not found for update.'], 404);
            }
        } else {
            //insert
            $userCertification = new UserCertification();
            $tableName = $userCertification->getTable();
            $uniqueId = $this->GenerateUniqueRandomString($table = $tableName, $column = "users_cert_id", $chars = 6);
          
        }
    
        if ($request->hasFile('image')) {
            $users_cert_document = $this->handleImageUpload($request, $request->file('image'), 'masteradmin/certification_image');
            $validatedData['users_cert_document'] = $users_cert_document;
        } else {
            $validatedData['users_cert_document'] = $userCertification->users_cert_document ?? '';
        }
        if ($request->has('users_cert_id') && $request->users_cert_id != null) {
            
            $userCertification->where('users_cert_id', $request->users_cert_id)->update($validatedData);
            \MasterLogActivity::addToLog('Master Admin Users Certification Created.');
        }else{
            $userCertification->fill([
                'users_cert_id' => $uniqueId,
                'users_cert_name' => $validatedData['users_cert_name'],
                'users_cert_person_name' => $validatedData['users_cert_person_name'],
                'users_cert_completed_date' => $validatedData['users_cert_completed_date'],
                'users_cert_expiration' => $validatedData['users_cert_expiration'],
                'users_cert_desc' => $validatedData['users_cert_desc'],
                'users_cert_document' => $validatedData['users_cert_document'],
                'id' => $user->id, 
                'users_status' => 1,
            ]);
            $userCertification->save();

            \MasterLogActivity::addToLog('Master Admin Users Certification Updated.');
        }
        
    
        return response()->json(['success' => 'Record saved successfully.']);
    }
    
    


    public function index(Request $request)
    {
     
        if ($request->ajax()) {
            
            $user = Auth::guard('masteradmins')->user();
            $certification = UserCertification::where(['id' => $user->id])->latest()->get();
            if ($certification) {
                return response()->json(['users_certification' => $certification]);  // Return the entire user object for debugging
            } else {
                return response()->json(['status' => 404, 'message' => 'User Certification not found']);
            }
           
        }
        
    }

    public function edit($id)
    {
        $certification = UserCertification::where('users_cert_id',$id)->firstOrFail();
        return response()->json($certification);
    }

    public function destroy($id)
    {
        $userCertification = UserCertification::where('users_cert_id', $id)->first();

        if ($userCertification) {
            if ($userCertification->users_cert_document) {
                Storage::delete('masteradmin/certification_image/' . $userCertification->users_cert_document);
            }

            $userCertification->where('users_cert_id', $id)->delete();

            return response()->json(['success' => 'Certification deleted successfully.']);
        }

        return response()->json(['error' => 'Certification not found.'], 404);
    }
}
