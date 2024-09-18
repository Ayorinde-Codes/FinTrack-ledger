<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'client_id' => $this->client_id,
            'client' => new ClientResource($this->whenLoaded('client')),
            'avatar' => $this->avatar,
            'status' => $this->status,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'phone' => $this->phone,
            'address' => $this->address,
            'created_at' => $this->created_at,
        ];
    }
}
