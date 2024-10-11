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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image with specific rules
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
            'image.required' => 'An image is required',
            'image.image' => 'The uploaded file must be an image',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif',
            'image.max' => 'The image size must not exceed 2MB',
        ]);

        // if ($request->hasFile('lib_image')) {
        //     $file = $request->file('lib_image');
        //     $filename = time() . '_' . $file->getClientOriginalName();
        //     $filePath = $file->storeAs('uploads/libraries', $filename, 'public');
        // }

        // if ($request->hasFile('image')) {
        //     $users_cert_document = $this->handleImageUpload($request, $request->file('image'), 'masteradmin/certification_image');
        //     $validatedData['users_cert_document'] = $users_cert_document;
        // } else {


        if ($request->hasFile('image')) {
            $library_image = $this->handleImageUpload($request, $request->file('image'), 'masteradmin/library_image');
            //$['lib_image'] = $library;
        }

        $library = new Library();

        $tableName = $library->getTable();
        $uniqueId1 = $this->GenerateUniqueRandomString($table = $tableName, $column = "lib_id", $chars = 6);


        $library->lib_id = $uniqueId1;  // Assuming `user_id` is the column in the Library table
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
        $library->lib_image = $library_image;
        $library->lib_status = 1;

        // if ($request->hasFile('lib_image')) {
        //     $library->lib_image = $filePath; // Save image file path to the database
        // }

        // Save the library entry to the database
        $library->save();
        \MasterLogActivity::addToLog('Master Admin Users Certification Created.');
        return redirect()->route('library.index')->with('success', 'Library entry created successfully.');
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
        // dd($request->all()); die();

        $user = Auth::guard('masteradmins')->user();

        $library = Library::where('lib_id', $id)->firstOrFail();

        //dd($id); die();
        // dd($id, $library);  die();


        // Validate the request data
        $validationData =  $request->validate([
            'lib_category' => 'required|string',
            'lib_name' => 'required|string',
            'lib_currency' => 'required|string',
            'lib_country' => 'required|string',
            'lib_state' => 'required|string',
            'lib_city' => 'required|string',
            'lib_zip' => 'required|string',
            'lib_basic_information' => 'required|string',
            'lib_sightseeing_information' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image field is optional
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

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete the old image from storage if it exists
            if ($library->lib_image) {
                Storage::disk('public')->delete('masteradmin/library_image/' . $library->lib_image);
            }

            // Upload new image and assign the path
            $library_image = $this->handleImageUpload($request, $request->file('image'), 'masteradmin/library_image');
            $library->lib_image = $library_image;
        }

        // Update library fields
        $library->lib_category = $request->input('lib_category');
        $library->lib_name = $request->input('lib_name');
        $library->lib_currency = $request->input('lib_currency');
        $library->lib_country = $request->input('lib_country');
        $library->lib_state = $request->input('lib_state');
        $library->lib_city = $request->input('lib_city');
        $library->lib_zip = $request->input('lib_zip');
        $library->lib_basic_information = $request->input('lib_basic_information');
        $library->lib_sightseeing_information = $request->input('lib_sightseeing_information');
        $library->lib_status = 1; // Default status to active

        // Save the updated record to the database
        $library->where('lib_id', $id)->update($validationData);
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

        return redirect()->route(route: 'library.index')
        ->with('success', 'Library deleted successfully');
    }

    public function view($id): View
    {
        // dd()
        $library = Library::where('lib_id', $id)->firstOrFail();
        $libraries = Library::all(); 

        // dd($trip);
        return view('masteradmin.library.view', compact('library','libraries'));
    }
}
