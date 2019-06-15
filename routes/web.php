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

// スタッフ系
Route::group(['prefix' => 'staff', 'middleware' => ['authority.level.three']], function () {
    Route::get('edit-password/{staff_id}', 'StaffController@editPassword')->name('staff.edit.password');
    Route::put('update-password/{staff_id}', 'StaffController@updatePassword')->name('staff.update.password');
});
Route::resource('/staff', 'StaffController')->except(['show']);

// 医療機関系
Route::resource('/hospital', 'HospitalController')->except(['show']);
Route::get('/hospital/search', 'HospitalController@index')->name('hospital.search');
Route::get('/hospital/search/text', 'HospitalController@searchText')->name('hospital.search.text');

// 医療機関スタッフ系
Route::get('/hospital-staff/edit-password', 'HospitalStaffController@editPassword'); // ログインユーザーのパスワード編集画面に遷移する
Route::put('/hospital-staff/update-password/{hospital_staff_id}', 'HospitalStaffController@updatePassword')->name('hospital-staff.update.password'); // ログインユーザーのパスワードを更新する
Route::get('/hospital-staff/show-password-resets-mail', 'HospitalStaffController@showPasswordResetsMail'); // パスワードのリセットメール送信画面に遷移する
Route::get('/hospital-staff/send-password-resets-mail', 'HospitalStaffController@sendPasswordResetsMail')->name('hospital-staff.send.password-reset'); // パスワードのリセットメール送信を送信する
Route::get('/hospital-staff/show-reset-password/{reset_token}/{email}', 'HospitalStaffController@showResetPassword'); // パスワードのリセット画面に遷移する
Route::put('/hospital-staff/reset-password/{hospital_staff_id}', 'HospitalStaffController@resetPassword')->name('hospital-staff.reset.password'); // パスワードをリセットする
Route::resource('hospital-staff', 'HospitalStaffController')->except([
    'show',
]);

// 検査コース分類系
Route::post('/classification/{id}/restore', 'ClassificationController@restore')->name('classification.restore');
Route::get('/classification/sort', 'ClassificationController@sort')->name('classification.sort');
Route::patch('/classification/sort/update', 'ClassificationController@updateSort')->name('classification.updateSort');
Route::resource('/classification', 'ClassificationController')->except(['show']);

// 検査コース系
Route::resource('/course', 'CourseController')->except(['show']);
Route::get('/course/sort', 'CourseController@sort')->name('course.sort');
Route::get('/course/{id}/copy', 'CourseController@copy')->name('course.copy');
Route::patch('/course/sort/update', 'CourseController@updateSort')->name('course.updateSort');

// メールテンプレート系
Route::resource('/email-template', 'EmailTemplateController')->except(['show']);

// 受付メール設定系
Route::resource('/reception-email-setting', 'ReceptionEmailSettingController');

// ログイン系
Route::get('/login', function () {
    return view('/vendor/adminlte/login');
});

Route::get('/register', function () {
    return view('/vendor/adminlte/register');
});

// Calendar
Route::get('/calendar/holiday', 'CalendarController@holiday_setting')->name('calendar.holiday');
Route::patch('/calendar/holiday', 'CalendarController@update_holiday')->name('calendar.updateHoliday');
Route::get('/calendar/{id}/setting', 'CalendarController@setting')->name('calendar.setting');
Route::patch('/calendar/{id}/setting', 'CalendarController@updateSetting')->name('calendar.updateSetting');
Route::resource('/calendar', 'CalendarController')->except(['show']);

Auth::routes();
Route::get('/login', 'Auth\LoginController@getLogin')->name('login');
Route::post('/login', 'Auth\LoginController@postLogin');

Route::get('/home', 'HomeController@index')->name('home');

/*
|--------------------------------------------------------------------------
| Medical Institution Management Option
|--------------------------------------------------------------------------
*/
Route::get('option/sort', 'OptionController@sort')->name('option.sort');
Route::resource('option', 'OptionController', ['excerpt' => 'show']);
Route::patch('option/sort/update', 'OptionController@updateSort')->name('option.updateSort');

Route::resource('/reservation', 'ReservationController', ['only' => ['index']]);



/*
|--------------------------------------------------------------------------
| Customer Route
|--------------------------------------------------------------------------
*/

Route::resource('customer', 'CustomerController');
Route::post('customer/detail', 'CustomerController@detail')->name('customer.detail');
//Route::get('customer/basic-information', 'CustomerController@basicInformationCreate');
Route::post('customer/import', 'CustomerController@importData')->name('customer.import.data');
Route::post('customer/email/{customer_id}', 'CustomerController@showEmailForm')->name('customer.show.email.form');
Route::post('customer/email-send/{customer_id}', 'CustomerController@emailSend')->name('customer.email.send');
