<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;
class ToDo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'details' => $this->details, 
            'dueTime' => $this->dueTime, 
            'isChecked' => $this->is_checked,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }

}