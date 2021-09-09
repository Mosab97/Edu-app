<?php


Route::group(['middleware' => 'localWeb'], function () {

    Auth::routes();

    Route::get('/', 'Web\HomeController@welcome')->name('home');

    Route::get('lang/{local}', function ($local) {
        session(['lang' => $local]);
        if (Auth::check())
            $user = Auth::user()->update(['local' => $local,]);

        app()->setLocale($local);
        return back();
    })->name('switch-language');

    Route::group(['prefix' => 'manager'], function () {
        Route::get('/login', 'ManagerAuth\LoginController@showLoginForm')->name('manager.login');
        Route::post('/login', 'ManagerAuth\LoginController@login');
        Route::post('/logout', 'ManagerAuth\LoginController@logout')->name('logout');

        Route::get('/register', 'ManagerAuth\RegisterController@showRegistrationForm')->name('register');
        Route::post('/register', 'ManagerAuth\RegisterController@register');

        Route::post('/password/email', 'ManagerAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
        Route::post('/password/reset', 'ManagerAuth\ResetPasswordController@reset')->name('password.email');
        Route::get('/password/reset', 'ManagerAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::get('/password/reset/{token}', 'ManagerAuth\ResetPasswordController@showResetForm');
    });

});


Route::get('migrate', function () {
    Artisan::call('migrate');
});

Route::get('cache', function () {
    Artisan::call('config:cache');
});




