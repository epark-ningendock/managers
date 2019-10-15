<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewDetail extends SoftDeleteModel
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_categorie_id',
        'question',
        'answer',
        'order',
    ];

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function scopeInterviewOrder($query)
    {
        return $query->orderBy('order', 'asc');
    }

}
