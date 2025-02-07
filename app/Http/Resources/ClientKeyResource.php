<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'private_key' => $this->private_key,
            'public_key' => $this->public_key,
            'client_id' => $this->client_id,
            'client' => new ClientResource($this->whenLoaded('clients')),
        ];
    }
}
