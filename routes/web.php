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

//Route::get('/staff', 'StaffController@index')->middleware('can:view-staff');
//
//Route::resource('/staff', 'StaffController')->except(['show', 'index'])->middleware('can:edit-staff');

/*
|--------------------------------------------------------------------------
| Login Routes
|--------------------------------------------------------------------------
*/
Auth::routes();
Route::get('/login', 'Auth\LoginController@getLogin')->name('login');
Route::post('/login', 'Auth\LoginController@postLogin')->name('postLogin');

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
Route::put('/hospital-staff/reset-password/{email}', 'HospitalStaffController@resetPassword')->name('hospital-staff.reset.password');

/*
|--------------------------------------------------------------------------
| Staff authentication required
|--------------------------------------------------------------------------
*/
Route::middleware('auth:staffs')->group(function () {
    Route::get('/staff/edit-password-personal', 'StaffController@editPersonalPassword')->name('staff.edit.password-personal');
    Route::post('/staff/update-password-personal', 'StaffController@updatePersonalPassword')->name('staff.update.password-personal');
    Route::middleware('authority.level.not-contract-staff')->group(function () {
        /*
        |--------------------------------------------------------------------------
        | Staff Routes
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'staff', 'middleware' => ['authority.level.admin']], function () {
            Route::get('edit-password/{staff_id}', 'StaffController@editPassword')->name('staff.edit.password');
            Route::put('update-password/{staff_id}', 'StaffController@updatePassword')->name('staff.update.password');
        });
        Route::resource('/staff', 'StaffController')->except(['show']);

        /*
        |--------------------------------------------------------------------------
        | Hospital Routes
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'hospital'], function () {
            /*
            |--------------------------------------------------------------------------
            | 医療機関 検索
            |--------------------------------------------------------------------------
            */
            Route::get('/search', 'HospitalController@index')->name('hospital.search');
            Route::get('/search/text', 'HospitalController@searchText')->name('hospital.search.text');
            Route::get('/select/{id}', 'HospitalController@selectHospital')->name('hospital.select');

            /*
            |--------------------------------------------------------------------------
            | 医療機関 画像情報
            |--------------------------------------------------------------------------
            */
            Route::post('/{hospital}/images/store', 'HospitalImagesController@store')->name('hospital.image.store');
            Route::get('/{hospital}/images/create', 'HospitalImagesController@create')->name('hospital.image.create');
            Route::get('/{hospital}/images/{hospital_category_id}/{hospital_image_id}/delete', 'HospitalImagesController@delete')->name('hospital.image.delete');
            Route::get('/{hospital}/images/{hospital_image_id}/delete_image', 'HospitalImagesController@deleteImage')->name('hospital.delete_image');
            Route::get('/{hospital}/images/{hospital_image_id}/delete_main_image/{is_sp}', 'HospitalImagesController@deleteMainImage')->name('hospital.delete_main_image');
            /*
            |--------------------------------------------------------------------------
            | 医療機関 こだわり情報
            |--------------------------------------------------------------------------
            */
            Route::get('/{hospital}/attention/create', 'HospitalAttentionController@create')->name('hospital.attention.create');
            Route::post('/{hospital}/attention/store', 'HospitalAttentionController@store')->name('hospital.attention.store');
            Route::get('/{hospital}/interview/{interview_id}/delete', 'HospitalImagesController@deleteInterview')->name('hospital.delete_interview');
        });
        /*
        |--------------------------------------------------------------------------
        | 医療機関 一覧＆基本情報
        |--------------------------------------------------------------------------
        */
        Route::resource('/hospital', 'HospitalController')->except(['show']);
        Route::post('/hospital/find-rails', 'HospitalController@findRails')->name('hospital.find-rails');
        Route::post('/hospital/find-stations', 'HospitalController@findStations')->name('hospital.find-stations');

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
        Route::patch('/reservation/{id}/accept', 'ReservationController@accept')->name('reservation.accept');
        Route::delete('/reservation/{id}/cancel', 'ReservationController@cancel')->name('reservation.cancel');
        Route::patch('/reservation/{id}/complete', 'ReservationController@complete')->name('reservation.complete');
        Route::resource('/reservation', 'ReservationController', ['only' => ['index']]);
        Route::get('reservation/operation', 'ReservationController@operation')->name('reservation.operation');
        Route::get('/reservation', 'ReservationController@index')->name('reservation.index');
    });


    /*
     |--------------------------------------------------------------------------
     | 契約情報
     |--------------------------------------------------------------------------
     */
    Route::group(['prefix' => 'hospital'], function() {
        Route::post('/contract/upload', 'HospitalContractInformationController@upload')->name('contract.upload');
        Route::post('/contract/upload/store', 'HospitalContractInformationController@storeUpload')->name('contract.upload.store');
        Route::get('/contract', 'HospitalContractInformationController@index')->name('contract.index');
        Route::get('/{hospital_id}/contract/show', 'HospitalContractInformationController@show')->name('contract.show');
    });

    /*
    |--------------------------------------------------------------------------
    | Course Classification Routes
    |--------------------------------------------------------------------------
    */
    Route::post('/classification/{id}/restore', 'ClassificationController@restore')->name('classification.restore');
    Route::get('/classification/sort', 'ClassificationController@sort')->name('classification.sort');
    Route::patch('/classification/sort/update', 'ClassificationController@updateSort')->name('classification.updateSort');
    Route::resource('/classification', 'ClassificationController')->except(['show']);

});

/*
|--------------------------------------------------------------------------
| Staff, Hospital staff authentication required
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:staffs,hospital_staffs', 'permission.hospital.edit'])->group(function () {

    Route::get('billing/excel-export', 'BillingController@excelExport')->name('billing.excel.export');
    Route::resource('billing', 'BillingController');
    Route::get('billing/{billing}/{hospital_id}/status/update', 'BillingController@statusUpdate')->name('billing.status.update');
    Route::get('hospital/billing', 'BillingController@hospitalBilling')->name('hospital.billing');

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
    Route::get('/course/json/{id}/detail', 'CourseController@course_detail')->name('course.detail.json');
    Route::resource('/course', 'CourseController')->except(['show']);
    Route::get('/course/sort', 'CourseController@sort')->name('course.sort');
    Route::get('/course/{id}/copy', 'CourseController@copy')->name('course.copy');
    Route::patch('/course/sort/update', 'CourseController@updateSort')->name('course.updateSort');
    Route::get('/course/images/{course_image_id}/delete', 'CourseController@deleteImage')->name('course.image.delete');
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
    Route::get('/email-template/{id}/copy', 'EmailTemplateController@copy')->name('email-template.copy');
    /*
    |--------------------------------------------------------------------------
    | Reception email setting Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('/hospital-email-setting', 'HospitalEmailSettingController');
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
    Route::get('courses/{course_id}/reservation-days', 'CalendarController@reservationDays')->name('course.reservation.days');

    /*
    |--------------------------------------------------------------------------
    | Customer Routes
    |--------------------------------------------------------------------------
    */
    Route::post('customer/{id}/integration', 'CustomerController@integration')->name('customer.integration');
    Route::post('customer/email-history/{customer_id}', 'CustomerController@email_history')->name('customer.email.history');
    Route::resource('customer', 'CustomerController');
    Route::post('customer/detail', 'CustomerController@detail')->name('customer.detail');
    //Route::get('customer/basic-information', 'CustomerController@basicInformationCreate');
    Route::post('customer/import', 'CustomerController@importData')->name('customer.import.data');
    Route::post('customer/email/{customer_id}', 'CustomerController@showEmailForm')->name('customer.show.email.form');
    Route::post('customer/email-send/{customer_id}', 'CustomerController@emailSend')->name('customer.email.send');
    Route::post('customer/search', 'CustomerController@customerSearch')->name('customer.search');

    /*
    |--------------------------------------------------------------------------
    | Reception Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/reception/csv', 'ReservationController@reception_csv')->name('reception.csv');
    Route::patch('/reception/reservation_status', 'ReservationController@reservation_status')->name('reservation.bulk_status');

    Route::patch('/reservation/{id}/accept', 'ReservationController@accept')->name('reservation.accept');
    Route::delete('/reservation/{id}/cancel', 'ReservationController@cancel')->name('reservation.cancel');
    Route::patch('/reservation/{id}/complete', 'ReservationController@complete')->name('reservation.complete');
    Route::resource('reservation', 'ReservationController')->except(['show', 'delete']);
    Route::get('reservation/operation', 'ReservationController@operation')->name('reservation.operation');
	Route::get('reservation/operation', 'ReservationController@operation')->name('reservation.operation');


});
Route::get('/ok', 'CalendarController@showCalendarGenerator');
