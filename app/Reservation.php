<?php

namespace App;

class Reservation extends BaseModel
{
    protected $dates = [
        'completed_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static $channel = [
        '0' => 'Tel',
        '1' => 'Web',
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

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function scopeByRequest($query, $request)
    {
        if (strlen($request->claim_month)) {
            $query->where('claim_month', $request->claim_month);
        }

        if (isset($request->reservation_date_start) && isset($request->reservation_date_end)) {
            $query->whereBetween('reservation_date', [$request->reservation_date_start, $request->reservation_date_end]);
        }

        return $query;
    }
}
