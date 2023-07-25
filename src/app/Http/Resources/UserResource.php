<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
            'document' => new DocumentResource($this->document),
        ];
    }
}