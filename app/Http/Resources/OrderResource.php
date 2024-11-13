<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'address'  => [
                "city"     => $this->address['city'],
                "street"   => $this->address['street'],
                "district" => $this->address['district'],
            ],
            'price'    => $this->price,
            'currency' => $this->currency
        ];
    }
}
