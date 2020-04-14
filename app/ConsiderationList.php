<?php

namespace App;

use App\Filters\Filterable;

class ConsiderationList extends SoftDeleteModel
{
    use Filterable;

    protected $fillable = [
        'epark_member_id',
        'hospital_id',
        'course_id',
        'display_kbn',
        'status'
    ];

    protected $guarded = [
        'id',
    ];

    public function hospital()
    {
        return $this->belongsTo('App\Hospital');
    }

    public function contract_informations()
    {
        return $this->belongsTo('App\ContractInformation', 'hospital_id', 'hospital_id');
    }

    public function course()
    {
        return $this->belongsTo('App\Course', 'course_id', 'id');
    }
}
