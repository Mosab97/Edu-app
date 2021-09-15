<?php
Route::group(['namespace' => 'Api\v1\Adminapiapp', "middleware" => ['localization', 'CheckTokenFromDashboard']], function () {
    Route::post('login', 'LoginController@login');
    Route::group(["middleware" => ["auth:adminapiapp"]], function () {
        Route::get('orders', 'OrderController@index');
    });
});


