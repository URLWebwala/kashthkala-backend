<?php

namespace App\Http\Resources;

use App\Models\Teams;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Teams::ID,
            Teams::NAME,
            Teams::ROLE,
            Teams::PHONE,
            Teams::EMAIL,
            Teams::DESCRIPTION,
            Teams::FACEBOOK_LINK,
            Teams::TWITTER_LINK,
            Teams::LINKEDIN_LINK,
            Teams::INSTAGRAM_LINK,
        ]);

        $element[Teams::IMAGE] = getUploadImgUrl($this[Teams::IMAGE]);
        return $element;
    }
}
