<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Product::TABLE;
    }

    public function modelQuery()
    {
        return Product::query();
    }

    public function query($paginate = false)
    {
        $selections = [
            $this->tableName() . '.*',
        ];

        $model = $this->modelQuery()->select($selections)->with('images', 'category', 'subCategory');

        if (request('search')) {
            $model->where(function ($query) {
                $search = request('search');
                $query->orWhere($this->tableName() . '.' . Product::NAME, 'like', "%{$search}%")
                      ->orWhere($this->tableName() . '.' . Product::SKU, 'like', "%{$search}%");
            });
        }

        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . Product::STATUS, request('status'));
        }

        if (request('category_id')) {
            $model->where($this->tableName() . '.' . Product::CATEGORY_ID, request('category_id'));
        }

        if (request('sub_category_id')) {
            $model->where($this->tableName() . '.' . Product::SUB_CATEGORY_ID, request('sub_category_id'));
        }

        if ($paginate) {
            if (request('limit') && request('limit') !== 'All') {
                $start = (request('page') - 1) * request('limit');
                $model->offset($start)->limit(request('limit'));
            }
        }
        return $model;
    }

    public function listing()
    {
        return [$this->query(true)->get(), $this->query()->count()];
    }

    public function getInputs()
    {
        $inputs = request()->only([
            Product::ID,
            Product::NAME,
            Product::SLUG,
            Product::SKU,
            Product::CATEGORY_ID,
            Product::SUB_CATEGORY_ID,
            Product::BRAND,
            Product::STATUS,
            Product::VISIBILITY,
            Product::SHORT_DESCRIPTION,
            Product::FULL_DESCRIPTION,
            Product::PRICE,
            Product::SALE_PRICE,
            Product::STOCK_QUANTITY,
            Product::STOCK_STATUS,
            Product::ALLOW_BACKORDERS,
        ]);

        // Map SEO fields if they come in nested 'seo' array (from frontend)
        if (request()->has('seo')) {
            $seo = request()->input('seo');
            if (isset($seo['meta_title'])) $inputs[Product::META_TITLE] = $seo['meta_title'];
            if (isset($seo['meta_description'])) $inputs[Product::META_DESCRIPTION] = $seo['meta_description'];
            if (isset($seo['keywords'])) {
                $inputs[Product::KEYWORDS] = is_array($seo['keywords']) ? implode(',', $seo['keywords']) : $seo['keywords'];
            }
        } else {
            // Also check flat inputs just in case
            if (request()->has('meta_title')) $inputs[Product::META_TITLE] = request()->input('meta_title');
            if (request()->has('meta_description')) $inputs[Product::META_DESCRIPTION] = request()->input('meta_description');
            if (request()->has('keywords')) {
                $keywords = request()->input('keywords');
                $inputs[Product::KEYWORDS] = is_array($keywords) ? implode(',', $keywords) : $keywords;
            }
        }

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Product::ID);
        }

        return Product::where(Product::ID, $id)->first();
    }

    public function store($inputs): Product
    {
        $product = Product::create($inputs);
        $this->handleImages($product);
        return $product;
    }

    public function update(Product $model, $inputs): Product
    {
        $model->update($inputs);
        $this->handleImages($model);
        return $model;
    }

    protected function handleImages(Product $product)
    {
        $primaryIndex = request()->input('primary_image_index', 0);
        $primaryImageId = request()->input('primary_image_id');

        // Handle existing images / deletions
        if (request()->has('existing_images')) {
            $existingImageIds = explode(',', request()->input('existing_images'));
            
            // Remove images that are NOT in the existing_images list
            $imagesToDelete = ProductImage::where('product_id', $product->id)
                ->whereNotIn('id', $existingImageIds)
                ->get();
            
            foreach ($imagesToDelete as $image) {
                deleteFile($image->image_path);
                $image->delete();
            }

            // Update primary status for existing images
            if ($primaryImageId) {
                ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
                ProductImage::where('id', $primaryImageId)->update(['is_primary' => true]);
            }
        }

        // Handle new image files
        if (request()->hasFile('images')) {
            $images = request()->file('images');
            foreach ($images as $index => $image) {
                $path = uploadFile($image, 'products');
                
                $isPrimary = false;
                // If primaryIndex matches this new image index
                if (!$primaryImageId && $index == $primaryIndex) {
                    $isPrimary = true;
                    // Reset other primary images if we are setting a new one as primary
                    ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
                }

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary
                ]);
            }
        }

        // Ensure at least one image is primary if any images exist
        if (!ProductImage::where('product_id', $product->id)->where('is_primary', true)->exists()) {
            $firstImage = ProductImage::where('product_id', $product->id)->first();
            if ($firstImage) {
                $firstImage->update(['is_primary' => true]);
            }
        }
    }

    public function getExportQuery($request)
    {
        $query = $this->modelQuery();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where(Product::NAME, 'like', "%{$search}%")
                  ->orWhere(Product::SKU, 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where(Product::STATUS, $request->status);
        }

        return $query;
    }

    public function getActiveProducts()
    {
        return $this->modelQuery()->where(Product::STATUS, Product::STATUS_ACTIVE)->with('images', 'category', 'subCategory')->get();
    }

    public function findBySlug($slug)
    {
        return $this->modelQuery()->where(Product::SLUG, $slug)->with('images', 'category', 'subCategory')->first();
    }
}
