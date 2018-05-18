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

    Route::any('/fetch/', 'BlogController@fetch_blog')->middleware('verifyToken');
    Route::post('/read/', 'BlogController@read_blog')->middleware('verifyToken');
    Route::post('/like/', 'BlogController@like_blog')->middleware('verifyToken');

?>
