<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' =>     $this->id,
            'name'      =>($this->name == null) ? "User" : $this->name,
            'email'      =>$this->email,
            'phone'      =>$this->phone,
            'gender'      =>$this->gender,
            'status' => (int)$this->status,
            'photo' =>    setImage($this->photo , 'user'),
            'Profile_Strength'=>(int)$this->profile_strengt,
        ];
    }
}
