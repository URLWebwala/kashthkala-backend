<?php

namespace App\Http\Resources;

use App\Models\SeoSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            SeoSettings::ID,
            SeoSettings::META_TITLE,
            SeoSettings::META_DESCRIPTION,
            SeoSettings::META_KEYWORDS,
            SeoSettings::SLUG,
            SeoSettings::CANONICAL_URL,
            SeoSettings::ROBOTS,
            SeoSettings::NOINDEX,
            SeoSettings::NOFOLLOW,
            SeoSettings::LANGUAGE,
            SeoSettings::WEBSITE_H1,
            SeoSettings::CONTENT,
            SeoSettings::OG_TITLE,
            SeoSettings::OG_DESCRIPTION,
            SeoSettings::OG_URL,
            SeoSettings::OG_TYPE,
            SeoSettings::OG_LOCALE,
            SeoSettings::TWITTER_CARD,
            SeoSettings::TWITTER_TITLE,
            SeoSettings::TWITTER_DESCRIPTION,
            SeoSettings::TWITTER_SITE,
            SeoSettings::TWITTER_CREATOR,
            SeoSettings::SCHEMA_JSON,
            SeoSettings::PAGE_PRIORITY,
            SeoSettings::CHANGEFREQ,
            SeoSettings::META_AUTHOR,
            SeoSettings::PAGE_TITLE,
            SeoSettings::PAGE_NAME,
            SeoSettings::CREATED_AT,
            SeoSettings::UPDATED_AT,
        ], $this[SeoSettings::STATUS]);

        $element[SeoSettings::OG_IMAGE] = getUploadImgUrl($this[SeoSettings::OG_IMAGE]);
        $element[SeoSettings::TWITTER_IMAGE] = getUploadImgUrl($this[SeoSettings::TWITTER_IMAGE]);
        return $element;
    }
}
