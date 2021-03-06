<?php

namespace App;

use App\Enums\PaymentStatus;
use App\Enums\ReservationStatus;
use App\Enums\TerminalType;
use Carbon\Carbon;
use Reshadman\OptimisticLocking\OptimisticLocking;

class Reservation extends SoftDeleteModel
{
    use OptimisticLocking;

    protected $dates = [
        'completed_date',
        'created_at',
        'updated_at',
        'deleted_at',
        'reservation_date'
    ];

    public static $channel = [
        '2' => 'Tel',
        '1' => 'Web',
    ];


    protected $enums = [
        'reservation_status' => ReservationStatus::class,
        'payment_status' => PaymentStatus::class,
        'terminal_type' => TerminalType::class
    ];

    public static $is_billable = [
        '0' => '未課金',
        '1' => '課金',
    ];

    public static $english_names = [
        TerminalType::HOSPITAL => '院内',
        TerminalType::PC => 'PC',
        TerminalType::SMART_PHONE => 'スマホ',
        TerminalType::PHONE_RESERVATION_API => '電話予約(API）',
        TerminalType::PHONE_RESERVATION_PPC => '電話予約(PPC)'
    ];


    protected $fillable = [
        'hospital_id',
        'course_id',
        'reservation_date',
        'start_time_hour',
        'start_time_min',
        'end_time_hour',
        'end_time_min',
        'channel',
        'reservation_status',
        'completed_date',
        'cancel_date',
        'user_message',
        'site_code',
        'customer_id',
        'epark_member_id',
        'member_number',
        'terminal_type', //need to confirm initial value
        'time_selected',
        'is_repeat', // need to confirm
        'tax_included_price',
        'adjustment_price',
        'tax_rate',
        'second_date',
        'is_choose',
        'campaign_code',
        'tel_timezone',
        'insurance_assoc_id',
        'insurance_assoc',
        'mail_type', //not sure what field need to add
        'cancelled_appoint_code',
        'is_billable',
        'claim_month',
        'is_payment',
        'payment_status',
        'trade_id',
        'order_id',
        'settlement_price',
        'payment_method',
        'cashpo_used_price',
        'amount_unsettled',
        'reservation_memo',
        'todays_memo',
        'internal_memo',
        'acceptance_number',
        'y_uid',
        'applicant_name',
        'applicant_name_kana',
        'applicant_tel',
        'fee_rate',
        'fee',
        'is_free_hp_link',
        'is_health_insurance',
        'payment_date',
        'kenshin_sys_yoyaku_no',
        'cancellation_reason',
        'status'

    ];

    protected $appends = ['tax_excluded_price'];

    public static function getChannel($channel)
    {
        if (array_key_exists($channel, self::$channel)) {
            return self::$channel[$channel];
        }
        return 'その他';
    }

    protected $guarded = [
        'id',
    ];

    protected $table = 'reservations';

    public function hospital()
    {
        return $this->belongsTo('App\Hospital');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer')
            ->withTrashed();
    }

    public function scopeNearestDate($query)
    {
        return $query->orderBy('reservation_date', 'asc');
    }

    public function scopeDescOrder($query)
    {
        return $query->orderBy('reservation_date', 'desc');
    }

    public function course()
    {
        return $this->belongsTo('App\Course')->withTrashed();
    }

    public function reservation_options()
    {
        return $this->hasMany('App\ReservationOption');
    }

    public function reservation_kenshin_sys_options()
    {
        return $this->hasMany('App\ReservationKenshinSysOption');
    }

    public function reservation_answers()
    {
        return $this->hasMany(ReservationAnswer::class);
    }

    public function medical_examination_system()
    {
        return $this->belongsTo('App\MedicalExaminationSystem');
    }

    public function scopeByRequest($query, $request)
    {
        if (isset($request->claim_month)) {
            $query->where('claim_month', $request->claim_month);
        } else {
            $query->where('claim_month', Carbon::now()->format('Y/m'));
        }

        if (isset($request->reservation_date_start) && isset($request->reservation_date_end)) {
            $query->whereBetween('reservation_date',
                [$request->reservation_date_start, $request->reservation_date_end]);
        }

        if (isset($request->completed_date_start) && isset($request->completed_date_end)) {
            $query->whereBetween('completed_date', [$request->completed_date_start, $request->completed_date_end]);
        }

        if (isset($request->customer_id)) {
            $query->where('customer_id', 'LIKE', "%$request->customer_id%");
        }

        if (isset($request->customer_name)) {
            $query->whereHas('Customer', function ($q) use ($request) {
                $q->where('first_name', 'LIKE', "%$request->customer_name%")->orWhere('family_name', 'LIKE',
                    "%$request->customer_name%");
            });
        }

        if (isset($request->hospital_name)) {
            $query->whereHas('Hospital', function ($q) use ($request) {
                $q->where('name', 'LIKE', "%$request->hospital_name%");
            });
        }

        return $query;
    }

    public function getIsRepeatDescAttribute()
    {
        if ($this->is_repeat == '0') {
            return 'はじめて受診する';
        } else {
            if ($this->is_repeat == '1') {
                return '過去に受診あり';
            }
        }
        return '-';
    }

    public function getIsRepresentativeDescAttribute()
    {
        if ($this->is_representative == '0') {
            return '本人以外';
        } else {
            if ($this->is_representative == '1') {
                return '本人';
            }
        }
        return '-';
    }

    /**
     * 既予約数取得
     *
     * @param $request
     * @param $reservation_date
     *
     * @return 取得結果
     */
    public static function getReservationCount($request, $reservation_date, $course_ids)
    {
        return self::where('hospital_id', $request->input('hospital_id'))
            ->whereIn('course_id', $course_ids)
            ->whereIn('reservation_status', [1, 3, 5])
            ->whereDate('reservation_date', $reservation_date)->count();
    }

    /**
     * 既予約情報取得
     *
     * @param $request
     * @param $reservation_date
     *
     * @return 取得結果
     */
    public static function getUpdateTarget($request, $reservation_date)
    {

        $entity = Reservation::with([
            'customer' => function ($query) use ($request) {
                $query->where('email', $request->input('email'));
            }
        ])
            ->where('hospital_id', $request->input('hospital_id'))
            ->where('course_id', $request->input('course_id'))
            ->where('reservation_date', $reservation_date)
            // 1 => 「仮受付」のもの
            ->where('reservation_status', '1')
            // reservation_id 若番のもの１件
            ->first();
        return $entity;
    }

    public function taxIncludedPrice()
    {
        return $this->belongsTo(TaxClass::class);
    }


    public function getTaxExcludedPriceAttribute()
    {
        return (!$this->fee) ? 0 : $this->fee / ($this->tax_rate / 100 + 1);
    }

    public function customerWithTrashed()
    {
        return $this->belongsTo(Customer::class)
            ->withTrashed();
    }
}
