<?php
Route::group(['prefix' => 'v1', 'namespace' => ROOT_NAMESPACE], function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('student/login', 'AuthController@student_login');
        Route::post('teacher/login', 'AuthController@teacher_login');
        Route::post('register', 'AuthController@register');
        Route::post('forget_password', 'AuthController@forget_password');
            Route::post('logout', 'AuthController@logout');
        Route::post('change_password', 'AuthController@change_password')->middleware(['auth_guard:student']);
        Route::post('verified_code', 'AuthController@verified_code');
        Route::post('logoutAllAuthUsers', 'AuthController@logoutAllAuthUsers');
    });


    Route::group(['prefix' => 'student', 'namespace' => 'Student'], function () {

        Route::post('sign-up', 'StaffController@signUp');
        //unauthenticated routes for customers here

        Route::group(['middleware' => ['auth_guard:student']], function () {
            Route::get('courses', 'CourseController@courses');
            Route::get('questions/{course_id}', 'CourseController@questions');
            Route::get('groups/{course_id}', 'CourseController@groups');
            Route::get('group/{group_id}', 'CourseController@group');
            Route::get('my_groups', 'CourseController@my_groups');
            Route::post('test', 'StudentController@index');
            Route::get('notifications', 'NotificationController@notifications');
            Route::get('notification/{id}', 'NotificationController@notification');
            Route::post('sendNotificationForAllStudents', 'NotificationController@sendNotificationForAllStudents');


            Route::get('profile', 'ProfileController@profile');
            Route::post('update_profile', 'ProfileController@updateProfile');
            Route::post('update_language', 'ProfileController@updateLanguage');
            Route::post('update_location', 'ProfileController@update_location');
        });
    });


    Route::get('system_constants', 'SystemConstantsController@system_constants');

});


