<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelerDocument;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class TravelerDocumentController extends Controller
{
    //
    public function index(Request $request,$id)
    {
        // dd($id);
      
        $access = view()->shared('access');
        // dd($access);
        $user = Auth::guard('masteradmins')->user();
        $document = TravelerDocument::where(['tr_id' => $id])->with(['traveler', 'documenttype','trip'])->latest()->get();
        

       //dd($document);
    
        if ($request->ajax()) {
            $document = TravelerDocument::where([ 'tr_id' => $id])->with(['traveler', 'documenttype','trip'])->latest()->get();
            //  dd($access);
            return Datatables::of($document)
                    ->addIndexColumn()
                    
                    ->addColumn('traveler_name', function($document) {
                        $firstName = $document->traveler->trtm_first_name ?? '';
                        $middleName = $document->traveler->trtm_middle_name ?? '';
                        $lastName = $document->traveler->trtm_last_name ?? '';
                        
                        return trim($firstName . ' ' . $middleName . ' ' . $lastName) ?: $document->trip->tr_traveler_name;
                    })
                    ->addColumn('document_type', function($document) {
                        return $document->documenttype->docty_name ?? 'Unknown Document Type';

                    })
            
                    ->addColumn('trvd_document', function($document) {
                        $images = json_decode($document->trvd_document, true);
                    
                        return $images;
                    })
                
                    ->addColumn('action', function($document) use ($access){
                        $btn = '';

                        if(isset($access['workflow']) && $access['workflow']) {
                            $btn .= '<a data-id="'.$document->trvd_id.'" data-toggle="tooltip" data-original-title="Edit Role" class="editDocument"><i class="fas fa-pen-to-square edit_icon_grid"></i></a>';
                        }

                        if(isset($access['workflow']) && $access['workflow']) {

                            $images = json_decode($document->trvd_document, true);

                            $userFolder = session('userFolder');
                            $baseUrl = config('app.image_url');

                            
                            $btn .= '
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-download download_icon_grid"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                                        foreach ($images as $image) {
                                            $imagePath = $userFolder . '/document_image/' . $image;
                                            $imageUrl = ($imagePath);
                                            
                                            $baseUrl = rtrim($baseUrl, '/');
                                            
                                            $fullImageUrl = $baseUrl . '/'.$imageUrl;
                                            
                                            $btn .= '<a class="dropdown-item" href="' . $fullImageUrl . '" download>' . $image . '</a>';
                                        }
                            $btn .= '</div>
                                    </div>';
                        }
                        
                        if(isset($access['workflow']) && $access['workflow']) {
                            $btn .= '<a data-toggle="modal" data-target="#delete-role-modal-'.$document->trvd_id.'">
                                        <i class="fas fa-trash delete_icon_grid"></i>
                                        <div class="modal fade" id="delete-role-modal-'.$document->trvd_id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body pad-1 text-center">
                                                        <i class="fas fa-solid fa-trash delete_icon"></i>
                                                        <p class="company_business_name px-10"><b>Delete Document </b></p>
                                                        <p class="company_details_text px-10">Are You Sure You Want to Delete This Document ?</p>
                                                        <button type="button" class="add_btn px-15" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="delete_btn px-15 deleteDocumentbtn" data-id='.$document->trvd_id.'>Delete</button>
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
    //  dd($request->all());
        $user = Auth::guard('masteradmins')->user();
        $dynamicId = $user->users_id; 

            $validatedData = $request->validate([
                'trvd_name' => 'required|string',
                'trvm_id' => 'required|string',
                'trvd_document' => 'required',
                'trvd_document.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',

            ], [
                'trvd_name.required' => 'Documents Type is required',
                'trvm_id.required' => 'Traveler is required',
                'trvd_document.required' => 'The Document is required.',
                'trvd_document.*.image' => 'The Document must be an image.',
                'trvd_document.*.mimes' => 'The Document must be a file of type: jpeg, png, jpg, gif, svg.',
                'trvd_document.*.max' => 'The Document may not be greater than 2048 kilobytes.',
            ]);


                $tripDocument = new TravelerDocument();
                $tableName = $tripDocument->getTable();
                $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "trvd_id", $chars = 6);
                
                if ($request->hasFile('trvd_document')) {

                    if (is_array($request->file('trvd_document'))) {
                         
                        $userFolder = session('userFolder');
                        $documents_images =  $this->handleImageUpload($request, 'trvd_document', null, 'document_image', $userFolder);
        
                        $documentimg = json_encode($documents_images);
        
                    }
                } 
                
                $tripDocument->fill($validatedData);

                $tripDocument->tr_id = $id;
                $tripDocument->id = $user->users_id;
                $tripDocument->trvd_document = $documentimg; 
                $tripDocument->trvd_status = 1;
                $tripDocument->trvd_id = $uniqueId1;

                $tripDocument->save();
          
            \MasterLogActivity::addToLog('Master Admin Trip Traveller Document is Uploaded.');
    
     
        return response()->json(['success'=>'Record saved successfully.']);
    }
    
    public function edit($id, $trip_id)
    {   

        $document = TravelerDocument::where(['trvd_id' => $id, 'tr_id' => $trip_id])->firstOrFail();

        return response()->json($document);
    }

    public function deleteImage(Request $request, $id, $image)
    {

            // dd($image);
            $document = TravelerDocument::where('trvd_id', $id)->firstOrFail();
        
            $images = json_decode($document->trvd_document, true);
            
            if (($key = array_search($image, $images)) !== false) {
                $userFolder = session('userFolder');
                //dd($userFolder);
                unset($images[$key]);
        
                $document->trvd_document = json_encode(array_values($images));
                
                $imagePath = storage_path('app/' . $userFolder . '/document_image/' . $image);
                File::delete($imagePath);

                \MasterLogActivity::addToLog('Master Admin Trip Traveller Document is Deleted.');
                // dd($imagePath);
                $document->save(); 
        
                return redirect()->back()->with('success', 'Image deleted successfully.');
            }
        
            return redirect()->back()->with('error', 'Image not found.');
    }


    public function update(Request $request, $tr_id, $trvd_id)
    {
        // dd($request->all());
        $user = Auth::guard('masteradmins')->user();
        $dynamicId = $user->id; 
        $document = TravelerDocument::where(['id' =>$user->users_id, 'tr_id' => $tr_id, 'trvd_id' => $trvd_id])->firstOrFail();

        // dd($task);
        if($document)
        {
            $validatedData = $request->validate([
                'trvd_name' => 'required|string',
                'trvm_id' => 'required|string',
                'trvd_document' => 'nullable',
                'trvd_document.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            ], [
                'trvd_name.required' => 'Documents Type is required',
                'trvm_id.required' => 'Traveler is required',
                'trvd_document.nullable' => 'The Document is required.',
                'trvd_document.*.image' => 'The Document must be an image.',
                'trvd_document.*.mimes' => 'The Document must be a file of type: jpeg, png, jpg, gif, svg.',
                'trvd_document.*.max' => 'The Document may not be greater than 2048 kilobytes.',
            ]);

            
            $document_images = [];

            if ($document->trvd_document) {
                $existingImages = json_decode($document->trvd_document, true);
                if (is_array($existingImages)) {
                    $document_images = $existingImages;
                }
            }

            $userFolder = session('userFolder');

            if (is_array($request->file('trvd_document'))) {
                // Upload multiple images
                $newImages = $this->handleImageUpload($request, 'trvd_document', null, 'document_image', $userFolder);
                $document_images = array_merge($document_images, $newImages); 
            }
            $validatedData['trvd_document'] = $document_images;
            $document->where('trvd_id' , $trvd_id)->update($validatedData);
            $document->save();

            \MasterLogActivity::addToLog('Master Admin Trip Traveller Document is Updated.');

            return response()->json(['success'=>'Record updated successfully.']);
        }
    }

    public function destroy($tr_id,$trvd_id)
    {
        // dd($trvd_id);
        $document = TravelerDocument::where('trvd_id', $trvd_id)->firstOrFail();
        $images = json_decode($document->trvd_document, true);

        $userFolder = session('userFolder');

        foreach ($images as $image) {
            $imagePath = storage_path('app/' . $userFolder . '/document_image/' . $image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $document->where('trvd_id', $trvd_id)->delete();

        \MasterLogActivity::addToLog('Master Admin Trip Traveller Document is Deleted.');

        return response()->json(['success'=>'Record deleted successfully.']);
    }

    
}
