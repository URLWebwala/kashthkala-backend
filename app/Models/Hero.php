<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hero extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = self::TABLE_NAME;

    const TABLE_NAME = 'hero';

    const SINGLE_NAME = 'Hero';

    const ID = 'id';
    const TAGLINE = 'tagline';
    const MAIN_TITLE = 'main_title';
    const DESCRIPTION = 'description';
    const PRIMARY_BUTTON_TEXT = 'primary_button_text';
    const PRIMARY_LINK = 'primary_link';
    const SECONDARY_BUTTON_TEXT = 'secondary_button_text';
    const SECONDARY_LINK = 'secondary_link';
    const IMAGE = 'image';
    const DISPLAY_ORDER = 'display_order';
    const STATUS = 'status';

    protected $fillable = [
        self::TAGLINE,
        self::MAIN_TITLE,
        self::DESCRIPTION,
        self::PRIMARY_BUTTON_TEXT,
        self::PRIMARY_LINK,
        self::SECONDARY_BUTTON_TEXT,
        self::SECONDARY_LINK,
        self::IMAGE,
        self::DISPLAY_ORDER,
        self::STATUS,
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
            'name' => self::MAIN_TITLE,
            'value' => self::MAIN_TITLE,
            'column' => self::TABLE_NAME . '.' . self::MAIN_TITLE,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::IMAGE,
            'value' => self::IMAGE,
            'column' => self::TABLE_NAME . '.' . self::IMAGE,
            'show' => false,
            'sortable' => true,
        ],
        [
            'name' => self::DISPLAY_ORDER,
            'value' => self::DISPLAY_ORDER,
            'column' => self::TABLE_NAME . '.' . self::DISPLAY_ORDER,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::STATUS,
            'value' => self::STATUS,
            'column' => self::TABLE_NAME . '.' . self::STATUS,
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
