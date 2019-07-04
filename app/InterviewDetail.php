<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewDetail extends SoftDeleteModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

}
