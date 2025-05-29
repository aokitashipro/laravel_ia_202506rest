<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SportingGoodListResource extends JsonResource
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
            'price'          => number_format($this->price), // "12,000" のように整形
            'is_available'   => $this->is_available ? '販売中' : '在庫切れ',
            'created_at'     => $this->created_at->diffForHumans(), // "2日前" のように
            ];
    }
}
