<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform one dynamic page into the flattened API format expected by clients.
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'language_id' => $this->language_id,
            'code' => strtolower((string) ($this->language?->code ?? '')),
            'page_title' => $this->page_title,
            'page_content' => $this->page_content,
        ];
    }
}
