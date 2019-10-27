<?php
namespace App\Http\Resources;
use App\Enums\Status;
use App\Hospital;
use App\Rail;
use App\Station;
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
                'count'=>Hospital::where('prefecture_id', $this['pref']->code)
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
                    ->count(),
                'stations' => $this->createStation($rail)
            ];

            array_push($results, $result);

        }

        return $results;
    }

    private function createStation(Rail $rail) {

        $stations = Station::join('rail_station', 'rail_station.station_id', 'stations.id')
            ->join('rails', function ($join) {
                $join->on('rails.id', '=', 'rail_station.rail_id')
                    ->where('rails.status', Status::VALID);
            })
            ->where('stations.status', Status::VALID)
            ->get();

        if (!$stations) {
            return [];
        }

        $results = [];
        foreach ($stations as $station) {
            $result = ['station_no' => $station->id,
            'station_name' => $station->name,
            'count' => Hospital::where('prefecture_id', $station->prefecture_id)->count()];

            array_merge($results, $result);
        }

        return $results;
    }
}