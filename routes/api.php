<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Users Routes
Route::group(['prefix' => 'users'], function () {
    Route::post('/register', 'AuthController@signup');
    Route::post('/login', 'AuthController@login');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/logout', 'AuthController@logout');
        Route::get('/show-user', 'AuthController@user');

        //codes urls
        Route::post('create', 'ProposalCodeController@create');
        Route::get('show/{id}', 'ProposalCodeController@show');
        Route::get('codes', 'ProposalCodeController@listCodes');
    });
});