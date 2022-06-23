<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFavouriteUniversityResource extends JsonResource
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
        $userFavourite = auth()->user()->userFavouriteUniversities()->with('universities')->get();
        foreach ($userFavourite as $userFavourites){
            if($userFavourites->university_id == $this->id){
                $hello = 'Seleted';
            }
        }
        return [
            'Seleted' => $hello,
            'id' =>  $this->id,
            'name' => $this->name,
            'image' => setImage($this->logo)
        ];
    }
}
