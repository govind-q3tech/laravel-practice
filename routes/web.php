<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\BlogControoler;
use App\Http\Controllers\EventNotificationController;
use App\Http\Controllers\SubscriptionController;
// use Laravel\Fortify\Http\Controllers\NewPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
// use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
// use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\FavouritesController;
use App\Http\Controllers\AdvertisementsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserHashTagController;
use App\Http\Controllers\ExportUsersController;
use App\Http\Controllers\BulkUploadController;
use App\Http\Controllers\CheckpdfController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/phpinfo', function () {
    phpinfo();
    die;
});


Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    return '<h1>Cache facade value cleared</h1>';
});

Route::get('/google-analytics', function () {
    return '<h1>google analytics</h1>';
});

Route::group(['as' => 'frontend.'], function () {

  
    $enableViews = config('fortify.views', true);
    // Authentication...
    if ($enableViews) {
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])->middleware(['guest'])->name('login');
    }

    $limiter = config('fortify.limiters.login');
    $twoFactorLimiter = config('fortify.limiters.two-factor');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware(array_filter(['guest', $limiter ? 'throttle:' . $limiter : null,]));
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    // Password Reset...
    if (Features::enabled(Features::resetPasswords())) {
        if ($enableViews) {
            Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->middleware(['guest'])->name('password.request');
            Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->middleware(['guest'])->name('password.reset');
        }
        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware(['guest'])->name('password.email');
        Route::post('/reset-password', [NewPasswordController::class, 'store'])->middleware(['guest'])->name('password.update');
    }
    // Registration...
    if (Features::enabled(Features::registration())) {
        if ($enableViews) {
            Route::get('/register', [RegisteredUserController::class, 'create'])->middleware(['guest'])->name('register');
            // Route::get('/sign-up', [RegisteredUserController::class, 'sessionClear'])->middleware(['guest'])->name('session-clear');
        }

        Route::post('/register', [RegisteredUserController::class, 'store'])->middleware(['guest']);
    }
    // Registration...
    if (Features::enabled(Features::registration())) {
        if ($enableViews) {
            Route::get('/register', [RegisteredUserController::class, 'create'])->middleware(['guest'])->name('register');
        }

        Route::post('/register', [RegisteredUserController::class, 'store'])->middleware(['guest']);
    }
    // Email Verification...
    if (Features::enabled(Features::emailVerification())) {

        Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');
    }

    
    Route::get('/', [HomeController::class, 'home'])->name("home");
    Route::get('/check-guzzle', [HomeController::class, 'checkGuzzle'])->name('checkGuzzle');
    

    Route::get('page/{slug}', [PagesController::class, 'show'])->name('page.show');
    Route::get('contact-us', [ContactController::class, 'index'])->name('contacts.index');
    Route::post('contact-us', [ContactController::class, 'store'])->name('contacts.store');
   
    Route::get('blog/post/{slug?}', [BlogController::class, 'blogDetails'])->name('blog.detail');
    Route::get('blog/{slug?}', [BlogController::class, 'index'])->name('blog');
    Route::post('blog/comment', [BlogController::class, 'comment'])->name('comment');

});