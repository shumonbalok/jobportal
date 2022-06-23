<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class UserResorce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        // $data = $this->load('roles.permissions');
        $permissions = $this->getAllPermissions();
        $collection =  $permissions->map(function($permission){
            return $permission->name;
        });


        return [
            'id' =>     $this->id,
            'name'      =>($this->name == null) ? "User" : $this->name,
            'email'      =>$this->email,
            'phone'      =>$this->phone,
            'Balance'   =>(string)$this->balance,
            'photo' =>    setImage($this->photo , 'user'),
            'Profile_Strength'=> (int)$this->profile_strength,
            'Basic_Info'=>!$this->basicInfo ? null : BasicInfoResource::make($this->whenLoaded('basicInfo') ),
            'Experience'=> !$this->experience ? null : ExperienceResource::collection($this->whenLoaded('experience')),
            'Skills'    =>!$this->skill ? null : SkillResource::collection($this->whenLoaded('skill')),
            'Address'   =>!$this->address? null : AddressResorce::collection($this->whenLoaded('address')->load('district','upazila','postOffice')),
            'Education_Under_Graduate' =>!$this->graduates? null: GraduateResource::collection($this->whenLoaded('graduates')->load('examination','board','passingYear','group')),
            'Education_Post_Graduate' =>!$this->higherGraduates? null: HigherGraduateResource::collection($this->whenLoaded('higherGraduates')),
            'documents'=>DocumentResource::make($this->allphoto),
            'permission' =>  $collection,
            'roles' =>    $this->roles,
            'photosub' => PhotoSubResource::collection($this->allphotosub) ?? null,
            'type' => (int)$this->type
        ];
    }
}
