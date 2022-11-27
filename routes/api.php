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

Route::get('/aboutus', array('middleware' => 'cors', 'uses' => 'AboutUsController@index'));
Route::post('/aboutus', array('middleware' => 'cors', 'uses' => 'AboutUsController@store'));
Route::get('/aboutus/{id?}', array('middleware' => 'cors', 'uses' => 'AboutUsController@show'));
Route::post('/aboutus/update/{id?}', array('middleware' => 'cors', 'uses' => 'AboutUsController@update'));
Route::delete('/aboutus/{id?}', array('middleware' => 'cors', 'uses' => 'AboutUsController@destroy'));

Route::get('/news', array('middleware' => 'cors', 'uses' => 'NewsController@index'));
Route::post('/news', array('middleware' => 'cors', 'uses' => 'NewsController@store'));
Route::get('/news/{id?}', array('middleware' => 'cors', 'uses' => 'NewsController@show'));
Route::post('/news/update/{id?}', array('middleware' => 'cors', 'uses' => 'NewsController@update'));
Route::delete('/news/{id?}', array('middleware' => 'cors', 'uses' => 'NewsController@destroy'));

Route::get('/article', array('middleware' => 'cors', 'uses' => 'ArticleController@index'));
Route::post('/article', array('middleware' => 'cors', 'uses' => 'ArticleController@store'));
Route::get('/article/{id?}', array('middleware' => 'cors', 'uses' => 'ArticleController@show'));
Route::post('/article/update/{id?}', array('middleware' => 'cors', 'uses' => 'ArticleController@update'));
Route::delete('/article/{id?}', array('middleware' => 'cors', 'uses' => 'ArticleController@destroy'));

Route::get('/blog', array('middleware' => 'cors', 'uses' => 'BlogController@index'));
Route::post('/blog', array('middleware' => 'cors', 'uses' => 'BlogController@store'));
Route::get('/blog/{id?}', array('middleware' => 'cors', 'uses' => 'BlogController@show'));
Route::post('/blog/update/{id?}', array('middleware' => 'cors', 'uses' => 'BlogController@update'));
Route::delete('/blog/{id?}', array('middleware' => 'cors', 'uses' => 'BlogController@destroy'));
