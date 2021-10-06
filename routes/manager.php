<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Manager'], function () {
    Route::get('/home', 'SettingController@home')->name('home');
    //Set Local
    Route::get('lang/{local}', 'SettingController@lang')->name('switch-language');

    //Settings Management
    Route::get('settings', 'SettingController@settings')->name('settings.general');
    Route::post('settings', 'SettingController@updateSettings')->name('settings.updateSettings');
    Route::get('profile', 'SettingController@view_profile')->name('profile.show');
    Route::post('profile', 'SettingController@profile')->name('profile.update');

    //Level Routes
    Route::resource(\App\Models\Level::manager_route, 'LevelController');

    //Age Routes
    Route::resource(\App\Models\Age::manager_route, 'AgeController');

    //Course Routes
    Route::resource(\App\Models\Course::manager_route, 'CourseController');


    //Teacher Routes
    Route::resource(\App\Models\User::manager_route_user_type['teacher'], 'TeacherController');

    //Student Routes
    Route::resource(\App\Models\User::manager_route_user_type['student'], 'StudentController');


    //Group Routes
    Route::resource(\App\Models\Group::manager_route, 'GroupController');

    //payments Routes
    Route::resource('payment', 'PaymentController');
    //User Routes
    Route::resource('user', 'UserController');

    Route::get('user_notifications', 'UserController@notifications')->name('user.notifications');


    //Contact us Management
    Route::get('contact_us', 'ContactUsController@index')->name('contact_us.index');
    Route::get('contact_us/{id}', 'ContactUsController@show')->name('contact_us.show');
    Route::delete('contact_us/{id}', 'ContactUsController@destroy')->name('contact_us.destroy');








    Route::resource('manager', 'ManagerController');
    //Roles
    Route::resource('manager_roles', 'RoleController');
});
