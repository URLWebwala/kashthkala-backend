<?php

namespace App\Http\Resources;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            BlogCategory::ID,
            BlogCategory::CATEGORY_NAME,
            BlogCategory::SLUG,
            BlogCategory::CREATED_AT,
            BlogCategory::UPDATED_AT,
        ], $this[BlogCategory::STATUS]);

        $element[BlogCategory::IMAGE] = getUploadImgUrl($this[BlogCategory::IMAGE]);
        $element[BlogCategory::ICON] = getUploadImgUrl($this[BlogCategory::ICON]);

        return $element;
    }
}
