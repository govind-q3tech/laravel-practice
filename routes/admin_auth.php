<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\BusinessCategoriesController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\Auth\CreatePasswordController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ModulesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\AdvertisementsController;
use App\Http\Controllers\AdvertisementsController as WebAdvertisementsController;
use App\Http\Controllers\CategoriesController;
use Illuminate\Support\Facades\Route;


Route::middleware(['prevent.history'])->as('admin.')->group(function () {

    Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

    Route::post('/create-password', [CreatePasswordController::class, 'store'])->name('password.newcreate');

    Route::get('/password/create/{token}', [CreatePasswordController::class, 'create'])->name('password.create');

    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
});

Route::middleware(['auth:admin', 'prevent.history','AdminAuth'])->as('admin.')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminUserController::class, 'dashboard'])->middleware('verified:admin.verification.notice')->name('dashboard');

    Route::get('/profile', [App\Http\Controllers\Admin\AdminUserController::class, 'profile'])->middleware('verified:admin.verification.notice')->name('profile');

    Route::post('/updateprofile', [App\Http\Controllers\Admin\AdminUserController::class, 'updateprofile'])->middleware('verified:admin.verification.notice')->name('updateprofile');

    Route::get('/report', [App\Http\Controllers\Admin\AdminUserController::class, 'reports'])->middleware('verified:admin.verification.notice')->name('report.index');

    Route::get('/report-dahsboard', [App\Http\Controllers\Admin\AdminUserController::class, 'reportDashboard'])->middleware('verified:admin.verification.notice')->name('report.report-dashboard');

    // Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');

    // Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');

    Route::get('/modules/{id}', [ModulesController::class, 'index'])->name('modules.index');

    Route::post('/modules/store', [ModulesController::class, 'store'])->name('modules.store');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['throttle:6,1'])->name('verification.send');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');

    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change-password.index');

    Route::post('/change-password', [ChangePasswordController::class, 'store'])->name('change.password');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('contact-edit/{id}', [ContactController::class, 'contactEdit'])->name('contacts.contactedit');
    Route::patch('contact-update/{id}', [ContactController::class, 'contactReply'])->name('contacts.contactupdate');
    Route::delete('contact/delete/{id}', [ContactController::class, 'deleteContact'])->name('contacts.delete');

    Route::post('/select-category', [BusinessCategoriesController::class, 'selectedCategory'])->name('selected.category');
    Route::post('/search-category', [BusinessCategoriesController::class, 'searchCategory'])->name('search.category');

    Route::post('/users-approve/{id}', [UsersController::class, 'userApprove'])->name('users.approve');

    Route::get('advertisements/featured', [AdvertisementsController::class, 'featured'])->name('advertisements.featured');
    Route::post('/approve/{id}', [AdvertisementsController::class, 'listApprove'])->name('advertisements.approve');
    Route::post('/declined/{id}', [AdvertisementsController::class, 'listDeclined'])->name('advertisements.declined');

    Route::get('advertisements/ajax/hashtags', [WebAdvertisementsController::class, 'getHashtags'])->name('advertisements.ajax.hashtags');
    Route::get('advertisements/edit-step-two-hashtags/{id}', [WebAdvertisementsController::class, 'editStepTwoHashtags'])->name('advertisements.edit-step-two-hashtags');
    Route::post('advertisements/ajax/subcategory', [CategoriesController::class, 'getSubCategory'])->name('advertisements.ajax.subcategory');
    Route::post('advertisements/ajax/areas', [WebAdvertisementsController::class, 'getAreas'])->name('advertisements.ajax.areas');
    Route::post('advertisements/ajax/locations', [WebAdvertisementsController::class, 'getLocationsAjax'])->name('advertisements.ajax.locations');

    Route::resources([
        'admin-users' => App\Http\Controllers\Admin\AdminUserController::class,
        'settings' => App\Http\Controllers\Admin\SettingController::class,
        'pages' => App\Http\Controllers\Admin\PagesController::class,
        // 'locations' => App\Http\Controllers\Admin\LocationsController::class,
        'users' => App\Http\Controllers\Admin\UsersController::class,
        'business-categories' => App\Http\Controllers\Admin\BusinessCategoriesController::class,
        'plans' => App\Http\Controllers\Admin\Membership\PlanController::class,
        'advertisements' => App\Http\Controllers\Admin\AdvertisementsController::class,
        'metatags' => App\Http\Controllers\Admin\MetatagsController::class,
        'faqs' => App\Http\Controllers\Admin\FaqsController::class,
        'galleries' => App\Http\Controllers\Admin\GalleriesController::class,
        'openings' => App\Http\Controllers\Admin\OpeningsController::class,
        'contacts' => App\Http\Controllers\Admin\ContactController::class,
        'reviews' => App\Http\Controllers\Admin\UserReviewController::class,
        'claims' => App\Http\Controllers\Admin\ClaimsController::class,
        'redirecturls' => App\Http\Controllers\Admin\RedirectUrlsManagerController::class,
        'vouchers' => App\Http\Controllers\Admin\VouchersController::class,
        'adminroles' => App\Http\Controllers\Admin\RoleController::class,
        'hooks' => App\Http\Controllers\Admin\EmailHooksController::class,
        'email-preferences' => App\Http\Controllers\Admin\EmailPreferencesController::class,
        'email-templates' => App\Http\Controllers\Admin\EmailTemplatesController::class,
        'cities' => App\Http\Controllers\Admin\CitiesController::class,
        'locations' => App\Http\Controllers\Admin\AreasController::class,
        'areas' => App\Http\Controllers\Admin\AreasController::class,
        'make' => App\Http\Controllers\Admin\MakeController::class,
        'models' => App\Http\Controllers\Admin\ModelController::class,
        'attributes' => App\Http\Controllers\Admin\AttributeController::class,
        'attribute-options' => App\Http\Controllers\Admin\AttributeOptionController::class,
        'payment' => App\Http\Controllers\Admin\PaymentController::class,
        'blogcategory' => App\Http\Controllers\Admin\BlogCategoryController::class,
        'blog' => App\Http\Controllers\Admin\BlogController::class,
        'comments' => App\Http\Controllers\Admin\CommentController::class,
        'messages' => App\Http\Controllers\Admin\MessageController::class,

       
        
    ]);
    
    Route::post('review/approve/{review?}/', [App\Http\Controllers\Admin\UserReviewController::class, 'reviewApprove'])->name('review.approve');
    Route::post('featured-approve', [App\Http\Controllers\Admin\AdvertisementsController::class, 'featuredApprove'])->name('advertisements.featured-approve');
    Route::get('status', [App\Http\Controllers\Admin\CommentController::class, 'status'])->name('status');
    Route::post('galleries/image/{id}', [App\Http\Controllers\Admin\GalleriesController::class, 'imageUpdate'])->name('galleries.image');
    Route::post('galleries/upload-image', [App\Http\Controllers\Admin\GalleriesController::class, 'uploadImage'])->name('galleries.uploadImage');
    Route::delete('galleries/delete-gallery/{id}', [App\Http\Controllers\Admin\GalleriesController::class, 'deleteGallery'])->name('galleries.deleteGallery');
});
