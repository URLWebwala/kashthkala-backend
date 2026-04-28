<?php

namespace App\Http\Resources\Settings;

use App\Models\Settings\Branding;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Branding::ID,
            Branding::WEBSITE_LOGO,
            Branding::WEBSITE_FAVICON,
            Branding::META_FAVICON,
            Branding::APP_FAVICON,
            Branding::CREATED_AT,
            Branding::UPDATED_AT,
        ]);

        $element[Branding::WEBSITE_LOGO] = getUploadImgUrl($this[Branding::WEBSITE_LOGO]);
        $element[Branding::WEBSITE_FAVICON] = getUploadImgUrl($this[Branding::WEBSITE_FAVICON]);
        $element[Branding::META_FAVICON] = getUploadImgUrl($this[Branding::META_FAVICON]);
        $element[Branding::APP_FAVICON] = getUploadImgUrl($this[Branding::APP_FAVICON]);

        return $element;
    }
}
