<?php

namespace App;

use App\Enums\Status;
use Illuminate\Support\Facades\Log;

use App\Enums\WebReception;
use App\Helpers\EnumTrait;
use Reshadman\OptimisticLocking\OptimisticLocking;

class Course extends SoftDeleteModel
{
    use EnumTrait, OptimisticLocking;

    protected $fillable = [
        'hospital_id',
        'calendar_id',
        'code',
        'name',
        'web_reception',
        'is_category',
        'course_sales_copy',
        'course_point',
        'course_notice',
        'course_cancel',
        'reception_start_date',
        'reception_end_date',
        'reception_acceptance_date',
        'publish_start_date',
        'publish_end_date',
        'cancellation_deadline',
        'is_price',
        'price',
        'is_price_memo',
        'price_memo',
        'regular_price',
        'discounted_p[rice',
        'tax_class',
        'display_setting',
        'pv',
        'pvad',
        'order',
        'cancellation_deadline',
        'reception_start_date',
        'reception_end_date',
        'pre_account_price',
        'is_local_payment',
        'is_pre_account',
        'auto_calc_application',
        'reception_acceptance_date',
        'status',
        'created_at',
        'updated_at',
        'lock_version',
        'publish_start_date',
        'publish_end_date',
        'reception_acceptance_day_end'
    ];

    protected $attributes = [
        'course_cancel' => '0'
    ];

    protected $dates = [
        'publish_start_date',
        'publish_end_date'
    ];

    protected $enums = ['web_reception' => WebReception::class];

    public function course_options()
    {
        return $this->hasMany('App\CourseOption');
    }

    public function options()
    {
        return $this->hasManyThrough('App\Option', 'App\CourseOption', 'course_id', 'id', null, 'option_id');
    }

    public function course_details()
    {
        return $this->hasMany('App\CourseDetail');
    }

    public function course_questions()
    {
        return $this->hasMany('App\CourseQuestion')->orderBy('question_number');
    }

    public function course_images()
    {
        return $this->hasMany('App\CourseImage');
    }

    public function course_metas()
    {
        return $this->hasOne('App\CourseMeta');
    }

    public function hospital_metas()
    {
        return $this->hasOne('App\HospitalMeta', 'hospital_id', 'hospital_id');
    }

    public function hospital()
    {
        return $this->belongsTo('App\Hospital');
    }

    public function contract_information()
    {
        return $this->hasOne('App\ContractInformation', 'hospital_id', 'hospital_id');
    }

    public function kenshin_sys_courses()
    {
        return $this->belongsToMany('App\KenshinSysCourse', 'course_match')
            ->as('course_match')->withTimestamps();
    }

//    public function hospital()
//    {
//        return $this->hasOne('App\Hospital');
//    }

    public function calendar()
    {
        // return $this->hasOne('App\Calendar');
        return $this->belongsTo('App\Calendar');
    }

    public function attributes()
    {
        $attributes = [
            'pre_account_price' => '事前決済価格'
        ];
        return $attributes;
    }

    public function course_meta_informations()
    {
        return $this->hasMany('App\HospitalMeta');
    }

    public function calendar_days()
    {
        return $this->hasMany('App\CalendarDay', 'calendar_id', 'calendar_id');
    }

    public function tax_class()
    {
        return $this->belongsTo('App\TaxClass');
    }

    public function reservations()
    {
        return $this->hasMany('App\Reservation');
    }

    /**
     * 検査コース一覧検索
     *
     * @return クエリ
     */
    public function scopeWhereForSearchAPI($query, $request)
    {
        // フリーワード（施設名など）
        if ($request->input('freewords') !== null)
            $query->whereHas('course_meta_informations', function ($query) use ($request) {
                $query->where('freewords', 'like', '%' . $request->input('freewords') . '%');
            });

        // フリーワード（エリアなど）
        if ($request->input('freewords') !== null)
            $query->whereHas('course_meta_informations', function ($query) use ($request) {
                $query->where('area_station', 'like', '%' . $request->input('freewords') . '%');
            });

        // フリーワード（施設特徴）
        if ($request->input('freewords') !== null)
            $query->whereHas('course_meta_informations', function ($query) use ($request) {
                $query->where('hospital_classification', 'like', '%' . $request->input('freewords') . '%');
            });

        // フリーワード（路線）
        if ($request->input('freewords') !== null)
            $query->whereHas('course_meta_informations', function ($query) use ($request) {
                $query->where('rails', 'like', '%' . $request->input('freewords') . '%');
            });

        // 都道府県コード
        $pref_cd = $request->input('pref_cd');
        if (isset($pref_cd)) {
            $query->whereHas('hospital', function ($query) use ($pref_cd) {
                $query->where('prefecture_id', $pref_cd);
            });
        }

        // 市区町村コード
        $district_no = $request->input('district_no');
        if (isset($district_no)) {
            $districts = explode(',', $district_no);
            $query->whereHas('hospital.district_code', function ($query) use ($districts) {
                $query->whereIn('district_code', $districts);
            });
        }

        // 路線コード
        $rail_no = $request->input('rail_no');
        if (isset($rail_no)) {
            $rails = explode(',', $rail_no);
            $query->whereHas('hospital', function ($query) use ($rails) {
                $query->whereIn('rail1', $rails)->orWhereIn('rail2', $rails)->orWhereIn('rail3', $rails)->orWhereIn('rail4', $rails)->orWhereIn('rail5', $rails);
            });
        }

        // 駅コード
        $station_no = $request->input('station_no');
        if (isset($station_no)) {
            $stations = explode(',', $station_no);
            $query->whereHas('hospital', function ($query) use ($stations) {
                $query->whereIn('station1', $stations)->orWhereIn('station2', $stations)->orWhereIn('station3', $stations)->orWhereIn('station4', $stations)->orWhereIn('station5', $stations);
            });
        }

        $from = $request->input('reservation_dt_from');
        $to = $request->input('reservation_dt_to');
        // 受信希望日FROM TO
        if (isset($from) and isset($to)) {
            $query->whereHas('calendar_days', function ($query) use ($from, $to) {
                $query->where('date', '>=', $from)
                    ->where('date', '<=', $to)
                    ->where('is_reservation_acceptance', 0);
            });
        }
        // 受診希望日FROM
        else if (isset($from) and empty($to)) {
            $query->whereHas('calendar_days', function ($query) use ($from) {
                $query->where('date', '>=', $from)->where('is_reservation_acceptance', 0);
            });
        }
        // 受診希望日TO
        else if (empty($from) and isset($to)) {
            $query->whereHas('calendar_days', function ($query) use ($to) {
                $query->where('date', '<=', $to)->where('is_reservation_acceptance', 0);
            });
        }

        // 検査コース金額検索
        $price_upper_limit = $request->input('price_upper_limit');
        if (isset($price_upper_limit)) {
            $query->where('price', '<=', $price_upper_limit);
        }
        $price_lower_limit = $request->input('price_lower_limit');
        if (isset($price_lower_limit)) {
            $query->where('price', '>=', $price_lower_limit);
        }

        // 施設分類コード
        if ($request->input('hospital_category_code') !== null) {
            $hospital_category_code = explode(",", $request->input('hospital_category_code'));
            $query->whereHas('hospital.hospital_details', function ($query) use ($hospital_category_code) {
                $query->whereIn('hospital_category_sho_id', $hospital_category_code);
            });
        }
        // 検査コース分類コード
        if ($request->input('course_category_code') !== null) {
            $course_category_code = explode(",", $request->input('course_category_code'));
            $query->whereHas('course_details', function ($query) use ($course_category_code) {
                $query->whereIn('minor_classification_id', $course_category_code);
            });
        }

        // コースの料金による並べ替え
        if (intval($request->input('course_price_sort')) === 0) {
            $query->orderBy('price');
        }
        else {
            $query->orderBy('price', 'desc');
        }
        Log::debug($query->toSql());

        return $query;
    }

    const AILIAS_FOR_HOSPITAL_API = [
        'id',
        'hospital_id',
        'hospital_id as no',
        'id as course_no',
        'code as course_code',
        'name as course_name',
        'web_reception',
        'course_point',
        'is_price as flg_price',
        'price',
        'is_price_memo as flg_price_memo',
        'price_memo',
        'regular_price as price_2',
        'discounted_price as price_3',
        'tax_class_id',
        'pre_account_price',
        'is_local_payment as flg_local_payment',
        'is_pre_account as flg_pre_account',
        'auto_calc_application',
        'calender_id',
    ];

    public function preview_url() {
        $url = env('FRONT_URL');
        $contract = ContractInformation::where('hospital_id', $this->hospital_id)->first();
        if ($contract) {
            return $url . 'prev/' . $contract->code . '/' . $this->code;
        }
        return '';
    }
}
