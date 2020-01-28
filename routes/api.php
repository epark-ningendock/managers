<?php

use Illuminate\Http\Request;
//
///*
//|--------------------------------------------------------------------------
//| API Routes
//|--------------------------------------------------------------------------
//|
//| Here is where you can register API routes for your application. These
//| routes are loaded by the RouteServiceProvider within a group which
//| is assigned the "api" middleware group. Enjoy building your API!
//|
//*/
//
Route::middleware('auth:api')->get('/user', function (Request $request) {
   return $request->user();
});
Route::prefix('v1')->group(function () {
    // 医療機関一覧検索API
    Route::match(['get', 'post'], 'search/hospitals/', 'SearchController@hospitals');

    // 検査コース一覧検索API
    Route::match(['get', 'post'], 'search/courses/', 'SearchController@courses');

    // 医療機関・検査コース一覧検索API
    Route::match(['get', 'post'], 'search/', 'SearchController@index');

    // 医療機関基本情報検索API
    Route::match(['get', 'post'], 'hospital/basic/', 'HospitalController@basic');

    // 医療機関コンテンツ情報取得API
    Route::match(['get', 'post'], 'hospital/contents/', 'HospitalController@contents');

    // 医療機関検査コース一覧情報取得API
    Route::match(['get', 'post'], 'hospital/courses/', 'HospitalController@courses');

    // 医療機関情報取得API
    Route::match(['get', 'post'], 'hospital/', 'HospitalController@index');

    // 医療機関空き枠情報取得API
    Route::match(['get', 'post'], 'hospital/frame', 'HospitalController@frame');

    // 医療機関アクセス情報取得API
    Route::match(['get', 'post'], 'hospital/access', 'HospitalController@access');

    // 医療機関予約数取得API
    Route::match(['get', 'post'], 'hospital/reserve_cnt', 'HospitalController@reserve_cnt');

    // 検査コース基本情報取得API
    Route::match(['get', 'post'], 'course/basic/', 'CourseController@basic');

    // 検査コースコンテンツ情報取得API
    Route::match(['get', 'post'], 'course/contents/', 'CourseController@contents');

    // 検査コース情報取得API
    Route::match(['get', 'post'], 'course/', 'CourseController@index');

    // 検査コース空満情報（月別）取得API
    Route::match(['get', 'post'], 'course/calendar_monthly/', 'CourseController@calendar_monthly');

    // 検査コース空満情報（日別）取得API
    Route::match(['get', 'post'], 'course/calendar_daily/', 'CourseController@calendar_daily');

    // 対象一覧取得（住所）API
    Route::match(['get', 'post'], 'place/', 'PlaceController@index');

    // 対象一覧取得（路線）API
    Route::match(['get', 'post'], 'route/', 'RouteController@index');

// 以下予約API
// 予約登録/更新API
    Route::post('reservationstore', 'ReservationApiController@store')->name('reservation-api.reservationstore');
// 予約確認API
    Route::get('reservation-conf', 'ReservationApiController@conf');
// 予約キャンセルAPI
    Route::match(['get', 'post'], 'reservation-cancel/', 'ReservationApiController@cancel');
// PV登録
    Route::match(['get', 'post'], 'pv/', 'PvRegistController@store');
// EPARK会員ログイン情報
    Route::match(['get', 'post'], 'member-login-info-store/', 'MemberLoginInfoController@store');
    Route::match(['get', 'post'], 'member-login-info-show', 'MemberLoginInfoController@show');
// 検討中リスト
    Route::match(['get', 'post'], 'consideration-list-store/', 'ConsiderationListController@store');
    Route::match(['get', 'post'], 'consideration-list-show/', 'ConsiderationListController@show');
    Route::match(['get', 'post', 'delete'],'consideration-list-destroy/', 'ConsiderationListController@destroy');

    // コース通知API
    Route::post('registcourse', 'CourseInfoNotificationController@registcourse')->name('course-info-notification.registcourse');
    // コース通知API
    Route::post('registcoursewaku', 'CourseInfoWakuNotificationController@registcourse')->name('course-info-waku-notification.registcoursewaku');
    // 予約情報通知API
    Route::post('yoyakustate', 'ReservationInfoNotificationController@notice')->name('reservation-info-notification.notice');
});
