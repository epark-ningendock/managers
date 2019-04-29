<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HospitalStaff extends BaseModel
{
    //TODO remove this and extend SoftDeleteModel if status is added
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

	protected $fillable = [
		'name', 'email', 'login_id', 'password',
	];

}
