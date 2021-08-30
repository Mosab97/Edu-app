<?php


Route::group(['middleware' => 'localWeb'], function () {


//Route::get('login', 'LoginController@index');
    Auth::routes();
    Route::get('test_paypal', function () {
        return view('website.test_paypal');
    });
    Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
    Route::get('{provider}/callback', 'Auth\LoginController@handleProviderCallback');

    // Google login
    Route::get('login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('login/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);


    Route::get('/home', function () {
        return 'User is logged in';
    });


    Route::get('/', 'Web\HomeController@welcome')->name('home');
    Route::get('/profile', 'Web\HomeController@profile')->name('profile');
    Route::post('/post_profile', 'Web\HomeController@post_profile')->name('post_profile');
    Route::get('/all_faq', 'Web\HomeController@all_faq')->name('all_faq');
    Route::get('/home', 'Web\HomeController@welcome');
    Route::get('/about_us', 'Web\HomeController@about_us')->name('about_us');
    Route::get('/blogs', 'Web\HomeController@blogs')->name('blogs');
    Route::get('/blog/{id}', 'Web\HomeController@blog')->name('blog');

    Route::get('privacy_policy', 'Web\HomeController@privacy_policy')->name('privacy_policy');
    Route::get('faq', 'Web\HomeController@faq')->name('faq');
    Route::get('conditions', 'Web\HomeController@conditions')->name('conditions');

    Route::get('contact_us', 'Web\HomeController@view_contactUs')->name('view_contactUs');
    Route::post('contact_us', 'Web\HomeController@contactUs')->name('contact_us');


    Route::get('view_service_form/{id}', 'Web\ServiceController@view_service_form')->name('view_service_form');
    Route::get('view_service_details/{id}', 'Web\ServiceController@view_service_details')->name('view_service_details');
    Route::get('special_service_form', 'Web\ServiceController@view_special_service_form')->name('view_special_service_form');
    Route::group(['middleware' => 'CheckIsAuth'], function () {

        Route::group(['prefix' => 'payment', 'as' => 'payment.', 'namespace' => 'Web\paypal'], function () {
            Route::get('/', 'PayPalServicesController@index')->name('index');
            Route::get('payment/cancelBeforePayment/{order_id}', 'PayPalServicesController@cancelBeforePayment')->name('cancelBeforePayment');
            Route::post('payment/beforePayment', 'PayPalServicesController@beforePayment')->name('beforePayment');
            Route::post('payment/paypal-services/{order_id}', 'PayPalServicesController@payment')->name('paypal-services');
            Route::get('status', 'PayPalServicesController@status')->name('paypal.status');
            Route::get('cancel', 'PayPalServicesController@cancel')->name('paypal.cancel');


            /*****/
            Route::group(['prefix' => 'package', 'as' => 'package.'], function () {
                Route::get('/', 'PayPalPackagesController@index')->name('index');
                Route::post('payment/paypal-packages/{user_package}/{package}', 'PayPalPackagesController@payment')->name('paypal');
                Route::get('payment/cancelBeforePayment/{user_package}', 'PayPalPackagesController@cancelBeforePayment')->name('cancelBeforePayment');
                Route::post('payment/beforePayment/{package_id}', 'PayPalPackagesController@beforePayment')->name('beforePayment');
                Route::get('status', 'PayPalPackagesController@status')->name('paypal.status');
                Route::get('cancel', 'PayPalPackagesController@cancel')->name('paypal.cancel');
            });
        });


        Route::post('post_Help_in_choosing_the_accounting_program', 'Web\ServiceController@post_Help_in_choosing_the_accounting_program')->name('post_Help_in_choosing_the_accounting_program');
        Route::post('post_establishing_the_chart_of_accounts', 'Web\ServiceController@post_establishing_the_chart_of_accounts')->name('post_establishing_the_chart_of_accounts');
        Route::post('post_training_service', 'Web\ServiceController@post_training_service')->name('post_training_service');


        Route::post('post_special_service_form', 'Web\ServiceController@post_special_service_form')->name('post_special_service_form');

    });


    Route::post('post_training_service', 'Web\ServiceController@post_training_service')->name('post_training_service');


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




