<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    /**
     * Transform one FAQ into the flattened API format expected by clients.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            'language_id' => $this->language_id,
            'code' => strtolower((string) ($this->language?->code ?? '')),
            'question' => $this->question,
            'answer' => $this->answer,
        ];
    }
}
