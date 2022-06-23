<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return
        [
            'id'                        => $this->id,
            'pp_photo'                  => setImage($this?->pp_photos),
            'signature_photos'          => setImage($this?->signature_photos),
            'nid_photos'                => setImage($this?->nid_photos),
            'passport_photos'           => setImage($this?->passport_photos),
            'birth_certificate_photos'  => setImage($this?->birth_certificate_photos)
        ]
        ;
    }
}
