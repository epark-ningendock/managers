<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Foundation\Auth\User as Authenticatable;

class HospitalStaff extends Authenticatable
{
    //TODO remove this and extend SoftDeleteModel if status is added
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'hospital_staffs';

    protected $fillable = [
        'name', 'email', 'login_id', 'password','remember_token', 'hospital_id', 'reset_token_digest', 'reset_sent_at'
    ];

    public function contract_information()
    {
        return $this->hasOne('App\ContractInformation');
    }
}
