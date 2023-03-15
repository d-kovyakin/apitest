<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource {
    public static $wrap = 'products';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'product' => $this->product,
            'price' => $this->price,
        ];
    }
}
