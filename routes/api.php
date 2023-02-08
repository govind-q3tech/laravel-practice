<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UsersController;
use App\Http\Controllers\Api\V1\AdvertisementController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/users/check', [UsersController::class, 'userCheck'])->name('user.check');

Route::group(['namespace' => 'Api\V1', 'as' => 'api.', 'middleware' => ['api.header', 'cors']], function () {


  Route::post('/send-otp', [UsersController::class, 'sendOtpVerify'])->name('user.sendotpverify');
  Route::post('/users/register', [UsersController::class, 'register'])->name('user.register');
  Route::post('/users/verify-otp', [UsersController::class, 'verifyOtp'])->name('user.verifyOtp');
  Route::post('/users/login', [UsersController::class, 'login'])->name('user.login');
  Route::post('/users/forgot-password', [UsersController::class, 'forgotPassword'])->name('user.forgotPassword');
  Route::post('/users/reset-password', [UsersController::class, 'resetPassword'])->name('user.resetPassword');
  Route::get('/users/config', [UsersController::class, 'config'])->name('user.config');
  Route::get('/users/page-list', [UsersController::class, 'pageList'])->name('user.pageList');
  Route::get('/users/faq-list', [UsersController::class, 'faqList'])->name('user.faqList');

  // Route::group(['middleware' => ['auth:api', 'api.header', 'api.authtoken']], function () {

  Route::group(['middleware' => ['api.header', 'api.authtoken']], function () {


    Route::post('/users/change-password', [UsersController::class, 'changePassword'])->name('user.changePassword');
    Route::get('/users/get-profile', [UsersController::class, 'getProfile'])->name('user.getProfile');
    Route::post('/users/update-profile', [UsersController::class, 'updateProfile'])->name('user.updateProfile');
    Route::get('/users/home-list', [UsersController::class, 'homeList'])->name('user.homeList');
    Route::get('/users/category-list', [UsersController::class, 'categoryList'])->name('user.categoryList');
    Route::post('/users/contact-us', [UsersController::class, 'contactUs'])->name('user.contactUs');
    Route::post('/users/notification-list', [UsersController::class, 'notificationList'])->name('user.notificationList');
    Route::post('/users/internal-messaging-list', [UsersController::class, 'internalMessagingList'])->name('user.internalMessagingList');
    Route::post('/users/message-conversation-list', [UsersController::class, 'messageConversationList'])->name('user.messageConversationList');
    Route::post('/users/message-send-text', [UsersController::class, 'sendInternalMessagingText'])->name('user.sendInternalMessagingText');
    Route::post('/users/message-send-file', [UsersController::class, 'sendInternalMessagingFile'])->name('user.sendInternalMessagingFile');
    Route::get('/users/ads-listing', [UsersController::class, 'myAdslist'])->name('user.myAdslist');
    Route::get('/users/logout', [UsersController::class, 'logout'])->name('user.logout');
    
    Route::post('/users/subcategory-list', [AdvertisementController::class, 'subcategoryList'])->name('user.subcategoryList');
    Route::post('/users/subcategory-list-attribute', [AdvertisementController::class, 'subcategoryListAttribute'])->name('user.subcategoryListAttribute');


    Route::post('/users/area-list', [AdvertisementController::class, 'areaList'])->name('user.areaList');
    Route::post('/users/addlising-subcategory', [AdvertisementController::class, 'addListingBySubcategoryId'])->name('user.addListingBySubcategoryId');
    Route::post('/users/ads-search', [AdvertisementController::class, 'addSearch'])->name('user.addSearch');
    Route::post('/ads/detail', [AdvertisementController::class, 'addDetail'])->name('user.addDetail');
    Route::post('/ads/filter', [AdvertisementController::class, 'adsFilter'])->name('ads.adsFilter');
    Route::post('/ads/review-rating', [AdvertisementController::class, 'reviewRating'])->name('ads.reviewRating');
    Route::post('/ads/review-list', [AdvertisementController::class, 'reviewList'])->name('ads.reviewList');
    Route::post('/ads/add', [AdvertisementController::class, 'add'])->name('ads.add');
    Route::post('/ads/edit', [AdvertisementController::class, 'edit'])->name('ads.edit');

     Route::post('/ads/edit-ads', [AdvertisementController::class, 'editAds'])->name('ads.editAds');

    Route::post('/ads/delete', [AdvertisementController::class, 'delete'])->name('ads.delete');
    Route::post('/ads/change-status', [AdvertisementController::class, 'changeStatus'])->name('ads.status');
    Route::post('/ads/uplaod-image', [AdvertisementController::class, 'uplaodImage'])->name('ads.uplaodImage');
    Route::post('/ads/delete-image', [AdvertisementController::class, 'deleteImage'])->name('ads.deleteImage');
    Route::post('/ads/addremove-wishlist', [AdvertisementController::class, 'addRemoveWish'])->name('ads.addremove.wishlist');
    Route::post('/ads/wishlist', [AdvertisementController::class, 'getWishlist'])->name('ads.getwishlist');
    Route::post('/users/search', [AdvertisementController::class, 'search'])->name('ads.search');
    Route::get('/ads/myads-status', [AdvertisementController::class, 'myAdsStatus'])->name('ads.myAdsStatus');
    Route::post('/ads/myads-listing', [AdvertisementController::class, 'myAdsListing'])->name('ads.myAdsListing');
    Route::post('/ads/applyfor-featured', [AdvertisementController::class, 'applyForFeatured'])->name('ads.applyForFeatured');
    Route::post('/ads/change-status-publish', [AdvertisementController::class, 'changeStatusPublish'])->name('ads.changeStatusPublish');

     //============================ HashTag ===================================================
     Route::get('/users/plan-list', [AdvertisementController::class, 'planList'])->name('user.planList');

     Route::post('/users/get-hashtags', [AdvertisementController::class, 'getHashtags'])->name('user.getHashtags');
     Route::post('/users/create-hashtags', [AdvertisementController::class, 'createHashtags'])->name('user.createHashtags');
     Route::post('/users/advertisement-edit-hashtags', [AdvertisementController::class, 'editHashtagsadvertisement'])->name('ads.editHashtagsadvertisement');
     Route::get('/users/check-store-description', [AdvertisementController::class, 'checkstoredescription'])->name('user.checkstoredescription');
  });
});
