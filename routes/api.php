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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    Route::resource('/meeting', 'MeetingController')
        ->except(['create', 'edit']);
    Route::resource('/meeting/registration', 'RegisterController')
        ->only(['store', 'destroy']);;
    Route::post('/user/register', 'AuthController@store');
    Route::post('/user/signin', 'AuthController@signin');
});
