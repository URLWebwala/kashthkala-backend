<?php

namespace App\Http\Resources;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Event::ID,
            Event::TITLE,
            Event::CAPTION,
            Event::IMAGE,
            Event::SIZE,
            Event::CREATED_AT,
            Event::UPDATED_AT,
        ], $this[Event::STATUS]);

        $element[Event::IMAGE] = getUploadImgUrl($this[Event::IMAGE]);
        return $element;
    }
}
