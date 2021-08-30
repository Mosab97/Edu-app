<?php

use Illuminate\Support\Facades\Route;
use Spatie\Analytics\Period;



Route::group(['namespace' => 'Manager'], function () {

    Route::get('/google-anaytics',function(){
        //fetch the most visited pages for today and the past week
        $d = Analytics::fetchVisitorsAndPageViews(Period::days(7));
        dd($d);
    });
    Route::get('/home', 'SettingController@home')->name('home');
    //Set Local
    Route::get('lang/{local}', 'SettingController@lang')->name('switch-language');

    //Settings Management
    Route::get('settings', 'SettingController@settings')->name('settings.general');
    Route::post('settings', 'SettingController@updateSettings')->name('settings.updateSettings');
    Route::get('profile', 'SettingController@view_profile')->name('profile.show');
    Route::post('profile', 'SettingController@profile')->name('profile.update');

    //City Routes
    Route::resource('city', 'CityController');

    //advantage Routes
    Route::resource('advantage', 'AdvantageController');

    //customer_review Routes
    Route::resource('customer_review', 'CustomerReviewController');

    //blog Routes
    Route::resource('blog', 'BlogController');

    //payments Routes
    Route::resource('payment', 'PaymentController');
    //special_services Routes
    Route::resource('special_services', 'SpecialServicesController');
    //orders Routes
    Route::resource('order', 'OrderController');

    //Statistics Routes
    Route::resource('statistic', 'StatisticController');

    //Faq Routes
    Route::resource('faq', 'FaqController');
    //Bank Routes
    Route::resource('bank', 'BankController');
    //User Routes
    Route::resource('user', 'UserController');


    Route::get('user_notifications', 'UserController@notifications')->name('user.notifications');

    //Category Routes
    Route::resource('category', 'CategoryController');


    //Package Routes
    Route::resource('package', 'PackageController');
    //package_value Routes
    Route::resource('package_value', 'PackageValuesController');

    //type_of_services Routes
    Route::resource('orders', 'OrderController');
    //services Routes
    Route::resource('service', 'ServiceController');


    Route::post('sendNotification/{id}', 'OrderController@sendNotification')->name('order.sendNotification');

    //Contact us Management
    Route::get('contact_us', 'ContactUsController@index')->name('contact_us.index');
    Route::get('contact_us/{id}', 'ContactUsController@show')->name('contact_us.show');
    Route::delete('contact_us/{id}', 'ContactUsController@destroy')->name('contact_us.destroy');



    //Join us Management
    Route::get('join_us', 'JoinUsController@index')->name('join_us.index');
    Route::get('join_us/{id}/edit', 'JoinUsController@show')->name('join_us.edit');
    Route::post('join_us', 'JoinUsController@store')->name('join_us.store');
    Route::delete('join_us/{id}', 'JoinUsController@destroy')->name('join_us.destroy');

    //Pages Management
    Route::get('page', 'PageController@index')->name('page.index');
    Route::get('page/{id}/edit', 'PageController@edit')->name('page.edit');
    Route::patch('page/{id}/update', 'PageController@update')->name('page.update');

    //Notifications Routes
    Route::post('send_user_notification', 'UserController@sendNotification')->name('user_notification');
    Route::post('send_restaurant_notification', 'RestaurantController@sendNotification')->name('restaurant_notification');
    Route::post('send_branch_notification', 'BranchController@sendNotification')->name('branch_notification');

    //Notification Route
    Route::get('notification', 'NotificationController@index')->name('notification.index');
    Route::get('notification/{id}', 'NotificationController@show')->name('notification.show');
    Route::delete('notification/{id}', 'NotificationController@destroy')->name('notification.destroy');
    Route::post('notification', 'NotificationController@store')->name('notification.store');


    Route::resource('manager', 'ManagerController');
    //Roles
    Route::resource('manager_roles', 'RoleController');
});
