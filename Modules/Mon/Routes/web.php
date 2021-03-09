<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MonController@index')->name('home');
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('login/admin', 'Auth\LoginController@showAdminLoginForm')->name('login.admin');
Route::post('login', 'Auth\LoginController@login')->name('login.submit');
Route::any('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
if (config('user.account.register')) {
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');
}

// Password Reset Routes...
if (config('user.account.reset')) {
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
}

// Email Verification Routes...
if (config('user.account.verify')) {
    Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
    Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
}

Route::get('change/lang/{locale}', function ($locale) {
    $referer = Request::server('HTTP_REFERER');
    if ($referer && preg_match("#^" . config('app.url') . "#", $referer)) {
        $path = parse_url($referer)['path'];
        $currentLocale = app()->getLocale();
        if ($locale === config('app.default_locale')) {
            $path = str_replace("/" . $currentLocale, '', $path);
        } else {
            if ($currentLocale === config('app.default_locale')) {
                $path = '/'.$locale.$path;
            } else {
                $path = str_replace("/" . $currentLocale, $locale ? '/' . $locale : '', $path);
            }
        }

    } else {
        $path = config('app.url') . '/' . $locale;
    }

    return redirect()->to($path);
})->name('change.lang');
