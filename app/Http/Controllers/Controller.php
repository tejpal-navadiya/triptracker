<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\MasterUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

 
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function GenerateUniqueRandomString($table, $column, $chars)
    {
        $unique = false;
        do{
            $randomStr = Str::random($chars);
            $match = DB::table($table)->where($column, $randomStr)->first();
            if($match)
            {
                continue;
            }
            $unique = true;
        }
        while(!$unique);
        return $randomStr;
    }


    // protected function handleImageUpload(Request $request, $currentImage = null, $directory = 'default_directory')
    // {

    //     if ($request->hasFile('image')) {
    //         // Delete the old image if it exists
    //         if ($currentImage) {
    //             Storage::delete($directory . '/' . $currentImage);
    //         }

    //         // Generate a unique filename
    //         $extension = $request->file('image')->getClientOriginalExtension();
    //         $uniqueFilename = Str::uuid() . '.' . $extension;

    //         // Store the new image
    //         $request->file('image')->storeAs($directory, $uniqueFilename);

    //         return $uniqueFilename;
    //     }

    //     return $currentImage;
    // }


    //Main handleImageUpload /************* */


    // protected function handleImageUpload(Request $request, $type, $currentImage = null, $directory = 'default_directory', $userFolder = '')
    // {
    //     if ($userFolder) {
    //         $directory = $userFolder . '/' . $directory;
    //     }

    //     if ($request->hasFile($type)) {
    //         // Delete the old image if it exists
    //         if ($currentImage) {
    //             Storage::delete($directory . '/' . $currentImage);
    //         }

    //         // Generate a unique filename
    //         $extension = $request->file($type)->getClientOriginalExtension();
    //         $uniqueFilename = Str::uuid() . '.' . $extension;

    //         // Store the new image
    //         $request->file($type)->storeAs($directory, $uniqueFilename);

    //         return $uniqueFilename;
    //     }

    //     return $currentImage;
    // }


    //end Main handleImageUpload /************* */


    protected function handleImageUpload(Request $request, $type, $currentImages = null, $directory = 'default_directory', $userFolder = '')
    {
        if ($userFolder) {
            $directory = $userFolder . '/' . $directory;
        }
    
        $uploadedImages = [];
        
    
        // Check if multiple files are uploaded
        if (is_array($request->file($type))) {
            foreach ($request->file($type) as $key => $file) {
                // Delete the old image if it exists (if there's a corresponding entry in $currentImages)
                if ($currentImages && isset($currentImages[$key])) {
                    Storage::delete($directory . '/' . $currentImages[$key]);
                }
    
                // Generate a unique filename
                $extension = $file->getClientOriginalExtension();
                $uniqueFilename = Str::uuid() . '.' . $extension;
    
                // Store the new image
                $file->storeAs($directory, $uniqueFilename);
                
                // Add the filename to the list of uploaded images
                $uploadedImages[] = $uniqueFilename;
            }
    
            return $uploadedImages; // Return the list of filenames
        }


    
        // If it's a single file upload
        if ($request->hasFile($type)) {
            // Delete the old image if it exists
            if ($currentImages) {
                Storage::delete($directory . '/' . $currentImages);
            }
    
            // Generate a unique filename
            $extension = $request->file($type)->getClientOriginalExtension();
            $uniqueFilename = Str::uuid() . '.' . $extension;
    
            // Store the new image
            $request->file($type)->storeAs($directory, $uniqueFilename);
    
            return $uniqueFilename; // Return the filename for a single file
        }
    
        return $currentImages; // Return current images if no new file is uploaded
    }






    // public function handlemultiImageUpload(Request $request, $userFolder = ''){

    //     if ($request->hasFile('image') && count($request->file('image')) > 0) {
    //         foreach ($request->file('image') as $file) {
                   
    //          //dd($file);
    //          //Log::info('Processing image: ', ['originalName' => $file->getClientOriginalName(), 'size' => $file->getSize()]);


    //             if ($file instanceof \Illuminate\Http\UploadedFile) {
    //                 // Generate a unique file name
    //                 $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //                 // Define the path to store the image
    //                 $path = storage_path('app/' . $userFolder . '/library_image/');
                    
    //                 // Move the uploaded file to the specified path
    //                 $file->move($path, $fileName);
                    
    //                 // Store the image path in the array
    //                 $library_images[] = 'storage/' . $userFolder . '/library_image/' . $fileName;
    //             }
    //         }
    //     }

    
    

    public function CreateTable($id)
    {
        // Debugging to see the passed $id
        //dd($id); // Check if the ID being passed is correct
    
        $master_user = MasterUser::where('id', $id)->first();
        // dd($master_user);
        if (!$master_user) {
            return response()->json(['message' => 'No user found with this ID.'], 404);
        }
    
        // This will dump the value if a record is found
        if($master_user){
            $storeId = $master_user->buss_unique_id;

            if (!Schema::hasTable($storeId.'_tc_log_activities_table')){   
                Schema::create($storeId.'_tc_log_activities_table', function (Blueprint $table) {
                    $table->string('id')->unique()->primary();
                    $table->string('subject')->nullable();
                    $table->string('url')->nullable();
                    $table->string('method')->nullable();
                    $table->string('ip')->nullable();
                    $table->string('agent')->nullable();
                    $table->string('user_id')->nullable()->default(0);
                    $table->timestamps();
                });
            }

            if (!Schema::hasTable($storeId.'_tc_users_role')){  
                Schema::create($storeId.'_tc_users_role', function (Blueprint $table) {
                    $table->string('role_id')->unique()->primary();
                    $table->string('id');
                    $table->string('role_name');
                    $table->tinyInteger('role_status')->default(0);
                    $table->timestamps();
                });

            }


            if (!Schema::hasTable($storeId.'_tc_users_details')){   
                Schema::create($storeId.'_tc_users_details', function (Blueprint $table) {
                    $table->string('users_id')->unique()->primary();
                    $table->string('users_agencies_name')->nullable();
                    $table->string('users_franchise_name')->nullable();
                    $table->string('users_consortia_name')->nullable();
                    $table->string('users_first_name')->nullable();
                    $table->string('users_last_name')->nullable();
                    $table->string('users_email')->nullable()->unique();
                    $table->string('users_iata_clia_number')->nullable();
                    $table->string('users_clia_number')->nullable();
                    $table->string('users_iata_number')->nullable();
                    $table->string('users_address')->nullable();
                    $table->string('users_city')->nullable();
                    $table->integer('users_state')->nullable()->default(0);
                    $table->integer('users_zip')->nullable()->default(0);
                    $table->string('email_verified_at')->nullable()->unique();
                    $table->string('users_password')->nullable();
                    $table->string('users_phone')->nullable();
                    $table->string('users_bio')->nullable();
                    $table->string('users_image')->nullable();
                    $table->string('role_id')->nullable()->default(0);
                    $table->string('id')->nullable()->default(0);
                    $table->string('user_id')->nullable();
                    $table->string('remember_token')->nullable();
                    $table->tinyInteger('users_status')->default(0)->nullable();
                    $table->timestamps();
                });
            }else{
                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'users_phone')) {
                        $table->string('users_phone')->nullable();
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'users_bio')) {
                        $table->string('users_bio')->nullable();
                    }
                });
            }

            // master user access
            if (!Schema::hasTable($storeId.'_tc_master_user_access')){   
                Schema::create($storeId.'_tc_master_user_access', function (Blueprint $table) {
                    $table->string('id')->unique()->primary();
                    $table->string('u_id')->nullable()->default(0);
                    $table->string('role_id')->nullable()->default(0);
                    $table->string('mname')->nullable();
                    $table->string('mtitle')->nullable();
                    $table->integer('mid')->nullable();
                    $table->string('is_access')->nullable();
                    $table->timestamps();
                });
            }


            // user certificate
            if (!Schema::hasTable($storeId.'_tc_users_certification')){   
                Schema::create($storeId.'_tc_users_certification', function (Blueprint $table) {
                    $table->string('users_cert_id')->unique()->primary();
                    $table->string('id')->nullable()->default(0);
                    $table->string('users_cert_name')->nullable();
                    $table->string('users_cert_person_name')->nullable();
                    $table->string('users_cert_completed_date')->nullable();
                    $table->string('users_cert_expiration')->nullable();
                    $table->text('users_cert_desc')->nullable();
                    $table->text('users_cert_document')->nullable();
                    $table->tinyInteger('users_status')->default(0)->nullable();
                    $table->timestamps();
                });
            }

            //Trip
            if (!Schema::hasTable($storeId.'_tc_trip')){   
                Schema::create($storeId.'_tc_trip', function (Blueprint $table) {
                    $table->string('tr_id')->unique()->primary();
                    $table->string('id')->nullable()->default(0);
                    $table->string('tr_name')->nullable();
                    $table->string('tr_agent_id')->nullable();
                    $table->string('tr_traveler_name')->nullable();
                    $table->string('tr_dob')->nullable();
                    $table->string('tr_age')->nullable();
                    $table->string('tr_number')->nullable();
                    $table->string('tr_email')->nullable();
                    $table->string('tr_phone')->nullable();
                    $table->string('tr_num_people')->nullable();
                    $table->string('tr_start_date')->nullable();
                    $table->string('tr_end_date')->nullable();
                    $table->string('tr_value_trip')->nullable();
                    $table->text('tr_type_trip')->nullable();
                    $table->text('tr_desc')->nullable();
                    $table->string('status')->nullable();
                    $table->tinyInteger('tr_status')->default(0)->nullable();
                    $table->timestamps();
                });
            }else{
                Schema::table($storeId.'_tc_trip', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_trip', 'status')) {
                        $table->string('status')->nullable();
                    }
                });
            }

            //Trip Traveling Member
            if (!Schema::hasTable($storeId.'_tc_trip_traveling_member')){   
                Schema::create($storeId.'_tc_trip_traveling_member', function (Blueprint $table) use ($storeId) {
                    $table->string('trtm_id')->unique()->primary();
                    $table->string('id')->nullable()->default(0);
                    $table->string('tr_id')->constrained($storeId.'_tc_trip', 'tr_id')->onDelete('cascade');
                    $table->string('trtm_type')->nullable();
                    $table->string('trtm_first_name')->nullable();
                    $table->string(column: 'trtm_middle_name')->nullable();
                    $table->string('trtm_last_name')->nullable();
                    $table->string('trtm_nick_name')->nullable();
                    $table->string('trtm_gender')->nullable();
                    $table->string('trtm_dob')->nullable();
                    $table->string('trtm_age')->nullable();
                    $table->string('trtm_relationship')->nullable();
                    $table->tinyInteger('trtm_status')->default(0)->nullable();
                    $table->timestamps();
                });
            }

            //Trip Task
            if (!Schema::hasTable($storeId.'_tc_traveling_task')){   
                Schema::create($storeId.'_tc_traveling_task', function (Blueprint $table) use ($storeId) {
                    $table->string('trvt_id')->unique()->primary();
                    $table->string('id')->nullable()->default(0);
                    $table->string('tr_id')->constrained($storeId.'_tc_trip', 'tr_id')->onDelete('cascade');
                    $table->string('trvt_name')->nullable();
                    $table->string('trvt_agent_id')->nullable();
                    $table->string('trvt_category')->nullable();
                    $table->string('trvt_priority')->nullable();
                    $table->string('trvt_date')->nullable();
                    $table->string('trvt_due_date')->nullable();
                    $table->text('trvt_document')->nullable();
                    $table->string('status')->nullable();
                    $table->tinyInteger('trvt_status')->default(0)->nullable();
                    $table->timestamps();
                });
            }

            //Trip Task Category
            if (!Schema::hasTable($storeId.'_tc_task_category')){   
                Schema::create($storeId.'_tc_task_category', function (Blueprint $table) use ($storeId) {
                    $table->string('task_cat_id')->unique()->primary();
                    $table->string('id')->nullable()->default(0);
                    $table->string('task_cat_name')->nullable();
                    $table->tinyInteger('task_cat_status')->default(0)->nullable();
                    $table->timestamps();
                });
            }

            if (!Schema::hasTable($storeId.'_tc_email_template')){   
                Schema::create($storeId.'_tc_email_template', function (Blueprint $table) {
                    $table->string('id')->unique()->primary();
                    $table->string('u_id')->nullable()->default(0);
                    $table->string('email_tid')->nullable()->default(0);
                    $table->string('category')->nullable();
                    // $table->string('mtitle')->nullable();
                    // $table->integer('mid')->nullable();
                    // $table->string('is_access')->nullable();
                    $table->timestamps();
                });
            }

            //Library 
            if (!Schema::hasTable($storeId.'_tc_library')){   
                Schema::create($storeId.'_tc_library', function (Blueprint $table) use ($storeId) {
                    $table->string('lib_id')->unique()->primary();
                    $table->string('id')->nullable()->default(0);
                    $table->string('lib_category')->constrained('tc_lib_categories', 'lib_id')->onDelete('cascade');
                    $table->string('lib_name')->nullable();
                    $table->string('lib_currency')->nullable();
                    $table->string('lib_country')->nullable();
                    $table->string('lib_state')->nullable();
                    $table->string('lib_city')->nullable();
                    $table->string('lib_zip')->nullable();
                    $table->text('lib_basic_information')->nullable();
                    $table->text('lib_sightseeing_information')->nullable();
                    $table->text('lib_image')->nullable();
                    $table->tinyInteger('lib_status')->default(0)->nullable();
                    $table->timestamps();
                });
            }
            
            //library Category
            if (!Schema::hasTable($storeId.'_tc_library_category')){   
                Schema::create($storeId.'_tc_library_category', function (Blueprint $table) use ($storeId) {
                    $table->string('lib_cat_id')->unique()->primary();
                    $table->string('lib_cat_name')->nullable();
                    $table->tinyInteger('lib_cat_status')->default(0)->nullable();
                    $table->timestamps();
                });

            }

            //Library 
            if (!Schema::hasTable($storeId.'_tc_library')){   
                Schema::create($storeId.'_tc_library', function (Blueprint $table) use ($storeId) {
                    $table->string('lib_id')->unique()->primary();
                    $table->string('id')->nullable()->default(0);
                    $table->string('lib_category')->constrained('tc_lib_categories', 'lib_id')->onDelete('cascade');
                    $table->string('lib_name')->nullable();
                    $table->string('lib_currency')->nullable();
                    $table->string('lib_country')->nullable();
                    $table->string('lib_state')->nullable();
                    $table->string('lib_city')->nullable();
                    $table->string('lib_zip')->nullable();
                    $table->text('lib_basic_information')->nullable();
                    $table->text('lib_sightseeing_information')->nullable();
                    $table->text('lib_image')->nullable();
                    $table->tinyInteger('lib_status')->default(0)->nullable();
                    $table->timestamps();
                });
            }

            //library Category
            if (!Schema::hasTable($storeId.'_tc_library_category')){   
                Schema::create($storeId.'_tc_library_category', function (Blueprint $table) use ($storeId) {
                    $table->string('lib_cat_id')->unique()->primary();
                    $table->string('lib_cat_name')->nullable();
                    $table->tinyInteger('lib_cat_status')->default(0)->nullable();
                    $table->timestamps();
                });

            }

            //Trip Document
            if (!Schema::hasTable($storeId.'_tc_traveling_document')){   
                Schema::create($storeId.'_tc_traveling_document', function (Blueprint $table) use ($storeId) {
                    $table->string('trvd_id')->unique()->primary();
                    $table->string('id')->nullable()->default(0);
                    $table->string('tr_id')->nullable()->default(0);
                    $table->string('trvm_id')->nullable()->default(0);
                    $table->string('trvd_name')->nullable();
                    $table->string('trvd_document')->nullable();                    
                    $table->tinyInteger('trvd_status')->default(0)->nullable();
                    $table->timestamps();
                });

            }

            // email template....
            if (!Schema::hasTable($storeId.'_tc_email_template')){   
                Schema::create($storeId.'_tc_email_template', function (Blueprint $table) {
                   
                    $table->string('email_tid')->unique()->primary();
                    $table->string('id')->nullable()->default(0);
                    $table->string('category')->nullable();
                    $table->string('title')->nullable();
                    $table->text('email_text')->nullable();
                    $table->timestamps();
                });
            }


            // email template....
            if (!Schema::hasTable($storeId.'_tc_email_template_details')){   
                Schema::create($storeId.'_tc_email_template_details', function (Blueprint $table) {
                    $table->string('emt_id')->unique()->primary();
                    $table->string('id')->nullable()->default(0);
                    $table->string('category_id')->nullable();
                    $table->string('traveller_id')->nullable();
                    $table->text('email_text')->nullable();
                    $table->tinyInteger('emt_status')->default(0)->nullable();
                    $table->tinyInteger('status')->default(0)->nullable();
                    $table->timestamps();
                });
            }

            //Library 
            if (!Schema::hasTable($storeId.'_tc_library')){   
                Schema::create($storeId.'_tc_library', function (Blueprint $table) use ($storeId) {

                    $table->string('lib_id')->unique()->primary();
                    $table->string('id')->nullable()->default(0);
                    $table->string('lib_category')->constrained('tc_lib_categories', 'lib_id')->onDelete('cascade');
                    $table->string('lib_name')->nullable();
                    $table->string(column: 'lib_currency')->nullable();
                    $table->string('lib_country')->nullable();
                    $table->string('lib_state')->nullable();
                    $table->string('lib_city')->nullable();
                    $table->string('lib_zip')->nullable();
                    $table->text('lib_basic_information')->nullable();
                    $table->text('lib_sightseeing_information')->nullable();
                    $table->text('lib_image')->nullable();
                    $table->tinyInteger('lib_status')->default(0)->nullable();
                    $table->timestamps();
                });
            }

            //library Category
            if (!Schema::hasTable($storeId.'_tc_library_category')){   
                Schema::create($storeId.'_tc_library_category', function (Blueprint $table) use ($storeId) {
                    $table->string('lib_cat_id')->unique()->primary();
                    $table->string('lib_cat_name')->nullable();
                    $table->tinyInteger('lib_cat_status')->default(0)->nullable();
                    $table->timestamps();
                });

            }

            //agency
            if (!Schema::hasTable($storeId.'_tc_agency')){   
            Schema::create($storeId.'_tc_agency', function (Blueprint $table) use ($storeId) {

                $table->string('age_id')->unique()->primary();
                $table->string('id')->nullable()->default(0);
                $table->string('age_user_id')->unique();
                $table->string('age_user_first_name')->nullable();
                $table->string('age_user_last_name')->nullable();
                $table->string('age_user_qualification')->nullable();
                $table->string('age_user_work_email')->nullable()->unique();
                $table->string('age_user_personal_email')->nullable()->unique();
                $table->string('age_user_dob')->nullable();
                $table->string('age_user_type')->nullable();
                $table->string('age_user_password')->nullable();
                $table->string('age_user_emergency_contact')->nullable();
                $table->string('age_user_phone_number')->nullable();
                $table->string('age_user_emergency_email')->nullable()->unique();
                $table->text('age_user_address')->nullable();
                $table->string('age_user_city')->nullable();
                $table->string('age_user_state_type')->nullable();
                $table->string('age_user_zip')->nullable();
                $table->timestamps();
            });
        }

            //Agency Dynamic input Phone
            if (!Schema::hasTable($storeId.'_tc_users_agency_phone')){   
            Schema::create($storeId.'_tc_users_agency_phone', function (Blueprint $table) use ($storeId) {

                $table->string('age_user_phone_id')->unique()->primary();
                $table->string('age_id')->nullable();
                $table->string('id')->nullable();
                $table->string('age_user_phone_number')->nullable();
                $table->string('age_user_type')->nullable();
                $table->timestamps();
            });
        }

         // trip type 
         if (!Schema::hasTable($storeId.'_tc_type_of_trip')){   
            Schema::create($storeId.'_tc_type_of_trip', function (Blueprint $table) {
                $table->string('trip_type_id')->unique()->primary();
                $table->string('id')->nullable()->default(0);
                $table->string('tr_id')->nullable();
                $table->string('trip_type_name')->nullable();
                $table->text('trip_type_text')->nullable();
                $table->text('trip_type_confirmation')->default(0)->nullable();
                $table->tinyInteger('trip_status')->default(0)->nullable();
                $table->timestamps();
            });
        }

        // trip type 
        if (!Schema::hasTable($storeId.'_tc_trip_itinerary_detail')){   
            Schema::create($storeId.'_tc_trip_itinerary_detail', function (Blueprint $table) {
                $table->string('trit_id')->unique()->primary();
                $table->string('id')->nullable()->default(0);
                $table->string('tr_id')->nullable();
                $table->string('trit_text')->nullable();
                $table->tinyInteger('trit_status')->default(0)->nullable();
                $table->timestamps();
            });
        }

        }
        
    }
    

    public function createTableRoute(Request $request)
    {
        
       $user = Auth::guard('masteradmins')->user();
       //dd($user);

        $id = $user->id;
        if (!$id) {
            return response()->json(['message' => 'ID is required'], 400);
        }
        try {
            $this->CreateTable($id);
            return response()->json(['message' => 'Table created or modified successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


}
    