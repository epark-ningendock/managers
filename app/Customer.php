<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends BaseModel
{

    use Filterable, SoftDeletes;


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
        'deleted_at',
    ];

    protected $guarded = [
        'id',
    ];

    const MALE = 'M';
    const FEMALE = 'F';

    public static $sex = [
        self::MALE => '男性',
        self::FEMALE => '女性',
    ];

    public function setParentCustomerIdAttribute($value)
    {
        $this->attributes['parent_customer_id'] = ($value == 'NULL' || empty($value)) ? null : $value;
    }

    public function setMemberNumberAttribute($value)
    {
        $this->attributes['member_number'] = ($value == 'NULL' || empty($value)) ? null : $value;
    }


    public function getName()
    {
        return $this->family_name . ' ' . $this->first_name;
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
}
