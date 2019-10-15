<?php

namespace App;

use Illuminate\Support\Carbon;

class MailHistory extends SoftDeleteModel
{
    protected $fillable = [
        'email',
        'sent_datetime',
        'sender_name',
        'sender_address',
        'title',
        'contents',
        'customer_id'
    ];

    protected $dates = [ 'sent_datetime' ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function($model) {
            $model->sent_datetime = Carbon::now();
        });
    }
}
