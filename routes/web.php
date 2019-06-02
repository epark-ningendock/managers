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
Route::resource('/hospital', 'HospitalController')->except(['show']);
Route::get('/hospital/search', 'HospitalController@index')->name('hospital.search');
Route::get('/hospital/search/text', 'HospitalController@searchText')->name('hospital.search.text');

Route::resource('/staff', 'StaffController')->except(['show']);

Route::resource('hospital-staff', 'HospitalStaffController')->except([
	'show'
]);

Route::post('/classification/{id}/restore', 'ClassificationController@restore')->name('classification.restore');
Route::get('/classification/sort', 'ClassificationController@sort')->name('classification.sort');
Route::patch('/classification/sort/update', 'ClassificationController@updateSort')->name('classification.updateSort');
Route::resource('/classification', 'ClassificationController')->except(['show']);

Route::resource('/course', 'CourseController')->except(['show']);
Route::get('/course/sort', 'CourseController@sort')->name('course.sort');
Route::get('/course/{id}/copy', 'CourseController@copy')->name('course.copy');
Route::patch('/course/sort/update', 'CourseController@updateSort')->name('course.updateSort');

Route::get('/login', function () {
    return view('/vendor/adminlte/login');
});

Route::get('/register', function () {
    return view('/vendor/adminlte/register');
});


Auth::routes();
Route::get('/login', 'Auth\LoginController@getLogin')->name('login');
Route::post('/login', 'Auth\LoginController@postLogin');

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/calendar', 'CalendarController')->except(['show']);
