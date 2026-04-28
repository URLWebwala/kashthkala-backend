<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeoSettings extends BaseModel
{
    use HasFactory;

    protected $table = 'seo';

    const TABLE_NAME = 'seo';
    const SINGLE_NAME = 'SEO Settings';

    const ID = 'id';
    const META_TITLE = 'meta_title';
    const META_DESCRIPTION = 'meta_description';
    const META_KEYWORDS = 'meta_keywords';
    const SLUG = 'slug';
    const CANONICAL_URL = 'canonical_url';
    const ROBOTS = 'robots';
    const NOINDEX = 'noindex';
    const NOFOLLOW = 'nofollow';
    const LANGUAGE = 'language';
    const WEBSITE_H1 = 'website_h1';
    const CONTENT = 'content';
    const OG_TITLE = 'og_title';
    const OG_DESCRIPTION = 'og_description';
    const OG_IMAGE = 'og_image';
    const OG_URL = 'og_url';
    const OG_TYPE = 'og_type';
    const OG_LOCALE = 'og_locale';
    const TWITTER_CARD = 'twitter_card';
    const TWITTER_TITLE = 'twitter_title';
    const TWITTER_DESCRIPTION = 'twitter_description';
    const TWITTER_IMAGE = 'twitter_image';
    const TWITTER_SITE = 'twitter_site';
    const TWITTER_CREATOR = 'twitter_creator';
    const SCHEMA_JSON = 'schema_json';
    const PAGE_PRIORITY = 'page_priority';
    const CHANGEFREQ = 'changefreq';
    const META_AUTHOR = 'meta_author';
    const PAGE_TITLE = 'page_title';
    const PAGE_NAME = 'page_name';

    protected $fillable = [
        self::META_TITLE,
        self::META_DESCRIPTION,
        self::META_KEYWORDS,
        self::SLUG,
        self::CANONICAL_URL,
        self::ROBOTS,
        self::NOINDEX,
        self::NOFOLLOW,
        self::LANGUAGE,
        self::WEBSITE_H1,
        self::CONTENT,
        self::OG_TITLE,
        self::OG_DESCRIPTION,
        self::OG_IMAGE,
        self::OG_URL,
        self::OG_TYPE,
        self::OG_LOCALE,
        self::TWITTER_CARD,
        self::TWITTER_TITLE,
        self::TWITTER_DESCRIPTION,
        self::TWITTER_IMAGE,
        self::TWITTER_SITE,
        self::TWITTER_CREATOR,
        self::SCHEMA_JSON,
        self::PAGE_PRIORITY,
        self::CHANGEFREQ,
        self::META_AUTHOR,
        self::PAGE_TITLE,
        self::PAGE_NAME,
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
            'name' => self::META_TITLE,
            'label' => 'Meta Title',
            'value' => self::META_TITLE,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::META_DESCRIPTION,
            'label' => 'Meta Description',
            'value' => self::META_DESCRIPTION,
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
        ],
        [
            'name' => self::PAGE_TITLE,
            'label' => 'Page Title',
            'value' => self::PAGE_TITLE,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::PAGE_NAME,
            'label' => 'Page Name',
            'value' => self::PAGE_NAME,
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
        ]
    ];

    protected $casts = [
        self::CREATED_AT    => 'datetime',
        self::UPDATED_AT    => 'datetime',
        self::DELETED_AT    => 'datetime',
    ];
}
