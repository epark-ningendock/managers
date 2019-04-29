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

Route::group(['prefix' => 'staff', 'middleware' => ['authority.level.three']], function(){
	Route::get('edit-password/{staff_id}', 'StaffController@editPassword')->name('staff.edit.password');
	Route::put('update-password/{staff_id}', 'StaffController@updatePassword')->name('staff.update.password');
});
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

