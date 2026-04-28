<?php

namespace App\Http\Resources;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Blog::ID,
            Blog::TITLE,
            Blog::CONTENT,
            Blog::IMAGE,
            Blog::AUTHOR_NAME,
            Blog::BLOG_CATEGORY_ID,
            Blog::SLUG,
            Blog::VISIBILITY,
            Blog::PUBLISHED_AT,
            Blog::META_TITLE,
            Blog::META_KEYWORD,
            Blog::META_DESCRIPTION,
            Blog::TOTAL_VIEW,
            Blog::CREATED_AT,
            Blog::UPDATED_AT,
        ], $this[Blog::STATUS]);

        $element[Blog::IMAGE] = getUploadImgUrl($this[Blog::IMAGE]);

        if ($this->blogCategory) {
            $element['category'] = new BlogCategoryResource($this->blogCategory);
        } else {
            $element['category'] = null;
        }

        return $element;
    }
}
