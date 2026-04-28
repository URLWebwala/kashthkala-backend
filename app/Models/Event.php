<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends BaseModel
{
    use HasFactory;

    protected $table = 'event';

    const TABLE = 'event';
    const ID = 'id';
    const TITLE = 'title';
    const CAPTION = 'caption';
    const IMAGE = 'image';
    const SIZE = 'size';

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
            'name' => self::CAPTION,
            'label' => 'Caption',
            'value' => self::CAPTION,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::SIZE,
            'label' => 'Size',
            'value' => self::SIZE,
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
        self::TITLE,
        self::CAPTION,
        self::IMAGE,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];
}
