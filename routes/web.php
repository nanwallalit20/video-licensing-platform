<?php

use App\Http\Controllers\BuyerContentController;
use App\Enums\Roles;
use App\Http\Controllers\DocusignController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SubcribtionController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\TitleRequestPayment;
use App\Http\Controllers\uploadVideoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapBuyerTitleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WebhookController;

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


Auth::routes();

Route::middleware(['auth','checkRole:' . Roles::Superadmin->value])->group(function () {
    Route::get('/su/buyer', [AdminController::class, 'buyerlist'])->name('superadmin.buyerlist');
    Route::get('/su/seller', [AdminController::class, 'sellerlist'])->name('superadmin.sellerlist');
    Route::get('/su/seller/{id}/remove', [SellerController::class, 'destroy'])->name('superadmin.seller-remove');
    Route::get('/su/seller/{id}/view', [SellerController::class, 'view'])->name('superadmin.seller-view');
    Route::get('/su/buyer/requests', [AdminController::class, 'buyerRequests'])->name('superadmin.buyerRequests');
    Route::get('/su/buyer/requests/{id}', [AdminController::class, 'viewBuyerRequests'])->name('superadmin.buyerRequests.view');
    Route::get('/su/title/requests', [AdminController::class, 'titleRequests'])->name('superadmin.titleRequests');
    Route::get('/su/title/requests/{id}', [AdminController::class, 'viewTitleRequests'])->name('superadmin.titleRequests.view');
    Route::post('/su/seller/title/statusUpdate', [TitleController::class, 'updateStatus'])->name('superadmin.sellers.title.updateStatus');
    Route::post('/su/seller/title/{slug}/addMessage', [TitleController::class, 'addMessage'])->name('superadmin.sellers.title.addMessage');
    Route::get('/su/seller/title/{slug}/viewUrls',[SellerController::class,'viewMediaUrl'])->name('superadmin.seller.getMediaUrl');
    Route::get('/su/seller/title/{slug}/viewDocument',[SellerController::class,'viewDocument'])->name('superadmin.seller.viewDocument');
    Route::post('/su/title/{orderId}/requests/{status}/update', [AdminController::class, 'updateTitleRequests'])->name('superadmin.titleRequests.update');
    Route::get('/su/title/requests/create-payment-modal/{orderId}', [TitleRequestPayment::class, 'createPaymentModal'])->name('superadmin.titleRequests.getPaymentModal');
    Route::post('/su/title/requests/create-payment-link', [TitleRequestPayment::class, 'createPaymentLink'])->name('superadmin.titleRequests.createPaymentLink');
    Route::post('/su/agreement-status/{slug}/{status}', [TitleController::class, 'agreementStatus'])->name('title.agreementStatus');
});

Route::middleware(['auth','checkRole:' . Roles::Seller->value.','.Roles::Superadmin->value])->group(function () {
    Route::get('/switch-account/{id?}', [DashboardController::class, 'switchAccount'])->name('switchAccount');
    Route::get('/titles', [TitleController::class, 'index'])->name('titles');
    Route::get('/title-create/{type}', [TitleController::class, 'create'])->name('titleCreate');
    Route::get('/title/{uuid}/edit', [TitleController::class, 'edit'])->name('titleEdit');
    Route::post('/upload-file/{mediaType?}', [FileUploadController::class, 'upload'])->name('uploadFile');
    Route::post('/title/{uuid}/edit-profile', [TitleController::class, 'titleEditProfile'])->name('titleEditProfile');
    Route::post('/title/{uuid}/edit-contact', [TitleController::class, 'titleEditContact'])->name('titleEditContact');
    Route::post('/title/{uuid}/edit-document', [TitleController::class, 'titleEditDocument'])->name('titleEditDocument');
    Route::post('/title/{uuid}/edit-videos', [TitleController::class, 'titleEditVideo'])->name('titleEditVideo');
    Route::post('/title/{uuid}/edit-season', [TitleController::class, 'titleEditSeason'])->name('titleEditSeason');
    Route::post('/title/{uuid}/submit-review', [TitleController::class, 'submitForReview'])->name('titleSubmitForReview');
    Route::get('/title-revenue-plan/{slug}', [TitleController::class, 'revenuePlan'])->name('title.revenuePlan');
    Route::post('/title-revenue-plan-submit/{slug}', [TitleController::class, 'revenuePlanSubmit'])->name('title.revenuePlanSubmit');
    Route::post('/title-revenue-plan-sign/{slug}', [TitleController::class, 'revenuePlanSign'])->name('title.revenuePlanSign');
    Route::get('/view-agreement/{slug}', [TitleController::class, 'viewAgreement'])->name('title.viewAgreement');
    Route::get('/se/analytics', [TitleController::class, 'sellerAnalytics'])->name('seller.analytics');

    Route::post('/se/remove-title-media/{id?}', [SellerController::class, 'removeMedia'])->name('seller.remove-media');
    Route::post('/se/remove-episode/{id}', [SellerController::class, 'removeEpisode'])->name('seller.remove-episode');
    Route::post('/se/remove-season/{id}', [SellerController::class, 'removeSeason'])->name('seller.remove-season');
    Route::post('/se/remove-movie-media/{id}', [SellerController::class, 'removeMovieMedia'])->name('seller.remove-movie-media');
});

Route::middleware(['auth','checkRole:' . Roles::Buyer->value])->group(function () {
     // Buyer Request Routes
     Route::post('/buyer/{buyer_id}/request/{status}', [AdminController::class, 'buyerRequestsStatus'])->name('buyer.request.status');
     Route::post('/buyer/subscriptions/link/{userId}', [SubcribtionController::class, 'buyerSubscriptionLink'])->name('buyer.subscription.link');
     Route::get('/fetch-titles-by-ids', [AjaxController::class, 'fetchTitlesByIds'])->name('fetchTitlesByIds');
     Route::post('/update-titles', [MapBuyerTitleController::class, 'updateSelectedTitles'])->name('updateSelectedTitles');
     Route::post('/bu/toggle-cart-item/{titleId}/{seasonId?}', [CartController::class, 'toggleCartItem'])->name('buyer.toggle-cart-item');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/title/{slug}/profile/{seasonSlug?}', [TitleController::class, 'titleProfile'])->name('title.profile');
    Route::get('/genres', [AjaxController::class, 'getGenres'])->name('genres.get');
    Route::get('/keywords', [AjaxController::class, 'getKeywords'])->name('keywords.get');
    Route::get('/countries', [AjaxController::class, 'getCountries'])->name('countries.get');
    Route::get('/rating', [AjaxController::class, 'getRating'])->name('ratings.get');
    Route::get('/directors', [AjaxController::class, 'getDirectors'])->name('directors.get');
    Route::get('/producers', [AjaxController::class, 'getProducers'])->name('producers.get');
    Route::get('/writers', [AjaxController::class, 'getWriters'])->name('writers.get');
    Route::get('/composers', [AjaxController::class, 'getComposers'])->name('composers.get');
    Route::get('/tags', [AjaxController::class, 'getTags'])->name('tags.get');
    Route::get('/festivals', [AjaxController::class, 'getFestivals'])->name('festival.get');
    Route::get('/advisories', [AjaxController::class, 'getAdvisories'])->name('advisories.get');

    // Docusign only access
    Route::get('/docusign-auth', [DocusignController::class, 'authenticateDocusign']);
    Route::get('/docusign-authorized-user', [DocusignController::class, 'authorizedUser'])->name('docusign-authorized-user');

    Route::get('/bu/my-content', [BuyerContentController::class, 'myContent'])->name('buyer.my-content');
    Route::get('/bu/download-content/{id}', [BuyerContentController::class, 'downloadContent'])->name('buyer.download-content');
});
// title request payment
Route::get('/payment/success/{order_id}', [TitleRequestPayment::class, 'handlePaymentSuccess'])
    ->name('payment.success');
Route::get('/payment/failed/{order_id}', [TitleRequestPayment::class, 'handlePaymentFailed'])
    ->name('payment.failed');


Route::get('buyer/subscription/pricing/{token}', [SubcribtionController::class, 'buyerPricing'])->middleware(['signed'])->name('buyer.subscription.pricing');
Route::get('buyer/subscription/{userId}/checkout/{priceId}', [SubcribtionController::class, 'buyerCheckout'])->name('buyer.subscription.checkout');
Route::get('stripe/success/{userId}', [SubcribtionController::class, 'stripeSuccess'])->name('stripe.success');
Route::get('stripe/cancel', [SubcribtionController::class, 'stripeCancel'])->name('stripe.cancel');

// Make sure this route exists and is not blocked by CSRF
Route::post(
    'stripe/webhook',
    [WebhookController::class, 'handleWebhook']
)->name('cashier.webhook')->middleware('stripe.webhook');

