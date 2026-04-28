<?php

namespace App\Http\Resources;

use App\Models\Hero;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeroResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $heroElement = createElements($this, [
            Hero::ID,
            Hero::TAGLINE,
            Hero::MAIN_TITLE,
            Hero::DESCRIPTION,
            Hero::PRIMARY_BUTTON_TEXT,
            Hero::PRIMARY_LINK,
            Hero::SECONDARY_BUTTON_TEXT,
            Hero::SECONDARY_LINK,
            Hero::DISPLAY_ORDER,
            Hero::CREATED_AT,
            Hero::UPDATED_AT,
        ], $this[Hero::STATUS]);

        $heroElement[Hero::IMAGE] = getUploadImgUrl($this[Hero::IMAGE]);

        return $heroElement;
    }
}
