<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionPlan extends SoftDeleteModel
{
    use Filterable, SoftDeletes;

	protected $enums = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'option_plan_name', 'option_plan_price'
    ];

}
