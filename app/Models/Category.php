<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Category extends BaseModel
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $table = self::TABLE_NAME;

    const TABLE_NAME = 'category';

    const SINGLE_NAME = 'Category';

    const ID = 'id';

    const CATEGORY_NAME = 'category_name';

    const CATEGORY_IMAGE = 'category_image';

    const CATEGORY_ICON = 'category_icon';

    const CATEGORY_SLUG = 'category_slug';

    const IS_COMMING = 'is_comming';

    protected $fillable = [
        self::CATEGORY_NAME,
        self::CATEGORY_IMAGE,
        self::CATEGORY_ICON,
        self::CATEGORY_SLUG,
        self::IS_COMMING,
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
            'name' => self::CATEGORY_NAME,
            'value' => self::CATEGORY_NAME,
            'column' => self::TABLE_NAME . '.' . self::CATEGORY_NAME,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::CATEGORY_IMAGE,
            'value' => self::CATEGORY_IMAGE,
            'column' => self::TABLE_NAME . '.' . self::CATEGORY_IMAGE,
            'show' => false,
            'sortable' => true,
        ],
        [
            'name' => self::CATEGORY_ICON,
            'value' => self::CATEGORY_ICON,
            'column' => self::TABLE_NAME . '.' . self::CATEGORY_ICON,
            'show' => false,
            'sortable' => true,
        ],
        [
            'name' => self::CATEGORY_SLUG,
            'value' => self::CATEGORY_SLUG,
            'column' => self::TABLE_NAME . '.' . self::CATEGORY_SLUG,
            'show' => false,
            'sortable' => true,
        ],
        [
            'name' => self::CATEGORY_SLUG,
            'value' => self::CATEGORY_SLUG,
            'column' => self::TABLE_NAME . '.' . self::CATEGORY_SLUG,
            'show' => false,
            'sortable' => true,
        ],
        [
            'name' => self::IS_COMMING,
            'value' => self::IS_COMMING,
            'column' => self::TABLE_NAME . '.' . self::IS_COMMING,
            'show' => true,
            'sortable' => true,
        ]
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime',
    ];

}
