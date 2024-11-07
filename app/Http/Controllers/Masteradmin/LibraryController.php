<?php

namespace App\Http\Controllers\Masteradmin;

use App\Http\Controllers\Controller;
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


class LibraryController extends Controller
{
    public function index(): View
    {
        $user = Auth::guard('masteradmins')->user();

        $library = Library::with('libcategory', 'currency', 'state', 'city', 'country')->where(['lib_status' => 1, 'id' => $user->users_id])->get();
        $libraries = Library::all();


        return view('masteradmin.library.index', compact('library','libraries'));
    }

    public function create(): View
    {
        $librarycategory = LibraryCategory::all();
        $librarycurrency = Countries::all();
        $librarystate = States::all();


        return view(
            'masteradmin.library.create',
            compact(
                'librarycategory',
                'librarycurrency',
                'librarystate',

            )
        );
    }

    public function store(Request $request)
    {
        $user = Auth::guard('masteradmins')->user();

        $dynamicId = $user->users_id;

        // Validate the request data
        $validatedData = $request->validate([
            'lib_category' => 'required|string',

            'lib_name' => [
            'required',         
            'string',          
            'max:255',         
            'regex:/^[a-zA-Z\s\-]+$/', 
            ],

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

            if (is_array($request->file('lib_image'))) {
                 
                $userFolder = session('userFolder');
                $documents_images =  $this->handleImageUpload($request, 'lib_image', null, 'library_image', $userFolder);

                $documentimg = json_encode($documents_images);

            }
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

       // $library->lib_image = $libraryimg;

        $library->lib_image = $documentimg ?? '';

      

        $library->lib_status = 1;

        $library->save();

        \MasterLogActivity::addToLog('Library Created.');


        return redirect()->route('library.index')->with('success', 'Library entry created successfully.');
    }

    public function deleteImage(Request $request, $id, $image)
    {
        // Retrieve the library instance using the provided lib_id
        $library = Library::where('lib_id', $id)->firstOrFail();

        // Decode existing images
        $images = json_decode($library->lib_image, true);

        // Check if the image exists in the array
        if (($key = array_search($image, $images)) !== false) {
            $userFolder = session('userFolder');

            // Remove the image from the array
            unset($images[$key]);

            // Update the library record with the new images
            $library->lib_image = json_encode(array_values($images)); // Re-index the array and encode it back to JSON

            // Save the updated library record
            $library->save(); // Call save on the library instance

            // Optionally, delete the image file from storage
            Storage::delete('public/' . $userFolder . '/library_image/' . $image);

            return redirect()->back()->with('success', 'Image deleted successfully.');
        }

        return redirect()->back()->with('error', 'Image not found.');
    }

    public function edit($id)
    {
        $library = Library::where('lib_id', $id)->firstOrFail();

        $selectedCountryId = $library->lib_country;

        $categories = LibraryCategory::select('lib_cat_id', 'lib_cat_name')->get();

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

        $validatedData = $request->validate(
            [
                'lib_category' => 'required|string',
                
            'lib_name' => [
                'required',         
                'string',          
                'max:255',         
                'regex:/^[a-zA-Z\s\-]+$/', 
                ],

                'tag_name' => 'nullable|string',
                'lib_currency' => 'nullable|string',
                'lib_country' => 'nullable|string',
                'lib_state' => 'nullable|string',
                'lib_city' => 'nullable|string',
                'lib_zip' => 'nullable|numeric|digits_between:1,6',
                'lib_basic_information' => 'nullable|string',
                'lib_sightseeing_information' => 'nullable|string',
                'lib_image' => 'nullable',
                'lib_image.*' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            ],
            [
                'lib_image.nullable' => 'The Document is required.',
                'lib_image.*.image' => 'The Document must be an image.',
                'lib_image.*.mimes' => 'The Document must be a file of type: jpeg, png, jpg, gif, svg.',
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
        }
        $validatedData['lib_image'] = $document_images;
        
        $library->where('lib_id', $id)->update($validatedData);


        \MasterLogActivity::addToLog('Master Admin Library Updated.');

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

        $library->where('lib_id', $lib_id)->delete();


        \MasterLogActivity::addToLog('Master Admin Library Deleted.');

        return redirect()->route(route: 'library.index')->with('success', 'Library deleted successfully');
    }

    public function show($id)
    {

        $library = Library::where('lib_id', $id)->firstOrFail();

        $user = Auth::guard('masteradmins')->user();
        $libraries = Library::all();
        $library = Library::with('libcategory', 'currency', 'state', 'city', 'country')->where(['lib_status' => 1, 'id' => $user->users_id, 'lib_id' => $id])->firstOrFail();

        // dd($trip);
        return view('masteradmin.library.details', compact('library', 'libraries'));
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

}
