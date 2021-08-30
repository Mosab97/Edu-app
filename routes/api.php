<?php


Route::group(['prefix' => 'v1', 'namespace' => ROOT_NAMESPACE, "middleware" => ['localization']], function () {

    Route::post('test_fire_base', 'HomeController@test_fire_base');
    Route::get('terms&conditions', 'HomeController@termsConditions');
    Route::get('cities', 'HomeController@cities');
    Route::get('countries', 'HomeController@countries');
    Route::get('car_models', 'HomeController@car_models');
    Route::get('fuel_type', 'HomeController@fuel_type');
    Route::get('packages', 'HomeController@packages');
    Route::get('banks', 'HomeController@banks');

    Route::get('home', 'HomeController@home');
    Route::get('ads', 'AdController@ads');
    Route::get('ad/{id}', 'AdController@ad');
    Route::get('get_user_ratings/{id}', 'RateController@get_user_ratings');

    Route::get('settings', 'HomeController@settings');
    Route::get('filter_data', 'HomeController@filter_data');
    Route::post('contact_us', 'HomeController@contactUs');


    Route::group(["middleware" => ["auth:api", "CheckIsVerified", "CheckIsRegistered"]], function () {
        Route::post('chat/create/{id}', 'ChatController@create');
        Route::post('chat_contact_us/send_message', 'ChatContactUsController@send_message');
        Route::get('chat_contact_us/contact_messages', 'ChatContactUsController@contact_messages');
        Route::post('chat/send_message/{id}', 'ChatController@send_message');
        Route::get('chat/get_chats', 'ChatController@get_chats');
        Route::get('chat/get_chat_messages/{id}', 'ChatController@get_chat_messages');
        Route::post('make_ad_featured/{id}', 'AdController@make_ad_featured');
        Route::post('ad_notices/{id}', 'AdController@ad_notices');
        Route::post('ad', 'AdController@store');
        Route::post('client_rate_ad/{id}', 'RateController@client_rate_ad');
        Route::post('ad_owner_rate_order/{id}', 'RateController@ad_owner_rate_order');
        Route::delete('ad/{id}', 'AdController@destroy');
        Route::post('sold/{id}', 'AdController@sold');
        Route::get('order/{id}', 'AdController@order');
        Route::post('end_sale/{id}', 'AdController@end_sale');
        Route::post('sold_checkout/{id}', 'AdController@sold_checkout');
        Route::get('transfer/{id}', 'AdController@transfer');

        Route::group(['prefix' => 'user',], function () {
            Route::post('favorite/{id}', 'HomeController@favorite');
            Route::get('favorites', 'HomeController@favorites');
            Route::apiResource('address', 'User\AddressController');

            Route::get('notifications', 'User\UserController@notifications');
            Route::get('notification/{id}', 'User\UserController@notification');
            Route::get('profile', 'User\UserController@profile');
            Route::post('update_profile', 'User\UserController@updateProfile');
            Route::post('update_image', 'User\UserController@update_image');
            Route::post('update_mobile', 'User\UserController@updateMobile');
            Route::post('update_language', 'User\UserController@updateLanguage');
            Route::post('update_notification', 'User\UserController@updateNotification');
            Route::post('update_show_phone_number', 'User\UserController@updateShowPhoneNumber');
        });
    });

//     All API Auth Routes here
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register')->middleware('auth:api');
        Route::post('signup_delivery_build_account', 'AuthController@signup_delivery_build_account')->middleware(['api', 'CheckIsDriver']);
        Route::post('logoutAllAuthUsers', 'AuthController@logoutAllAuthUsers');
        Route::post('resendCode', 'AuthController@resendCode');
        Route::post('verified_code', 'AuthController@verified_code');
        Route::group(['middleware' => 'auth:api'], function () {
            Route::post('logout', 'AuthController@logout');
        });
    });
    Route::group(['namespace' => 'notifications', 'prefix' => 'notifications'], function () {
        Route::post('saveFcmToken', 'NotificationsController@SaveFCMToken');
        Route::post('sendNotificationForAllUsers', 'NotificationsController@sendNotificationForAllUsers');
    });
});


