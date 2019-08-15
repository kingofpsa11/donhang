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

    //Customer
    Route::get('customer/listCustomer', 'CustomerController@listCustomer')->name('customer.listCustomer');

    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('existTax', 'CustomerController@existTax')->name('exist_tax');
    });
    Route::resource('customers','CustomerController');

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

    Route::get('suppliers/listSupplier', 'SupplierController@listSupplier')->name('suppliers.listSupplier');
    Route::resource('suppliers', 'SupplierController');

    Route::get('price/search', 'PriceController@shows')->name('prices.shows');
    Route::get('prices/create/{product?}', 'PriceController@create')->name('prices.create');
    Route::resource('prices', 'PriceController')->except('create');

    Route::prefix('output-orders')->name('output-orders.')->group(function () {
        Route::get('getUndoneOutputOrder', 'OutputOrderController@getUndoneOutputOrders')->name('getUndoneOutputOrder');
        Route::get('existNumber', 'OutputOrderController@existNumber')->name('exist_number');
    });
    Route::resource('output-orders', 'OutputOrderController');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('allProducts', 'ProductController@allProducts')->name('all_products');
        Route::get('getProduct', 'ProductController@getProduct')->name('get_product');
        Route::get('existCode', 'ProductController@existCode')->name('exist_code');
        Route::delete('deleteImage', 'ProductController@deleteImage')->name('delete_image');
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

    Route::prefix('manufacturer-orders')->name('manufacturer-orders.')->group(function() {
        Route::get('get-all-manufacturers', 'ManufacturerOrderController@getAllManufacturers')->name('get_all_manufacturers');
        Route::get('/get-manufacturers-by-status', 'ManufacturerOrderController@getManufacturerByStatus')->name('get_manufacturers_by_status');
    });

    Route::resource('manufacturer-orders', 'ManufacturerOrderController');

    Route::prefix('manufacturer-notes')->name('manufacturer-notes.')->group(function () {
        Route::get('get-by-step', 'ManufacturerNoteController@getByStep')->name('get_by_step');
        Route::get('get-manufacturer-note', 'ManufacturerNoteController@getManufacturerNote')->name('get-manufacturer-note');
    });
    Route::resource('manufacturer-notes', 'ManufacturerNoteController');

    Route::resource('step-notes', 'StepNoteController');

    Route::resource('shape-notes', 'ShapeNoteController');

    Route::get('store/listStore', 'StoreController@listStore')->name('store.listStore');
    Route::resource('store', 'StoreController');

    Route::resource('good-receive', 'GoodReceiveController');

    Route::resource('good-deliveries', 'GoodDeliveryController');

    Route::prefix('inventories')->name('inventories.')->group(function () {
        Route::get('all', 'InventoryController@all')->name('all');
    });
    Route::resource('inventories', 'InventoryController');

    Route::get('notifications/all', 'NotificationController@index')->name('notifications.index');
});

Auth::routes();