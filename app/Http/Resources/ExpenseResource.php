<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'id' => $this->id,
                'user_id' => $this->client_id,
                'client_id' => $this->client_id,
                'expense_category' => $this->expense_category,
                'amount' => $this->amount,
                'receipt' => $this->receipt,
                'client' => new ClientResource($this->whenLoaded('client')),
                'user' => new UserResource($this->whenLoaded('user')),
            ];
    }
}
