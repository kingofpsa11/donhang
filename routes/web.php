<?php

use App\Customer;
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


Route::get('/home', function () {
    return view('home');
});

Route::resource('customer','CustomerController');

Route::get('/getlast/{customer_id}', 'ContractController@getLastContract');
Route::get('contract/search', 'ContractController@shows')->name('contract.shows');
Route::resource('contract','ContractController');

Route::get('manufacturer-order/', 'ManufacturerOrderController@index')->name('manufacturer-order.index');
Route::get('manufacturer-order/create/{contract}', 'ManufacturerOrderController@create')->name('manufacturer-order.create');
Route::post('manufacturer-order/{contract}', 'ManufacturerOrderController@store')->name('manufacturer-order.store');
Route::get('manufacturer-order/{contract}', 'ManufacturerOrderController@show')->name('manufacturer-order.show');
Route::get('manufacturer-order/{contract}/edit', 'ManufacturerOrderController@edit')->name('manufacturer-order.edit');
Route::post('manufacturer-order/{contract}/update', 'ManufacturerOrderController@update')->name('manufacturer-order.update');
Route::get('newNumber/{supplier_id}', 'ManufacturerOrderController@getNewNumber')->name('manufacturer-order.as');

Route::get('price/search', 'PriceController@shows')->name('prices.shows');
Route::resource('price', 'PriceController');

Route::resource('output-order', 'OutputOrderController');

Route::get('product/all', 'ProductController@shows')->name('product.shows');
Route::resource('product', 'ProductController');

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
