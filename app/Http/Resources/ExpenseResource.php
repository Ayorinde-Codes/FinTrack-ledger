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
                'user_id' => $this->company_id,
                'company_id' => $this->company_id,
                'expense_category' => $this->expense_category,
                'amount' => $this->amount,
                'receipt' => $this->receipt,
                'company' => new CompanyResource($this->whenLoaded('company')),
                'user' => new UserResource($this->whenLoaded('user')),
            ];
    }
}
