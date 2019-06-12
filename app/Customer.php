<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends BaseModel
{

    use Filterable, SoftDeletes;


    protected $casts = [
        'birthday'  => 'date:Y-m-d',
    ];

    protected $fillable = [
        'parent_customer_id', 'member_number', 'registration_card_number', 'name_seri', 'name_mei', 'name_kana_seri', 'name_kana_mei', 'tel', 'email', 'postcode', 'prefecture_id', 'address1', 'address2', 'sex', 'birthday', 'memo', 'claim_count', 'recall_count'
    ];

    protected $guarded = [
        'id',
    ];


    public function getName()
    {
        return $this->name_seri . ' '. $this->name_mei;
    }

    public function hospitals()
    {
        return $this->HasMany('App\Hospital');
    }

    public function reservations() {
        return $this->hasMany(Reservation::class);
    }
}
