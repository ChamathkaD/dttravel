<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\TravelCategoryController;
use App\Http\Controllers\Api\TravelDestinationController;
use App\Http\Controllers\Api\TravelPackageController;
use App\Http\Controllers\APIAuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('travel-package-list', [TravelPackageController::class, 'index']);

Route::get('destinations', [TravelDestinationController::class, 'index']);
Route::get('travel-packages/{travel_package}', [TravelPackageController::class, 'show']);

Route::post('api-login', [APIAuthController::class, 'login']);
Route::get('countries', [CountryController::class, 'index']);
Route::post('create-traveler', [UserController::class, 'apiStore']);

Route::post('store/contacts', [ContactController::class, 'store']);
Route::get('categories', [TravelCategoryController::class, 'index']);
Route::post('confirm-payment', [BookingController::class, 'confirmPayment']);
Route::get('search-package', [TravelPackageController::class, 'searchPackage']);

Route::get('travelers/{agent_id}', [\App\Http\Controllers\Api\TravelerController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('book-now', [BookingController::class, 'store']);
    Route::post('booking/{id}', [BookingController::class, 'getBookingById']);
    Route::get('booking-by-order-number/{id}', [BookingController::class, 'getBookingByOrderId']);
    Route::post('booking/{id}/update-travelers', [BookingController::class, 'updateTravelers']);
    Route::post('booking/{id}/update-existing-travelers', [BookingController::class, 'updateExistingTravelers']);
    Route::post('traveler/{id}', [\App\Http\Controllers\Api\TravelerController::class, 'getTravelerById']);
});
