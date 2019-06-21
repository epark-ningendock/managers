<?php

namespace App;

use App\Enums\ReservationStatus;
use App\Enums\PaymentStatus;

class Reservation extends SoftDeleteModel
{
    const HOSPITAL = 1;
    const PC = 2;
    const SP = 3;
    const TEL_API = 4;
    const TEL_PPC = 5;

    protected $dates = [
        'completed_date',
        'created_at',
        'updated_at',
        'deleted_at',
        'reservation_date'
    ];

    public static $channel = [
        '0' => 'Tel',
        '1' => 'Web',
    ];


    protected $enums = [
        'reservation_status' => ReservationStatus::class,
        'payment_status' => PaymentStatus::class
    ];

    public static $is_billable = [
        '0' => '未課金',
        '1' => '課金',
    ];

    public static $english_names = [
        self::HOSPITAL => '院内',
        self::PC => 'PC',
        self::SP => 'スマホ',
        self::TEL_API => '電話予約(API）',
        self::TEL_PPC => '電話予約(PPC)'
    ];

    //todo channelがどういうケースが発生するのか未定なので、とりあえず仮で
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
        return $this->belongsTo('App\Customer');
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
        return $this->belongsTo('App\Course');
    }

    public function reservation_options()
    {
        return $this->hasMany('App\ReservationOption');
    }

    public function scopeByRequest($query, $request)
    {

        if (isset($request->claim_month)) {
            $query->where('claim_month', $request->claim_month);
        } else {
            $query->where('claim_month', Carbon::now()->format('Y/m'));
        }

        if (isset($request->reservation_date_start) && isset($request->reservation_date_end)) {
            $query->whereBetween('reservation_date', [$request->reservation_date_start, $request->reservation_date_end]);
        }

        if (isset($request->completed_date_start) && isset($request->completed_date_end)) {
            $query->whereBetween('completed_date', [$request->completed_date_start, $request->completed_date_end]);
        }

        if (isset($request->customer_id)) {
            $query->where('customer_id', 'LIKE', "%$request->customer_id%");
        }

        if (isset($request->customer_name)) {
            $query->whereHas('Customer', function ($q) use ($request) {
                $q->where('first_name', 'LIKE', "%$request->customer_name%")->orWhere('family_name', 'LIKE', "%$request->customer_name%");
            });
        }

        if (isset($request->hospital_name)) {
            $query->whereHas('Hospital', function ($q) use ($request) {
                $q->where('name', 'LIKE', "%$request->hospital_name%");
            });
        }

        return $query;
    }

    public function getFullNameAttribute() {
        return $this->customer->name_seri.$this->customer->name_mei;
    }
}
