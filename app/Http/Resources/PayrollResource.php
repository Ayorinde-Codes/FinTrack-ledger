<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollResource extends JsonResource
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
            'client_id' => $this->client_id,
            'user_id' => $this->user_id,
            'salary' => $this->salary,
            'payment_date' => $this->payment_date,
            'taxes' => $this->taxes,
            'client' => new ClientResource($this->whenLoaded('client')),
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at
        ];
    }
}
