<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TrackingController;

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
Route::any('admin-tracking', [TrackingController::class, 'index']);
Route::get('test', [TestController::class, 'index']);
Route::view('/', 'index')->name('index');
Route::get('/payment/{slug}',[PaymentController::class,'index']);
Route::any('/iyzico-callback/{query}',[PaymentController::class,'callback'])->name('iyzico.callback');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/orders', [App\Http\Controllers\HomeController::class, 'orders'])->name('orders');
Route::get('/edit/{id}', [App\Http\Controllers\HomeController::class, 'edit'])->name('edit');
Route::get('/payment-status/{id}', [App\Http\Controllers\HomeController::class, 'paymentStatus'])->name('payment-status');

Route::get('/feedback', [App\Http\Controllers\HomeController::class, 'feedback'])->name('feedback');
Route::get('/faqs', [App\Http\Controllers\HomeController::class, 'faqs'])->name('faqs');
Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');

Route::group(['middleware' => 'auth'], function () {

    Route::resource('faqs', 'App\Http\Controllers\dashboard\FAQsController', ['names' => [
        'index' => 'faq.index',
        'create' => 'faq.create',
        'store' => 'faq.store',
        'edit' => 'faq.edit',
        'update' => 'faq.update',
        'destroy' => 'faq.destroy'
    ]]);
    Route::resource('price', 'App\Http\Controllers\dashboard\Price', ['names' => [
        'index' => 'price.index',
        'create' => 'price.create',
        'store' => 'price.store',
        'edit' => 'price.edit',
        'update' => 'price.update',
        'destroy' => 'price.destroy'
    ]]);
    Route::resource('knowledgebase', 'App\Http\Controllers\dashboard\KnowledgeBase', ['names' => [
        'index' => 'knowledgebase.index',
    ]]);
});
