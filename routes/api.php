<?php
Route::group(['prefix' => 'v1', 'namespace' => ROOT_NAMESPACE, "middleware" => ['localization']], function () {
    Route::group(["middleware" => ["auth:api", "CheckIsVerified", "CheckIsActive"]], function () {
    Route::get('settings', 'HomeController@settings');
        Route::group(['prefix' => 'teacher', 'namespace' => 'Teacher', 'middleware' => ['auth_guard:teacher']], function () {
            Route::get('profile', 'ProfileController@profile');
            Route::post('update_profile', 'ProfileController@updateProfile');

            Route::get('courses', 'CourseController@courses');
            Route::get('questions/{course_id}', 'CourseController@questions');
            Route::get('groups_course_id/{course_id}', 'GroupController@groups_course_id');
            Route::get('group/{group_id}', 'GroupController@group');
            Route::get('my_groups', 'GroupController@my_groups');
            Route::post('add_group', 'GroupController@add_group');
            Route::put('update_group/{group_id}', 'GroupController@update_group');
            Route::get('notifications', 'NotificationController@notifications');
            Route::get('notification/{id}', 'NotificationController@notification');
            Route::post('sendNotificationForAllStudents', 'NotificationController@sendNotificationForAllStudents');


        });
        Route::group(['prefix' => 'student', 'namespace' => 'Student', 'middleware' => ['auth_guard:student']], function () {
            Route::get('courses', 'CourseController@courses');
            Route::get('questions/{course_id}', 'CourseController@questions');
            Route::get('groups/{course_id}', 'CourseController@groups');
            Route::get('group/{group_id}', 'CourseController@group');
            Route::get('my_groups', 'CourseController@my_groups');
            Route::get('notifications', 'NotificationController@notifications');
            Route::get('notification/{id}', 'NotificationController@notification');
            Route::post('sendNotificationForAllStudents', 'NotificationController@sendNotificationForAllStudents');
            Route::get('profile', 'ProfileController@profile');
            Route::post('update_profile', 'ProfileController@updateProfile');
        });
    });
    Route::get('system_constants', 'SystemConstantsController@system_constants');


    Route::group(["middleware" => ['CheckHasCountry']], function () {
        Route::get('home', 'HomeController@home');
        Route::get('get_products', 'HomeController@get_products');
        Route::post('contact_us', 'HomeController@contactUs');
        Route::group(["middleware" => ["auth:api", "CheckIsVerified", "CheckIsNotBlocked", "CheckIsActive"]], function () {
            Route::post('favorite/{id}', 'HomeController@favorite');
            Route::get('favorites', 'HomeController@favorites');
            Route::get('ads_images', 'HomeController@ads_images');
            Route::post('createNewChat', 'ChatController@createNewChat');
            Route::post('sendMessage/{id}', 'ChatController@sendMessage');
            Route::get('getAllChats', 'ChatController@getAllChats');
            Route::get('chatMessages/{id}', 'ChatController@chatMessages');
            Route::post('share_app', 'HomeController@share_app');
            Route::group(['prefix' => 'user'], function () {
                Route::get('notifications', 'User\UserController@notifications');
                Route::get('notification/{id}', 'User\UserController@notification');
                Route::get('profile', 'User\UserController@profile');
                Route::post('update_profile', 'User\UserController@updateProfile');
                Route::post('update_language', 'User\UserController@updateLanguage');
                Route::post('update_location', 'User\UserController@update_location');
                Route::group(["middleware" => ['auth:api']], function () {
                    Route::post('search_another_distributor/{id}', 'OrderController@search_another_distributor');;
                    Route::group(["middleware" => ["CheckIsClient"]], function () {
                        Route::post('check_order', 'OrderController@checkOrder');
                        Route::post('checkout', 'OrderController@checkout');
                        Route::get('orders', 'OrderController@orders');
                        Route::get('order/{id}', 'OrderController@order');
                        Route::post('rate_order/{id}', 'OrderController@rateOrder');
                        Route::post('cancel_order/{id}', 'OrderController@cancelOrder');
                        Route::post('confirm_order/{id}', 'OrderController@confirmOrder');
                        Route::get('re_order/{id}', 'OrderController@reOrder');

                    });
                });
            });
            Route::group(['prefix' => 'distributor', "middleware" => ["CheckIsDistributor"]], function () {
                Route::get('get_products', 'DistributorController@get_products');
                Route::post('receive_orders', 'DistributorController@receive_orders');
                Route::get('home', 'DistributorController@home');
                Route::get('financial', 'DistributorController@financial');
                Route::get('orders', 'DistributorController@orders');
                Route::post('change_order_status/{id}', 'DistributorController@change_order_status');
            });
        });
    });
    Route::group(['namespace' => 'notifications', 'prefix' => 'notifications'], function () {
        Route::post('sendNotificationForAllUsers', 'NotificationsController@sendNotificationForAllUsers');
    });
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('resend_code', 'AuthController@resend_code');
        Route::post('register', 'AuthController@register');
        Route::post('forget_password', 'AuthController@forget_password');
        Route::group(['middleware' => 'auth:api'], function () {
            Route::post('logout', 'AuthController@logout');
            Route::post('verify_account', 'AuthController@verifiy_account')->middleware(["auth:api"]);
            Route::post('change_password', 'AuthController@change_password');
        });
        Route::post('verified_code', 'AuthController@verified_code');
        Route::post('logoutAllAuthUsers', 'AuthController@logoutAllAuthUsers');
    });
});


