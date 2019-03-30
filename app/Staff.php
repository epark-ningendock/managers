<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
  // Larvelの設定だと、staffの複数形はstaffなので、規約に合っていないが、
  // facility_staffsテーブルと粒度を揃えるために、カスタムテーブルを使用し、staffsで扱えるようにする
  protected $table = 'staffs';
  protected $fillable = [
    'name', 'email', 'login_id', 'password',
  ];
}
