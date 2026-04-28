<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'category_id' => $this->category_id,
            'category_name' => $this->category ? $this->category->category_name : null,
            'sub_category_id' => $this->sub_category_id,
            'sub_category_name' => $this->subCategory ? $this->subCategory->subcategory_name : null,
            'brand' => $this->brand,
            'status' => $this->status,
            'visibility' => $this->visibility,
            'short_description' => $this->short_description,
            'full_description' => $this->full_description,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'stock_quantity' => $this->stock_quantity,
            'stock_status' => $this->stock_status,
            'allow_backorders' => $this->allow_backorders,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'keywords' => $this->keywords,
            'images' => $this->images->map(function($image) {
                return [
                    'id' => $image->id,
                    'url' => getUploadImgUrl($image->image_path),
                    'path' => $image->image_path,
                    'is_primary' => (bool)$image->is_primary,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
