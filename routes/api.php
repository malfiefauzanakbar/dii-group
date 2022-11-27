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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', array('middleware' => 'cors', 'uses' => 'AuthController@register'));
Route::post('/login', array('middleware' => 'cors', 'uses' => 'AuthController@login'));
Route::post('/logout', array('middleware' => 'cors', 'uses' => 'AuthController@logout'));

Route::get('/company', array('middleware' => 'cors', 'uses' => 'CompanyController@index'));
Route::post('/company', array('middleware' => 'cors', 'uses' => 'CompanyController@store'));
Route::get('/company/{id?}', array('middleware' => 'cors', 'uses' => 'CompanyController@show'));
Route::post('/company/update/{id?}', array('middleware' => 'cors', 'uses' => 'CompanyController@update'));
Route::delete('/company/{id?}', array('middleware' => 'cors', 'uses' => 'CompanyController@destroy'));
