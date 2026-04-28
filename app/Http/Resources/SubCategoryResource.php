<?php

namespace App\Http\Resources;

use App\Enums\UserTypeEnum;
use App\Lib\Api;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            SubCategory::ID,
            SubCategory::CATEGORY_ID,
            SubCategory::SUBCATEGORY_NAME,
            SubCategory::SUBCATEGORY_SLUG,
            SubCategory::CREATED_AT,
            SubCategory::UPDATED_AT,
        ], $this[SubCategory::STATUS]);

        $element['category'] = $this->whenLoaded('category', function () {
            return createElements($this->category, [
                Category::ID,
                Category::CATEGORY_NAME,
                Category::CATEGORY_SLUG,
            ]);
        });

        $element[SubCategory::SUBCATEGORY_IMAGE] = getUploadImgUrl($this[SubCategory::SUBCATEGORY_IMAGE]);
        $element[SubCategory::SUBCATEGORY_ICON] = getUploadImgUrl($this[SubCategory::SUBCATEGORY_ICON]);

        return $element;
    }
}
