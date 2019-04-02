<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffAuth extends BaseModel
{
  /**
  * このスタッフを所有するstaff_authを取得
  */
  public function staff()
  {
    return $this->belongsTo('App\Staff');
  }
}
