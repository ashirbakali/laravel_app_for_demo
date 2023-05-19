<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\UserController;
use App\Http\Controllers\API\Auth\ForgotPasswordController;
use API\VendorServices\VendorServicesApiController;
use API\Services\ServicesApiController;
use App\Http\Controllers\API\Services\ServiceLinksApiController;
use API\Categories\CategoriesApiController;
use API\VendorAvailability\VendorAvailabilityApiController;
use API\Appointment\AppointmentController;
use API\Complain\ComplainController;
use API\GeneralSetting\GeneralSettingController;
use API\Country\CountryController;
use API\User\ServiceController;
use API\ReviewRatings\ReviewRatingAPIController;
use API\User\FavoriteController;
use API\User\CourseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::controller(CountryController::class)->group(function(){
    Route::get('/getAllCountries', 'getAllCountries');
    Route::get('/getCountryWiseStates/{id}', 'getCountryWiseStates');
    Route::get('/getStatesWiseCity/{id}', 'getStatesWiseCity');
});
Route::post('user/login', [LoginController::class, 'login']);
Route::get('user-exists', [Controller::class, 'userExists']);
Route::post('user/register', [RegisterController::class, 'register'])->name('user');
Route::post('vendor/register', [RegisterController::class, 'register'])->name('client');
Route::post('forgot/password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password');
Route::post('confirm/otp', [ForgotPasswordController::class, 'validateOTP'])->name('verify-otp');
Route::post('reset/password', [ForgotPasswordController::class, 'resetPassword'])->name('reset-password');

Route::middleware(['auth:api', 'checkuser'])->group(function () {
    Route::get('user', [UserController::class, 'user']);
    Route::post('change/password', [UserController::class, 'changePassword']);
    Route::resource('service', VendorServicesApiController::class);
    Route::resource('courses', ServicesApiController::class);
    Route::resource('courses/links', ServiceLinksApiController::class)->except(['index', 'show', 'edit']);
    Route::put('service/links/edit/{service_id}', [ServiceLinksApiController::class, 'ServiceLinksupdate']);
    Route::resource('categories', CategoriesApiController::class);
    Route::resource('availability', VendorAvailabilityApiController::class);
    Route::get('notifications', [UserController::class, 'getNotifications']);
    Route::get('notifications/{id}', [UserController::class, 'getNotificationRead']);
    Route::post('notifications/all', [UserController::class, 'readAllNotification']);
    Route::prefix('user')->group(function(){
        Route::get('/', [UserController::class, 'user']);
        Route::post('/updateprofile', [UserController::class, 'updateprofile']);
        Route::post('/addBankDetail', [UserController::class, 'addBankDetail']);
        Route::get('/userBankDetailList', [UserController::class, 'userBankDetailList']);
    });

    Route::resource('ratings', ReviewRatingAPIController::class);

    Route::controller(ComplainController::class)->prefix('complain')->group(function(){
        Route::post('/', 'addComplainDetail');
        Route::get('/', 'complainList');
    });

    Route::controller(GeneralSettingController::class)->prefix('generalsetting')->group(function(){
        Route::get('/{key}', 'getGeneralSetting');
    });
    Route::resource('/appointment', AppointmentController::class);


    Route::controller(ServiceController::class)->group(function(){
        Route::get('/topServices',  'topServices');
        Route::get('/onlineSessionList', 'onlineSessionList');
        Route::get('/searchClient', 'searchClient');
        Route::get('/getUserDetailWithCourses/{id}', 'getUserDetailWithCourses');
        Route::get('/getVendorDateWiseTimeSlots', 'getVendorDateWiseTimeSlots');
        Route::post('/bookAppointment', 'bookAppointment');
        Route::get('/getAllUserAppointments', 'getAllUserAppointments');
        Route::get('/getUserAppointmentDetail/{id}', 'getUserAppointmentDetail');
    });


    Route::controller(CourseController::class)->group(function(){
        Route::get('/getAllCoursesList',  'getAllCoursesList');
        Route::get('/getCourseDetail/{id}',  'getCourseDetail');
        Route::post('/buyCourse',  'buyCourse');
        Route::get('/getUserPurchaseCourses',  'getUserPurchaseCourses');
    });
    Route::resource('/favorite', FavoriteController::class);
});
