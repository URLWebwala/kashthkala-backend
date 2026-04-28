<?php

namespace App\Http\Resources;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Todo::ID,
            Todo::TITLE,
            Todo::DESCRIPTION,
            Todo::USER_ID,
            Todo::IS_COMPLETED,
            Todo::PRIORITY,
            Todo::DUE_DATE,
        ]);
        return $element;
    }
}
