<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'API',
], function () {

    Route::apiResource('cars', 'CarAPIController');
    Route::apiResource('employees', 'EmployeeAPIController');
    Route::apiResource('brands', 'BrandAPIController');
    Route::apiResource('customers', 'CustomerAPIController');
    Route::apiResource('sales', 'SaleAPIController');

});

Route::group([
    'prefix' => 'auth',
    'namespace' => 'API\Auth',
    'middleware' => 'api'
], function () {

    Route::post('login', 'AuthAPIController@login');
    Route::post('register', 'RegisterController@register');
    Route::post('logout', 'AuthAPIController@logout');
    Route::post('refresh', 'AuthAPIController@refresh');
    Route::post('me', 'AuthAPIController@me');

    Route::group(['prefix' => 'social', 'middleware' => 'web'], function () {
        Route::get('/{provider}', 'AuthAPIController@redirectToProvider');
        Route::get('/{provider}/callback', 'AuthAPIController@handleProviderCallback');
    });
    Route::post('social/{provider}', 'AuthAPIController@loginWithSocial');

    Route::post('reset_password', 'ResetPasswordController@sendEmailWithUri');
    Route::put('reset_password', 'ResetPasswordController@resetPassword');
});
