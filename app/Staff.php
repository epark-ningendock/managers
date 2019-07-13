<?php

namespace App;

use App\Enums\Authority;
use App\Enums\Permission;
use App\Enums\StaffStatus;
use App\Helpers\EnumTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use EnumTrait;

    protected $table = 'staffs';
    protected $fillable = [
        'name',
        'login_id',
        'password',
        'authority',
        'email',
        'status',
        'department_id'
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
