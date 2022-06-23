<?php

namespace App\Http\Resources;

use App\Models\Admission;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'id'=> $this->id,
            'transation_number' =>  $this->transation_number ,
            'methods' => $this->methods,
            'balance' => $this->balance,
            'status' => (int)$this->status,
            'users' => $this->users,
            'job' => JobResource::make($this->whenLoaded('jobs')),
            'admission' => AdmissionResource::make($this->whenLoaded('admissions')),
            'payment_status'=> (int)$this->payement_status,
        ];
    }
}
