<?php

Route::middleware(['authenticate', 'authorize'])->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
});

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// Route only for testing purposes
Route::get("/A1IboUB4N6w27hLNMeKnecsl7obntg", function () {
    return "gtnbo7lscenKeMNLh72w6N4BUobI1A";
})->middleware(['authenticate', 'authorize'])
    ->name("A1IboUB4N6w27hLNMeKnecsl7obntg");


