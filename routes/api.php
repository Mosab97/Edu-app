<?php
Route::group(['prefix' => 'v1', 'namespace' => ROOT_NAMESPACE, "middleware" => ['localization'/*, 'AppVersion'*/]], function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('login', 'AuthController@login');
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


        Route::get('system_constants','SystemConstantsController@system_constants');

});


