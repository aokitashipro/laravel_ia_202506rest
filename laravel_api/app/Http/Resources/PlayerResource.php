<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TeamResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'team' => new TeamResource($this->whenLoaded('team')),
            'positions' => $this->whenLoaded('positions', fn () =>
                $this->positions->map(fn ($pos) => [
                    'id'   => $pos->id,
                    'name' => $pos->name,
                    'skill'         => (int) $pos->pivot->skill,
                    'assigned_from' => $pos->pivot->assigned_from,
                ])
            ),
        ];
    }
}
