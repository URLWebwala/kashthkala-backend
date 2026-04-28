<?php

namespace App\Http\Resources\Settings;

use App\Models\Settings\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialMediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            SocialMedia::ID,
            SocialMedia::WHATSAPP_URL,
            SocialMedia::FACEBOOK_URL,
            SocialMedia::TWITTER_URL,
            SocialMedia::INSTAGRAM_URL,
            SocialMedia::LINKEDIN_URL,
            SocialMedia::YOUTUBE_URL,
            SocialMedia::WHATSAPP_ICON,
            SocialMedia::FACEBOOK_ICON,
            SocialMedia::TWITTER_ICON,
            SocialMedia::INSTAGRAM_ICON,
            SocialMedia::LINKEDIN_ICON,
            SocialMedia::YOUTUBE_ICON,
            SocialMedia::MOBILE,
            SocialMedia::EMAIL,
            SocialMedia::ADDRESS
        ]);

        return $element;
    }
}
