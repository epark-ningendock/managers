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
| Contract Information
|--------------------------------------------------------------------------
*/
Route::post('/contract/upload', 'ContractInformationController@upload')->name('contract.upload');
Route::post('/contract/upload/store', 'ContractInformationController@storeUpload')->name('contract.upload.store');
Route::get('/contract', 'ContractInformationController@index')->name('contract.index');
Route::post('/contract-information/store', 'ContractInformationController@store')->name('contract.store');
Route::get('/hospital/contract-information', 'ContractInformationController@create')->name('hospital.contractInfo');

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
            Route::get('/search', 'HospitalController@index')->name('hospital.search');
            Route::get('/search/text', 'HospitalController@searchText')->name('hospital.search.text');
            Route::get('/search/contract-info', 'HospitalController@searchHospiralContractInfo')->name('hospital.search.contractInfo');
            Route::get('/select/{id}', 'HospitalController@selectHospital')->name('hospital.select');
            Route::get('/image-information', 'HospitalController@createImageInformation')->name('hospital.image.information');
            Route::get('/attention-information/create', 'HospitalController@createAttentionInformation')->name('hospital.attention-information.show');
            Route::post('/attention-information/store', 'HospitalController@storeAttentionInformation')->name('hospital.attention-information.store');
        });
        Route::resource('/hospital', 'HospitalController')->except(['show']);
        
        Route::group(['prefix' => 'hospital'], function () {
            Route::get('/{hospital}/images/create', 'HospitalImagesController@create')->name('hospital.image.create');
            Route::post('/{hospital}/images/store', 'HospitalImagesController@store')->name('hospital.image.store');
            Route::get('/{hospital}/images/create', 'HospitalImagesController@create')->name('hospital.image.create');
            Route::post('/{hospital}/images/store', 'HospitalImagesController@store')->name('hospital.image.store');
            Route::get('/{hospital}/images/{hospital_category_id}/{hospital_image_id}/delete', 'HospitalImagesController@delete')->name('hospital.image.delete');
            Route::get('/{hospital}/images/{hospital_image_id}/delete_image', 'HospitalImagesController@deleteImage')->name('hospital.delete_image');
            Route::get('/{hospital}/interview/{interview_id}/delete', 'HospitalImagesController@deleteInterview')->name('hospital.delete_interview');
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
    });
});

/*
|--------------------------------------------------------------------------
| Staff, Hospital staff authentication required
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:staffs,hospital_staffs', 'permission.hospital.edit'])->group(function () {
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

    /*
    |--------------------------------------------------------------------------
    | Reception Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/reception/csv', 'ReservationController@reception_csv')->name('reception.csv');
    Route::patch('/reception/reservation_status', 'ReservationController@reservation_status')->name('reservation.bulk_status');
    Route::get('/reception', 'ReservationController@reception');
});
