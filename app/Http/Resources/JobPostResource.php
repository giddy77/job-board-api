<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'salary_min' => number_format($this->salary_min, 2, '.', ''),
            'salary_max' => number_format($this->salary_max, 2, '.', ''),
            'requirements' => $this->requirements,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'company' => new CompanyResource($this->whenLoaded('company')), //only fetch when loaded
        ];
    }
}
