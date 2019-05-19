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

    // Larvelの設定だと、staffの複数形はstaffなので、規約に合っていないが、
    // facility_staffsテーブルと粒度を揃えるために、カスタムテーブルを使用し、staffsで扱えるようにする
    protected $table = 'staffs';
    protected $fillable = [
        'name', 'login_id', 'password', 'authority', 'email', 'status'
    ];

    protected $enums = [
        'status' => StaffStatus::class,
        'authority' => Authority::class
    ];

    /**
     * ユーザーに関連する電話レコードを取得
     */
    public function staff_auth()
    {
        return $this->hasOne('App\StaffAuth');
    }

    /**
     * Checking permission
     * @param $function_name
     * @param Permission $permission
     * @return int
     */
    public function hasPermission($function_name, Permission $permission) {
        return $this->staff_auth[$function_name] & $permission->getPermissionBit() == $permission->getPermissionBit();
    }
}
