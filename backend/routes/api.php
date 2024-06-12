<?php

use App\Http\Controllers\Api\AddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\LiveSearchController;
use App\Http\Controllers\Api\OfficialFeeController;
use App\Http\Controllers\Api\PatentController;
use App\Http\Controllers\Api\ServiceFeeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\SendMailController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\FailedRequestController;
use App\Http\Controllers\Api\Renewal\CustomReminderController;
use App\Http\Controllers\Api\Renewal\KnowledgeBaseController;
use App\Http\Controllers\Api\Renewal\ReminderController as RenewalReminderController;
use App\Http\Controllers\Api\Renewal\UserController as RenewalUserController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\dashboard\RenewalController;
use App\Http\Controllers\dashboard\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;

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

// Auth Routes
Route::post('login', [AuthController::class, 'signin']);
Route::post('loginAfterEmailValidation', [AuthController::class, 'loginAfterEmailValidation']);
Route::post('register', [AuthController::class, 'signup']);
Route::post('first-register', [AuthController::class, 'firstRegister']);
Route::post('verify-first-register', [AuthController::class, 'verifyFirstRegister']);

// Email Verification
Route::get('email/verify/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::group(['prefix'=>'v1/renewals'], function(){
    Route::any('/calendar', [RenewalController::class, 'calendar']);
    Route::post('/users/{user_id}/set-role', [RenewalUserController::class, 'setRole']);
    Route::apiResource('/users', RenewalUserController::class);
    Route::post('/reminders/{reminder_id}/fragment/add', [RenewalReminderController::class, 'addFragment']);
    Route::post('/reminders/{reminder_id}/fragment/{fragment_id}/delete', [RenewalReminderController::class, 'deleteFragment']);
    Route::apiResource('/reminders', RenewalReminderController::class);

    Route::post('/custom-reminders/get-reminder-dates', [CustomReminderController::class, 'getReminderDates']);
    Route::post('/custom-reminders/get-fields', [CustomReminderController::class, 'getFields']);
    Route::apiResource('/custom-reminders', CustomReminderController::class);

    Route::get('/knowledge-base', [KnowledgeBaseController::class, 'index']);
    Route::get('/knowledge-base/{slug}', [KnowledgeBaseController::class, 'show']);
});

Route::group(['prefix' => 'v1'], function () {
    Route::any('/calendar/{secret_id}/{password?}', [CalendarController::class, 'getCalendar']);
    Route::post('teams/{team}/users/{user_id}/delete', [TeamController::class, 'deleteTeamMember']);
    Route::apiResource('/teams', TeamController::class);
    Route::apiResource('/companies', CompanyController::class);
    // Profile
    Route::get('/profile/timezones', [ProfileController::class, 'timezones']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::get('/countries', [AddressController::class, 'country']);
    Route::get('/states/{state}', [AddressController::class, 'state']);
    Route::get('/cities/{city}', [AddressController::class, 'city']);
    Route::apiResource('/faqs', FaqController::class);
    Route::get('/live-search/{lang}/{word}', [LiveSearchController::class, 'search']);

    Route::get('/knowledge-base/{category}', [BlogController::class, 'category']);
    Route::get('/knowledge-base/{category}/{slug}', [BlogController::class, 'blog']);

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/order', [OrderController::class, 'store']);
    Route::post('/order/pctentry', [OrderController::class, 'pctstore']);
    Route::post('/order/patentannuity', [OrderController::class, 'annuitystore']);
    Route::post('/order-details',[OrderController::class,'show']);
    Route::post('/accept-quote',[OrderController::class,'acceptQuote']);
    Route::post('/additional-info',[OrderController::class,'additionalInfo']);
    Route::post('/store/ep-validation', [PatentController::class, 'storeEpValidation']);
    // EP Validation
    Route::any('/query/{query}', [PatentController::class, 'query']);
    // Patent Annuity
    Route::any('/query/{query}/tr', [PatentController::class, 'queryTR']);
    // PCT Entry
    Route::any('/query/{query}/pct', [PatentController::class, 'queryPCT']);
    Route::any('/query/{query}/pct/fees', [PatentController::class, 'queryPCTFees']);

    Route::post('/query/{query}/data-list', [PatentController::class, 'dataList']);
    Route::apiResource('/official-fee', OfficialFeeController::class);
    Route::apiResource('/service-fee', ServiceFeeController::class, ['except' => ['show']]);
    Route::apiResource('/contact', ContactController::class);
    Route::apiResource('/feedback', FeedbackController::class);
    Route::any('/send-mail', [SendMailController::class, 'sendMail']);
    Route::any('/send-instruction', [SendMailController::class, 'sendInstruction']);

    Route::get('/dollar-exchange-rate',[PatentController::class,'UsdTry']);
    Route::get('/euro-exchange-rate',[PatentController::class,'EurTry']);

    Route::get('/generate-pdf/{order}',[OrderController::class,'generatepdf']);

    Route::resource('renewals', RenewalController::class);
    Route::post('/search', [SearchController::class, 'search']);
    Route::post('/search-application-no', [SearchController::class, 'searchApplicationNo']);
    Route::post('/save-renewal-request', [SearchController::class, 'saveRenewalRequest']);
    Route::post('/save-wrong-response', [SearchController::class, 'saveWrongResponse']);
    Route::apiResource('/failed-requests', FailedRequestController::class);
    Route::post('/paymentOne', [\App\Http\Controllers\Api\Renewal\PaymentController::class, 'paymentOne']);
    Route::post('/paymentAll', [\App\Http\Controllers\Api\Renewal\PaymentController::class, 'paymentAll']);
});

Route::get('test', [TestController::class, 'test']);
Route::fallback(function () {
    return response()->json([
        'code' => 404,
        'message' => 'Page Not Found'
    ], 404);
});
