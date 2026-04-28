<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PortfolioCategory extends BaseModel
{
    use HasFactory;

    protected $table = 'portfolio_category';

    const TABLE = 'portfolio_category';
    const ID = 'id';
    const CATEGORY_NAME = 'category_name';
    const IMAGE = 'image';
    const SLUG = 'slug';
   
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
        ],
        [
            'name' => self::SLUG,
            'label' => 'Slug',
            'value' => self::SLUG,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ]
    ];

    protected $fillable = [
        self::CATEGORY_NAME,
        self::IMAGE,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];
}
