<?php
Route::group(['prefix' => 'v1', 'namespace' => ROOT_NAMESPACE, "middleware" => ['localization']], function () {
    Route::group(['prefix' => 'guest', 'namespace' => 'Guest'], function () {
        Route::get('groups/{course_id?}', 'GroupController@groups');
        Route::get('group/{group_id}', 'GroupController@group');
    });

    Route::get('system_constants', 'SystemConstantsController@system_constants');
    Route::get('standards/{standard_type}', 'HomeController@standards');

        Route::post('contact_us', 'HomeController@contactUs');
    Route::get('ages', 'HomeController@ages');
    Route::get('levels', 'HomeController@levels');
    Route::get('activities', 'HomeController@activities');
    Route::group(["middleware" => ["auth:api", "CheckIsVerified", "CheckIsActive"]], function () {
        Route::get('settings', 'HomeController@settings');

        Route::get('group_students/{group_id}', 'HomeController@group_students');
        Route::get('group_student/{group_id}/{student_id}', 'HomeController@group_student');
        Route::post('rate/{user_id}', 'RateController@post_rate');
        Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {
            Route::get('notifications', 'NotificationController@notifications');
            Route::get('notification/{id}', 'NotificationController@notification');
            Route::post('sendNotificationForAllUsers', 'NotificationController@sendNotificationForAllUsers');
            Route::get('profile', 'UserController@profile');
            Route::post('update_profile', 'UserController@updateProfile');
            Route::get('profile', 'ProfileController@profile');
            Route::post('update_profile', 'ProfileController@updateProfile');
        });

//        Student
        Route::group(['prefix' => 'student', 'middleware' => ['auth_guard:' . \App\Models\User::user_type['STUDENT']]], function () {
            Route::post('complaint/{course_id}', 'ComplaintController@post_complaint');
            Route::group(['namespace' => 'Student'], function () {
                Route::get('courses', 'CourseController@courses');
                Route::get('questions/{course_id}', 'QuestionController@questions');
                Route::get('instructions/{course_id}', 'InstructionController@instructions');
                Route::post('check_level', 'QuestionController@check_level');
                Route::post('subscribe/{course_id}', 'GroupController@subscribe');
                Route::get('groups/{course_id}', 'GroupController@groupsByCourse');
                Route::get('groups/{course_id}/{level_id}', 'GroupController@groupsByLevel');
                Route::get('group/{group_id}', 'GroupController@group');
                Route::get('teacher_profile/{teacher_id}', 'GroupController@teacher_profile');
                Route::get('my_groups', 'GroupController@my_groups');
                Route::get('meetings', 'MeetingController@meetings');
            });
        });


//        Teacher
        Route::group(['prefix' => 'teacher', 'middleware' => ["auth_guard:" . \App\Models\User::user_type['TEACHER']]], function () {
            Route::post('complaint/{course_id}/{student_id}', 'ComplaintController@teacher_post_complaint');
            Route::group(['namespace' => 'Teacher'], function () {
                Route::apiResource('advantages', 'AdvantageController');
                Route::apiResource('lessons', 'LessonController');
                Route::apiResource('meetings', 'MeetingController');
                Route::apiResource('group.activates', 'ActivityController');
                Route::get('category_activity', 'ActivityController@category_activity');
                Route::get('my_groups', 'GroupController@my_groups');
                Route::get('profile', 'ProfileController@profile');
                Route::post('update_profile', 'ProfileController@updateProfile');
                Route::get('courses', 'CourseController@courses');
                Route::get('questions/{course_id}', 'CourseController@questions');
                Route::get('groups_course_id/{course_id}', 'GroupController@groups_course_id');
                Route::get('group/{group_id}', 'GroupController@group');
                Route::post('add_group', 'GroupController@add_group');
                Route::put('update_group/{group_id}', 'GroupController@update_group');
            });
        });


        Route::group(['namespace' => 'Chat'], function () {
            Route::post('createNewChat', 'ChatController@createNewChat');
            Route::post('sendMessage/{id}', 'ChatController@sendMessage');
            Route::get('getAllChats', 'ChatController@getAllChats');
            Route::get('chatMessages/{id}', 'ChatController@chatMessages');
            Route::get('group_media/{group_id}', 'ChatController@group_media');
            Route::delete('delete_media/{file_id}', 'ChatController@delete_media');
        });
    });
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('social_login', 'AuthController@socialLogin');

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


