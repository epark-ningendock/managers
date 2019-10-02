<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrefectureRail extends Model
{
    public function prefecture()
    {
        return $this->belongsTo('App\Prefecture');
    }

    /** バリデーションrule */
    private $_rules = array(
        'rail_id' => 'required|integer',
    );
    /** エラーメッセージリスト */
    private $_errors;
    // エラーメッセージ
    private $_messages = [
        'rail_id.required' => '{A-00020}・・・駅すぱあとコード',
        'rail_id.integer'  => '{A-00021}・・・駅すぱあとコード',
    ];

    /**
     * バリデーション
     *
     */
    public function validate($data)
    {
        $v = Validator::make($data, $this->_rules, $this->_messages);
        if ($v->fails()) {
            $this->_errors = $v->errors();
            return false;
        }
        return true;
    }

    public function errors()
    {
        return $this->_errors;
    }
}
