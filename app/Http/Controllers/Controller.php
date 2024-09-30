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


    protected function handleImageUpload(Request $request, $currentImage = null, $directory = 'default_directory')
    {
        
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($currentImage) {
                Storage::delete($directory . '/' . $currentImage);
            }

            // Generate a unique filename
            $extension = $request->file('image')->getClientOriginalExtension();
            $uniqueFilename = Str::uuid() . '.' . $extension;

            // Store the new image
            $request->file('image')->storeAs($directory, $uniqueFilename);
            
            return $uniqueFilename;
        }

        return $currentImage;
    }

    public function CreateTable($id)
    {
        // Debugging to see the passed $id
        //dd($id); // Check if the ID being passed is correct
    
        $master_user = MasterUser::where('buss_unique_id', $id)->first();
    
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

        }
        
    }
    

    public function createTableRoute(Request $request)
    {
        
       $user = Auth::guard('masteradmins')->user();

        $id = $user->user_id;
        //dd($id);
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
    
