<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Libraries;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\LibrariesCatgory;
use App\Models\Countries;
use App\Models\States;
use App\Models\Cities;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class LibrariesController extends Controller
{
    //
    public function index(Request $request)
    {

        $category = $request->input('category'); 
        $tag_name = $request->input('tag_name'); 

        $librariesQuery = Libraries::with('libcategory')->where(['lib_status' => 1]);

        if ($category) {
            $librariesQuery->where('lib_category', $category);
        }

        if ($tag_name) {
            $librariesQuery->where('tag_name', 'LIKE', '%' . $tag_name . '%'); 
        }

        // Paginate results and append filter parameters
        $libraries = $librariesQuery->paginate(12)->appends([
            'category' => $category,
            'tag_name' => $tag_name,
        ]);

        $librarycategory = LibrariesCatgory::where('lib_cat_status', 1)->orderBy('lib_cat_name', 'asc')->get();


        if ($request->ajax()) {
            return view('superadmin.library.filtered_results', compact('libraries', 'librarycategory'))->render();
        }
        return view('superadmin.library.index', compact('libraries','librarycategory'));
    }

    public function create(): View
    {
        $librarycategory = LibrariesCatgory::where('lib_cat_status', 1)->orderBy('lib_cat_name', 'asc')->get();
      
    

        return view('superadmin.library.create',compact(
                'librarycategory')
        );
    }

    public function store(Request $request)
    {
        // dd($request->all());


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
        

                $userFolder = 'superadmin';
                $documents_images =  $this->handleImageUpload($request, 'lib_image', null, 'library_image', $userFolder);

                $documentimg = json_encode($documents_images);

            
        } 

        $library = new Libraries();

        $library->lib_category = $validatedData['lib_category'];
        $library->lib_name = $validatedData['lib_name'];
        $library->tag_name = $validatedData['tag_name'];

        $library->lib_basic_information = $validatedData['lib_basic_information'];

        $library->lib_image = $documentimg ?? '';

        $library->lib_status = 1;

        $library->save();

        \LogActivity::addToLog('Admin is Library Created.');


        return redirect()->route('libraries.index')->with('success', 'Library entry created successfully.');
    }

    public function edit($id)
    {

        $library = Libraries::where('lib_id', $id)->firstOrFail();

        $selectedCountryId = $library->lib_country;

        $categories = LibrariesCatgory::where('lib_cat_status', 1)->orderBy('lib_cat_name', 'asc')->get();
       

        return view('superadmin.library.edit', compact('library', 'categories'));
    }

    public function deleteImage(Request $request, $id, $image)
    {
        // dd($id);
        // Retrieve the library instance using the provided lib_id
        $library = Libraries::where('lib_id', $id)->firstOrFail();
        //dd($library);
        // Decode existing images
        $images = json_decode($library->lib_image, true);

        // Check if the image exists in the array
        if (($key = array_search($image, $images)) !== false) {

            // Remove the image from the array
            unset($images[$key]);

            // Update the library record with the new images
            $library->lib_image = json_encode(array_values($images)); // Re-index the array and encode it back to JSON

            // Save the updated library record
            $library->save(); // Call save on the library instance

            // Optionally, delete the image file from storage
            Storage::delete('app/superadmin/library_image/' . $image);

            return redirect()->back()->with('success', 'Image deleted successfully.');
        }

        return redirect()->back()->with('error', 'Image not found.');
    }

    public function update(Request $request, $id)
    {
        $library = Libraries::where('lib_id', $id)->firstOrFail();
        // dd($request->lib_image);
        $validatedData = $request->validate(
            [
                'lib_category' => 'required|string',
                'lib_name' => 'required|string',
                'tag_name' => 'nullable|string',
                'lib_basic_information' => 'nullable|string',
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

        $userFolder = 'superadmin';

        if (is_array($request->file('lib_image'))) {
            // Upload multiple images
            $newImages = $this->handleImageUpload($request, 'lib_image', null, 'library_image', $userFolder);
            $document_images = array_merge($document_images, $newImages);
            //dd($document_images);
        }
        $validatedData['lib_image'] = $document_images;
        
        $library->where('lib_id', $id)->update($validatedData);


        \LogActivity::addToLog('Admin is Library Created.');

        return redirect()->route('libraries.index')->with('success', 'Library updated successfully.');
    }

    public function destroy($lib_id)
    {

        $library = Libraries::where('lib_id', $lib_id)->firstOrFail();

        $images = json_decode($library->lib_image, true);

        $userFolder = 'superadmin';

        foreach ($images as $image) {
            $imagePath = storage_path('app/' . $userFolder . '/library_image/' . $image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $library->where('lib_id', $lib_id)->delete();
        \LogActivity::addToLog('Admin Library Deleted.');

        return redirect()->route('libraries.index')->with('success', 'Library deleted successfully');
    }

    public function show($id)
    {

        $library = Libraries::with('libcategory')->where(['lib_status' => 1, 'lib_id' => $id])->firstOrFail();

        // dd($trip);
        return view('superadmin.library.details', compact('library'));
    }
    
}
