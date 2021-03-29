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
Route::get('/', function(){
    return redirect('/home/page/1');
});
Route::get('/home', function(){
    return redirect('/home/page/1');
});
Route::get('/home/page/{page}', 'clonegame@Home');
Route::get('/home/game/{id}', 'clonegame@GameID');
Route::get('/admin/getfullpagedata', 'clonegame@getFullPageData');
Route::post('/home/view', 'clonegame@selectGame');
Route::post('/wait_download', 'clonegame@waitdownload');
Route::post('/countdownloaded', 'clonegame@countDownloaded');
Route::post('/home/search/{page}', 'clonegame@search');

