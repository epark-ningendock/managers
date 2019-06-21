<?php

namespace App;

use App\Filters\Filterable;

class Customer extends SoftDeleteModel
{
    use Filterable;


    protected $casts = [
        'birthday' => 'date:Y-m-d',
    ];

    protected $fillable = [
        'parent_customer_id',
        'member_number',
        'registration_card_number',
        'name_seri',
        'name_mei',
        'name_kana_seri',
        'name_kana_mei',
        'tel',
        'email',
        'postcode',
        'prefecture_id',
        'address1',
        'address2',
        'sex',
        'birthday',
        'memo',
        'claim_count',
        'recall_count'
    ];

    protected $guarded = [
        'id',
    ];


    public function setParentCustomerIdAttribute($value)
    {
        $this->attributes['parent_customer_id'] = ($value == 'NULL' || empty($value)) ? null : $value;
    }

    public function setMemberNumberAttribute($value)
    {
        $this->attributes['member_number'] = ($value == 'NULL' || empty($value)) ? null : $value;
    }


    public function getNameAttribute()
    {
        return $this->name_seri . ' ' . $this->name_mei;
    }

    public function hospitals()
    {
        return $this->HasMany('App\Hospital');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
