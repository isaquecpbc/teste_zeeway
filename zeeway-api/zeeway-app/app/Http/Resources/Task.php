<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Task extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'user_id'           => $this->user_id,
            'user_name'         => $this->user?->name,
            'title'             => $this->title, // Adicionado novo campo
            'description'       => $this->description,
            'status'            => $this->status,
            'due_date'          => $this->due_date ? $this->due_date->format('d/m/Y') : null,
            'updated_at'        => $this->updated_at ? $this->updated_at->format('H:i d/m/Y') : $this->created_at->format('H:i d/m/Y'),
            'deleted_at'        => $this->deleted_at ? $this->deleted_at->format('H:i d/m/Y') : null,
        ];
    }
}
