<?php

namespace App;

use App\Enums\CalendarDisplay;
use App\Helpers\EnumTrait;
use Reshadman\OptimisticLocking\OptimisticLocking;

class Calendar extends SoftDeleteModel
{
    use EnumTrait, OptimisticLocking;

    protected $fillable = [ 'name', 'hospital_id', 'is_calendar_display', 'hospital_id', 'auto_update_flg', 'auto_update_start_date', 'auto_update_end_date', 'lock_version', 'created_at', 'updated_at' ];

    protected $enums = [ 'is_calendar_display' => CalendarDisplay::class ];

    public function courses()
    {
        return $this->hasMany('App\Course')->orderBy('order');
    }

    public function calendar_days()
    {
        return $this->hasMany('App\CalendarDay');
    }

    public function calendar_base_wakus() {
        return $this->hasOne('App\CalendarBaseWaku');
    }

    public function hospital()
    {
        return $this->belongsTo('App\Hospital');
    }
}
