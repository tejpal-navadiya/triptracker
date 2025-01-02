<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Library;
use App\Models\LibraryCategory;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Notifications\LeadTravelerEmail;
use App\Models\TripTravelingMember;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->input('category'); 
        $tag_name = $request->input('tag_name'); 

        $user = Auth::guard('masteradmins')->user();
        
        $librariesQuery = Library::with('libcategory', 'currency', 'state', 'city', 'country')
            ->where([ 'id' => $user->users_id]);

        if ($category) {
            $librariesQuery->where('lib_category', $category);
        }

        if ($tag_name) {
            $librariesQuery->where('tag_name', 'LIKE', '%' . $tag_name . '%'); 
        }

        $libraries = $librariesQuery->paginate(10)->appends([
            'category' => $category,
            'tag_name' => $tag_name,
        ]);

        if ($user->users_id && $user->role_id == 0) {
            $librarycategory = LibraryCategory::where('lib_cat_status', 1)->orderBy('lib_cat_name', 'asc')->get();
        } else {
            $librarycategory = LibraryCategory::where('lib_cat_status', 1)->where('id', $user->users_id)->orderBy('lib_cat_name', 'asc')->get();
        }
        if ($request->ajax()) {
             return view('masteradmin.library.filtered_results', compact('libraries', 'librarycategory'))->render();
         }
        
        return view('masteradmin.library.index', compact('libraries', 'librarycategory'));
    }
    public function create(): View
    {
        $user = Auth::guard('masteradmins')->user();
        if($user->users_id && $user->role_id ==0 ){
            $librarycategory = LibraryCategory::where('lib_cat_status', 1)->orderBy('lib_cat_name', 'asc')->get();
        }else{
            $librarycategory = LibraryCategory::where('lib_cat_status', 1)->where('id', $user->users_id)->orderBy('lib_cat_name', 'asc')->get();
        }

      
        $librarycurrency = Countries::all();
        $librarystate = States::all();


        return view('masteradmin.library.create',compact(
                'librarycategory',
                'librarycurrency',
                'librarystate'
            )
        );
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $user = Auth::guard('masteradmins')->user();

        $dynamicId = $user->users_id;

        // Validate the request data
        $validatedData = $request->validate([
            'lib_category' => 'required|string',

            'lib_name' => 'required|string',

            'tag_name' => 'nullable|string',
            'lib_basic_information' => 'nullable|string',
            'image' => 'nullable', 
            'image.*' => 'image|mimes:jpeg,png,jpg,pdf|max:2048',

        ], [
            'lib_category.required' => 'Category is required',
            'lib_name.required' => 'Name is required',
            'tag_name' => 'Tag Name is Required',
            'lib_basic_information.nullable' => 'Basic information is required',

            'lib_image.nullable' => 'The Document is required.',
            'lib_image.*.image' => 'The Document must be an image.',
            'lib_image.*.mimes' => 'The Document must be a file of type: jpeg, png, jpg, gif, svg.',
            'lib_image.*.max' => 'The Document may not be greater than 2048 kilobytes.',
        ]);




        if ($request->hasFile('lib_image')) {
        

                $userFolder = session('userFolder');
                $documents_images =  $this->handleImageUpload($request, 'lib_image', null, 'library_image', $userFolder);

                $documentimg = json_encode($documents_images);

            
        } 

        $library = new Library();

        $tableName = $library->getTable();
        $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "lib_id", $chars = 6);

        $library->lib_id = $uniqueId1;
        $library->id = $user->users_id;

        $library->lib_category = $validatedData['lib_category'];
        $library->lib_name = $validatedData['lib_name'];
        $library->tag_name = $validatedData['tag_name'];

        $library->lib_basic_information = $validatedData['lib_basic_information'];

        $library->lib_image = $documentimg ?? '';

        $library->lib_status = 1;

        $library->save();

        \MasterLogActivity::addToLog('Master Admin Library is Created.');


        return redirect()->route('library.index')->with('success', 'Library entry created successfully.');
    }

    public function deleteImage(Request $request, $id, $image)
    {
        $library = Library::where('lib_id', $id)->firstOrFail();

        $images = json_decode($library->lib_image, true);

        if (($key = array_search($image, $images)) !== false) {
            $userFolder = session('userFolder');

            Log::info('User  folder: ' . $userFolder);
            Log::info('Image to delete: ' . $image);
           

            unset($images[$key]);

            $library->lib_image = json_encode(array_values($images)); // Re-index the array and encode it back to JSON

            $library->save(); 

            $userFolder = storage_path('app/' . session('userFolder'). '/library_image/');
            $filePath = $userFolder . $image; 

            Log::info('Attempting to delete file at path: ' . $filePath);
    
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
                Log::info('File deleted successfully: ' . $filePath);
            } else {
                Log::warning('File does not exist: ' . $filePath);
            }
            return redirect()->back()->with('success', 'Image deleted successfully.');
        }
        \MasterLogActivity::addToLog('Master Admin Library is Deleted.');

        return redirect()->back()->with('error', 'Image not found.');
    }

    public function edit($id)
    {
        $user = Auth::guard('masteradmins')->user();

        $library = Library::where('lib_id', $id)->firstOrFail();

        $selectedCountryId = $library->lib_country;

        if($user->users_id && $user->role_id ==0 ){
            $categories = LibraryCategory::where('lib_cat_status', 1)->orderBy('lib_cat_name', 'asc')->get();
        }else{
            $categories = LibraryCategory::where('lib_cat_status', 1)->where('id', $user->users_id)->orderBy('lib_cat_name', 'asc')->get();
        }


        // $categories = LibraryCategory::select('lib_cat_id', 'lib_cat_name')->get();

        $currencies = Countries::where('id', $selectedCountryId)->get();

        $states = States::where('country_id', $selectedCountryId)->get();

        $selectedStateId = $library->lib_state;

        $cities = Cities::where('state_id', $selectedStateId)->get();

        $countries = Countries::select('id', 'name', 'iso2')->get();

        return view('masteradmin.library.edit', compact('library', 'currencies', 'categories', 'countries', 'states', 'cities'));
    }

    public function update(Request $request, $id)
    {
        $library = Library::where('lib_id', $id)->firstOrFail();
        // dd($request->lib_image);
        $validatedData = $request->validate(
            [
                'lib_category' => 'required|string',
                'lib_name' => 'required|string',
                'tag_name' => 'nullable|string',
                'lib_currency' => 'nullable|string',
                'lib_country' => 'nullable|string',
                'lib_state' => 'nullable|string',
                'lib_city' => 'nullable|string',
                'lib_zip' => 'nullable|numeric|digits_between:1,6',
                'lib_basic_information' => 'nullable|string',
                'lib_sightseeing_information' => 'nullable|string',
                'lib_image' => 'nullable',
                'lib_image.*' => 'nullable|mimes:jpeg,png,jpg,pdf',
            ],
            [
                'lib_image.nullable' => 'The Document is required.',
                'lib_image.*.image' => 'The Document must be an image.',
                'lib_image.*.mimes' => 'The Document must be a file of type: jpeg, png, jpg',
                'lib_image.*.max' => 'The Document may not be greater than 2048 kilobytes.',
            ]
        );
        
        $document_images = [];

        if ($library->lib_image) {
            $existingImages = json_decode($library->lib_image, true);
            if (is_array($existingImages)) {
                $document_images = $existingImages;
            }
        }

        $userFolder = session('userFolder');

        if (is_array($request->file('lib_image'))) {
            // Upload multiple images
            $newImages = $this->handleImageUpload($request, 'lib_image', null, 'library_image', $userFolder);
            $document_images = array_merge($document_images, $newImages);
            //dd($document_images);
        }
        $validatedData['lib_image'] = $document_images;
        
        $library->where('lib_id', $id)->update($validatedData);


        \MasterLogActivity::addToLog('Master Admin Library is Updated.');

        return redirect()->route('library.index')->with('success', 'Library updated successfully.');
    }

    public function getStates($countryId)
    {
        // $librarystate = States::all();
        $states = States::where('country_id', $countryId)->orderBy('name', 'ASC')->get();

        return response()->json($states);
    }

    public function getCurrencies($countryId)
    {
        $currencies = Countries::where('id', $countryId)->orderBy('id', 'ASC')->get();

        return response()->json($currencies);
    }

    public function getCities($stateId)
    {
        $cities = Cities::where('state_id', $stateId)->orderBy('name', 'ASC')->get();  // Fetch cities by state_id
        return response()->json($cities);
    }

    public function loadCities(Request $request)
    {
        $cities = Cities::when($request->state_id, function ($query) use ($request) {
            return $query->where('state_id', $request->state_id);
        })->paginate(25);

        return response()->json($cities);
    }

    public function destroy($lib_id)
    {
        $user = Auth::guard('masteradmins')->user();

        $library = Library::where('lib_id', $lib_id)->firstOrFail();

        $images = json_decode($library->lib_image, true); // Decode the JSON array

        $userFolder = storage_path('app/' . session('userFolder'). '/library_image/');
        // Log::info('User  folder path: ' . $userFolder);

        if (is_array($images)) {
            foreach ($images as $image) {
                $destinationFilePath = $userFolder . $image; 
                // Log::info('Checking file: ' . $destinationFilePath);

                if (File::exists($destinationFilePath)) {
                    File::delete($destinationFilePath);
                    // Log::info('Deleted file: ' . $destinationFilePath);

                }else{
                    // Log::warning('File does not exist: ' . $destinationFilePath);

                }
            }
        }
        
        $library->where('lib_id', $lib_id)->delete();


        \MasterLogActivity::addToLog('Master Admin Library is Deleted.');

        return redirect()->route('library.index')->with('success', 'Library deleted successfully');
    }

    public function show($id)
    {

        $library = Library::where('lib_id', $id)->firstOrFail();

        $user = Auth::guard('masteradmins')->user();
        $libraries = Library::all();
        $lead_traveler = TripTravelingMember::where('id', $user->users_id)->where('lead_status',1)->get();
      //  dd($lead_traveler);
        $library = Library::with('libcategory', 'currency', 'state', 'city', 'country')->where(['lib_status' => 1, 'id' => $user->users_id, 'lib_id' => $id])->firstOrFail();

        // dd($trip);
        return view('masteradmin.library.details', compact('library', 'libraries','lead_traveler'));
    }

    public function view()
    {

        // $library = Library::where('lib_id', $id)->firstOrFail();
        $libraries = Library::all();

        return view('masteradmin.library.view', compact('libraries'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $libraries = Library::where('lib_name', 'LIKE', "%{$query}%")
            ->orWhere('lib_basic_information', 'LIKE', "%{$query}%")
            ->get();


        return view('masteradmin.library.view', compact('libraries'));
    }

    public function sendEmail(Request $request,$id)
    {
       // dd($id);
        //dd($request->all());
        // Validate the request to ensure a traveler is selected
        $request->validate([
            'traveler_id' => 'required', // Validate ID exists in the table
        ]);

        // Retrieve the selected lead traveler by ID
        $leadTraveler = TripTravelingMember::where('trtm_id', $request->traveler_id)->firstOrFail();
        //dd($leadTraveler);
        if (!$leadTraveler || !$leadTraveler->trtm_email) {
            return back()->withErrors(['error' => 'Invalid traveler selected or email is missing.']);
        }
        
        $library = Library::with('libcategory')->where('lib_id', $id)->firstOrFail();
        // dd($library);
        if (!$library) {
            return back()->withErrors(['error' => 'Invalid traveler selected or email is missing.']);
        }

          // Prepare attachments
        $attachments = [];
        if ($library->lib_image) {
            $files = json_decode($library->lib_image, true);
            if (is_array($files)) {
                foreach ($files as $file) {
                    $userFolder = session('userFolder');
                    $filePath = storage_path('app/' . $userFolder . '/library_image/' . $file);

                    if (file_exists($filePath) && !in_array($filePath, $attachments)) {
                        $attachments[] = $filePath; // Add only unique file paths
                    }
                }
            }
        }

         
        // Prepare email data
        $emailData = [
            'subject' => $library->lib_name, 
            'travelerName' => $leadTraveler->trtm_first_name ?? '',
            'category' => $library->libcategory->lib_cat_name ?? '',
            'basicinformation' => $library->lib_basic_information ?? '',
            'attachment' =>  $attachments ?? '',
        ];
        

       
        // dd($attachments);

        // Send the email
        try {
            Mail::to($leadTraveler->trtm_email)->send(new LeadTravelerEmail($emailData));
            
            session()->flash('link-success', __('messages.masteradmin.user.link_send_success'));
        } catch (\Exception $e) {
            session()->flash('link-error', __('messages.masteradmin.user.link_send_error'));
        }

        // Redirect with success message
        return redirect()->back()->with('success', 'Email sent successfully to ' . $leadTraveler->trtm_first_name ?? '');
    }

}
