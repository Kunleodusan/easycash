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

Route::group(['prefix'=>'v1'],function (){


    Route::group(['prefix'=>'test'],function () {

        Route::get('success','TestController@success');
        Route::get('error','TestController@error');

    });

    Route::group(['prefix'=>'admin'],function () {
        Route::post('register','AdminController@create');
        Route::post('/login','AdminController@auth');
        Route::get('/','AdminController@getAll');
        Route::get('{id}','AdminController@get');
        Route::post('{id}','AdminController@update');
    });

    Route::group(['prefix'=>'bank'],function () {
        Route::post('register','BankController@create');
        Route::post('/login','BankController@auth');
        Route::get('/','BankController@getAll');
        Route::get('{id}','BankController@get');
        Route::post('{id}','BankController@update');

    });
    Route::group(['prefix'=>'customer'],function () {
        Route::post('register','CustomerController@create');
        Route::post('/login','CustomerController@auth');
        Route::get('/','CustomerController@getAll');
        Route::get('{id}','CustomerController@get');
        Route::post('{id}','CustomerController@update');
    });

    Route::group(['prefix'=>'task'],function () {

    });
    Route::group(['prefix'=>'question'],function () {

    });
});
