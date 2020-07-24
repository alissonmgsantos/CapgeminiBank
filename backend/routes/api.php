<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group([
    'prefix' => 'session'
], function () {
    Route::post('signin', 'SessionController@signin');
    Route::post('signup', 'SessionController@signup');

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('signout', 'SessionController@signout');
        Route::get('profile', 'SessionController@profile');
    });
});


Route::group([
    'prefix' => 'account',
    'middleware' => 'auth:api'
  ], function() {
      Route::post('deposit', 'AccountController@deposit');
      Route::post('withDrawing', 'AccountController@withDrawing');
  });
