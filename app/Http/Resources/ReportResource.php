<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
                'client_id' => $this->client_id,
                'report_type' => $this->report_type,
                'data' => $this->data,
                'client' => new ClientResource($this->whenLoaded('client')),
                'generated_at' => $this->generated_at,
                'created_at' => $this->created_at,
            ];
    }
}
