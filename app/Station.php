<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'es_code',
        'prefecture_id',
        'name',
        'kana',
        'longitude',
        'latitude',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsToMany
     */
    public function rails(): BelongsToMany
    {
        return $this->belongsToMany(Rail::class);
    }

    /**
     * 路線名、駅名、アクセス要素取得
     * Station Model+路線名
     * @param  駅番号 $id
     * @return 路線名、駅名
     */
    static function getStations($rails, $stations, $accesses)
    {
        $returnData = array();
        for ($i = 0; $i < count($rails); $i++) {
            if (isset($rails[$i])) {
                $r =  Rail::select(['name'])->find($rails[$i]);
                $returnData[$i]['rail_line'] = $r['name'];
            }
            if (isset($stations[$i])) {
                $s =  Station::select(['name'])->find($stations[$i]);
                $returnData[$i]['station'] = $s['name'];
            } else {
                $returnData[$i]['station'] = '';
            }
            if (isset($accesses[$i])) {
                $returnData[$i]['access'] =  $accesses[$i];
            } else {
                $returnData[$i]['access'] =  '';
            }

        }
        return $returnData;
    }

    /** バリデーションrule */
    private $_rules = array(
        'es_code' => 'required|integer|min:0',
        'prefecture_id' => 'required|integer',
        'name' => 'required|max:100',
        'kana' => 'max:100',
        'longitude' => 'max:20',
        'latitude' => 'max:20',
    );

    /** エラーメッセージリスト */
    private $_errors;
    // エラーメッセージ
    private $_messages = [
        'name.required' => '{A-00020}・・・駅名',
        'name.max' => '{A-00022}・・・駅名',
        'es_code.required' => '{A-00020}・・・駅すぱあとコード',
        'es_code.integer'  => '{A-00021}・・・駅すぱあとコード',
        'es_code.min' => '{A-00022}・・・駅すぱあとコード',
        'prefecture_id.required' => '{A-00020}・・・都道府県番号',
        'prefecture_id.integer'  => '{A-00021}・・・都道府県番号',
        'longitude.max' => '{A-00022}・・・経度',
        'latitude.max' => '{A-00022}・・・緯度',

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
