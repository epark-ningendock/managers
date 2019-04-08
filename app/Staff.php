<?php

namespace App;

use App\Enums\Authority;
use App\Enums\Status;
use App\Helpers\EnumTrait;
use BenSampo\Enum\Enum;

class Staff extends BaseModel
{
    use EnumTrait;

    // Larvelの設定だと、staffの複数形はstaffなので、規約に合っていないが、
    // facility_staffsテーブルと粒度を揃えるために、カスタムテーブルを使用し、staffsで扱えるようにする
    protected $table = 'staffs';
    protected $fillable = [
        'name', 'login_id', 'password', 'authority', 'email', 'status'
    ];

    protected $enums = [
        'status' => Status::class,
        'authority' => Authority::class
    ];

    /**
     * ユーザーに関連する電話レコードを取得
     */
    public function staff_auth()
    {
        return $this->hasOne('App\StaffAuth');
    }
}
