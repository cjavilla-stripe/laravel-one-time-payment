<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/jobs', 'JobController@index');
Route::post('/jobs', 'JobController@store');
Route::get('/jobs/new', 'JobController@create');
Route::post('/jobs/create_payment_intent', 'JobController@create_payment_intent');
