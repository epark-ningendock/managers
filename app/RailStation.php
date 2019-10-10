<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RailStation extends Model
{
    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'rail_station';

    public function rails()
    {
        return $this->belongsTo('App\Rail');
    }
    public function station()
    {
        return $this->belongsTo('App\Station');
    }

    /** バリデーションrule */
    private $_rules = array(
        'rail_id' => 'required|integer',
    );

    /** エラーメッセージリスト */
    private $_errors;
    // エラーメッセージ
    private $_messages = [
        'rail_id.required' => '{A-00020}・・・駅番号',
        'rail_id.integer'  => '{A-00021}・・・駅番号',
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
