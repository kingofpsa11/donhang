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

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('customer/listCustomer', 'CustomerController@listCustomer')->name('customer.listCustomer');
    Route::resource('customer','CustomerController');

    Route::get('/getlast/{customer_id}', 'ContractController@getLastContract');
    Route::get('contract/search', 'ContractController@shows')->name('contract.shows');
    Route::get('contract/getManufacturerOrder', 'ContractController@getManufacturerOrder')->name('contract.getManufacturerOrder');
    Route::get('contract/checkNumber', 'ContractController@checkNumber')->name('contract.checkNumber');
    Route::resource('contract','ContractController');

    Route::resource('manufacturer-order', 'ManufacturerOrderController');

    Route::get('newNumber/{supplier_id}', 'ManufacturerOrderController@getNewNumber')->name('manufacturer-order.as');

    Route::get('supplier/listSupplier', 'SupplierController@listSupplier')->name('supplier.listSupplier');
    Route::resource('supplier', 'SupplierController');

    Route::get('price/search', 'PriceController@shows')->name('prices.shows');
    Route::resource('price', 'PriceController');

    Route::get('output-order/getUndoneOutputOrder', 'OutputOrderController@getUndoneOutputOrders')->name('output-order.getUndoneOutputOrder');
    Route::get('output-order/checkNumber', 'OutputOrderController@checkNumber')->name('output-order.checkNumber');
    Route::resource('output-order', 'OutputOrderController');

    Route::get('product/all', 'ProductController@shows')->name('product.shows');
    Route::get('product/getProduct', 'ProductController@getProduct')->name('product.getProduct');
    Route::resource('product', 'ProductController');

    Route::resource('roles', 'RoleController');

    Route::get('/markAsReadNotifications', 'UserController@markAsReadNotification')->name('users.markAsReadNotifications');
    Route::get('/notifications', 'UserController@notifications');
    Route::get('export', 'UserController@export');
    Route::resource('users', 'UserController');


    Route::get('change-password', 'Auth\ChangePasswordController@showChangePasswordForm');
    Route::put('change-password', 'Auth\ChangePasswordController@changePassword')->name('auth.change-password');



    Route::resource('profit-rate', 'ProfitRateController');

    Route::get('bom/getBom', 'BomController@getBom')->name('bom.getBom');
    Route::resource('bom', 'BomController');

    Route::resource('manufacturer-note', 'ManufacturerNoteController');

    Route::get('store/listStore', 'StoreController@listStore')->name('store.listStore');
    Route::resource('store', 'StoreController');

    Route::resource('good-receive', 'GoodReceiveController');

    Route::resource('good-delivery', 'GoodDeliveryController');

    Route::get('good-transfer/showInventory', 'GoodTransferController@showInventory')->name('good-transfer.showInventory');
    Route::resource('good-transfer', 'GoodTransferController');
});

Auth::routes();

Route::get('/send', 'NotificationController@index')->name('send');
Route::post('/postMessage', 'NotificationController@sendNotification')->name('postMessage');