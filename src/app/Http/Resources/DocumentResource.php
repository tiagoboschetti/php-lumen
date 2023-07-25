<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * @param \Laravel\Lumen\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        if (!$this->resource ?? null) {
            return [];
        }

        return [
            'type' => $this->type,
            'numer' => $this->number,
        ];
    }
}