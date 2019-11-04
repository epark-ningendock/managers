<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Reshadman\OptimisticLocking\OptimisticLocking;

class KenshinSysCooperation extends SoftDeleteModel
{
    use SoftDeletes, OptimisticLocking;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'];

    protected $fillable = [
        'medical_examination_system_id',
        'app_kbn',
        'api_url',
        'partner_code',
        'hash_key',
        'subscription_key',
        'ip',
        'created_at',
        'updated_at'
        ];

    public function medical_examination_systems()
    {
        return $this->belongsTo('App\MedicalExaminationSystem');
    }
}
