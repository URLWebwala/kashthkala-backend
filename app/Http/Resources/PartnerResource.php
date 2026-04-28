<?php

namespace App\Http\Resources;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Partner::ID,
            Partner::NAME,
            Partner::DESCRIPTION,
            Partner::IMAGE,
            Partner::LINK,
            Partner::DESIGNATION,
        ], $this[Partner::STATUS]);

        $element[Partner::IMAGE] = getUploadImgUrl($this[Partner::IMAGE]);
        return $element;
    }
}
