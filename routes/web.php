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

Route::get('/', function () {
    return view('index');
});

//Route::get('/staff', 'StaffController@index')->middleware('can:view-staff');
//
//Route::resource('/staff', 'StaffController')->except(['show', 'index'])->middleware('can:edit-staff');

Route::resource('/hospital', 'HospitalController')->except(['show']);
Route::get('/hospital/search', 'HospitalController@index')->name('hospital.search');
Route::get('/hospital/search/text', 'HospitalController@searchText')->name('hospital.search.text');

Route::resource('/staff', 'StaffController')->except(['show']);

Route::resource('hospital-staff', 'HospitalStaffController')->except([
	'show'
]);

Route::post('/classification/{id}/restore', 'ClassificationController@restore')->name('classification.restore');
Route::resource('/classification', 'ClassificationController')->except(['show']);


Route::get('/login', function () {
    return view('/vendor/adminlte/login');
});

Route::get('/register', function () {
    return view('/vendor/adminlte/register');
});

