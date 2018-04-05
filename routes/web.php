<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/payment', 'PaymentController@index')->name('payment');
// Route::get('/make-payment', 'PaymentController@makePayment');
Route::get('/payment-history', 'PaymentController@paymentHistory');
Route::get('/payment-setting', 'PaymentController@paymentSetting');

Route::post('/checkout', 'PaymentController@checkout')->name('checkout');
Route::get('/thank-you', 'PaymentController@paymentSuccess')->name('thank-you');
Route::get('/cards-and-accounts', 'PaymentController@getCardsandAccounts')->name('card-account.list');
// -- stripe customer --
Route::post('/add-customer', 'PaymentController@addCustomer')->name('customer.add');
Route::post('/update-customer', 'PaymentController@updateCustomer')->name('customer.update');
Route::post('/delete-customer', 'PaymentController@deleteCustomer')->name('customer.delete');

// -- stripe card --
Route::post('/add-card', 'PaymentController@addCard')->name('card.add');
Route::post('/update-card', 'PaymentController@updateCard')->name('card.update');
Route::post('/delete-card', 'PaymentController@deleteCard')->name('card.delete');

// -- stripe bank account --
Route::post('/add-account', 'PaymentController@addAccount')->name('account.add');
Route::post('/update-account', 'PaymentController@updateAccount')->name('account.update');
Route::post('/delete-account', 'PaymentController@deleteAccount')->name('account.delete');
