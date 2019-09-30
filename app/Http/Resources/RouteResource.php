<?php
namespace App\Http\Resources;
use App\Hospital;
use Illuminate\Http\Resources\Json\JsonResource;
class RouteResource extends JsonResource
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
                'pref_no'=>$this->prefecture_rails[0]->prefecture->id,
                'pref_name'=>$this->prefecture_rails[0]->prefecture->name,
                'count'=>$this->prefecture_rails[0]->prefecture->hospitals->count(),
                'rails'=>[
                    'rail_no'=>$this->id,
                    'rail_name'=>$this->name,
                    'count'=>Hospital::where('rail1',$this->id)
                    ->orWhere('rail2', $this->id)
                    ->orWhere('rail3', $this->id)
                    ->orWhere('rail4', $this->id)
                    ->orWhere('rail5', $this->id)
                    ->count(),
                    'stations'=> StationResource::collection($this->rail_station)                     
                ]
            ]           
        ];     
    }
}