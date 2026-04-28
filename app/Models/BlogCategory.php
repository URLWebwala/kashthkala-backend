<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogCategory extends BaseModel
{
    use HasFactory;

    protected $table = 'blog_category';

    const TABLE = 'blog_category';
    const ID = 'id';
    const CATEGORY_NAME = 'category_name';
    const SLUG = 'slug';
    const IMAGE = 'image';
    const ICON = 'icon';
    const STATUS = 'status';
   
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
            'name' => self::CATEGORY_NAME,
            'label' => 'Category Name',
            'value' => self::CATEGORY_NAME,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::IMAGE,
            'label' => 'Image',
            'value' => self::IMAGE,
            'show' => true,
            'sortable' => false,
            'export' => false,
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
        [
            'name' => self::UPDATED_AT,
            'label' => 'Updated At',
            'value' => self::UPDATED_AT,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ]
    ];

    protected $fillable = [
        self::CATEGORY_NAME,
        self::SLUG,
        self::IMAGE,
        self::ICON,
        self::STATUS,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];
}
