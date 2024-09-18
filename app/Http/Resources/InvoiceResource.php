<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'invoice_number' => $this->invoice_number,
            'customer_name' => $this->customer_name,
            'user_id' => $this->client_id,
            'client_id' => $this->client_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'client' => new ClientResource($this->whenLoaded('client')),
            'user' => new UserResource($this->whenLoaded('user')),
            'due_date' => $this->due_date,
            'recurrence' => $this->recurrence,
            'created_at' => $this->created_at,
        ];
    }
}
