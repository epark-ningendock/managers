<?php

//use Illuminate\Http\Request;
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
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::prefix('v1')->group(function () {
    // 医療機関一覧検索API
    Route::match(['get', 'post'], 'search/hospitals/', 'SearchController@hospitals')->middleware('cacheResponse:259200,search_h');

    // 検査コース一覧検索API
    Route::match(['get', 'post'], 'search/courses/', 'SearchController@courses')->middleware('cacheResponse:129600,search_c');

    // 医療機関・検査コース一覧検索API
    Route::match(['get', 'post'], 'search/', 'SearchController@index')->middleware('cacheResponse:259200,,search_h');

    // 医療機関基本情報検索API
    Route::match(['get', 'post'], 'hospital/basic/', 'HospitalController@basic')->middleware('cacheResponse:259200,hospital');

    // 医療機関コンテンツ情報取得API
    Route::match(['get', 'post'], 'hospital/contents/', 'HospitalController@contents')->middleware('cacheResponse:259200,hospital');

    // 医療機関検査コース一覧情報取得API
    Route::match(['get', 'post'], 'hospital/courses/', 'HospitalController@courses')->middleware('cacheResponse:259200,course');

    // 医療機関情報取得API
    Route::match(['get', 'post'], 'hospital/', 'HospitalController@index')->middleware('cacheResponse:259200,course');

    // 公開中医療機関情報取得API
    Route::match(['get', 'post'], 'hospitals/', 'HospitalController@release')->middleware('cacheResponse:259200');

    // 公開中医療機関コース情報取得API
    Route::match(['get', 'post'], 'courses/', 'HospitalController@release_course')->middleware('cacheResponse:259200');


    // 医療機関空き枠情報取得API
    Route::match(['get', 'post'], 'hospital/frame', 'HospitalController@frame');

    // 医療機関アクセス情報取得API
    Route::match(['get', 'post'], 'hospital/access', 'HospitalController@access');

    // 医療機関予約数取得API
    Route::match(['get', 'post'], 'hospital/reserve_cnt', 'HospitalController@reserve_cnt');

    // 検査コース基本情報取得API
    Route::match(['get', 'post'], 'course/basic/', 'CourseController@basic')->middleware('cacheResponse:259200,course');

    // 検査コースコンテンツ情報取得API
    Route::match(['get', 'post'], 'course/contents/', 'CourseController@contents')->middleware('cacheResponse:259200,course');

    // 検査コース情報取得API
    Route::match(['get', 'post'], 'course/', 'CourseController@index')->middleware('cacheResponse:259200,course');

    // 検査コース空満情報（月別）取得API
    Route::match(['get', 'post'], 'course/calendar_monthly/', 'CourseController@calendar_monthly')->middleware('cacheResponse:259200,cal');

    // 検査コース空満情報（日別）取得API
    Route::match(['get', 'post'], 'course/calendar_daily/', 'CourseController@calendar_daily')->middleware('cacheResponse:7200,cal');

    // 対象一覧取得（住所）API
    Route::match(['get', 'post'], 'place/', 'PlaceController@index')->middleware('cacheResponse:2592000');

    // 対象一覧取得（路線）API
    Route::match(['get', 'post'], 'route/', 'RouteController@index')->middleware('cacheResponse:2592000');
  
    // 医療機関・検査コース毎の予約数取得API
    Route::match(['get', 'post'], 'reserve_vol/', 'ReserveVolController@index');

    // iFlag契約者ID取得API
    Route::match(['get','post'], 'hospital/shopowner/', 'HospitalController@shopowner');

    // 医療機関手数料取得API
    Route::match(['get', 'post'], 'fee_rate/', 'HospitalController@fee_rate');

// 以下予約API
// 予約登録/更新API
    Route::post('reservation-store/', 'ReservationApiController@store');
// 予約確認API
    Route::get('reservation-conf', 'ReservationApiController@conf');
// 予約キャンセルAPI
    Route::post('reservation-cancel/', 'ReservationApiController@cancel');
    // 予約一覧取得
    Route::get('reservation-get-all', 'ReservationApiController@get_all');
// PV登録
    Route::post('pv/', 'PvRegistController@store');
// EPARK会員ログイン情報
    Route::match(['get', 'post'], 'member-login-info-store/', 'MemberLoginInfoController@store');
    Route::match(['get', 'post'], 'member-login-info-show', 'MemberLoginInfoController@show');
// 検討中リスト
    Route::post('consideration-list-store/', 'ConsiderationListController@store');
    Route::match(['get', 'post'], 'consideration-list-show/', 'ConsiderationListController@show');
    Route::delete('consideration-list-destroy/', 'ConsiderationListController@destroy');

    // コース通知API
    Route::post('registcourse/', 'CourseInfoNotificationController@registcourse');
    // コース通知API
    Route::post('registcoursewaku/', 'CourseInfoWakuNotificationController@registcoursewaku');
    // 予約情報通知API
    Route::post('yoyakustate/', 'ReservationInfoNotificationController@notice');
});

