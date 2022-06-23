<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFavouriteDepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $hello = 'UnSeleted';
        $userFavourite = auth()->user()->userFavouriteJobs()->with('departments')->get();
        foreach ($userFavourite as $userFavourites){
            if($userFavourites->department_id == $this->id){
                $hello = 'Seleted';
            }
        }
        return [
            'Seleted' => $hello,
            'id' => $this->id ,
            'name' => $this->name,
            'image' => setImage($this->image)
        ];
    }
}
