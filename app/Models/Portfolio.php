<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Portfolio extends BaseModel
{
    use HasFactory;

    protected $table = 'portfolio';

    const TABLE = 'portfolio';
    const ID = 'id';
    const TITLE = 'title';
    const WEBSITE_URL = 'website_url';
    const ADMIN_URL = 'admin_url';
    const ANDROID_APP_URL = 'android_app_url';
    const IOS_APP_URL = 'ios_app_url';
    const DESCRIPTION = 'description';
    const VISIBLE_ON_SITE = 'visible_on_site';
    const FEATURE_PROJECT = 'feature_project';
    const IMAGE = 'image';
    const ICON = 'icon';
    const PORTFOLIO_CATEGORY_ID = 'portfolio_category_id';

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
            'name' => self::IMAGE,
            'label' => 'Image',
            'value' => self::IMAGE,
            'show' => true,
            'sortable' => false,
            'export' => false,
        ],
        [
            'name' => self::PORTFOLIO_CATEGORY_ID,
            'label' => 'Category',
            'value' => self::PORTFOLIO_CATEGORY_ID,
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
        ],
        [
            'name' => self::VISIBLE_ON_SITE,
            'label' => 'Visible On Site',
            'value' => self::VISIBLE_ON_SITE,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::FEATURE_PROJECT,
            'label' => 'Feature Project',
            'value' => self::FEATURE_PROJECT,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::WEBSITE_URL,
            'label' => 'Website URL',
            'value' => self::WEBSITE_URL,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::ADMIN_URL,
            'label' => 'Admin URL',
            'value' => self::ADMIN_URL,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::ANDROID_APP_URL,
            'label' => 'Android App URL',
            'value' => self::ANDROID_APP_URL,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::IOS_APP_URL,
            'label' => 'iOS App URL',
            'value' => self::IOS_APP_URL,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ]
    ];

    protected $fillable = [
        self::TITLE,
        self::WEBSITE_URL,
        self::ADMIN_URL,
        self::ANDROID_APP_URL,
        self::IOS_APP_URL,
        self::IMAGE,
        self::ICON,
        self::DESCRIPTION,
        self::VISIBLE_ON_SITE,
        self::FEATURE_PROJECT,
        self::PORTFOLIO_CATEGORY_ID,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];

    public function portfolioCategory()
    {
        return $this->belongsTo(PortfolioCategory::class, self::PORTFOLIO_CATEGORY_ID);
    }
}
