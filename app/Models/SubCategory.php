<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SubCategory extends BaseModel
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = self::TABLE_NAME;

    const TABLE_NAME = 'sub_category';

    const SINGLE_NAME = 'SubCategory';

    const ID = 'id';

    const CATEGORY_ID = 'category_id';

    const SUBCATEGORY_NAME = 'subcategory_name';

    const SUBCATEGORY_IMAGE = 'subcategory_image';

    const SUBCATEGORY_ICON = 'subcategory_icon';

    const SUBCATEGORY_SLUG = 'subcategory_slug';

    protected $fillable = [
        self::CATEGORY_ID,
        self::SUBCATEGORY_NAME,
        self::SUBCATEGORY_IMAGE,
        self::SUBCATEGORY_ICON,
        self::SUBCATEGORY_SLUG,
    ];

    const COLUMN = [
        [
            'name' => self::ID,
            'value' => self::ID,
            'column' => self::TABLE_NAME . '.' . self::ID,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::SUBCATEGORY_NAME,
            'value' => self::SUBCATEGORY_NAME,
            'column' => self::TABLE_NAME . '.' . self::SUBCATEGORY_NAME,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::SUBCATEGORY_IMAGE,
            'value' => self::SUBCATEGORY_IMAGE,
            'column' => self::TABLE_NAME . '.' . self::SUBCATEGORY_IMAGE,
            'show' => false,
            'sortable' => true,
        ],
        [
            'name' => self::SUBCATEGORY_ICON,
            'value' => self::SUBCATEGORY_ICON,
            'column' => self::TABLE_NAME . '.' . self::SUBCATEGORY_ICON,
            'show' => false,
            'sortable' => true,
        ],
        [
            'name' => self::SUBCATEGORY_SLUG,
            'value' => self::SUBCATEGORY_SLUG,
            'column' => self::TABLE_NAME . '.' . self::SUBCATEGORY_SLUG,
            'show' => false,
            'sortable' => true,
        ],
        [
            'name' => self::SUBCATEGORY_SLUG,
            'value' => self::SUBCATEGORY_SLUG,
            'column' => self::TABLE_NAME . '.' . self::SUBCATEGORY_SLUG,
            'show' => false,
            'sortable' => true,
        ],
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, self::CATEGORY_ID, Category::ID);
    }
}
