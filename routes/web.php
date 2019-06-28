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
})->middleware('auth:staffs,hospital_staffs');

/*
|--------------------------------------------------------------------------
| Login Routes
|--------------------------------------------------------------------------
*/
Auth::routes();
Route::get('/login', 'Auth\LoginController@getLogin')->name('login');
Route::post('/login', 'Auth\LoginController@postLogin');

/*
|--------------------------------------------------------------------------
| Hospital staff authentication required
|--------------------------------------------------------------------------
*/
Route::middleware('auth:hospital_staffs')->group(function () {
    Route::get('/hospital-staff/edit-password', 'HospitalStaffController@editPassword')->name('hospital-staff.edit.password');
    Route::put('/hospital-staff/update-password/{hospital_staff_id}', 'HospitalStaffController@updatePassword')->name('hospital-staff.update.password');
});

/*
|--------------------------------------------------------------------------
| Hospital staff Routes
|--------------------------------------------------------------------------
*/
Route::get('/hospital-staff/show-password-resets-mail', 'HospitalStaffController@showPasswordResetsMail')->name('hospital-staff.show.password-reset');
Route::get('/hospital-staff/send-password-resets-mail', 'HospitalStaffController@sendPasswordResetsMail')->name('hospital-staff.send.password-reset');
Route::get('/hospital-staff/show-reset-password/{reset_token}/{email}', 'HospitalStaffController@showResetPassword');
Route::put('/hospital-staff/reset-password/{hospital_staff_id}', 'HospitalStaffController@resetPassword')->name('hospital-staff.reset.password');

/*
|--------------------------------------------------------------------------
| Staff authentication required
|--------------------------------------------------------------------------
*/
Route::middleware('auth:staffs')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Staff Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'staff', 'middleware' => ['authority.level.three']], function () {
        Route::get('edit-password/{staff_id}', 'StaffController@editPassword')->name('staff.edit.password');
        Route::put('update-password/{staff_id}', 'StaffController@updatePassword')->name('staff.update.password');
    });
    Route::resource('/staff', 'StaffController')->except(['show']);
    /*
    |--------------------------------------------------------------------------
    | Hospital Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('/hospital', 'HospitalController')->except(['show']);
    Route::get('/hospital/search', 'HospitalController@index')->name('hospital.search');
    Route::get('/hospital/search/text', 'HospitalController@searchText')->name('hospital.search.text');
    Route::get('/hospital/select/{id}', 'HospitalController@selectHospital')->name('hospital.select');
    Route::get('/hospital/attention-information/show', 'HospitalController@showAttentionInformation')->name('hospital.attention-information.show');
    Route::post('/hospital/attention-information/store', 'HospitalController@storeAttentionInformation')->name('hospital.attention-information.store');
    /*
    |--------------------------------------------------------------------------
    | Course Classification Routes
    |--------------------------------------------------------------------------
    */
    Route::post('/classification/{id}/restore', 'ClassificationController@restore')->name('classification.restore');
    Route::get('/classification/sort', 'ClassificationController@sort')->name('classification.sort');
    Route::patch('/classification/sort/update', 'ClassificationController@updateSort')->name('classification.updateSort');
    Route::resource('/classification', 'ClassificationController')->except(['show']);
    /*
    |--------------------------------------------------------------------------
    | Reservation Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('/reservation', 'ReservationController', ['only' => ['index']]);
    Route::get('reservation/operation', 'ReservationController@operation')->name('reservation.operation');
});

/*
|--------------------------------------------------------------------------
| Staff, Hospital staff authentication required
|--------------------------------------------------------------------------
*/
Route::middleware('auth:staffs,hospital_staffs')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Hospital staff Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('hospital-staff', 'HospitalStaffController')->except([
        'show',]);
    /*
    |--------------------------------------------------------------------------
    | Course Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('/course', 'CourseController')->except(['show']);
    Route::get('/course/sort', 'CourseController@sort')->name('course.sort');
    Route::get('/course/{id}/copy', 'CourseController@copy')->name('course.copy');
    Route::patch('/course/sort/update', 'CourseController@updateSort')->name('course.updateSort');
    /*
    |--------------------------------------------------------------------------
    | Course option Routes
    |--------------------------------------------------------------------------
    */
    Route::get('option/sort', 'OptionController@sort')->name('option.sort');
    Route::resource('option', 'OptionController', ['excerpt' => 'show']);
    Route::patch('option/sort/update', 'OptionController@updateSort')->name('option.updateSort');
    /*
    |--------------------------------------------------------------------------
    | Email template Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('/email-template', 'EmailTemplateController')->except(['show']);
    /*
    |--------------------------------------------------------------------------
    | Reception email setting Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('/reception-email-setting', 'ReceptionEmailSettingController');
    /*
    |--------------------------------------------------------------------------
    | Calendar Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/calendar/holiday', 'CalendarController@holiday_setting')->name('calendar.holiday');
    Route::patch('/calendar/holiday', 'CalendarController@update_holiday')->name('calendar.updateHoliday');
    Route::get('/calendar/{id}/setting', 'CalendarController@setting')->name('calendar.setting');
    Route::patch('/calendar/{id}/setting', 'CalendarController@updateSetting')->name('calendar.updateSetting');
    Route::resource('/calendar', 'CalendarController')->except(['show']);

    /*
    |--------------------------------------------------------------------------
    | Customer Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('customer', 'CustomerController');
    Route::post('customer/detail', 'CustomerController@detail')->name('customer.detail');
    //Route::get('customer/basic-information', 'CustomerController@basicInformationCreate');
    Route::post('customer/import', 'CustomerController@importData')->name('customer.import.data');
    Route::post('customer/email/{customer_id}', 'CustomerController@showEmailForm')->name('customer.show.email.form');
    Route::post('customer/email-send/{customer_id}', 'CustomerController@emailSend')->name('customer.email.send');
});
