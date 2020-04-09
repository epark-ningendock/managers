<?php
namespace App\Http\Resources;
use App\Enums\Status;
use App\Hospital;
use App\Rail;
use App\RailStation;
use App\Station;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;

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
                'count'=>$this['pref']->hospital_count,
                'rails'=> collect($this->createRailData())->map(function ($c) {
                    return (object)[
                        'rail_no' => $c['rail_no'],
                        // 予約可否配列の積をとり、0になればどこかに「受付可能(0)」あり
                        'rail_name' => $c['rail_name'],
                        'count' => $c['count'],
                        'stations' => $c['stations']
                    ];
                })->toArray(),
            ]
        ];
    }

    private function createRailData() {
        $results = [];
        foreach ($this['routes'] as $rail) {
            $result = [
                'rail_no'=>$rail->id,
                'rail_name'=>$rail->name,
                'count'=>$rail->hospital_count,
                'stations' => $this->createStation($rail)
            ];

            array_push($results, $result);

        }

        return $results;
    }

    private function createStation(Rail $rail) {

        $select = [
            DB::raw("stations.id AS id "),
            DB::raw("stations.name AS name "),
            DB::raw("COUNT(hospitals.id) AS hospital_count ")
        ];

        $query = RailStation::query();
        $query->select($select);
        $query->join('stations', 'rail_station.station_id', 'stations.id');
        $query->leftJoin('hospitals' , function ($join) {
            $join->on('hospitals.prefecture_id', '=', 'stations.prefecture_id')
                ->where(function ($q) {
                    $q->orWhere('hospitals.station1', '=', 'stations.id');
                    $q->orWhere('hospitals.station2', '=', 'stations.id');
                    $q->orWhere('hospitals.station3', '=', 'stations.id');
                    $q->orWhere('hospitals.station4', '=', 'stations.id');
                    $q->orWhere('hospitals.station5', '=', 'stations.id');
                });

        });
        $query->where('rail_station.rail_id', $rail->id);
        $query->groupBy('stations.id', 'stations.name');
        $stations = $query->get();

        if (!$stations) {
            return [];
        }

        $results = [];
        foreach ($stations as $station) {
            $result = ['station_no' => $station->id,
            'station_name' => $station->name,
            'count' => $station->hospital_count];

            $results[] = $result;
        }

        return $results;
    }
}