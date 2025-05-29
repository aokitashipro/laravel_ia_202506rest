<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SportingGoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'category'       => $this->category,
            'brand'          => $this->brand,
            'price'          => number_format($this->price), // "12,000" のように整形
            'weight_kg'      => $this->weight ? $this->weight . ' kg' : null,
            'is_available'   => $this->is_available ? '販売中' : '在庫切れ',
            'stock'          => $this->stock_quantity,
            'release_date'   => $this->release_date->format('Y-m-d'),
            'color'          => $this->color ?? '指定なし',
            'sku'            => $this->sku,
            'created_at'     => $this->created_at->diffForHumans(), // "2日前" のように
            ];
    }
}
