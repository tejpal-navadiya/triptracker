<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TripController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});
Route::get('country_list',            [AuthController::class,'getAllCountry']);
Route::post('state_list',            [AuthController::class,'getState']);
Route::post('city_list',            [AuthController::class,'getCity']);
Route::get('subscription_plans_list',            [AuthController::class,'getPlan']);
Route::post('forgot_password',            [AuthController::class,'forgotPassword']);


Route::middleware(['handleAuthErrors'])->group( function () {
    //user profile
    Route::get('GetUserProfile',     [AuthController::class,'GetUserProfile']);
    Route::post('update_profile',     [AuthController::class,'updateUserProfile']);
    Route::post('change_password',     [AuthController::class,'changePassword']);

    //trip
    Route::get('trip_list',     [TripController::class,'GetTripList']);
    Route::post('task_list',     [TripController::class,'GetTaskList']);
    Route::post('reminder_task_list',     [TripController::class,'GetTaskReminderList']);
    Route::post('add_trip',     [TripController::class,'AddTrip']);
    
    Route::get('Logout',            [AuthController::class,'Logout']);
});