<?php

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
        
        Route::get('/businessdetails', [BusinessDetailController::class, 'index'])->name('businessdetails.index');
        Route::get('/businessdetails/{id}', [BusinessDetailController::class, 'show'])->name('businessdetails.show');
        Route::post('/business-detail/{id}/update-status', [BusinessDetailController::class, 'updateStatus'])->name('masteradmin.updateStatus');

        //logs
        Route::get('/logActivity', [ProfileController::class, 'logActivity'])->name('adminlog.index');
        
    });
});

Route::group(['prefix' => $busadminRoute], function () {
    
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
        
    });

    Route::middleware(['auth_master', 'guard.session:masteradmins', 'prevent.back.history','set.user.details','setUserFolder'])->group(function () {
        
        //profile
        Route::get('/dashboard', [HomeController::class, 'create'])->name('masteradmin.home');
        Route::get('/profile', [ProfilesController::class, 'edit'])->name('masteradmin.profile.edit');
        Route::get('/profile/{id}', [ProfilesController::class, 'edits'])->name('masteradmin.profile.edits');
        Route::patch('/profile', [ProfilesController::class, 'update'])->name('masteradmin.profile.update');
        Route::delete('/profile', [ProfilesController::class, 'destroy'])->name('masteradmin.profile.destroy');
        Route::get('fetch-users', [ProfilesController::class, 'fetchUser'])->name('masteradmin.profile.fetchUser');

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

       //trip family member 
       Route::get('family-member/{id}', [TripTravelingMemberController::class, 'index'])->name('masteradmin.family-member.index');
       Route::post('/family-member-store/{id}', [TripTravelingMemberController::class, 'store'])->name('masteradmin.family-member.store');
       Route::get('/family-member-edit/{id}/{trip_id}', [TripTravelingMemberController::class, 'edit'])->name('masteradmin.family-member.edit');
       Route::patch('/family-member-update/{trip_id}/{trtm_id}', [TripTravelingMemberController::class, 'update'])->name('masteradmin.family-member.update');
       Route::delete('/family-member-update/{trip_id}/{trtm_id}', [TripTravelingMemberController::class, 'destroy'])->name('masteradmin.family-member.destroy');

       //Task
       Route::get('task/{id}', [TripTaskController::class, 'index'])->name('masteradmin.task.index');
       Route::post('/task-store/{id}', [TripTaskController::class, 'store'])->name('masteradmin.task.store');
       Route::get('task-edit/{id}/{trip_id}', [TripTaskController::class, 'edit'])->name('masteradmin.task.edit');
       Route::patch('/task-update/{trip_id}/{trvt_id}', [TripTaskController::class, 'update'])->name('masteradmin.task.update');
       Route::delete('/task-delete/{trip_id}/{trtm_id}', [TripTaskController::class, 'destroy'])->name('masteradmin.task.destroy');

        //library
       Route::resource( 'library', LibraryController::class);

       //Library Add Dropdown
       Route::get('states/{countryId}', [LibraryController::class, 'getStates']);
       Route::get('/currencies/{countryId}', [LibraryController::class, 'getCurrencies']);
       Route::get('/get-cities/{stateId}', [LibraryController::class, 'getCities']);
      //Library Edit Dropdown
       Route::get('/cities/{stateId}', [LibraryController::class, 'getCities']);
       Route::get('/library/view/{id}', [LibraryController::class, 'view'])->name('masteradmin.library.view');

       //Library Delete Image
       Route::get('/library/{id}/image/{image}', [LibraryController::class, 'deleteImage'])->name('library.image.delete');

       //Library View 
       Route::get('/library/view/{id}', [LibraryController::class, 'view'])->name('masteradmin.library.view');
       

        //trip traveler document 
        Route::get('traveler-document/{id}', [TravelerDocumentController::class, 'index'])->name('masteradmin.document.index');
        Route::post('/traveler-document-store/{id}', [TravelerDocumentController::class, 'store'])->name('masteradmin.document.store');
        Route::get('traveler-document-edit/{id}/{trip_id}', [TravelerDocumentController::class, 'edit'])->name('masteradmin.document.edit');
        Route::delete('/traveler-document/{id}/image/{image}', [TravelerDocumentController::class, 'deleteImage'])->name('document.image.delete');
        Route::patch('/traveler-document-update/{trip_id}/{trvd_id}', [TravelerDocumentController::class, 'update'])->name('masteradmin.document.update');
        Route::delete('/traveler-document-delete/{trip_id}/{trvd_id}', [TravelerDocumentController::class, 'destroy'])->name('masteradmin.document.destroy');



        //email template 
        Route::get('/email', [EmailTemplateController::class, 'index'])->name('masteradmin.emailtemplate.index');
        Route::get('/email-create', [EmailTemplateController::class, 'create'])->name('masteradmin.emailtemplate.create');

        Route::post('/emailtemplate/store', [EmailTemplateController::class, 'store'])->name('emailtemplate.store');
        Route::delete('/emailtemplate/{emailTemplate}', [EmailTemplateController::class, 'destroy'])->name('masteradmin.emailtemplate.destroy');
        Route::get('/emailtemplate/{emailTemplate}', [EmailTemplateController::class, 'edit'])->name('masteradmin.emailtemplate.edit');
        Route::patch('/emailtemplate/{emailTemplate}', [EmailTemplateController::class, 'update'])->name('masteradmin.emailtemplate.update');
        Route::get('/emaildetail', [EmailTemplateController::class, 'EmailTemplate'])->name('masteradmin.emailtemplate.EmailTemplate');
        Route::post('/fetch-email-text', [EmailTemplateController::class, 'fetchEmailText'])->name('fetchEmailText');
        Route::post('/fetch-traveller-details', [EmailTemplateController::class, 'fetchTravellerDetails'])->name('fetchTravellerDetails');
        Route::post('/email-template/store', [EmailTemplateController::class, 'storeEmailTemplate'])->name('email-template.store');


       
       //Agency
    //    Route::resource( 'agency', AgencyController::class);


        //Task list
        Route::get('task-details/', [TripTaskController::class, 'allDetails'])->name('masteradmin.task.all');
        Route::get('task-incomplete-details/', [TripTaskController::class, 'incompleteDetails'])->name('masteradmin.task.incomplete');


         //Travelers  
         Route::get('/travelers-details', [TripController::class, 'travelersDetails'])->name('masteradmin.travelers.travelersDetails');
         Route::get('/travelers-create', [TripController::class, 'createTravelers'])->name('masteradmin.travelers.create');

    });
     

});

require __DIR__.'/auth.php';