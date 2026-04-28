<?php

namespace App\Http\Resources;

use App\Models\PortfolioCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            PortfolioCategory::ID,
            PortfolioCategory::CATEGORY_NAME,
            PortfolioCategory::IMAGE,
            PortfolioCategory::SLUG,
            PortfolioCategory::CREATED_AT,
            PortfolioCategory::UPDATED_AT,
        ], $this[PortfolioCategory::STATUS]);

        $element[PortfolioCategory::IMAGE] = getUploadImgUrl($this[PortfolioCategory::IMAGE]);

        return $element;
    }
}
