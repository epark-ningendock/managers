<?php

namespace App;

use App\Enums\Gender;
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
        'first_name',
        'family_name',
        'family_name_kana',
        'first_name_kana',
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
        'recall_count',
        'epark_member_id',
        'hospital_id',
    ];

    protected $guarded = [
        'id',
    ];

    protected $enums = [
        'sex' => Gender::class
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
        return $this->family_name . ' ' . $this->first_name;
    }

    public function getNameKanaAttribute()
    {
        return $this->family_name_kana . ' ' . $this->first_name_kana;
    }

    public function hospitals()
    {
        return $this->HasMany('App\Hospital');
    }

    public function prefecture()
    {
        return $this->belongsTo('App\Prefecture');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    private function getMostParent(Customer $customer)
    {
        if (is_null($customer->parent_customer_id)) {
            return $customer;

        }
        return $this->getMostParent($customer->parent);
    }

    public function getParentCustomerAttribute()
    {
        return $this->getMostParent($this);
    }

    public function parent()
    {
        return $this->belongsTo(Customer::class, 'parent_customer_id')
            ->withTrashed();
    }

}
