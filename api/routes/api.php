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

    Route::get('/',function(){
        $data=file_get_contents(asset('api.json'));
        return $data;
    });

    Route::group(['prefix'=>'test'],function () {

        Route::get('/','TestController@test');
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
        #add task
        Route::post('/','TaskController@addTask');
        #cancel task
        Route::get('/{id}','TaskController@cancelTask');
        #verify completed task.
        Route::post('/','TaskController@verifyTask');
        #task log.
        Route::get('/','TaskController@taskLog');
    });

    Route::group(['prefix'=>'card'],function () {

        #add Card
        Route::post('/', 'CardController@addCard');
        #delete Card
        Route::get('{id}/delete', 'CardController@deleteCard');

    });

    Route::group(['prefix'=>'question'],function () {
        #add Question
        Route::post('/','QuestionController@addQuestion');

        #get all question
        Route::get('/','QuestionController@getAllQuestion');

        #add Question options
        Route::post('/option','QuestionController@addQuestionOptions');

        #delete question option.
        Route::get('/option/{id}/delete','QuestionController@deleteQuestionOption');

        #get question
        Route::get('/{id}','QuestionController@getQuestion');

        #delete question
        Route::get('/{id}/delete','QuestionController@deleteQuestion');

        #get question responses
        Route::get('/{id}/response','QuestionController@questionResponse');
    });

});
