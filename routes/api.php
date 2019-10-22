<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function () {
    // 医療機関一覧検索API
    Route::match(['get', 'post'], 'search/hospitals/', 'SearchController@hospitals')->middleware('jsonp');

    // 検査コース一覧検索API
    Route::match(['get', 'post'], 'search/courses/', 'SearchController@courses')->middleware('jsonp');

    // 医療機関・検査コース一覧検索API
    Route::match(['get', 'post'], 'search/', 'SearchController@index')->middleware('jsonp');

    // 医療機関基本情報検索API
    Route::match(['get', 'post'], 'hospital/basic/', 'HospitalController@basic')->middleware('jsonp');

    // 医療機関コンテンツ情報取得API
    Route::match(['get', 'post'], 'hospital/contents/', 'HospitalController@contents')->middleware('jsonp');

    // 医療機関検査コース一覧情報取得API
    Route::match(['get', 'post'], 'hospital/courses/', 'HospitalController@courses')->middleware('jsonp');

    // 医療機関情報取得API
    Route::match(['get', 'post'], 'hospital/', 'HospitalController@index')->middleware('jsonp');

    // 公開中医療機関情報取得API
    Route::match(['get', 'post'], 'hospital/release', 'HospitalController@release')->middleware('jsonp');

    // 公開中医療機関コース情報取得API
    Route::match(['get', 'post'], 'hospital/release_course', 'HospitalController@release_course')->middleware('jsonp');

    // 医療機関予約数取得API
    Route::match(['get', 'post'], 'hospital/reserve_cnt', 'HospitalController@reserve_cnt')->middleware('jsonp');

    // 検査コース基本情報取得API
    Route::match(['get', 'post'], 'course/basic/', 'CourseController@basic')->middleware('jsonp');

    // 検査コースコンテンツ情報取得API
    Route::match(['get', 'post'], 'course/contents/', 'CourseController@contents')->middleware('jsonp');

    // 検査コース情報取得API
    Route::match(['get', 'post'], 'course/', 'CourseController@index')->middleware('jsonp');

    // 検査コース空満情報（月別）取得API
    Route::match(['get', 'post'], 'course/calendar_monthly/', 'CourseController@calendar_monthly')->middleware('jsonp');

    // 検査コース空満情報（日別）取得API
    Route::match(['get', 'post'], 'course/calendar_daily/', 'CourseController@calendar_daily')->middleware('jsonp');

    // 対象一覧取得（住所）API
    Route::match(['get', 'post'], 'place/', 'PlaceController@index')->middleware('jsonp');

    // 対象一覧取得（路線）API
    Route::match(['get', 'post'], 'route/', 'RouteController@index')->middleware('jsonp');

// 以下予約API
// 予約登録/更新API
    Route::post('reservation-store', 'ReservationController@store');
// 予約確認API
    Route::get('reservation-conf', 'ReservationController@conf');
// 予約キャンセルAPI
    Route::post('reservation-cancel', 'ReservationController@cancel');
// PV登録
    Route::post('pvRegist', 'PvRegistController@store')->name('pv-regist.store');
// EPARK会員ログイン情報
    Route::post('memberLoginInfo', 'MemberLoginInfoController@store')->name('member-login-info.store');
    Route::get('memberLoginInfo', 'MemberLoginInfoController@show')->name('member-login-info.show');
// 検討中リスト
    Route::post('considerationList', 'ConsiderationListController@store')->name('consideration-list.store');
    Route::get('considerationList', 'ConsiderationListController@show')->name('consideration-list.show');
    Route::delete('considerationList', 'ConsiderationListController@destroy')->name('consideration-list.destroy');
});

