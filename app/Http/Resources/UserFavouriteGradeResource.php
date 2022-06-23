<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFavouriteGradeResource extends JsonResource
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
        $userFavourite = auth()->user()->userFavouriteGrade()->with('grade')->get();
        foreach ($userFavourite as $userFavourites){
            if($userFavourites->grade_id == $this->id){
                $hello = 'Seleted';
            }
        }
        return [
            'Seleted' => $hello,
            'id' => $this->id ,
            'name' => $this->name,
             'image' => setImage(null)
        ];
    }
}
