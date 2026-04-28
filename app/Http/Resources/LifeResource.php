<?php

namespace App\Http\Resources;

use App\Models\Life;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LifeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Life::ID,
            Life::TITLE,
            Life::CAPTION,
            Life::IMAGE,
            Life::SIZE,
            Life::CREATED_AT,
            Life::UPDATED_AT,
        ], $this[Life::STATUS]);

        $element[Life::IMAGE] = getUploadImgUrl($this[Life::IMAGE]);
        return $element;
    }
}
