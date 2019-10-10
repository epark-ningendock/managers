<?php
namespace App\Http\Resources;
use App\Hospital;
use Illuminate\Http\Resources\Json\Resource;

class StationResource extends Resource
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
           'stations_no'=> $this->station->id ?? '',
           'stations_name' => $this->station->name ?? '',
           'count'=> isset($this->station->id) ? Hospital::where('station1', $this->station->id)
           ->orWhere('station2', $this->station->id)
           ->orWhere('station3', $this->station->id)
           ->orWhere('station4', $this->station->id)
           ->orWhere('station5', $this->station->id)
           ->count() : 0,
        ];     
    }
} 