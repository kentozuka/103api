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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    Route::post('signin', 'SignInController');
    Route::post('signout', 'SignOutController');
    Route::post('register', 'RegisterController');
    Route::get('me', 'MeController');
    Route::get('refresh', 'RefreshController');
});

Route::middleware('auth:api', 'throttle:80,1')->group(function () {
    Route::get('/class/{url}', 'ClassController@index');
    Route::get('/saved', 'ClassController@saved');
    Route::post('/search', 'ClassController@search');
});