<?php
Route::group(['prefix' => 'v1', 'namespace' => ROOT_NAMESPACE, "middleware" => ['localization']], function () {
    Route::group(["middleware" => ["auth:api", "CheckIsVerified", "CheckIsActive"]], function () {
        Route::post('contact_us', 'HomeController@contactUs');
        Route::get('system_constants', 'SystemConstantsController@system_constants');
        Route::get('settings', 'HomeController@settings');
        Route::group(['prefix' => 'user','namespace' => 'User'], function () {
            Route::get('notifications', 'NotificationController@notifications');
            Route::get('notification/{id}', 'NotificationController@notification');
            Route::post('sendNotificationForAllUsers', 'NotificationController@sendNotificationForAllUsers');
            Route::get('profile', 'UserController@profile');
            Route::post('update_profile', 'UserController@updateProfile');
            Route::get('profile', 'ProfileController@profile');
            Route::post('update_profile', 'ProfileController@updateProfile');
        });

        Route::group(['prefix' => 'teacher', 'namespace' => 'Teacher', 'middleware' => ["auth_guard:" . \App\Models\User::user_type['TEACHER']]], function () {
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
        Route::group(['prefix' => 'student', 'namespace' => 'Student', 'middleware' => ['auth_guard:' . \App\Models\User::user_type['STUDENT']]], function () {
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


