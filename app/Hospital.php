<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Reshadman\OptimisticLocking\OptimisticLocking;

class Hospital extends Model
{
    use SoftDeletes, OptimisticLocking;
    protected $table = 'hospitals';

    //Note $fillable is temporary for factory, make it realistic field when business logic
    protected $fillable = [
        'old_karada_dog_id',
        'name',
        'kana',
        'postcode',
        'prefecture_id',
        'district_code_id',
        'medical_examination_system_id',
        'course_meta_information_id',
        'address1',
        'address2',
        'longitude',
        'latitude',
        'direction',
        'streetview_url',
        'tel',
        'paycall',
        'fax',
        'url',
        'consultation_note',
        'memo',
        'rail1',
        'station1',
        'access1',
        'rail2',
        'station2',
        'access2',
        'rail3',
        'station3',
        'access3',
        'rail4',
        'station4',
        'access4',
        'rail5',
        'station5',
        'access5',
        'memo1',
        'memo2',
        'memo3',
        'principal',
        'principal_history',
        'pv_count',
        'pvad',
        'is_pickup',
        'status',
        'free_area',
        'search_word',
        'plan_code',
        'hplink_contract_type',
        'hplink_count',
        'hplink_price',
        'is_pre_account',
        'pre_account_discount_rate',
        'pre_account_commission_rate',
        'created_at',
        'updated_at',
        'lock_version',
        'biography',
        'representative',

    ];

    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class)
            ->withDefault();
    }

    public function districtCode()
    {
        return $this->belongsTo(DistrictCode::class)
            ->withDefault();
    }

    /**
     * 医療機関に関連する受付メール設定レコードを取得
     */
    public function hospital_email_setting()
    {
        return $this->hasOne('App\HospitalEmailSetting');
    }

    public function hospital_images()
    {
        return $this->hasMany('App\HospitalImage');
    }

    public function hospital_categories()
    {
        return $this->hasMany('App\HospitalCategory');
    }

    public function hospital_details()
    {
        return $this->hasMany('App\HospitalDetail');
    }

    public function contract_information()
    {
        return $this->hasOne('App\ContractInformation');
    }

    public function lock()
    {
        return $this->hasOne('App\Lock');
    }

    public function courses()
    {
        return $this->hasMany('App\Course');
    }

    public function options()
    {
        return $this->hasMany('App\Option');
    }

    public function district_code()
    {
        return $this->belongsTo('App\DistrictCode');
    }

    public function medical_treatment_times()
    {
        return $this->hasMany('App\MedicalTreatmentTime');
    }

    public function reception_email_setting()
    {
        return $this->hasOne('App\ReceptionEmailSetting');
    }

    /**
     * 医療機関一覧検索
     *
     * @return クエリ
     */
    public function scopeWhereForSearchAPI($query, $request)
    {
        // フリーワード（施設名など）
        if ($request->input('freewords') !== null)
            $query->whereHas('courses.course_meta_informations', function ($query) use ($request) {
                $query->where('freewords', 'like', '%' . $request->input('freewords') . '%');
            });

        // フリーワード（エリアなど）
        if ($request->input('freewords_area') !== null)
            $query->whereHas('courses.course_meta_informations', function ($query) use ($request) {
                $query->where('area_station', 'like', '%' . $request->input('freewords_area') . '%');
            });

        // フリーワード（施設特徴）
        if ($request->input('freewords_hospital_point') !== null)
            $query->whereHas('courses.course_meta_informations', function ($query) use ($request) {
                $query->where('hospital_classification', 'like', '%' . $request->input('freewords_hospital_point') . '%');
            });

        // フリーワード（路線）
        if ($request->input('freewords_rail') !== null)
            $query->whereHas('courses.course_meta_informations', function ($query) use ($request) {
                $query->where('rails', 'like', '%' . $request->input('freewords_rail') . '%');
            });

        // 都道府県コード
        $pref_cd = $request->input('pref_cd');
        if (isset($pref_cd)) {
            $query->whereHas('prefecture', function ($query) use ($pref_cd) {
                $query->where('prefecture_id', $pref_cd);
            });
        };

        // 市区町村コード
        $district_no = $request->input('district_no');
        if (isset($district_no)) {
            $query->whereHas('district_code', function ($query) use ($district_no) {
                $query->where('district_code', $district_no);
            });
        };

        // 路線コード
        $rail_no = $request->input('rail_no');
        if (isset($rail_no)) {
            $query->orWhere('rail1', $rail_no)
                ->orWhere('rail2', $rail_no)
                ->orWhere('rail3', $rail_no)
                ->orWhere('rail4', $rail_no)
                ->orWhere('rail5', $rail_no);
        };

        // 駅コード
        $station_no = $request->input('station_no');
        if (isset($station_no)) {
            $query->orWhere('station1', $station_no)
                ->orWhere('station2', $station_no)
                ->orWhere('station3', $station_no)
                ->orWhere('station4', $station_no)
                ->orWhere('station5', $station_no);
        };

        $from = $request->input('reservation_dt_from');
        $to = $request->input('reservation_dt_to');
        // 受信希望日FROM TO
        if (isset($from) and isset($to)) {
            $query->whereHas('courses.calendar_days', function ($query) use ($from, $to) {
                $query->where('date', '>=', $from)
                    ->where('date', '<=', $to)
                    ->where('is_reservation_acceptance', 1);
            });
        }
        // 受診希望日FROM
        else if (isset($from) and empty($to)) {
            $query->whereHas('courses.calendar_days', function ($query) use ($from) {
                $query->where('date', '>=', $from)
                    ->where('is_reservation_acceptance', 1);
            });
        }
        // 受診希望日TO
        else if (empty($from) and isset($to)) {
            $query->whereHas('courses.calendar_days', function ($query) use ($to) {
                $query->where('date', '<=', $to)
                    ->where('is_reservation_acceptance', 1);
            });
        }

        // 検査コース金額検索
        $price_upper_limit = $request->input('price_upper_limit');
        if (isset($price_upper_limit)) {
            $query->whereHas('courses', function ($query) use ($price_upper_limit) {
                $query->where('price', '<=', $price_upper_limit);
            });
        }
        $price_lower_limit = $request->input('price_lower_limit');
        if (isset($price_lower_limit)) {
            $query->whereHas('courses', function ($query) use ($price_lower_limit) {
                $query->where('price', '>=', $price_lower_limit);
            });
        }

        // 施設分類コード
        if ($request->input('hospital_category_code') !== null) {
            $hospital_category_code = explode(",", $request->input('hospital_category_code'));
            $query->whereHas('hospital_details', function ($query) use ($hospital_category_code) {
                $query->whereIn('hospital_category_sho_id', $hospital_category_code);
            });
        }
        // 検査コース分類コード
        if ($request->input('course_category_code') !== null) {
            $course_category_code = explode(",", $request->input('course_category_code'));
            $query->whereHas('courses.course_details', function ($query) use ($course_category_code) {
                $query->whereIn('minor_classification_id', $course_category_code);
            });
        }

        Log::debug($query->toSql());

        return $query;
    }
  
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reservationByCompletedDate($start, $end)
    {
        return $this->reservations()->whereBetween('completed_date', [ $start, $end ])->get();
    }

    public function hospitalPlans()
    {
        return $this->hasMany(HospitalPlan::class);
    }

    public function hospitalPlanByDate($date)
    {
        return $this->hospitalPlans()->where([
            ['from','<', $date],
            ['to','>', $date]
        ])->first();
    }

}
