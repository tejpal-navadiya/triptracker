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
use Carbon\Carbon;
use PhpParser\Node\Stmt\Else_;
 
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

     protected function handleImageUpload(Request $request, $type, $currentImages = null, $directory = 'default_directory', $userFolder = '')
    {
        if ($userFolder) {
            $directory = $userFolder . '/' . $directory;
        }

        $fullDirectoryPath = storage_path('app/' . $directory);
        if (!file_exists($fullDirectoryPath)) {
            mkdir($fullDirectoryPath, 0755, true); // Create the directory with 0755 permissions
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
                $filePath = $file->storeAs($directory, $uniqueFilename);
                // dd($filePath);
               // dd(storage_path('app/' . $filePath));
                // chmod(storage_path('app/' . $filePath), 0755);

                // chmod($filePath, 0755);
                $fullPath = storage_path('app/' . $filePath);
                if (file_exists($fullPath)) {
                    chmod($fullPath, 0755);
                } else {
                    // Handle the error, e.g., log it or throw an exception
                    \Log::error("File does not exist: " . $fullPath);
                }
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
            $fullPath = storage_path('app/' . $directory . '/' . $uniqueFilename);
            chmod($fullPath, 0755); // Set file permissions

    
            return $uniqueFilename; // Return the filename for a single file
        }
    
        return $currentImages; // Return current images if no new file is uploaded
    }


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
                    $table->string('users_personal_email')->nullable()->unique();
                    $table->string('users_business_phone')->nullable()->unique();
                    $table->string('users_personal_phone')->nullable()->unique();
                    $table->string('users_iata_clia_number')->nullable();
                    $table->string('users_clia_number')->nullable();
                    $table->string('users_iata_number')->nullable();
                    $table->string('users_address')->nullable();
                    $table->integer('users_country')->nullable()->default(0);
                    $table->integer('users_state')->nullable()->default(0);
                    $table->string('users_city')->nullable();
                    $table->integer('users_zip')->nullable()->default(0);
                    $table->string('email_verified_at')->nullable()->unique();
                    $table->string('users_password')->nullable();
                    $table->string('users_phone')->nullable();
                    $table->string('users_bio')->nullable();
                    $table->string('user_agency_numbers')->nullable();
                    $table->string('user_qualification')->nullable();
                    $table->string('user_work_email')->nullable()->unique();
                    $table->string('user_dob')->nullable();
                    $table->string('user_emergency_contact_person')->nullable();
                    $table->string('user_emergency_phone_number')->nullable();
                    $table->string('user_emergency_email')->nullable()->unique();
                    $table->string('users_image')->nullable();
                    $table->string('role_id')->nullable()->default(0);
                    $table->string('id')->nullable()->default(0);
                    $table->string('user_id')->nullable();
                    $table->string('remember_token')->nullable();
                    $table->tinyInteger('users_status')->default(0)->nullable();
                    $table->text('api_token')->nullable();
                    $table->text('stripe_id')->nullable();
                    $table->text('stripe_status')->nullable();
                    $table->text('plan_id')->nullable();
                    $table->text('start_date')->nullable();
                    $table->text('end_date')->nullable();
                    $table->text('plan_type')->nullable();
                    $table->text('agency_logo')->nullable();
                    $table->timestamps();
                });
            }else{
                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'users_phone')) {
                        $table->string('users_phone')->nullable();
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'users_country')) {
                        $table->integer('users_country')->nullable()->default(0);
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'users_bio')) {
                        $table->string('users_bio')->nullable();
                    }
                });

                // Modified User Details
                
                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {

                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'user_agency_numbers')) {

                        $table->string('user_agency_numbers')->nullable();
    
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {

                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'user_qualification')) {

                        $table->string('user_qualification')->nullable();
    
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {

                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'user_work_email')) {

                        $table->string('user_work_email')->nullable()->unique();
    
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {

                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'user_dob')) {

                        $table->string('user_dob')->nullable();
    
                    }
                });

                
                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {

                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'user_emergency_contact_person')) {

                        $table->string('user_emergency_contact_person')->nullable();
    
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {

                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'user_emergency_phone_number')) {

                        $table->string('user_emergency_phone_number')->nullable();
    
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {

                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'user_emergency_email')) {

                        $table->string('user_emergency_email')->nullable()->unique();
    
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {

                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'users_personal_email')) {

                        $table->string('users_personal_email')->nullable()->unique();
    
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {

                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'users_business_phone')) {

                        $table->string('users_business_phone')->nullable()->unique();
    
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {

                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'users_personal_phone')) {

                        $table->string('users_personal_phone')->nullable()->unique();
    
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'api_token')) {
                        $table->text('api_token')->nullable();
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'stripe_id')) {
                        $table->text('stripe_id')->nullable();
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'stripe_status')) {
                        $table->text('stripe_status')->nullable();
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'plan_id')) {
                        $table->text('plan_id')->nullable();
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'start_date')) {
                        $table->text('start_date')->nullable();
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'end_date')) {
                        $table->text('end_date')->nullable();
                    }
                });


                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'plan_type')) {
                        $table->text('plan_type')->nullable();
                    }
                });

                Schema::table($storeId.'_tc_users_details', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_users_details', 'agency_logo')) {
                        $table->text('agency_logo')->nullable();
                    }
                });


                
            }

            // End Modified User Details


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
                Schema::table($storeId.'_tc_trip', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_trip', 'tr_country')) {
                        $table->string('tr_country')->nullable();
                    }
                });
                Schema::table($storeId.'_tc_trip', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_trip', 'tr_state')) {
                        $table->string('tr_state')->nullable();
                    }
                });
                Schema::table($storeId.'_tc_trip', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_trip', 'tr_city')) {
                        $table->string('tr_city')->nullable();
                    }
                });
                Schema::table($storeId.'_tc_trip', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_trip', 'tr_address')) {
                        $table->string('tr_address')->nullable();
                    }
                });
                Schema::table($storeId.'_tc_trip', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_trip', 'tr_zip')) {
                        $table->string('tr_zip')->nullable();
                    }
                });
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
                    $table->text('trvt_note')->nullable();
                    $table->string('status')->nullable();
                    $table->tinyInteger('trvt_status')->default(0)->nullable();
                    $table->timestamps();
                });
            }else{
                Schema::table($storeId.'_tc_traveling_task', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_traveling_task', 'trvt_note')) {
                        $table->text('trvt_note')->nullable();
                    }
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
                   
                    $table->string('email_tid') ->unique()->primary();
                    $table->string('id')->nullable()->default(0);
                    $table->string('category')->nullable()->default(0);
                    $table->text('title')->nullable();
                    $table->text('email_text')->nullable();
                    $table->timestamps();
                
                });
            }else{
                Schema::table($storeId.'_tc_email_template', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_email_template', 'title')) {
                        $table->text('title')->nullable();
                    }
                });
                Schema::table($storeId.'_tc_email_template', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_email_template', 'email_text')) {
                        $table->text('email_text')->nullable();
                    }
                });
            }
                //email Category
                if (!Schema::hasTable($storeId.'_tc_email_category')){   
                    Schema::create($storeId.'_tc_email_category', function (Blueprint $table) use ($storeId) {
                        $table->increments('main_id');
                        $table->string('email_cat_id');
                        $table->string('id')->nullable()->default(0);
                        $table->string('email_cat_name')->nullable();
                        $table->tinyInteger('email_cat_status')->default(0)->nullable();
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
                    $table->string('tag_name')->nullable();
                    $table->text('lib_basic_information')->nullable();
                    $table->text('lib_image')->nullable();
                    $table->tinyInteger('lib_status')->default(0)->nullable();
                    $table->timestamps();
                });
            }
            
            //library Category
            if (!Schema::hasTable($storeId.'_tc_library_category')){   
                Schema::create($storeId.'_tc_library_category', function (Blueprint $table) use ($storeId) {
                    $table->string('lib_cat_id')->unique()->primary();
                    $table->string('id')->nullable()->default(0);
                    $table->string('lib_cat_name')->nullable();
                    $table->tinyInteger('lib_cat_status')->default(0)->nullable();
                    $table->timestamps();
                });

            }else{
                Schema::table($storeId.'_tc_library_category', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_library_category', 'id')) {
                        $table->string('id')->nullable()->default(0);
                    }
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
                    $table->text('email_subject')->nullable();
                    $table->text('email_text')->nullable();
                    $table->tinyInteger('emt_status')->default(0)->nullable();
                    $table->tinyInteger('status')->default(0)->nullable();
                    $table->timestamps();
                });
            }else{
                Schema::table($storeId.'_tc_email_template_details', function (Blueprint $table) use ($storeId) {
                    if (!Schema::hasColumn($storeId.'_tc_email_template_details', 'email_subject')) {
                        $table->text('email_subject')->nullable();
                    }
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
                $table->string(column: 'id')->nullable()->default(0);
                $table->string('tr_id')->nullable();
                $table->string('trit_text')->nullable();
                $table->tinyInteger('trit_status')->default(0)->nullable();
                $table->timestamps();
            });
        }

        //mail configration
        if (!Schema::hasTable($storeId.'_tc_mail_smtp_settings')){   
            Schema::create($storeId.'_tc_mail_smtp_settings', function (Blueprint $table) {
                $table->integer('mail_smtp_id')->unique()->primary();
                $table->string('id')->nullable()->default(0);
                $table->string('mail_username')->nullable()->default(0);
                $table->string('mail_password')->nullable();
                $table->string('mail_outgoing_port')->nullable();
                $table->string('mail_incoming_port')->nullable();
                $table->string('mail_outgoing_host')->nullable();
                $table->string('mail_incoming_host')->nullable();
                $table->tinyInteger('mail_smtp_status')->default(0)->nullable();
                $table->timestamps();
            });
        }

    
    }
        
}
    
    public function getLocalTime($date, $timezone) 
    {

       // return Carbon::parse($date)
        //          ->setTimezone($timezone)   // Set the timezone
        //          ->format('M d, Y');  

   
            $carbonDate = Carbon::parse($date)->setTimezone($timezone);
        
            $formattedDate = $carbonDate->format('M d, Y \a\t h:i A');
        
            $offsetMinutes = $carbonDate->offsetMinutes; 
            $offsetHours = intdiv($offsetMinutes, 60); 
            $offsetMinutes = abs($offsetMinutes % 60); 
        
            $formattedOffset = sprintf('GMT%+d:%02d', $offsetHours, $offsetMinutes);
        
            return $formattedDate . ' ' . $formattedOffset; 

    }

    public function getDate($date, $timezone) 
    {

       return Carbon::parse($date)
                 ->setTimezone($timezone) 
                 ->format('M d, Y');  
        
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

    public function UserResponse($response)
    {
       //  dd($response);
        if(!empty($response->users_image))
        {
            $userFolder = 'masteradmin/' .$response->user_id.'_'.$response->user_first_name;
        //    dd($userFolder);
            $imageurl = url(env('APP_URL') .''.asset('storage/app/' . $userFolder . '/profile_image/'.$response->users_image));
        }else{
            $imageurl="";
        }
       
        $data = [
       
            'users_id'           => (string)$response->users_id,
            'users_agencies_name'         => (isset($response->users_agencies_name) && $response->users_agencies_name != null) ? $response->users_agencies_name : '',
            'users_franchise_name'             => (isset($response->users_franchise_name) && $response->users_franchise_name != null) ? $response->users_franchise_name : '',
            'users_consortia_name'        => (isset($response->users_consortia_name) && $response->users_consortia_name != null) ? $response->users_consortia_name : '',
            'users_first_name'             => (isset($response->users_first_name) && $response->users_first_name != null) ? $response->users_first_name : '',
            'users_last_name'             => (isset($response->users_last_name) && $response->users_last_name != null) ? $response->users_last_name :'',                    
            'users_email'             => (isset($response->users_email) && $response->users_email != null) ? $response->users_email : '',
            'users_iata_clia_number'             => (isset($response->users_iata_clia_number) && $response->users_iata_clia_number != null) ? $response->users_iata_clia_number : '',
            'users_clia_number'             => (isset($response->users_clia_number) && $response->users_clia_number != null) ? $response->users_clia_number : 0,
            'users_iata_number'             => (isset($response->users_iata_number) && $response->users_iata_number != null) ? $response->users_iata_number : 0,
            'users_address'             => (isset($response->users_address) && $response->users_address != null) ? $response->users_address : 0,
            'users_country'             => (isset($response->users_country) && $response->users_country != null) ? $response->users_country : 0,
            'country_name'             => (isset($response->country) && $response->country != null) ? $response->country :'',
            'users_state'             => (isset($response->users_state) && $response->users_state != null) ? $response->users_state : 0,

            'state_name'             => (isset($response->state) && $response->state != null) ? $response->state : '',

            'users_city'             => (isset($response->users_city) && $response->users_city != null) ? $response->users_city : 0,

            'city_name'             => (isset($response->city) && $response->city != null) ? $response->city : '',

            'users_image'     => $imageurl,

            'users_zip'             => (isset($response->users_zip) && $response->users_zip != null) ? $response->users_zip : '',
            'users_password'        => (isset($response->users_password) && $response->users_password != null) ? $response->users_password : '',
            'users_phone'             => (isset($response->users_phone) && $response->users_phone != null) ? $response->users_phone : 0,
            'users_bio'        => (isset($response->users_bio) && $response->users_bio != null) ? $response->users_bio : '',
            'role_id'        => (isset($response->role_id) && $response->role_id != null) ? $response->role_id : '',
            'remember_token'=> (isset($response->remember_token) && $response->remember_token != null) ? $response->remember_token : '',
            'users_status'=> (isset($response->users_status) && $response->users_status != null) ? $response->users_status : '',
            'sp_id'=> (isset($response->sp_id) && $response->sp_id != null) ? $response->sp_id : 0,
            'plan_name'=> (isset($response->plan) && $response->plan != null) ? $response->plan : '',
            'sp_expiry_date'=> (isset($response->sp_expiry_date) && $response->sp_expiry_date != null) ? $response->sp_expiry_date : '',
            'isActive'=> (isset($response->isActive) && $response->isActive != null) ? $response->isActive : '',
            'Authorization'     => (isset($response->token) &&$response->token != null) ? 'Bearer '.$response->token : '',
        ];
        return $data;
    }

    public function TripListResponse($trips)
    {
        $data = [];
        
        if (count($trips) > 0) {
            foreach ($trips as $trip) {
                // dd($trip);
               // dd($trip->uniqueId);
                
                $created_at = $this->getLocalTime($trip->created_at, 'Asia/Kolkata');
                $updated_at = $this->getLocalTime($trip->updated_at, 'Asia/Kolkata');

                $tr_start_date = $this->getDate($trip->tr_start_date, 'Asia/Kolkata');
                $tr_end_date = $this->getDate($trip->tr_end_date, 'Asia/Kolkata');
                $tr_dob = $this->getDate($trip->tr_dob, 'Asia/Kolkata');

                $tripData = [
                    'tr_id'            => $trip->tr_id,
                    'id'               => $trip->id,
                    'tr_name'          => $trip->tr_name,
                    'tr_agent_id'      => $trip->tr_agent_id,
                    'agent_name'      => '',
                    'tr_traveler_name' => $trip->tr_traveler_name,
                    'tr_dob'           => $tr_dob,
                    'tr_age'           => $trip->tr_age,
                    'tr_number'        => $trip->tr_number,
                    'tr_email'         => $trip->tr_email,
                    'tr_phone'         => $trip->tr_phone,
                    'tr_num_people'    => $trip->tr_num_people,
                    'tr_start_date'    => $tr_start_date,
                    'tr_end_date'      => $tr_end_date,
                    'tr_value_trip'    => $trip->tr_value_trip,
                    'tr_type_trip'     => $trip->tr_type_trip,
                    'tr_desc'          => $trip->tr_desc,
                    'tr_status'        => $trip->tr_status,
                    'created_at'       => $created_at,    
                    'updated_at'       => $updated_at,   
                    'member_count'    => $trip->member_count,
                    'workflow_days' => '',
                    'task_low' => $trip->low_count,
                    'task_medium' => $trip->medium_count,
                    'task_high' => $trip->high_count,
                ];

                array_push($data, $tripData);
            }
        }

        return $data;
    }

    public function TripTaskListResponse($tasks)
    {
        $data = [];
        
        if (count($tasks) > 0) {
            foreach ($tasks as $task) {
                // dd($trip);
               // dd($trip->uniqueId);
            
                $trvt_date = $this->getDate($task->trvt_date, 'Asia/Kolkata');
                $trvt_due_date = $this->getDate($task->trvt_due_date, 'Asia/Kolkata');

                $taskData = [
                    'trvt_id'            => $task->trvt_id,
                    'id'               => $task->id,
                    'tr_id'          => $task->tr_id,
                    'tr_name'          => $task->tr_name,
                    'trvt_name'      => $task->trvt_name,
                    'trvt_category'           => $task->trvt_category,
                    'category_name'           => $task->task_cat_name,
                    'trvt_priority'        => $task->trvt_priority,
                    'trvt_date'         => $trvt_date,
                    'trvt_due_date'         => $trvt_due_date,
                    'status'        => $task->status                    
                ];

                array_push($data, $taskData);
            }
        }

        return $data;
    }
    
    
}
    