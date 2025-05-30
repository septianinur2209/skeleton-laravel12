<?php

namespace App\Http\Resources\Setting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'picture' => $this->picture,

            // timestamp
            'created_by' => $this->created_by->createdBy->name ?? 'Superadmin',
            'updated_by' => $this->updated_by->updatedBy->name ?? 'Superadmin',
            'created_at' => $this->created_at ? date('d M Y H:i:s', strtotime($this->created_at)) : null,
            'updated_at' => $this->updated_at ? date('d M Y H:i:s', strtotime($this->updated_at)) : null,
        ];
    }
}
