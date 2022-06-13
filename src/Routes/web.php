<?php

use Illuminate\Support\Facades\Route;
use AdminUI\AdminUIUserBase\Controllers\ResetPassword;
use AdminUI\AdminUIUserBase\Controllers\UserController;
use AdminUI\AdminUIUserBase\Controllers\LoginController;
use AdminUI\AdminUIUserBase\Controllers\UserApiController;
use AdminUI\AdminUIUserBase\Controllers\RegisterController;


// User verify account
Route::group([
    'middleware' => ['web']
], function () {
    Route::get('/user/account/{vue_capture?}', [UserController::class, 'index'])->where('vue_capture', '[\/\w\.-]*')->name('userbase.account');
});

// Need Auth Routes
Route::group([
    'middleware' => ['web', 'auth']
], function () {
    // User Logout
    Route::post('/user/account/logout', [LoginController::class, 'logout'])->name('userbase.api.logout');
});

// Login | Register Route need to be logout to view this page
Route::group([
    'middleware' => ['web', 'guest']
], function () {
    // User Login
    Route::get('/user/login', [LoginController::class, 'index'])->name('login');
    // Do login
    Route::post('/login/user', [LoginController::class, 'login'])->name('userbase.login');

    // User Registration
    Route::get('/user/register', [RegisterController::class, 'index'])->name('userbase.register');
    Route::post('/user/register', [RegisterController::class, 'userRegister']);

    // Password Reset
    Route::get('/user/forgot-password', [ResetPassword::class, 'index'])->name('forgot-password');
    Route::post('/user/password-reset', [ResetPassword::class, 'reset'])->name('password-reset');
    Route::get('/user/password-reset/{token}', [ResetPassword::class, 'passwordReset'])->name('password.reset');
    Route::post('/user/password-change', [ResetPassword::class, 'passwordChange'])->name('password.change');
});

// User verify account
Route::group([
    'middleware' => ['web']
], function () {
    // War the user need to be verify
    Route::get('/email/verify', [LoginController::class, 'needVerify'])->name('verification.notice');

    // Login to verify the user
    Route::get('/user/verify/{id}/{time}', [LoginController::class, 'verify'])->name('userbase.verify');
});

/* *********************************************************
 * USER ACCOUNT API ROUTES
***********************************************************/
Route::group([
    'middleware' => ['web', 'auth']
], function () {

    // WISHLIST
    Route::post('/user/api/wishlist', [UserApiController::class, 'getWishList'])
        ->name('userbase.api.wishlist');
    Route::patch('/user/api/wishlist/{product}', [UserApiController::class, 'toggleWishList'])
        ->name('userbase.api.wishlist');

    // ACCOUNTS
    Route::get('/user/api/accounts', [UserApiController::class, 'getAccounts'])
        ->name('userbase.api.accounts');

    // ADDRESSES
    Route::get('/user/api/addresses/{account}', [UserApiController::class, 'getAccountAddresses'])
        ->name('userbase.api.addresses');
    Route::post('/user/api/address', [UserApiController::class, 'createAccountAddress'])
        ->name('userbase.api.address');
    Route::patch('/user/api/address', [UserApiController::class, 'updateAccountAddress']);
    Route::delete('/user/api/address', [UserApiController::class, 'deleteAccountAddress']);

    // PROFILE
    Route::patch('/user/api/password', [UserApiController::class, 'userPasswordUpdate'])
        ->name('userbase.api.password');
    Route::patch('/user/api/profile', [UserApiController::class, 'userProfileUpdate'])
        ->name('userbase.api.profile');

    // ORDERS
    Route::get('/user/api/orders', [UserApiController::class, 'getUserOrders'])
        ->name('userbase.api.orders');

    // NOTIFICATIONS
    Route::get('/user/api/notifications', [UserApiController::class, 'userGetNotification'])
        ->name('userbase.api.notifications');
    Route::patch('/user/api/notifications', [UserApiController::class, 'userReadNotification']);
    Route::delete('/user/api/notifications', [UserApiController::class, 'userDeleteNotification']);

    // USER PRODUCTS REVIEW
    Route::get('/user/api/reviews', [UserApiController::class, 'getReviews'])
        ->name('userbase.api.reviews');
    Route::delete('/user/api/reviews', [UserApiController::class, 'deleteReviews']);

    Route::post('/user/api/review/{product_id}', [UserApiController::class, 'addReview'])
        ->name('userbase.api.review.add');
    Route::patch('/user/api/review', [UserApiController::class, 'updateReview'])
        ->name('userbase.api.review');

    // RETURNS
    Route::post('/user/api/returns', [UserApiController::class, 'sendReturn'])
        ->name('userbase.api.returns');

    // Send notificaton test
    Route::any('/user/send/notification', [UserApiController::class, 'sendTestNotification'])
        ->name('userbase.send.notification');
});

/* *********************************************************
 * USER ACCOUNT API ROUTES END
***********************************************************/
