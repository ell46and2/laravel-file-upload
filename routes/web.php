<?php

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

// Route::get('/', function () {
//     return view('home');
// });

Route::get('/', 'HomeController@index');

// Route::apiResource('attachments', 'AttachmentController');

Route::post('/attachments', 'AttachmentController@store')->name('attachments.store');

Route::delete('/attachments/{attachment}', 'AttachmentController@destroy');

Route::post('avatars', function() {
	request()->file('avatar')->store('avatars', 's3');

	return back();
});


Route::get('upload', 'uploadController@index');
Route::post('upload', 'uploadController@storeImage')->name('dropzone.store');
