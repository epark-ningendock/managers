<?php

namespace App\Http\Resources;

use App\Enums\CalendarDisplay;
use App\Enums\Status;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

use App\Reservation;
use App\Holiday;
use App\Enums\WebReception;

class CoursesFramesResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->baseCollections()->toArray();
    }

    /**
     * 検査コース共通情報
     *
     * @return Illuminate\Support\Collection
     */
    protected function baseCollections()
    {
        return collect([
            'course_no' => $this->id,
            'course_code' => $this->code,
            'course_name' => $this->name,
            'course_url' => $this->createURL() . "/detail_hospital/" . $this->contract_information->code . "/detail/" . $this->code . ".html",
            'flg_price' => $this->is_price,
            'price' => $this->price,
            'flg_price_memo' => $this->is_price_memo,
            'price_memo' => $this->price_memo ?? '',
            'pre_account_price' => $this->pre_account_price,
            'category_type' => $this->getCategoryType(),
            'all_calender' => new DailyCalendarResource($this),
        ]);
    }

    /**
     * @return array
     */
    private function getCategoryType() {

        $results = [];
        foreach ($this->course_details as $detail) {
            if ($detail->major_classification_id == 13
                && $detail->select_status == 1
                && $detail->status == '1') {
                $result = ['id' => $detail->minor_classification_id, 'title' => $detail->minor_classification->name];
                $results[] = $result;
            }
        }

        return $results;
    }

    private function createURL() {
        return (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'];
    }
}
