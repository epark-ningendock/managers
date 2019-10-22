<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'es_code',
        'railway_company_id',
        'name',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsToMany
     */
    public function stations(): BelongsToMany
    {
        return $this->belongsToMany(Station::class);
    }

//    /**
//     * @return BelongsToMany
//     */
//    public function prefectures(): BelongsToMany
//    {
//        return $this->belongsTo('App\Prefecture');
//    }

    public function prefecture_rail()
    {
        return $this->hasOne('App\PrefectureRail', 'rail_id');
    }
    public function rail_station()
    {
        return $this->hasMany('App\RailStation', 'rail_id');
    }

    /** バリデーションrule */
    private $_rules = array(
        'es_code' => 'required|integer|min:0',
        'name' => 'required|max:50',
        'railway_company_id' => 'required|integer|min:0',
    );

    /** エラーメッセージリスト */
    private $_errors;
    // エラーメッセージ
    private $_messages = [
        'es_code.required' => '{A-00020}・・・駅すぱあとコード',
        'es_code.integer'  => '{A-00021}・・・駅すぱあとコード',
        'es_code.min' => '{A-00022}・・・駅すぱあとコード',
        'Name.required' => '{A-00020}・・・路線名',
        'Name.max' => '{A-00022}・・・路線名',
        'railway_company_id.required' => '{A-00020}・・・企業番号',
        'railway_company_id.integer'  => '{A-00021}・・・企業番号',
        'railway_company_id.min' => '{A-00022}・・・企業番号',
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
