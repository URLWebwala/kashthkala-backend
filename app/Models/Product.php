<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    const TABLE = 'products';
    const ID = 'id';
    const NAME = 'name';
    const SLUG = 'slug';
    const SKU = 'sku';
    const CATEGORY_ID = 'category_id';
    const SUB_CATEGORY_ID = 'sub_category_id';
    const BRAND = 'brand';
    const STATUS = 'status';
    const VISIBILITY = 'visibility';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    const VISIBILITY_PUBLIC = 1;
    const VISIBILITY_PRIVATE = 2;
    const SHORT_DESCRIPTION = 'short_description';
    const FULL_DESCRIPTION = 'full_description';
    const PRICE = 'price';
    const SALE_PRICE = 'sale_price';
    const STOCK_QUANTITY = 'stock_quantity';
    const STOCK_STATUS = 'stock_status';
    const ALLOW_BACKORDERS = 'allow_backorders';
    const META_TITLE = 'meta_title';
    const META_DESCRIPTION = 'meta_description';
    const KEYWORDS = 'keywords';

    protected $fillable = [
        self::NAME,
        self::SLUG,
        self::SKU,
        self::CATEGORY_ID,
        self::SUB_CATEGORY_ID,
        self::BRAND,
        self::STATUS,
        self::VISIBILITY,
        self::SHORT_DESCRIPTION,
        self::FULL_DESCRIPTION,
        self::PRICE,
        self::SALE_PRICE,
        self::STOCK_QUANTITY,
        self::STOCK_STATUS,
        self::ALLOW_BACKORDERS,
        self::META_TITLE,
        self::META_DESCRIPTION,
        self::KEYWORDS,
    ];

    const COLUMN = [
        [
            'name' => self::ID,
            'label' => 'ID',
            'value' => self::ID,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::NAME,
            'label' => 'Name',
            'value' => self::NAME,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::SKU,
            'label' => 'SKU',
            'value' => self::SKU,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::PRICE,
            'label' => 'Price',
            'value' => self::PRICE,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::STOCK_QUANTITY,
            'label' => 'Stock',
            'value' => self::STOCK_QUANTITY,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::STATUS,
            'label' => 'Status',
            'value' => self::STATUS,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::CREATED_AT,
            'label' => 'Created At',
            'value' => self::CREATED_AT,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::PRICE => 'decimal:2',
        self::SALE_PRICE => 'decimal:2',
        self::STOCK_QUANTITY => 'integer',
        self::ALLOW_BACKORDERS => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, self::CATEGORY_ID);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, self::SUB_CATEGORY_ID);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
