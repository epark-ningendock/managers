<?php

namespace App;

use App\Enums\Authority;
use App\Enums\Permission;
use App\Enums\StaffStatus;
use App\Helpers\EnumTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use EnumTrait;
    use SoftDeletes;

    protected $table = 'staffs';
    protected $fillable = [
        'name',
        'login_id',
        'password',
        'authority',
        'email',
        'remember_token',
        'status',
        'department_id',
        'first_login_at',
        'created_at',
        'updated_at',
    ];

    protected $enums = [
        'status' => StaffStatus::class,
        'authority' => Authority::class
    ];

    public function staff_auth()
    {
        return $this->hasOne('App\StaffAuth');
    }

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    /**
     * Checking permission
     * @param $function_name
     * @param Permission $permission
     * @return int
     */
    public function hasPermission($function_name, Permission $permission)
    {
        return $this->staff_auth[$function_name] & $permission->getPermissionBit() == $permission->getPermissionBit();
    }
}
