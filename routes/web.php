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

    //Contract
    Route::prefix('contracts')->name('contracts.')->group(function() {
        Route::get('checkNumber', 'ContractController@checkNumber')->name('checkNumber');
        Route::post('allContracts', 'ContractController@allContracts')->name('all_contracts');
        Route::get('existNumber', 'ContractController@existNumber')->name('exist_number');
    });
    Route::get('/getlast/{customer_id}', 'ContractController@getLastContract');
    Route::get('contracts/search', 'ContractController@shows')->name('contract.shows');
    Route::get('contracts/getManufacturerOrder', 'ContractController@getManufacturerOrder')->name('contract.getManufacturerOrder');

    Route::resource('contracts','ContractController');

    Route::get('newNumber/{supplier_id}', 'ManufacturerOrderController@getNewNumber')->name('manufacturer-order.as');

    Route::get('supplier/listSupplier', 'SupplierController@listSupplier')->name('supplier.listSupplier');
    Route::resource('supplier', 'SupplierController');

    Route::get('price/search', 'PriceController@shows')->name('prices.shows');
    Route::get('prices/create/{product?}', 'PriceController@create')->name('prices.create');
    Route::resource('prices', 'PriceController')->except('create');

    Route::prefix('output-orders')->name('output-orders.')->group(function () {
        Route::get('getUndoneOutputOrder', 'OutputOrderController@getUndoneOutputOrders')->name('getUndoneOutputOrder');
        Route::get('existNumber', 'OutputOrderController@existNumber')->name('exist_number');
    });
    Route::resource('output-orders', 'OutputOrderController');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('all', 'ProductController@shows')->name('shows');
        Route::get('getProduct', 'ProductController@getProduct')->name('get_product');
        Route::get('existCode', 'ProductController@existCode')->name('exist_code');
    });
    Route::resource('products', 'ProductController');

    Route::resource('roles', 'RoleController');

    Route::get('/markAsReadNotifications', 'UserController@markAsReadNotification')->name('users.markAsReadNotifications');
    Route::get('/notifications', 'UserController@notifications');
    Route::get('export', 'UserController@export');
    Route::resource('users', 'UserController');

    Route::get('change-password', 'Auth\ChangePasswordController@showChangePasswordForm');
    Route::put('change-password', 'Auth\ChangePasswordController@changePassword')->name('auth.change-password');

    Route::resource('profit-rate', 'ProfitRateController');

    Route::get('boms/getBom', 'BomController@getBom')->name('boms.get_bom');
    Route::resource('boms', 'BomController');

    Route::resource('manufacturer-order', 'ManufacturerOrderController');

    Route::get('manufacturer-notes/{manufacturerOrder}/create', 'ManufacturerNoteController@create')->name('manufacturer-notes.create');
    Route::resource('manufacturer-notes', 'ManufacturerNoteController')->except(['create']);

    Route::get('store/listStore', 'StoreController@listStore')->name('store.listStore');
    Route::resource('store', 'StoreController');

    Route::resource('good-receive', 'GoodReceiveController');

    Route::resource('good-deliveries', 'GoodDeliveryController');

    Route::get('good-transfer/showInventory', 'GoodTransferController@showInventory')->name('good-transfer.showInventory');
    Route::resource('good-transfer', 'GoodTransferController');
});

Auth::routes();

Route::get('/send', 'NotificationController@index')->name('send');
Route::post('/postMessage', 'NotificationController@sendNotification')->name('postMessage');