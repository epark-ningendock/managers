<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RailwayCompany extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'es_code',
        'name',
        'status',
        'created_at',
        'updated_at',
    ];

    /** バリデーションrule */
    private $_rules = array(
        'es_code' => 'required|integer|min:0',
        'name' => 'required|max:100',
    );

    /** エラーメッセージリスト */
    private $_errors;
    // エラーメッセージ
    private $_messages = [
        'es_code.required' => '{A-00020}・・・駅すぱあとコード',
        'es_code.integer'  => '{A-00021}・・・駅すぱあとコード',
        'es_code.min' => '{A-00022}・・・駅すぱあとコード',
        'Name.required' => '{A-00020}・・・鉄道会社名',
        'Name.max' => '{A-00022}・・・鉄道会社名',
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
