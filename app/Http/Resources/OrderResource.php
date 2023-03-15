<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource {
    public static $wrap = 'order';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'client_name' => $this->client_name,
            'client_phone' => $this->client_phone,
            'client_address' => $this->client_address,
            'client_wishes' => $this->client_wishes,
            'status' => $this->status,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//            'deleted_at' => $this->deleted_at,
        ];
    }
}
