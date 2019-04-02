<?php

namespace App;

use BenSampo\Enum\Enum;

class Staff extends BaseModel
{
  // Larvelの設定だと、staffの複数形はstaffなので、規約に合っていないが、
  // facility_staffsテーブルと粒度を揃えるために、カスタムテーブルを使用し、staffsで扱えるようにする
  protected $table = 'staffs';
  protected $fillable = [
    'name', 'login_id', 'password',
  ];

  /**
  * ユーザーに関連する電話レコードを取得
  */
  public function staff_auth()
  {
    return $this->hasOne('App\StaffAuth');
  }
}
