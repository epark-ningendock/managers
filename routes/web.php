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


Route::resource('facility-staff', 'FacilityStaffController')->except([
	'show'
]);

Route::get('/login', function () {
    return view('/vendor/adminlte/login');
});

Route::get('/register', function () {
    return view('/vendor/adminlte/register');
});


Route::prefix('examples')->group(function () {
	Route::get('listing', function () {
		return view('examples.listing');
	});
});

