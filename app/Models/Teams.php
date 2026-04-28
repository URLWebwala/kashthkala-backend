<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teams extends BaseModel
{
    use HasFactory;

    protected $table = 'teams';

    const TABLE = 'teams';
    const ID = 'id';
    const NAME = 'name';
    const ROLE = 'role';
    const PHONE = 'phone';
    const EMAIL = 'email';
    const DESCRIPTION = 'description';
    const FACEBOOK_LINK = 'facebook_link';
    const TWITTER_LINK = 'twitter_link';
    const LINKEDIN_LINK = 'linkedin_link';
    const INSTAGRAM_LINK = 'instagram_link';
    const IMAGE = 'image';

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
            'name' => self::ROLE,
            'label' => 'Role',
            'value' => self::ROLE,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::PHONE,
            'label' => 'Phone',
            'value' => self::PHONE,
            'show' => true,
            'sortable' => false,
            'export' => false,
        ],
        [
            'name' => self::EMAIL,
            'label' => 'Email',
            'value' => self::EMAIL,
            'show' => true,
            'sortable' => false,
            'export' => false,
        ],
        [
            'name' => self::DESCRIPTION,
            'label' => 'Description',
            'value' => self::DESCRIPTION,
            'show' => true,
            'sortable' => false,
            'export' => false,
        ],
        [
            'name' => self::FACEBOOK_LINK,
            'label' => 'Facebook Link',
            'value' => self::FACEBOOK_LINK,
            'show' => true,
            'sortable' => false,
            'export' => false,
        ],
        [
            'name' => self::TWITTER_LINK,
            'label' => 'Twitter Link',
            'value' => self::TWITTER_LINK,
            'show' => true,
            'sortable' => false,
            'export' => false,
        ],
        [
            'name' => self::LINKEDIN_LINK,
            'label' => 'LinkedIn Link',
            'value' => self::LINKEDIN_LINK,
            'show' => true,
            'sortable' => false,
            'export' => false,
        ],
        [
            'name' => self::INSTAGRAM_LINK,
            'label' => 'Instagram Link',
            'value' => self::INSTAGRAM_LINK,
            'show' => true,
            'sortable' => false,
            'export' => false,
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
        ]
    ];

    protected $fillable = [
        self::NAME,
        self::ROLE,
        self::PHONE,
        self::EMAIL,
        self::DESCRIPTION,
        self::FACEBOOK_LINK,
        self::TWITTER_LINK,
        self::LINKEDIN_LINK,
        self::INSTAGRAM_LINK,
        self::IMAGE,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];
}
