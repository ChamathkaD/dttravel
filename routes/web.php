<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\TravelCategoryController;
use App\Http\Controllers\TravelDestinationController;
use App\Http\Controllers\TravelerController;
use App\Http\Controllers\TravelPackageController;
use App\Http\Controllers\TravelPackageImageController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

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
Route::controller(UserAccountController::class)->group(function () {
    Route::get('/email-confirmation/{token}', 'showEmailConfirmationView')
        ->name('email-confirmation');

    Route::prefix('user-account/wizard')->name('user-account.wizard.')->group(function () {
        Route::get('/create', 'create')->name('create');

        Route::put('/step-one', 'stepOne')->name('step-one');

        Route::put('/step-two', 'stepTwo')->name('step-two');

        Route::put('/update/{user}', 'update')->name('update');
    });
});

Route::controller(LoginController::class)->prefix('user')->name('user.')->group(function () {
    Route::get('/otp-request', 'create')->name('otp-request');

    Route::post('/otp-request', 'store')->name('otp-request');

    Route::get('/confirm-otp/{user}', 'showConfirmOtp')->name('show.confirm-otp');

    Route::post('/confirm-otp/store', 'confirmOtp')->name('confirm-otp');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:Super Administrator|Staff Member'])->group(function () {
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);

        Route::get('users/{user}/restore', [UserController::class, 'restore'])
            ->name('users.restore');

        Route::get('users/{user}/force-delete', [UserController::class, 'fDelete'])
            ->name('users.force-delete');
    });

    Route::resource('agents', AgentController::class)->except(['create', 'edit', 'update'])
        ->middleware(['role:Travel Agent|Super Administrator']);

    Route::resource('travelers', TravelerController::class)->except(['create', 'edit', 'update']);

    Route::resource('bookings', BookingController::class)->except(['create', 'edit']);

    Route::get('bookings/{booking}/cancel', [BookingController::class, 'cancel'])
        ->name('bookings.cancel');

    Route::get('bookings/{booking}/complete', [BookingController::class, 'complete'])
        ->name('bookings.complete');

    Route::post('bookings/note', [BookingController::class, 'createBookingNote'])
        ->name('bookings.create-note');

    Route::get('bookings/{booking}/invite-payments', [BookingController::class, 'inviteBookingPayment'])
        ->name('bookings.booking-invite-payments');

    Route::middleware(['role:Super Administrator|Staff Member|Traveler'])->group(function () {
        Route::resource('travel-categories', TravelCategoryController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        Route::get('travel-categories/{travel_category}/restore', [TravelCategoryController::class, 'restore'])
            ->name('travel-categories.restore');

        Route::get('travel-categories/{travel_category}/force-delete', [TravelCategoryController::class, 'fDelete'])
            ->name('travel-categories.force-delete');

        Route::resource('travel-destinations', TravelDestinationController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        Route::get('travel-destinations/{travel_destination}/restore', [TravelDestinationController::class, 'restore'])
            ->name('travel-destinations.restore');

        Route::get('travel-destinations/{travel_destination}/force-delete', [TravelDestinationController::class, 'fDelete'])
            ->name('travel-destinations.force-delete');

        Route::resource('travel-packages', TravelPackageController::class)->except('show');

        Route::get('travel-packages/{travel_package}/restore', [TravelPackageController::class, 'restore'])
            ->name('travel-packages.restore');

        Route::get('travel-packages/{travel_package}/force-delete', [TravelPackageController::class, 'fDelete'])
            ->name('travel-packages.force-delete');
    });

    // Agent Create Wizard Routes
    Route::post('agents/step-one/store', [AgentController::class, 'stepOneCreate'])
        ->name('agents.step-one.store');

    // Invite Agent Routes
    Route::post('agents/invite', [AgentController::class, 'inviteAgent'])
        ->name('agents.invite');

    // Agent Edit Wizard Routes
    Route::put('agents/account/update/{user}', [AgentController::class, 'updateAccount'])
        ->name('agents.account.update');

    Route::put('agents/business/update/{user}', [AgentController::class, 'updateBusiness'])
        ->name('agents.business.update');

    // Agent Payments History Routes
    Route::get('/agents/payment/history', [AgentController::class, 'paymentHistory'])
        ->name('agents.payment.history');

    Route::get('/agents/payment/details/{agentPayment}', [AgentController::class, 'paymentDetails'])
        ->name('agents.payment.details');

    Route::get('agents/payment/paid/{agentPayment}', [AgentController::class, 'updateStatus'])
        ->name('agents.payment.updateStatus');

    Route::get('/agents/invoice/{agentPayment}', [AgentController::class, 'showInvoice'])
        ->name('agents.show-invoice');

    // Other Travelers Routes
    Route::post('/travelers/validate/personalFrom', [TravelerController::class, 'validatePersonalForm'])
        ->name('travelers.validate.personalFrom');

    Route::post('/travelers/validate/contactForm', [TravelerController::class, 'validateContactForm'])
        ->name('travelers.validate.contactFrom');

    Route::put('travelers/update-personal-details/{user}', [TravelerController::class, 'updatePersonalDetails'])
        ->name('travelers.update-personal-details');

    Route::put('travelers/update-contact-details/{user}', [TravelerController::class, 'updateContactDetails'])
        ->name('travelers.update-contact-details');

    Route::put('travelers/update-highlight-details/{user}', [TravelerController::class, 'updateHighlightDetails'])
        ->name('travelers.update-highlight-details');

    // Booking Invoice
    Route::get('/bookings/invoice/{booking}', [BookingController::class, 'showInvoice'])
        ->name('booking.show-invoice');

    // Print Traveler Details
    Route::get('/bookings/print/{booking}', [BookingController::class, 'showPrintTravelerDetails'])
        ->name('booking.show-print-traveler-details');

    Route::post('bookings/{booking}/add-traveler-for-booking', [BookingController::class, 'addTravelersForBooking'])
        ->name('bookings.add-traveler-for-booking');

    // user profile routes
    Route::get('/user/profile', [UserProfileController::class, 'show'])
        ->name('profile.show');

    Route::put('/user/profile-information', [UserProfileController::class, 'update'])
        ->name('user-profile-information.update');

    Route::delete('/user/profile-photo', [UserProfileController::class, 'destroy'])
        ->name('current-user-photo.destroy');

    Route::put('/user/password', [UserProfileController::class, 'updatePassword'])
        ->name('user-password.update');

    Route::put('/user/first-login-password', [UserProfileController::class, 'updateFirstLoginPassword'])
        ->name('user-first-login-password.update');

    // Travel Packages Routes
    Route::post('travel-packages/inclusions', [TravelPackageController::class, 'storeInclusions'])
        ->name('travel-packages.inclusions.store');

    Route::delete('travel-packages/inclusions/{inclusion}', [TravelPackageController::class, 'deleteInclusion'])
        ->name('travel-packages.inclusions.destroy');

    Route::post('travel-packages/exclusions', [TravelPackageController::class, 'storeExclusions'])
        ->name('travel-packages.exclusions.store');

    Route::delete('travel-packages/exclusions/{exclusion}', [TravelPackageController::class, 'deleteExclusion'])
        ->name('travel-packages.exclusions.destroy');

    Route::post('travel-packages/value-add', [TravelPackageController::class, 'storeValueAdds'])
        ->name('travel-packages.value-add.store');

    Route::delete('travel-packages/value-add/{value_add}', [TravelPackageController::class, 'deleteValueAdds'])
        ->name('travel-packages.value-add.destroy');

    Route::post('travel-packages/conditions', [TravelPackageController::class, 'storeConditions'])
        ->name('travel-packages.conditions.store');

    Route::delete('travel-packages/conditions/{condition}', [TravelPackageController::class, 'deleteCondition'])
        ->name('travel-packages.conditions.destroy');

    Route::delete('travel-package-images/{id}', TravelPackageImageController::class)
        ->name('travel-package-images.destroy');

    Route::put('travel-packages/meta-image-delete/{travel_package}', [TravelPackageController::class, 'deleteMetaImage'])
        ->name('travel-package.delete-meta-image');

    Route::delete('travel-package-conditions/{condition}', ConditionController::class)
        ->name('travel-package-conditions.destroy');

    // Password Reset Route (For Super Administrator)
    Route::post('admin/password/reset', [UserAccountController::class, 'sendPasswordResetLink'])
        ->name('send-password-reset-link')
        ->middleware(['role:Super Administrator']);

    Route::controller(UserController::class)->prefix('user')->name('user.')->group(function () {
        // Enable & Disable Users
        Route::get('/enable-user/{user}', 'enableUser')->name('enable-user');

        Route::get('/disable-user/{user}', 'disableUser')->name('disable-user');

        // Resend Invitation Email
        Route::get('/resend-invitation/{user}', 'resendInvitationMail')->name('resend-invitation');
    });

    Route::resource('contacts', ContactController::class)->only('index', 'show', 'destroy');

});
Route::get('/debug', [\App\Http\Controllers\DebugController::class, 'index']);

Route::get('/download/invoice/{booking}', [PrintController::class, 'downloadInvoice'])->name('download-invoice');

Route::get('/download/traveler-details/{booking}', [PrintController::class, 'downloadTravelerDetails']);
