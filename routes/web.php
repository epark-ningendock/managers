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
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| Login Route
|--------------------------------------------------------------------------
*/
Auth::routes();
Route::get('/login', 'Auth\LoginController@getLogin')->name('login');
Route::post('/login', 'Auth\LoginController@postLogin');


/*
|--------------------------------------------------------------------------
| Staff Route
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'staff', 'middleware' => ['authority.level.three']], function () {
    Route::get('edit-password/{staff_id}', 'StaffController@editPassword')->name('staff.edit.password')->middleware('auth:staffs');
    Route::put('update-password/{staff_id}', 'StaffController@updatePassword')->name('staff.update.password')->middleware('auth:staffs');
});
Route::resource('/staff', 'StaffController')->except(['show'])->middleware('auth:staffs');

/*
|--------------------------------------------------------------------------
| Hospital Route
|--------------------------------------------------------------------------
*/
Route::resource('/hospital', 'HospitalController')->except(['show'])->middleware('auth:staffs');
Route::get('/hospital/search', 'HospitalController@index')->name('hospital.search')->middleware('auth:staffs');
Route::get('/hospital/search/text', 'HospitalController@searchText')->name('hospital.search.text')->middleware('auth:staffs');

/*
|--------------------------------------------------------------------------
| Hospital Staff Route
|--------------------------------------------------------------------------
*/
Route::get('/hospital-staff/edit-password', 'HospitalStaffController@editPassword')->middleware('auth'); // ログインユーザーのパスワード編集画面に遷移する
Route::put('/hospital-staff/update-password/{hospital_staff_id}', 'HospitalStaffController@updatePassword')->name('hospital-staff.update.password')->middleware('auth'); // ログインユーザーのパスワードを更新する
Route::get('/hospital-staff/show-password-resets-mail', 'HospitalStaffController@showPasswordResetsMail')->middleware('auth'); // パスワードのリセットメール送信画面に遷移する
Route::get('/hospital-staff/send-password-resets-mail', 'HospitalStaffController@sendPasswordResetsMail')->name('hospital-staff.send.password-reset')->middleware('auth'); // パスワードのリセットメール送信を送信する
Route::get('/hospital-staff/show-reset-password/{reset_token}/{email}', 'HospitalStaffController@showResetPassword')->middleware('auth'); // パスワードのリセット画面に遷移する
Route::put('/hospital-staff/reset-password/{hospital_staff_id}', 'HospitalStaffController@resetPassword')->name('hospital-staff.reset.password')->middleware('auth'); // パスワードをリセットする
Route::resource('hospital-staff', 'HospitalStaffController')->except([
    'show',])->middleware('auth:hospital_staffs');;

/*
|--------------------------------------------------------------------------
| Course Classification Route
|--------------------------------------------------------------------------
*/
Route::post('/classification/{id}/restore', 'ClassificationController@restore')->name('classification.restore')->middleware('auth:staffs');
Route::get('/classification/sort', 'ClassificationController@sort')->name('classification.sort')->middleware('auth:staffs');
Route::patch('/classification/sort/update', 'ClassificationController@updateSort')->name('classification.updateSort')->middleware('auth:staffs');
Route::resource('/classification', 'ClassificationController')->except(['show'])->middleware('auth:staffs');

/*¥
|--------------------------------------------------------------------------
| Course Route
|--------------------------------------------------------------------------
*/
Route::resource('/course', 'CourseController')->except(['show'])->middleware('auth');
Route::get('/course/sort', 'CourseController@sort')->name('course.sort')->middleware('auth');
Route::get('/course/{id}/copy', 'CourseController@copy')->name('course.copy')->middleware('auth');
Route::patch('/course/sort/update', 'CourseController@updateSort')->name('course.updateSort')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Course Option Route
|--------------------------------------------------------------------------
*/
Route::get('option/sort', 'OptionController@sort')->name('option.sort')->middleware('auth');
Route::resource('option', 'OptionController', ['excerpt' => 'show'])->middleware('auth');
Route::patch('option/sort/update', 'OptionController@updateSort')->name('option.updateSort')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Email Template Route
|--------------------------------------------------------------------------
*/
Route::resource('/email-template', 'EmailTemplateController')->except(['show'])->middleware('auth');

/*
|--------------------------------------------------------------------------
| Reception Email Setting Route
|--------------------------------------------------------------------------
*/
Route::resource('/reception-email-setting', 'ReceptionEmailSettingController')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Calendar Route
|--------------------------------------------------------------------------
*/
Route::get('/calendar/holiday', 'CalendarController@holiday_setting')->name('calendar.holiday')->middleware('auth');
Route::patch('/calendar/holiday', 'CalendarController@update_holiday')->name('calendar.updateHoliday')->middleware('auth');
Route::get('/calendar/{id}/setting', 'CalendarController@setting')->name('calendar.setting')->middleware('auth');
Route::patch('/calendar/{id}/setting', 'CalendarController@updateSetting')->name('calendar.updateSetting')->middleware('auth');
Route::resource('/calendar', 'CalendarController')->except(['show'])->middleware('auth');

/*
|--------------------------------------------------------------------------
| Reservation Route
|--------------------------------------------------------------------------
*/
Route::resource('/reservation', 'ReservationController', ['only' => ['index']])->middleware('auth');
Route::get('reservation/operation', 'ReservationController@operation')->name('reservation.operation')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Customer Route
|--------------------------------------------------------------------------
*/

Route::resource('customer', 'CustomerController')->middleware('auth');
Route::post('customer/detail', 'CustomerController@detail')->name('customer.detail')->middleware('auth');
//Route::get('customer/basic-information', 'CustomerController@basicInformationCreate');
Route::post('customer/import', 'CustomerController@importData')->name('customer.import.data')->middleware('auth');
Route::post('customer/email/{customer_id}', 'CustomerController@showEmailForm')->name('customer.show.email.form')->middleware('auth');
Route::post('customer/email-send/{customer_id}', 'CustomerController@emailSend')->name('customer.email.send')->middleware('auth');
