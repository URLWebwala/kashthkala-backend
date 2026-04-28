<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends BaseModel
{
    use HasFactory;

    protected $table = 'blog';

    const TABLE = 'blog';
    const ID = 'id';
    const TITLE = 'title';
    const SLUG = 'slug';
    const AUTHOR_NAME = 'author_name';
    const BLOG_CATEGORY_ID = 'blog_category_id';
    const VISIBILITY = 'visibility';
    const PUBLISHED_AT = 'published_at';
    const IMAGE = 'image';
    const CONTENT = 'content';
    const META_TITLE = 'meta_title';
    const META_KEYWORD = 'meta_keyword';
    const META_DESCRIPTION = 'meta_description';
    const TOTAL_VIEW = 'total_view';
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
            'name' => self::TITLE,
            'label' => 'Title',
            'value' => self::TITLE,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::AUTHOR_NAME,
            'label' => 'Author',
            'value' => self::AUTHOR_NAME,
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
            'name' => self::SLUG,
            'label' => 'Slug',
            'value' => self::SLUG,
            'show' => false,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::TOTAL_VIEW,
            'label' => 'Total View',
            'value' => self::TOTAL_VIEW,
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
        [
            'name' => self::UPDATED_AT,
            'label' => 'Updated At',
            'value' => self::UPDATED_AT,
            'show' => false,
            'sortable' => true,
            'export' => true,
        ]
    ];

    protected $fillable = [
        self::TITLE,
        self::SLUG,
        self::AUTHOR_NAME,
        self::BLOG_CATEGORY_ID,
        self::VISIBILITY,
        self::PUBLISHED_AT,
        self::IMAGE,
        self::CONTENT,
        self::META_TITLE,
        self::META_KEYWORD,
        self::META_DESCRIPTION,
        self::TOTAL_VIEW,
        self::STATUS,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::PUBLISHED_AT => 'datetime',
    ];

    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class, self::BLOG_CATEGORY_ID);
    }
}
