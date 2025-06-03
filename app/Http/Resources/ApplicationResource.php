<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'job'         => new JobListResource($this->whenLoaded('job')),
            'user_id'     => $this->user_id,
            'cover_letter'=> $this->cover_letter,
            'status'      => $this->status,
            'created_at'  => $this->created_at?->toIso8601String(),
        ];
    }
}
