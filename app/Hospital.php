<?php

namespace App;

use App\Enums\HplinkContractType;
use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Reshadman\OptimisticLocking\OptimisticLocking;

class Hospital extends Model
{
    use SoftDeletes, OptimisticLocking;
    protected $table = 'hospitals';
    private $kenshin_relation_flg;

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
        'kenshin_sys_hospital_id'

    ];

    protected $enumCasts = [
        'hplink_contract_type' => HplinkContractType::class,
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

    public function hospital_metas()
    {
        return $this->hasOne('App\HospitalMeta');
    }

    public function contract_information()
    {
        return $this->hasOne('App\ContractInformation', 'hospital_id', 'id');
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

    public function hospital_option_plans()
    {
        return $this->hasMany('App\HospitalOptionPlan');
    }

    public function district_code()
    {
        return $this->belongsTo('App\DistrictCode');
    }

    public function medical_treatment_times()
    {
        return $this->hasMany('App\MedicalTreatmentTime');
    }

    public function hospital_plan()
    {
        // DB の構成的には hasMany だが、仕様的に hasOne となったため
        return $this->hasOne('App\HospitalPlan')
            ->withDefault();
    }

    public function kenshin_sys_courses() {
        return $this->hasMany(KenshinSysCourse::class, 'kenshin_sys_hospital_id', 'kenshin_sys_hospital_id');
    }

    /**
     * 医療機関一覧検索
     *
     * @return クエリ
     */
    public function scopeWhereForSearchAPI($query, $request)
    {
        // フリーワード（施設名など）
        if ($request->input('freewords') !== null) {
            $query->whereHas('courses.course_meta_informations', function ($query) use ($request) {
                $query->where('freewords', 'like', '%' . $request->input('freewords') . '%');
            });
        }

        // フリーワード（エリアなど）
        if ($request->input('freewords') !== null) {
            $query->whereHas('courses.course_meta_informations', function ($query) use ($request) {
                $query->where('area_station', 'like', '%' . $request->input('freewords') . '%');
            });
        }

        // フリーワード（施設特徴）
        if ($request->input('freewords') !== null) {
            $query->whereHas('courses.course_meta_informations', function ($query) use ($request) {
                $query->where('hospital_classification', 'like', '%' . $request->input('freewords') . '%');
            });
        }

        // フリーワード（路線）
        if ($request->input('freewords') !== null) {
            $query->whereHas('courses.course_meta_informations', function ($query) use ($request) {
                $query->where('rails', 'like', '%' . $request->input('freewords') . '%');
            });
        }

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
            $districts = explode(',', $district_no);
            $query->whereHas('district_code', function ($query) use ($districts) {
                $query->whereIn('district_code', $districts);
            });
        };

        // 路線コード
        $rail_no = $request->input('rail_no');
        if (isset($rail_no)) {
            $rails = explode(',', $rail_no);
            $query->orWhereIn('rail1', $rails)
                ->orWhereIn('rail2', $rails)
                ->orWhereIn('rail3', $rails)
                ->orWhereIn('rail4', $rails)
                ->orWhereIn('rail5', $rails);
        };

        // 駅コード
        $station_no = $request->input('station_no');
        if (isset($station_no)) {
            $stations = explode(',', $station_no);
            $query->orWhereIn('station1', $stations)
                ->orWhereIn('station2', $stations)
                ->orWhereIn('station3', $stations)
                ->orWhereIn('station4', $stations)
                ->orWhereIn('station5', $stations);
        };

        $from = $request->input('reservation_dt_from');
        $to = $request->input('reservation_dt_to');
        // 受信希望日FROM TO
        if (isset($from) and isset($to)) {
            $query->whereHas('courses.calendar_days', function ($query) use ($from, $to) {
                $query->where('date', '>=', $from)
                    ->where('date', '<=', $to)
                    ->where('is_reservation_acceptance', 0);
            });
        } // 受診希望日FROM
        else {
            if (isset($from) and empty($to)) {
                $query->whereHas('courses.calendar_days', function ($query) use ($from) {
                    $query->where('date', '>=', $from)
                        ->where('is_reservation_acceptance', 0);
                });
            } // 受診希望日TO
            else {
                if (empty($from) and isset($to)) {
                    $query->whereHas('courses.calendar_days', function ($query) use ($to) {
                        $query->where('date', '<=', $to)
                            ->where('is_reservation_acceptance', 0);
                    });
                }
            }
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
                $query->whereIn('minor_classification_id', $hospital_category_code);
                $query->where('select_status', 1);
            });
        }

        // クレジットカード対応
        if ($request->input('site_card') == 1) {
            $query->whereHas('hospital_details', function ($query) {
                $query->where('minor_classification_id', 5);
                $query->whereNotNull('inputstring');
            });
        }
        // 検査コース分類コード
        if ($request->input('course_category_code') !== null) {
            $course_category_code = explode(",", $request->input('course_category_code'));
            $query->whereHas('courses.course_details', function ($query) use ($course_category_code) {
                $query->whereIn('minor_classification_id', $course_category_code);
            });
        }

//        Log::debug($query->toSql());

        return $query;
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reservationByCompletedDate($start, $end)
    {
        return $this->reservations()
            ->whereBetween('reservation_date', [$start, $end])
            ->where('reservation_status', '<>', ReservationStatus::CANCELLED )
            ->get();
    }

    public function hospitalPlans()
    {
        return $this->hasMany(HospitalPlan::class);
    }

    public function hospitalPlanByDate($date)
    {
        return $this->hospitalPlans()->whereDate('from', '<=', $date)
        ->where(function($q) use ($date) {
            $q->whereDate('to', '>=', $date)
                ->orWhere('to', '=', null);
        })->get()->first();
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function hpLinkMonthPrice() {
        if ($this->hplink_contract_type == HplinkContractType::MONTHLY_SUBSCRIPTION) {
            return $this->hplink_price;
        } else {
            return 0;
        }
    }

    public function hospitalOptionPlanPrice($billing_id, $date) {

        $hospital_option_plans = HospitalOptionPlan::with(['option_plan',
            'billing_option_plans' => function ($query) use ($billing_id) {
                $query->where('billing_id', $billing_id);
            }
        ])
        ->whereDate('from', '<=', $date)
            ->where(function($q) use ($date) {
                $q->whereDate('to', '>=', $date)
                    ->orWhere('to', '=', null);
            })
            ->where('hospital_id', $this->id)
            ->get();

        $optionPlanPrice = 0;

        if (!$hospital_option_plans) {
            return $optionPlanPrice;
        }

        foreach ($hospital_option_plans as $hospital_option_plan) {
            $billing_adjustment_price = 0;

            if (isset($hospital_option_plan->billing_option_plans)
                && isset($hospital_option_plan->billing_option_plans->adjustment_price)) {
                $billing_adjustment_price = $hospital_option_plan->billing_option_plans->adjustment_price;
            }
            $optionPlanPrice = $optionPlanPrice
                + $hospital_option_plan->price
                + $billing_adjustment_price;
        }

        return $optionPlanPrice;
    }

    public function hospitalOptionPlan($billing_id, $date) {

        $result = HospitalOptionPlan::with(['option_plan',
            'billing_option_plans' => function ($query) use ($billing_id) {
                $query->where('billing_id', $billing_id);
            }
        ])
            ->whereDate('from', '<=', $date)
            ->where(function($q) use ($date) {
                $q->whereDate('to', '>=', $date)
                    ->orWhere('to', '=', null);
            })
            ->where('hospital_id', $this->id)
            ->get();

        if (count($result) > 0) {
            return $result;
        } else {
            return null;
        }
    }

    public function setKenshinRelation($kenshin_relation_flg, $sex, $birth, $honnin_kbn) {

        if ($this->courses) {
            foreach ($this->courses as $course) {
                $course->kenshin_relation_flg = $kenshin_relation_flg;
                $course->sex = $sex;
                $course->birth = $birth;
                $course->honnin_kbn = $honnin_kbn;
            }
        }

        $this->kenshin_relation_flg = $kenshin_relation_flg;
    }

    public function getKenshinRelationFlg() {
        return $this->kenshin_relation_flg;
    }

    public function getStationInfo() {

        $hospital_metas = $this->hospital_metas;
        $returnData = [];

        for ($i = 1; $i < 6; $i++) {
            if (empty($hospital_metas->{'station' .$i})) {
                continue;
            }
            if (!empty($hospital_metas->{'rail' .$i})) {
                $returnData[$i - 1]['rail_line'] = $hospital_metas->{'rail' .$i};
            } else {
                $returnData[$i - 1]['rail_line'] = '';
            }
            if (!empty($hospital_metas->{'station' .$i})) {
                $returnData[$i - 1]['station'] = $hospital_metas->{'station' .$i};
            } else {
                $returnData[$i - 1]['station'] = '';
            }
            if (!empty($hospital_metas->{'access' .$i})) {
                $returnData[$i - 1]['access'] =  $hospital_metas->{'access' .$i};
            } else {
                $returnData[$i - 1]['access'] =  '';
            }
        }
        return $returnData;
    }
}
