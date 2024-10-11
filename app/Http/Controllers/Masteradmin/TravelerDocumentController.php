<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelerDocument;
use DataTables;


class TravelerDocumentController extends Controller
{
    //
    public function index(Request $request,$id)
    {
        // dd($id);
      
        $access = view()->shared('access');
        // dd($access);
        $user = Auth::guard('masteradmins')->user();
        $document = TravelerDocument::where(['id' => Auth::guard('masteradmins')->user()->id, 'tr_id' => $id])->latest()->get();
        // dd($roles);
    
        if ($request->ajax()) {
            $document = TravelerDocument::where(['id' => $user->id, 'tr_id' => $id])->latest()->get();
            //  dd($access);
            return Datatables::of($document)
                    ->addIndexColumn()
                    ->addColumn('action', function($document) use ($access){
                        $btn = '';
                        
                        if(isset($access['edit_role']) && $access['edit_role']) {
                            $btn .= '<a data-id="'.$document->trvd_id.'" data-toggle="tooltip" data-original-title="Edit Role" class="editMember"><i class="fas fa-pen-to-square edit_icon_grid"></i></a>';
                        }
                        
                        if(isset($access['delete_role']) && $access['delete_role']) {
                            $btn .= '<a data-toggle="modal" data-target="#delete-role-modal-'.$document->trvd_id.'">
                                        <i class="fas fa-trash delete_icon_grid"></i>
                                        <div class="modal fade" id="delete-role-modal-'.$document->trvd_id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body pad-1 text-center">
                                                        <i class="fas fa-solid fa-trash delete_icon"></i>
                                                        <p class="company_business_name px-10"><b>Delete Traveling Member </b></p>
                                                        <p class="company_details_text px-10">Are You Sure You Want to Delete This Traveling Member ?</p>
                                                        <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="delete_btn px-15 deleteMemberbtn" data-id='.$document->trvd_id.'>Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>';
                        }
                        // dd($access);
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->toJson();
                  
        }
      
        return view('masteradmin.trip.document-information', compact('document'));

    }

    public function store(Request $request, $id)
    {
        dd($request->all());
        $user = Auth::guard('masteradmins')->user();
        $dynamicId = $user->id; 

            $validatedData = $request->validate([
                'trvt_name' => 'required|string',
                'trvt_agent_id' => 'required|string',
                'trvt_category' => 'required|string',
                'trvt_priority' => 'required|string',
                'trvt_date' => 'required|string',
                'trvt_due_date' => 'required|string',
                'trvt_document' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [
                'trvt_name.required' => 'Task Name is required',
                'trvt_agent_id.required' => 'Assign Agent is required',
                'trvt_category.required' => 'Category is required',
                'trvt_priority.required' => 'Priority is required',
                'image.image' => 'The Document field is required.',
            ]);


                $tripTask = new TravelerDocument();
                $tableName = $tripTask->getTable();
                $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "trvt_id", $chars = 6);

                if ($request->hasFile('trvt_document')) {
                  
                    $userFolder = session('userFolder');
                    
                    $users_cert_document =  $this->handleImageUpload($request, 'trvt_document', null, 'task_image', $userFolder);
        
                    $validatedData['trvt_document'] = $users_cert_document;
                }
                
                $tripTask->fill($validatedData);

                $tripTask->tr_id = $id;
                $tripTask->id = $dynamicId;
                $tripTask->trvt_status = 1;
                $tripTask->status = 1;
                $tripTask->trvt_id = $uniqueId1;

                $tripTask->save();
          
            \MasterLogActivity::addToLog('Master Admin Trip Task is Created.');
    
     
    
        return response()->json(['success'=>'Record saved successfully.']);
    }
    
}
