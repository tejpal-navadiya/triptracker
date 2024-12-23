<?php

use App\Http\Controllers\Masteradmin\TaskCategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\superadmin\PlanController;
use App\Http\Controllers\Auth\MasterAdmin\RegisterController;
use App\Http\Controllers\Auth\MasterAdmin\LoginController;
use App\Http\Controllers\Auth\MasterAdmin\MasterPasswordResetLinkController;
use App\Http\Controllers\Auth\MasterAdmin\MasterNewPasswordController;
use App\Http\Controllers\Auth\MasterAdmin\MasterEmailVerificationPromptController;
use App\Http\Controllers\Auth\MasterAdmin\MasterEmailVerificationNotificationController;
use App\Http\Controllers\Masteradmin\HomeController;
use App\Http\Controllers\superadmin\BusinessDetailController;
use App\Http\Controllers\Masteradmin\ProfilesController;
use App\Http\Controllers\Auth\MasterAdmin\MasterPasswordController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\superadmin\HomesController;
use App\Http\Controllers\Masteradmin\UserController;
use App\Http\Controllers\Masteradmin\UserRoleController;
use App\Http\Controllers\Masteradmin\UserCertificationController;
use App\Http\Controllers\Masteradmin\TripController;
use App\Http\Controllers\Masteradmin\TripTravelingMemberController;
use App\Http\Controllers\Masteradmin\TripTaskController;
use App\Http\Controllers\Masteradmin\LibraryController;
use App\Http\Controllers\Masteradmin\TravelerDocumentController;
use App\Http\Controllers\Masteradmin\EmailTemplateController;
use App\Http\Controllers\Masteradmin\AgencyController;
use App\Http\Controllers\Masteradmin\BookedTripController;
use App\Http\Controllers\Masteradmin\libraryCatgoryController;
use App\Http\Controllers\Masteradmin\EmailCategoryController;
use App\Http\Controllers\superadmin\LibrariesCatgoryController;
use App\Http\Controllers\superadmin\LibrariesController;
use App\Http\Controllers\superadmin\EmailCategoriesController;
use App\Http\Controllers\superadmin\EmailsTemplatesController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MailController;
use App\Models\MasterUser;



/*
|--------------------------------------------------------------------------
| Web Routes 
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


global $adminRoute;
$adminRoute = config('global.superAdminURL');
$busadminRoute = config('global.businessAdminURL');

    // dd(resource_path('views/website/wp-load.php'));
    // require_once resource_path('views/website/wp-load.php');

    // Route::get('/', function () {
    //     return view('website.index'); // Laravel view
    // });

    // Route::get('/', function () {
    //     // Load WordPress front page
    //     include_once resource_path('website/wp-admin/admin-header.php');
    // });


     


Route::group(['prefix' => $adminRoute], function () {
  
    Route::middleware(['auth', 'guard.session:web', 'prevent.back.history'])->group(function () {
     
        Route::get('/dashboard', [HomesController::class, 'create'])->middleware(['auth', 'verified'])->name('dashboard');
    
        //profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        //Subscription Plans
        Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
        Route::get('/plans/create', [PlanController::class, 'create'])->name('plans.create');
        Route::post('/plans/store', [PlanController::class, 'store'])->name('plans.store');
        Route::get('/plans/edit/{plan}', [PlanController::class, 'edit'])->name('plans.edit');
        Route::put('/plans/update/{plan}', [PlanController::class, 'update'])->name('plans.update');
        Route::delete('/plans/destroy/{plan}', [PlanController::class, 'destroy'])->name('plans.destroy');
        Route::get('/plans/planrole/{plan}', [PlanController::class, 'planrole'])->name('plans.planrole');
        Route::POST('/plans/updaterole/{plan}', [PlanController::class, 'updaterole'])->name('plans.updaterole');

      

        //agencies list
        Route::get('/businessdetails', [BusinessDetailController::class, 'index'])->name('businessdetails.index');
        Route::get('/businessdetails/{id}', [BusinessDetailController::class, 'show'])->name('businessdetails.show');
        Route::get('/editbusinessdetails/{id}', [BusinessDetailController::class, 'edit'])->name('businessdetails.edit');
        Route::post('/updatebusinessdetails/{id}', [BusinessDetailController::class, 'update'])->name('businessdetails.update');
        
        
        //agency image security 
      Route::get('/agency_images/{filename}/{id}', function ($filename, $id) {
        $user = MasterUser::findOrFail($id);
    
        $userFolder = 'masteradmin/' . $user->buss_unique_id . '_' . $user->user_first_name;
    
        $filePath = storage_path('app/' . $userFolder . '/profile_image/' . $filename);
    
        if (!file_exists($filePath)) {
            abort(404);
        }
    
        // Return the file response
        return response()->file($filePath);
    })->name('agency.access');

        //Admin agencies list dropdown 
        Route::get('admin_agency_state/{countryId}', [RegisterController::class, 'getStates'])->name('get_admin_States');
        Route::get('admin_agency_cities/{stateId}', [RegisterController::class, 'getCities'])->name('get_admin_Cities');


        Route::post('/business-detail/{id}/update-status', [BusinessDetailController::class, 'updateStatus'])->name('masteradmin.updateStatus');

        //logs
        Route::get('/logActivity', [ProfileController::class, 'logActivity'])->name('adminlog.index');

        //library Category
        Route::resource('libraries-category',LibrariesCatgoryController::class);

         //library
       Route::resource('libraries', LibrariesController::class);
        //library image delete
       Route::post('/libraries/{id}/image/{image}', [LibrariesController::class, 'deleteImage'])->name('libraries.image.delete');

       //email category
       Route::resource('email-categories',EmailCategoriesController::class);
        //email template
       Route::resource('emails-templates',EmailsTemplatesController::class);
       
    //   Route::resource('emails-templates',EmailsTemplatesController::class);

        //store agency user
        Route::get('agency-users-create/{id}/', [BusinessDetailController::class, 'agencyCreate'])->name('businessdetails.agencycreate');
        Route::post('agency-users-store/{userId}/', [BusinessDetailController::class, 'agencyStore'])->name('businessdetails.agencystore');
        
          Route::get('/users/change-password', [UserController::class, 'changePassword'])
        ->name('masteradmin.userdetail.changePassword');
        
        //store agency
        Route::get('agency-create/', [BusinessDetailController::class, 'agencyAdd'])->name('businessdetails.agencyadd');
        Route::post('agency-store', [BusinessDetailController::class, 'agencyInsert'])->name('businessdetails.agencyinsert');
        
        //agency delete
          Route::delete('/businessdetails/{id}/{user_id}', [BusinessDetailController::class, 'destroy'])->name('businessdetails.destroy');


    });
});

Route::group(['prefix' => $busadminRoute], function () {



    Route::get('/', [LoginController::class, 'create'])->name('masteradmin.login');

       Route::get('auth_register_state/{countryId}', [RegisterController::class, 'getStates'])->name('authregisterStates');
       Route::get('auth_register_cities/{stateId}', [RegisterController::class, 'getCities'])->name('authregisterCities');
        

    
    Route::middleware(['masteradmin'])->group(function () {
        //login and register
        Route::get('login', [LoginController::class, 'create'])->name('masteradmin.login');
        Route::get('register', [RegisterController::class, 'create'])->name('masteradmin.register');
        Route::post('register', [RegisterController::class, 'store'])->name('masteradmin.register.store');
        Route::post('login', [LoginController::class, 'store'])->name('masteradmin.login.store');
        Route::get('forgot-password', [MasterPasswordResetLinkController::class, 'create'])
                        ->name('masteradmin.password.request');
        Route::post('forgot-password', [MasterPasswordResetLinkController::class, 'store'])
                        ->name('masteradmin.password.email');
        Route::get('reset-password/{token}', [MasterNewPasswordController::class, 'create'])
                        ->name('masteradmin.password.reset');
        Route::post('reset-password', [MasterNewPasswordController::class, 'store'])
                        ->name('masteradmin.password.store');

        //user change password
        Route::get('/users/change-password', [UserController::class, 'changePassword'])
        ->name('masteradmin.userdetail.changePassword');
        
        Route::post('/users/store-password/{user_id}', [UserController::class, 'storePassword'])
        ->name('masteradmin.userdetail.storePassword');

      


        Route::get('checkout', [CheckoutController::class, 'stripe'])->name('stripe');
        Route::get('success', [CheckoutController::class, 'success'])->name('success');
        Route::get('cancel', [CheckoutController::class, 'cancel'])->name('cancel');

        Route::post('/webhook', [StripeWebhookController::class, 'handleWebhook']);
        Route::get('/thank-you', function () {
            return view('thank-you');
        })->name('thank-you');

    });

    Route::middleware(['UserLoggedInListener','auth_master', 'guard.session:masteradmins', 'prevent.back.history','set.user.details','setUserFolder','check.subscription.status'])->group(function () {


        Route::get('agency-success', [CheckoutController::class, 'agencySuccess'])->name('agencysuccess');
        Route::get('agency-cancel', [CheckoutController::class, 'agencyCancel'])->name('agencycancel');

        //user profile update
        Route::post('users/profile/upload', [ProfilesController::class, 'upload'])->name('profile.upload');

        //profile
        Route::get('/dashboard', [HomeController::class, 'create'])->name('masteradmin.home');
        Route::get('/profile', [ProfilesController::class, 'edit'])->name('masteradmin.profile.edit');
       
        Route::get('/profile/{id}', [ProfilesController::class, 'edits'])->name('masteradmin.profile.edits');
        Route::get('/agencies-profile', [ProfilesController::class, 'agencyedits'])->name('masteradmin.profile.agencyedits');
        // Route::post('/agencyupdatebusinessdetails/{id}', [ProfilesController::class, 'updateagency'])->name('masteradmin.businessdetails.agencyupdate');
        Route::post('/agencyupdatebusinessdetails/{id}', [ProfilesController::class, 'updateagency'])->name('masteradmin.update');


        Route::patch('/profile', [ProfilesController::class, 'update'])->name('masteradmin.profile.update');
        Route::delete('/profile', [ProfilesController::class, 'destroy'])->name('masteradmin.profile.destroy');
        Route::get('fetch-users', [ProfilesController::class, 'fetchUser'])->name('masteradmin.profile.fetchUser');
        Route::get('fetch-agency-users', [ProfilesController::class, 'agencyfetchUser'])->name('masteradmin.profile.agencyfetchUser');
        Route::get('dashboard-task-incomplete-details/', [HomeController::class, 'incompleteDetailshome'])->name('masteradmin.dashboardtask.incomplete');


        Route::get('dashboard-task-edit/{id}/{trip_id}', [TripTaskController::class, 'edit'])->name('masteradmin.dashboardtask.edit');
        Route::patch('/dashboard-task-update/{trip_id}/{trvt_id}', [TripTaskController::class, 'update'])->name('masteradmin.dashboardtask.update');

        
        Route::put('password', [MasterPasswordController::class, 'update'])->name('masteradmin.password.update');
        Route::post('logout', [LoginController::class, 'destroy'])->name('masteradmin.logout');
        
        //create alter database
        Route::get('/create-table', [Controller::class, 'createTableRoute'])->name('create.table');
       
        //Log Activity
        Route::get('/logActivity', [ProfilesController::class, 'logActivity'])->name('masteradmin.masterlog.index');

        //Business Profile
        Route::get('/business-profile', [ProfilesController::class, 'businessProfile'])->name('masteradmin.business.edit');
        Route::patch('/business-profile-update', [ProfilesController::class, 'businessProfileUpdate'])->name('masteradmin.business.update');
        Route::patch('/business-profile-edit', [ProfilesController::class, 'updateBusinessDetails'])->name('masteradmin.business.edits');
        // //exp plan or not plan purchase
        // Route::get('/plan/purchase', [ProfilesController::class, 'purchase'])->name('business.plan.purchase');
            
        //user role 
       Route::delete('roledestroy/{role}', [UserRoleController::class, 'destroy'])->name('masteradmin.role.destroy');
       Route::patch('roleupdate/{role}', [UserRoleController::class, 'update'])->name('masteradmin.role.update');
       Route::resource('user-role-details', UserRoleController::class);
       Route::get('userrole/{userrole}', [UserRoleController::class, 'userrole'])->name('masteradmin.role.userrole');
       Route::put('updaterole/{userrole}', [UserRoleController::class, 'updaterole'])->name('masteradmin.role.updaterole');
                       

       // add by dx....master user details
       Route::get('/userdetails', [UserController::class, 'index'])->name('masteradmin.userdetail.index');
       Route::get('/usercreate', [UserController::class, 'create'])->name('masteradmin.userdetail.create');
       Route::post('/userstore', [UserController::class, 'store'])->name('masteradmin.userdetail.store');
       Route::get('/useredit/{userdetaile}', [UserController::class, 'edit'])->name('masteradmin.userdetail.edit');
       
       Route::patch('/userupdate/{userdetail}', [UserController::class, 'update'])->name('masteradmin.userdetail.update');
       Route::delete('/userdestroy/{userdetail}', [UserController::class, 'destroy'])->name('masteradmin.userdetail.destroy');

       //user certification
       Route::resource('user-certification', UserCertificationController::class);

       //trip
       Route::resource('trip', TripController::class);
       Route::get('/view-trip/{userdetail}', [TripController::class, 'view'])->name('trip.view');
      

        Route::get('/follow_up_trips_details', [TripController::class, 'follow_up_details'])->name('masteradmin.trip.follow_up_trip_details');
        Route::get('/follow_up_trips', [TripController::class, 'follow_up_after'])->name('masteradmin.trip.follow_up_trip');
        Route::get('/complete_follow_up_trips', [TripController::class, 'follow_up_after_complete'])->name('masteradmin.trip.follow_up_complete_trip');

        Route::get('/tripdetails/list-view', [TripController::class, 'listView'])->name('trip.listView');
        Route::get('/tripdetails/grid-view', [TripController::class, 'gridView'])->name('trip.gridView');
        Route::post('/trip/update-status', [TripController::class, 'updateStatus'])->name('trip.updateStatus');

        //book after 
        Route::get('/booked_trips/grid-view', [TripController::class, 'bookgridView'])->name('bookingtrip.gridView');
        Route::get('/booked_trips', [TripController::class, 'booked_after'])->name('masteradmin.trip.booked_after');

       //trip family member 
       Route::get('family-member/{id}', [TripTravelingMemberController::class, 'index'])->name('masteradmin.family-member.index');
       Route::post('/family-member-store/{id}', [TripTravelingMemberController::class, 'store'])->name('masteradmin.family-member.store');
       Route::get('/family-member-edit/{id}', [TripTravelingMemberController::class, 'edit'])->name('masteradmin.family-member.edit');
       Route::patch('/family-member-update/{trtm_id}', [TripTravelingMemberController::class, 'update'])->name('masteradmin.family-member.update');
       Route::delete('/family-member-update/{trip_id}/{trtm_id}', [TripTravelingMemberController::class, 'destroy'])->name('masteradmin.family-member.destroy');

       //Task
       Route::get('task/{id}', [TripTaskController::class, 'index'])->name('masteradmin.task.index');
       Route::post('/task-store/{id}', [TripTaskController::class, 'store'])->name('masteradmin.task.store');
       Route::get('task-edit/{id}/{trip_id}', [TripTaskController::class, 'edit'])->name('masteradmin.task.edit');
       Route::patch('/task-update/{trip_id}/{trvt_id}', [TripTaskController::class, 'update'])->name('masteradmin.task.update');
       Route::patch('/task-update/{trvt_id}', [TripTaskController::class, 'updateTask'])->name('masteradmin.task.updateTask');

       Route::delete('/task-delete/{trip_id}/{trtm_id}', [TripTaskController::class, 'destroy'])->name('masteradmin.task.destroy');

        //library
       Route::resource( 'library', LibraryController::class);

       //library Category
       Route::resource('library_category',libraryCatgoryController::class);


      Route::get('category', [LibraryController::class, 'category'])->name('masteradmin.library.category');



       //Library Add Dropdown
       Route::get('states/{countryId}', [LibraryController::class, 'getStates'])->name('getStates');
       Route::get('/currencies/{countryId}', [LibraryController::class, 'getCurrencies']);
       Route::get('/get-cities/{stateId}', [LibraryController::class, 'getCities'])->name('getRegisterCities');



       //Library Delete Image
       Route::post('/library/{id}/image/{image}', [LibraryController::class, 'deleteImage'])->name('library.image.delete');

       //Library View 
       //Route::get('/library/test', [LibraryController::class, 'view'])->name('masteradmin.library.test');
       Route::get('view', [LibraryController::class, 'view'])->name('masteradmin.library.view');


       //Library Show
       Route::get('/library/show/{id}', [LibraryController::class, 'show'])->name('masteradmin.library.show');

       //Library Search
       Route::get('/library/search', [LibraryController::class, 'search'])->name('masteradmin.library.search');

       //lib email send to traveller 
       Route::post('/send-library/{id}', [LibraryController::class, 'sendEmail'])->name('masteradmin.library.send.email');


        //trip traveler document 
        Route::get('traveler-document/{id}', [TravelerDocumentController::class, 'index'])->name('masteradmin.document.index');
        Route::post('/traveler-document-store/{id}', [TravelerDocumentController::class, 'store'])->name('masteradmin.document.store');
        Route::get('traveler-document-edit/{id}', [TravelerDocumentController::class, 'edit'])->name('masteradmin.document.edit');
        Route::delete('/traveler-document/{id}/image/{image}', [TravelerDocumentController::class, 'deleteImage'])->name('document.image.delete');
        Route::patch('/traveler-document-update/{trvd_id}', [TravelerDocumentController::class, 'update'])->name('masteradmin.document.update');
        Route::delete('/traveler-document-delete/{trip_id}/{trvd_id}', [TravelerDocumentController::class, 'destroy'])->name('masteradmin.document.destroy');


        //email template 
        Route::get('/email', [EmailTemplateController::class, 'index'])->name('masteradmin.emailtemplate.index');
        Route::get('/email-create', [EmailTemplateController::class, 'create'])->name('masteradmin.emailtemplate.create');

        Route::post('/emailtemplate/store', [EmailTemplateController::class, 'store'])->name('emailtemplate.store');
        Route::delete('/emailtemplate/{emailTemplate}', [EmailTemplateController::class, 'destroy'])->name('masteradmin.emailtemplate.destroy');
        Route::get('/emailtemplate/{emailTemplate}', [EmailTemplateController::class, 'edit'])->name('masteradmin.emailtemplate.edit');
        Route::patch('/emailtemplate/{emailTemplate}', [EmailTemplateController::class, 'update'])->name('masteradmin.emailtemplate.update');
        Route::post('/fetch-email-text', [EmailTemplateController::class, 'fetchEmailText'])->name('fetchEmailText');
        Route::post('/fetch-traveller-details', [EmailTemplateController::class, 'fetchTravellerDetails'])->name('fetchTravellerDetails');
        Route::post('/email-templates/store', [EmailTemplateController::class, 'storeEmailTemplate'])->name('email-template.store');

        Route::resource('email_category',EmailCategoryController::class);
        
        //Agency
        Route::resource( 'agency', AgencyController::class);
        Route::get('/agency/view/{id}', [AgencyController::class, 'view'])->name('masteradmin.agency.view');

       // Route::get('/agency/rolemodel/{id}', [AgencyController::class, 'rolemodel'])->name('rolemodel');

        Route::post('/rolemodel/assign/{userId}', [AgencyController::class, 'assignUserRole'])->name('rolemodel.assign');


         
        //Agency dropdown
         Route::get('register_state/{countryId}', [AgencyController::class, 'getStates'])->name('agencyStates');
         Route::get('register_cities/{stateId}', [AgencyController::class, 'getCities'])->name('agencyCities');
 

        //Task list
        Route::get('task-details/', [TripTaskController::class, 'allDetails'])->name('masteradmin.task.all');
        Route::get('task-incomplete-details/', [TripTaskController::class, 'incompleteDetails'])->name('masteradmin.task.incomplete');
        Route::post('/task-details-store', [TripTaskController::class, 'storeTask'])->name('masteradmin.taskdetails.store');
        Route::patch('/task-details-update/{trvt_id}', [TripTaskController::class, 'updateTask'])->name('masteradmin.taskdetails.update');
        Route::get('task-edit/{id}', [TripTaskController::class, 'edit_task'])->name('masteradmin.taskdetails.editTask');
        Route::delete('/task-details-delete/{trvt_id}', [TripTaskController::class, 'destroyTask'])->name('masteradmin.taskdetails.destroy');

        
         //Travelers  
         Route::get('/travelers-details', [TripController::class, 'travelersDetails'])->name('masteradmin.travelers.travelersDetails');
         Route::get('/travelers-create', [TripController::class, 'createTravelers'])->name('masteradmin.travelers.create');
         Route::post('/travelers-store', [TripController::class, 'travelerstoreData'])->name('masteradmin.travelers.store');
         Route::post('/trips/travelerss-store', [TripController::class, 'travelerStore'])->name('masteradmin.travelers.trip.store');
         Route::get('travelers-edit/{id}', [TripController::class, 'editDetails'])->name('masteradmin.travelers.edit');
         Route::put('/travelers-update/{id}', [TripController::class, 'travelerUpdate'])->name('masteradmin.travelers.update');
         Route::get('/view-travelers/{id}', [TripController::class, 'viewDetails'])->name('masteradmin.travelers.view');
         Route::delete('/travelers-delete/{id}', [TripController::class, 'destroyTraveler'])->name('masteradmin.travelers.destroy');


        //task category
        Route::resource('task-category', TaskCategoryController::class);

        //document image/document security 
        Route::get('/document/{filename}', function ($filename) {
            $userFolder = session('userFolder');

                $filePath = storage_path('app/'.$userFolder.'/document_image/' . $filename);

                if (!file_exists($filePath)) {
                    abort(404);
                }

                return response()->file($filePath); 
        

        })->name('document.access');

        //task image/document security 
        Route::get('/tasks/{filename}', function ($filename) {
            $userFolder = session('userFolder');

                $filePath = storage_path('app/'.$userFolder.'/task_image/' . $filename);

                if (!file_exists($filePath)) {
                    abort(404);
                }

                return response()->file($filePath); 
        

        })->name('task.access');

        //library image/document security 
        Route::get('/library_images/{filename}', function ($filename) {
            $userFolder = session('userFolder');

                $filePath = storage_path('app/'.$userFolder.'/library_image/' . $filename);

                if (!file_exists($filePath)) {
                    abort(404);
                }

                return response()->file($filePath); 
        

        })->name('library_images.access');

        //user certification image/document security 
        Route::get('/certification_images/{filename}', function ($filename) {
            $userFolder = session('userFolder');

                $filePath = storage_path('app/'.$userFolder.'/certification_image/' . $filename);

                if (!file_exists($filePath)) {
                    abort(404);
                }

                return response()->file($filePath); 
        

        })->name('certification.access');

         //agency image security 
         Route::get('/agencys_images/{filename}', function ($filename) {
            $userFolder = session('userFolder');

                $filePath = storage_path('app/'.$userFolder.'/profile_image/' . $filename);

                if (!file_exists($filePath)) {
                    abort(404);
                }

                return response()->file($filePath); 


        })->name('agencys.access');


        //sent email template
        Route::get('/emaildetail', [EmailTemplateController::class, 'EmailTemplate'])->name('masteradmin.emailtemplate.EmailTemplate');
        Route::get('/add-emailtemplate', [EmailTemplateController::class, 'add_emailtemplate'])->name('masteradmin.emailtemplate.addemailtemplate');
        Route::delete('/delete-emailtemplate/{id}', [EmailTemplateController::class, 'delete_emailtemplate'])->name('masteradmin.emailtemplate.destroyemailtemplate');
        Route::get('/view-emailtemplate/{id}', [EmailTemplateController::class, 'view_emailtemplate'])->name('masteradmin.emailtemplate.viewemailtemplate');
       
        Route::get('/checkout/{plan}', [CheckoutController::class])->name('checkout');

        //mail Configration
        Route::get('/mail-settings', [ProfilesController::class, 'mailsetting'])->name('masteradmin.mailsetting');
        Route::post('/update-mail-details', [ProfilesController::class, 'updatemail'])->name('masteradmin.updatemailsetting');
        Route::get('/fetch-emails/{tripId}/{user_id}/{unique_id}', [MailController::class, 'fetchEmails'])->name('masteradmin.trip.fetchEmails');

 
        
        // Route::get('/user/subscribe', function (Request $request) {
        //     $user = Auth::guard('masteradmins')->user();
        //     // dd($user);
         
        //     $subscription = $user->newSubscription('monthly', 'price_1QQ5LQCLWbq1l7svZaXNGW0J')->create();

        //     // Add the master_user_details_id to the subscription record
        //     $subscription->update([
        //         'master_user_details_id' => $user->users_id,
        //         'user_id' => $user->id,
        //     ]);
                        // $user->newSubscription(
            //     'monthly', // Subscription name
            //     'price_1QQ5LQCLWbq1l7svZaXNGW0J' // Stripe plan ID
            // )->create($request->paymentMethodId);

            
        // });
        
        //trip traveler serach bar 
        Route::get('trip/api/travelers', [TripController::class, 'getTravelerNames'])->name('travelers.autocomplete');

         
    });
     

});

require __DIR__.'/auth.php';