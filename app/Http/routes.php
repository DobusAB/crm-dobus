<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api'], function() {
    Route::get('companies', 'CompanyController@index');
    Route::get('company/{id}/email', 'CompanyController@show');
    // localhost:8000/api/company/hemsida24/email?city=Halmstad&homepage=http://api.eniro.com/proxy/homepage/hiOpQG8hntxHmgUm49bLx8XEOckxV8R0MegAVCHlQPI6xAtHDB_CbC2ekhFkpDvzNNwtn2cEe2nNhQMv7ItJNAUeZXN0vf71

    Route::get('leads', 'LeadController@index');
    Route::post('lead', 'LeadController@store');
});
