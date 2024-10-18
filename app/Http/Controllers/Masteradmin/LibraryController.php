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



class LibraryController extends Controller
{
    public function index(): View
    {
        $user = Auth::guard('masteradmins')->user();
        $library = Library::where(['lib_status' => 1, 'id' => $user->id])->get();

        return view('masteradmin.library.index', compact('library'));
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

        //dd($request->all());

        $dynamicId = $user->id;

        // Validate the request data
        $validatedData = $request->validate([
            'lib_category' => 'required|string',
            'lib_name' => 'required|string',
            'lib_currency' => 'required|string',
            'lib_country' => 'required|string',
            'lib_state' => 'required|string',
            'lib_city' => 'required|string',
            'lib_zip' => 'required|string',
            'lib_basic_information' => 'required|string',
            'lib_sightseeing_information' => 'required|string',
            'image' => 'required|array', // Validate that image is an array
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'lib_category.required' => 'Category is required',
            'lib_name.required' => 'Name is required',
            'lib_currency.required' => 'Currency is required',
            'lib_country.required' => 'Country is required',
            'lib_state.required' => 'State is required',
            'lib_city.required' => 'City is required',
            'lib_zip.required' => 'ZIP code is required',
            'lib_basic_information.required' => 'Basic information is required',
            'lib_sightseeing_information.required' => 'Sightseeing information is required',
            'image.required' => 'At least one image is required',
            'image.array' => 'The images must be provided as an array',
            'image.*.image' => 'Each file must be an image',
            'image.*.mimes' => 'Each image must be a file of type: jpeg, png, jpg, gif',
            'image.*.max' => 'Each image size must not exceed 2MB',
        ]);

    //For Multi Image

        if ($request->hasFile('image')) {

            if (is_array($request->file('image'))) {
                 
                $userFolder = session('userFolder');
                $library_images =  $this->handleImageUpload($request, 'image', null, 'library_image', $userFolder);

                $libraryimg = json_encode($library_images);

            } else {
                
                // Handle a single image
                $userFolder = session('userFolder');
                $library_image = $this->handleImageUpload($request, 'image', null, 'library_image',$userFolder);
            }
        }

        $library = new Library();

        $tableName = $library->getTable();
        $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "lib_id", $chars = 6);
        
        $library->lib_id = $uniqueId1;
        $library->id = $dynamicId;
        
        $library->lib_category = $validatedData['lib_category'];
        $library->lib_name = $validatedData['lib_name'];
        $library->lib_currency = $validatedData['lib_currency'];
        $library->lib_country = $validatedData['lib_country'];
        $library->lib_state = $validatedData['lib_state'];
        $library->lib_city = $validatedData['lib_city'];
        $library->lib_zip = $validatedData['lib_zip'];
        $library->lib_basic_information = $validatedData['lib_basic_information'];
        $library->lib_sightseeing_information = $validatedData['lib_sightseeing_information'];

        $library->lib_image = $libraryimg; 

        $library->lib_status = 1;
        
        $library->save();
        
        \MasterLogActivity::addToLog('Master Admin Users Certification Created.');


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

        // Get the selected country's ID
        $selectedCountryId = $library->lib_country;

        $categories = LibraryCategory::select('lib_cat_id', 'lib_cat_name')->get();

        // Get currencies related to the selected country
        $currencies = Countries::where('id', $selectedCountryId)->get();

        // Get states related to the selected country
        $states = States::where('country_id', $selectedCountryId)->get();

        // Get cities related to the selected state (if you want to pre-load cities, you can use the library's lib_state)
        $selectedStateId = $library->lib_state;
        
        $cities = Cities::where('state_id', $selectedStateId)->get();

        // If you want to get all countries for the dropdown
        $countries = Countries::select('id', 'name', 'iso2')->get();

        return view('masteradmin.library.edit', compact('library', 'currencies', 'categories', 'countries', 'states', 'cities'));
    }


    public function update(Request $request, $id)
    {
        $user = Auth::guard('masteradmins')->user();

        // Fetch the existing library record
        $library = Library::where('lib_id', $id)->firstOrFail();

        // Validate the request data
        $validatedData = $request->validate([
            'lib_category' => 'required|string',
            'lib_name' => 'required|string',
            'lib_currency' => 'required|string',
            'lib_country' => 'required|string',
            'lib_state' => 'required|string',
            'lib_city' => 'required|string',
            'lib_zip' => 'required|string',
            'lib_basic_information' => 'required|string',
            'lib_sightseeing_information' => 'required|string',
            'image' => 'nullable|array', // Make image optional
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'lib_category.required' => 'Library category is required',
            'lib_name.required' => 'Library name is required',
            'lib_currency.required' => 'Library currency is required',
            'lib_country.required' => 'Country is required',
            'lib_state.required' => 'State is required',
            'lib_city.required' => 'City is required',
            'lib_zip.required' => 'ZIP code is required',
            'lib_basic_information.required' => 'Basic information is required',
            'lib_sightseeing_information.required' => 'Sightseeing information is required',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif',
            'image.max' => 'Image size must not exceed 2MB',
        ]);

        // Initialize an array to hold new or existing images
        $library_images = [];

        // Get the existing images if any
        if ($library->lib_image) {
            $existingImages = json_decode($library->lib_image, true);
            if (is_array($existingImages)) {
                $library_images = $existingImages; // Keep existing images
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {

            // dd($request->all());

            $userFolder = session('userFolder');

            // Check if multiple images are uploaded
            if (is_array($request->file('image'))) {
                // Upload multiple images
                $newImages = $this->handleImageUpload($request, 'image', null, 'library_image', $userFolder);
                $library_images = array_merge($library_images, $newImages); // Merge with existing images
            } else {
                // Handle a single image upload
                $newImage = $this->handleImageUpload($request, 'image', null, 'library_image', $userFolder);
                $library_images[] = $newImage; // Add the single image to the array
            }
        }
        // Update library fields
        $library->lib_category = $validatedData['lib_category'];
        $library->lib_name = $validatedData['lib_name'];
        $library->lib_currency = $validatedData['lib_currency'];
        $library->lib_country = $validatedData['lib_country'];
        $library->lib_state = $validatedData['lib_state'];
        $library->lib_city = $validatedData['lib_city'];
        $library->lib_zip = $validatedData['lib_zip'];
        $library->lib_basic_information = $validatedData['lib_basic_information'];
        $library->lib_sightseeing_information = $validatedData['lib_sightseeing_information'];
        $library->lib_status = 1; // Default status to active

        // Update the images if new ones were uploaded or keep the existing ones
        if (!empty($library_images)) {
            $library->lib_image = json_encode($library_images); // Encode as JSON to store in the database
        }

        // Save the updated record to the database
        $library->save(); // Use save() instead of update()

        // Log the activity
        \MasterLogActivity::addToLog('Master Admin Library Updated.');

        return redirect()->route('library.index')->with('success', 'Library updated successfully.');
    }


    public function getStates($countryId)
    {
        // $librarystate = States::all();
        $states = States::where('country_id', $countryId)->get();

        return response()->json($states);
    }

    public function getCurrencies($countryId)
    {
        $currencies = Countries::where('id', $countryId)->get();

        return response()->json($currencies);
    }

    public function getCities($stateId)
    {
        $cities = Cities::where('state_id', $stateId)->get();  // Fetch cities by state_id
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
        // Get the authenticated master admin user
        $user = Auth::guard('masteradmins')->user();

        // Find the library by lib_id, checking for the user's ID if needed
        $library = Library::where('lib_id', $lib_id)->firstOrFail();

        // Delete the library record
        $library->where('lib_id', $lib_id)->delete();


        // Log the deletion
        \MasterLogActivity::addToLog('Master Admin Library Deleted.');

        return redirect()->route(route: 'library.index')->with('success', 'Library deleted successfully');
    }


    public function view($id): View
    {
        // dd()
        $library = Library::where('lib_id', $id)->firstOrFail();
        $libraries = Library::all();

        // dd($trip);
        return view('masteradmin.library.view', compact('library', 'libraries'));
    }
}
