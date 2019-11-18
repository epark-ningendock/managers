<?php

namespace App\Http\Resources;

use App\Enums\CalendarDisplay;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

use App\Enums\WebReception;

use Log;

class CourseIndexBaseResource extends Resource
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
                'course_no' => $this->id,
                'course_code' => $this->code,
                'course_name' => $this->name,
                'course_url' => $this->createURL() . "/detail_hospital/" . $this->hospital->contract_information->code . "/detail/" . $this->code . ".html",
                'web_reception' => $this->createReception(),
                'course_flg_category' => $this->is_category,
                'course_img' => $this->getFlowImagePath($this->hospital->hospital_categories, 1),
                'flow_pc_img' => $this->getFlowImagePath($this->hospital->hospital_categories, 2),
                'flow_sp_img' => $this->getFlowImagePath($this->hospital->hospital_categories, 3),
                'course_point' => $this->course_point,
                'course_notice' => $this->course_notice,
                'course_cancel' => $this->course_cancel,
                'flg_price' => $this->is_price,
                'price' => $this->price,
                'flg_price_memo' => $this->is_price_memo,
                'price_memo' => $this->price_memo,
                'price_2' => $this->regular_price,
                'price_3' => $this->discounted_price,
                'tax_class' => $this->tax_class_id,
                'pre_account_price' => $this->pre_account_price,
                'flg_local_payment' => $this->is_local_payment,
                'flg_pre_account' => $this->is_pre_account,
                'auto_calc_application' => $this->auto_calc_application,
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
            return isset($c->image_order) && intval($c->image_order) == $image_location_number;
        });
        $files = $categories->map(function ($c) {
            if(isset($c->hospital_image->path)) {
                return $c->hospital_image->path ;
            }
        })->toArray();
        return $files[0] ?? '';
    }

    /**
     * @return int
     */
    private function createReception()
    {
        if ($this->web_reception == strval(WebReception::NOT_ACCEPT)) {
            return WebReception::NOT_ACCEPT;
        }

        $target = Carbon::today();
        if (($this->publish_start_date != null &&
                $this->publish_start_date > $target)
            || ($this->publish_end_date != null &&
                $this->publish_end_date < $target)) {
            return WebReception::NOT_ACCEPT;
        }

        if (isset($this->calendar) && $this->calendar->is_calendar_display == strval(CalendarDisplay::HIDE)) {
            return WebReception::ACCEPT_HIDE_CALENDAR;
        }

        return WebReception::ACCEPT;
    }

    private function createURL() {
        return (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'];
    }
}
