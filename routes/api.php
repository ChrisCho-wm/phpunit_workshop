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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Demo
Route::group(['prefix' => 'dogs', 'middleware' => 'auth:api'], function () {
    Route::post('uploadImage', 'DogController@uploadDogImage');
    Route::post('getMyFavoriteDog', 'DogController@getMyFavoriteDog');
    Route::post('getDogDescription', 'DogController@getDogDescription');
});

// Session2
Route::group(['prefix' => 'cats', 'middleware' => 'auth:api'], function () {
    Route::post('uploadImage', 'CatController@uploadCatImage');
    Route::post('getMyFavoriteCat', 'CatController@getMyFavoriteCat');
    Route::post('getCatDescription', 'CatController@getCatDescription');
});


// Session2
// 1
// 2
// 3
