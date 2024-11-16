<?php

namespace App\Listeners;

use App\Models\EmailsTemplates;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use App\Models\EmailCategories;
use App\Models\EmailCategory;
use Illuminate\Support\Facades\Auth;
use Log;
use DB;
use App\Models\EmailTemplate;
use App\Models\LibrariesCatgory;
use App\Models\LibraryCategory;
use App\Models\Libraries;
use App\Models\Library;

class UserLoggedInListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        if (Auth::guard('masteradmins')->check()) {
            session()->flash('alert-configured-data', "We're currently configuring your data. Please wait a few moments.");

            $user = $event->user;
            $uniq_id = $user->user_id;

            // Get all email categories
            $emailCategories = EmailCategories::get();

            foreach ($emailCategories as $category) {
                $tableName = $uniq_id . '_tc_email_category';

                $exists = DB::table($tableName)
                    ->where('email_cat_id', $category->email_cat_id)
                    ->where('id', $user->users_id)
                    ->exists();

                if (!$exists) {
                    $emailCategoryModel = new EmailCategory();
                    $emailCategoryModel->setTable($tableName);

                    $emailCategoryModel->create([
                        'email_cat_id' => $category->email_cat_id,
                        'id' => $user->users_id,
                        'email_cat_name' => $category->email_cat_name,
                        'email_cat_status' => $category->email_cat_status
                    ]);
                }
            }

            // Get all email templates
            $emailsTemplates = EmailsTemplates::get();
            // dd($emailsTemplates);
            foreach ($emailsTemplates as $template) {
                $tableName = $uniq_id . '_tc_email_template';

                $exists = DB::table($tableName)
                    ->where('email_tid', $template->email_tid)
                    ->where('id', $user->users_id)
                    ->exists();

                if (!$exists) {
                    $emailTemplateModel = new EmailTemplate();
                    $emailTemplateModel->setTable($tableName);

                    $emailTemplateModel->create([
                        'email_tid' => $template->email_tid, 
                        'id' => $user->users_id,
                        'title' => $template->title,
                        'category' => $template->category, 
                        'email_text' => $template->email_text 
                    ]);
                }
            }

            // Insert LibrariesCatgory data into LibraryCategory
            $librariesCategories = LibrariesCatgory::get(); 

            foreach ($librariesCategories as $category) {
                $libraryCategoryModel = new LibraryCategory();
                $libraryCategoryModel->setTable($uniq_id . '_tc_library_category');

                $exists = $libraryCategoryModel->where('lib_cat_id', $category->lib_cat_id)
                    ->where('id', $user->users_id) 
                    ->exists();

                if (!$exists) {
                    $libraryCategoryModel->create([
                        'lib_cat_id' => $category->lib_cat_id,
                        'id' => $user->users_id, 
                        'lib_cat_name' => $category->lib_cat_name,
                        'lib_cat_status' => $category->lib_cat_status
                    ]);
                }
            }

            
            // Insert Libraries data into Library
            $libraries = Libraries::get(); 

            foreach ($libraries as $library) {
                $libraryModel = new Library();
                $libraryModel->setTable($uniq_id . '_tc_library');

                $exists = $libraryModel->where('lib_id', $library->lib_id)
                    ->where('id', $user->users_id) 
                    ->exists();

                if (!$exists) {
                    $libraryModel->create([
                        'lib_id' => $library->lib_id,
                        'id' => $user->users_id, 
                        'lib_category' => $library->lib_category,
                        'lib_name' => $library->lib_name,
                        'tag_name' => $library->tag_name,
                        'lib_basic_information' => $library->lib_basic_information,
                        'lib_image' => $library->lib_image,
                        'lib_status' => $library->lib_status
                    ]);
                }
            }
        }
    }
}
