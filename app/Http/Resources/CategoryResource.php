<?php

namespace App\Http\Resources;

use App\Lib\Api;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $categoryElement = createElements($this, [
            Category::ID,
            Category::CATEGORY_NAME,
            Category::CATEGORY_SLUG,
            Category::IS_COMMING,
            Category::CREATED_AT,
            Category::UPDATED_AT,
        ], $this[Category::STATUS]);

        $categoryElement[Category::CATEGORY_IMAGE] = getUploadImgUrl($this[Category::CATEGORY_IMAGE]);
        $categoryElement[Category::CATEGORY_ICON] = getUploadImgUrl($this[Category::CATEGORY_ICON]);

        return $categoryElement;
    }
}
