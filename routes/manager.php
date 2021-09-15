<?php

use Illuminate\Support\Facades\Route;


Route::group(['namespace' => 'Manager'], function () {
    Route::get('/home', 'SettingController@home')->name('home');
    Route::get('/generate_admin_app_api_new_token', 'SettingController@generate_admin_app_api_new_token')->name('generate_admin_app_api_new_token');
    //Set Local
    Route::get('lang/{local}', 'SettingController@lang')->name('switch-language');

    //Settings Management
    Route::get('bills/{distributor_id}', 'BillController@bills')->name('bills');
    Route::get('mark_as_paid/{distributor_id}/{date?}', 'BillController@mark_as_paid')->name('mark_as_paid');
    Route::get('mark_all_as_paid/{distributor_id}', 'BillController@mark_all_as_paid')->name('mark_all_as_paid');
    Route::get('settings', 'SettingController@settings')->name('settings.general');
    Route::post('settings', 'SettingController@updateSettings')->name('settings.updateSettings');
    Route::get('profile', 'SettingController@view_profile')->name('profile.show');
    Route::post('profile', 'SettingController@profile')->name('profile.update');
    Route::get('changeCountry/{id}/{segments}', 'SettingController@changeCountry')->name('settings.changeCountry');

    //map
    Route::resource('map', 'MapController');

    //Merchant
    Route::resource('merchant', 'MerchantController');
    //User
    Route::resource('user', 'UserController');
    //distributor
    Route::resource('distributor', 'DistributorController');
    Route::post('assign_to_distributor/{product_id}', 'DistributorController@assign_to_distributor')->name('assign_to_distributor');
    Route::get('distributor/products/{distributor_id}', 'DistributorController@distributor_products')->name('distributor.products');
    Route::post('distributor/products/{distributor_id}', 'DistributorController@add_products')->name('distributor.add_products');
    Route::delete('distributor/products/product/{product_id}', 'DistributorController@destroy_distributor_products')->name('distributor.destroy_distributor_products');

    //Category
    Route::resource('category', 'CategoryController');
    //financial_system
    Route::resource('financial_system', 'FinancialSystemController');
    //Reports
    Route::resource('report', 'ReportsController');
    //products
    Route::resource('product', 'ProductController');
    Route::get('product_distributors/{price_id}', 'ProductController@product_distributors')->name('product.product_distributors');
    Route::post('product_distributors/{product_distributor_id}', 'ProductController@update_amount')->name('product.update_amount');
    Route::delete('deleteDistributorProduct/{id}', 'ProductController@deleteDistributorProduct')->name('product.deleteDistributorProduct');
    //employee
    Route::resource('employee', 'EmployeeController');
    //mecca_pay
    Route::resource('gift', 'GiftController');
    //offer
    Route::resource('offer', 'OfferController');
    Route::get('product_images/{id}', 'ProductController@product_images')->name('product_images.index');
    Route::post('product_images', 'ProductImagesController@store')->name('product_images.store');
    Route::post('product_images/{id}', 'ProductImagesController@update')->name('product_images.update');
    Route::delete('product_images/{id}', 'ProductImagesController@destroy')->name('product_images.destroy');


    Route::get('excel', 'ExcelController@create')->name('excel');
    Route::post('excel-create', 'ExcelController@excel')->name('excel-create');


    //Orders
    Route::resource('order', 'OrderController');

    //City Routes
    Route::resource('city', 'CityController');
    //Country Routes
    Route::resource('country', 'CountryController');
    //currency Routes
    Route::resource('currency', 'CurrencyController');

    //Slider Routes
    Route::resource('slider', 'SliderController');

    //Posts Routes
    Route::resource('post', 'PostController');

    //ad_images Routes
    Route::resource('ad_images', 'AdImagesController');

    //Bank Routes
    Route::resource('bank', 'BankController');
    //User Routes
    Route::resource('user', 'UserController');
    Route::get('user_notifications', 'UserController@notifications')->name('user.notifications');

    //Notifications Routes
    Route::post('send_user_notification', 'UserController@sendNotification')->name('user_notification');


    //Address Routes
    Route::resource('address', 'AddressController');
    //Contact us Management
    Route::get('contact_us', 'ContactUsController@index')->name('contact_us.index');
    Route::get('contact_us/{id}', 'ContactUsController@show')->name('contact_us.show');
    Route::delete('contact_us/{id}', 'ContactUsController@destroy')->name('contact_us.destroy');
    //Join us Management
    Route::get('join_us', 'JoinUsController@index')->name('join_us.index');
    Route::get('join_us/{id}/edit', 'JoinUsController@show')->name('join_us.edit');
    Route::post('join_us', 'JoinUsController@store')->name('join_us.store');
    Route::delete('join_us/{id}', 'JoinUsController@destroy')->name('join_us.destroy');


    Route::resource('manager', 'ManagerController');
    //Roles
    Route::resource('manager_roles', 'RoleController');


    //Notification Route
    Route::get('notification', 'NotificationController@index')->name('notification.index');
    Route::get('notification/{id}', 'NotificationController@show')->name('notification.show');
    Route::delete('notification/{id}', 'NotificationController@destroy')->name('notification.destroy');
    Route::post('notification', 'NotificationController@store')->name('notification.store');


});
