<?php
namespace App\Http\Resources;
use App\Enums\Status;
use App\Hospital;
use Illuminate\Http\Resources\Json\Resource;

class RouteResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'status'=> 0,
            'place'=>[
                'pref_no'=>$this['pref']->code,
                'pref_name'=>$this['pref']->name,
                'count'=>Hospital::join('district_codes', 'district_codes.district_code', 'district_code_id')
                    ->where('district_codes.prefecture_id', $this['pref']->code)
                    ->where('district_codes.status', Status::VALID)
                    ->count(),
                'rails'=> [$this->createRailData()]
            ]
        ];
    }

    private function createRailData() {
        $results = [];
        foreach ($this['routes'] as $rail) {
            $result = [
                'rail_no'=>$rail->id,
                'rail_name'=>$rail->name,
                'count'=>Hospital::where('rail1',$rail->id)
                    ->orWhere('rail2', $rail->id)
                    ->orWhere('rail3', $rail->id)
                    ->orWhere('rail4', $rail->id)
                    ->orWhere('rail5', $rail->id)
                    ->count()
            ];
            array_push($results, $result);

        }

        return $results;
    }
}