<?php

namespace App\Http\Resources;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Portfolio::ID,
            Portfolio::TITLE,
            Portfolio::DESCRIPTION,
            Portfolio::IMAGE,
            Portfolio::PORTFOLIO_CATEGORY_ID,
            Portfolio::WEBSITE_URL,
            Portfolio::ADMIN_URL,
            Portfolio::ANDROID_APP_URL,
            Portfolio::IOS_APP_URL,
            Portfolio::VISIBLE_ON_SITE,
            Portfolio::FEATURE_PROJECT,
            Portfolio::CREATED_AT,
            Portfolio::UPDATED_AT,
        ], $this[Portfolio::STATUS]);

        $element[Portfolio::IMAGE] = getUploadImgUrl($this[Portfolio::IMAGE]);

        if ($this->portfolioCategory) {
            $element['category'] = new PortfolioCategoryResource($this->portfolioCategory);
        } else {
            $element['category'] = null;
        }

        return $element;
    }
}
