<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

use App\Enums\WebReception;

use Log;

class CourseBaseResource extends Resource
{
    /**
     * 検査コース基本情報 resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->baseCollections()->toArray();
    }

    /**
     * 検査コース基本情報
     *
     * @return Illuminate\Support\Collection
     */
    protected function baseCollections()
    {
        return collect(
            [
                'course_no' => $this[0]->id,
                'course_code' => $this[0]->code,
                'course_name' => $this[0]->name,
                'course_url' => $this->createURL() . "/detail_hospital/" . $this[0]->hospital->contract_information->code . "/detail/" . $this[0]->code . ".html",
                'web_reception' => $this->createReception(),
                'course_flg_category' => $this[0]->is_category,
                'course_img' => $this->getFlowImagePath($this[0]->hospital->hospital_categories, 1),
                'flow_pc_img' => $this->getFlowImagePath($this[0]->hospital->hospital_categories, 2),
                'flow_sp_img' => $this->getFlowImagePath($this[0]->hospital->hospital_categories, 3),
                'course_point' => $this[0]->course_point,
                'course_notice' => $this[0]->course_notice,
                'course_cancel' => $this[0]->course_cancel,
                'flg_price' => $this[0]->is_price,
                'price' => $this[0]->price,
                'flg_price_memo' => $this[0]->is_price_memo,
                'price_memo' => $this[0]->price_memo,
                'price_2' => $this[0]->regular_price,
                'price_3' => $this[0]->discounted_price,
                'tax_class' => $this[0]->tax_class_id,
                'pre_account_price' => $this[0]->pre_account_price,
                'flg_local_payment' => $this[0]->is_local_payment,
                'flg_pre_account' => $this[0]->is_pre_account,
                'auto_calc_application' => $this[0]->auto_calc_application,
            ]
        );
    }

    /**
     * コース画像取得
     * 
     * @param  医療機関カテゴリ
     * @return サブメイン画像
     */
    private function getFlowImagepath($hospital_categories, $image_location_number) : string
    {
        $categories = $hospital_categories->filter(function ($c) use ($image_location_number) {
            return isset($c->image_order) && intval($c->image_order->image_location_number) == $image_location_number;
        });
        $files = $categories->map(function ($c) {
            if(isset($c->hospital_image->path) && isset($c->hospital_image->name) && isset($c->hospital_image->extension)) {
                return $this->createURL() . $c->hospital_image->path . $c->hospital_image->name . $c->hospital_image->extension;
            }
        })->toArray();
        return $files[0] ?? '';
    }

    private function createReception()
    {

        if ($this[0]->web_reception == '0') {
            return '0';
        }

        $target = Carbon::today();
        if (($this[0]->publish_start_date <= $target)
            && ($this[0]->publish_end_date >= $target)) {
            return '1';
        }

        return '0';
    }

    private function createURL() {
        return (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'];
    }
}
