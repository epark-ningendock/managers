<?php

namespace App\Http\Resources;

class HospitalContentsResource extends HospitalContentBaseResource
{
    /**
     * 医療機関コンテンツ into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return collect([])
            ->put('status', 0)
            ->put('no', $this->id)
            ->put('hospital_code', $this->contract_information->code)
            ->merge(parent::baseCollections())->toArray();
    }
}
