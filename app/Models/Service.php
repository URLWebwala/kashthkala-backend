<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends BaseModel
{
    use HasFactory;

    protected $table = 'services';

    const TABLE = 'services';
    const ID = 'id';
    const SERVICE_TITLE = 'service_title';
    const ICON = 'icon';
    const SHORT_DESCRIPTION = 'short_description';
    const LONG_DESCRIPTION = 'long_description';
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
            'name' => self::SERVICE_TITLE,
            'label' => 'Service Title',
            'value' => self::SERVICE_TITLE,
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
    ];

    protected $fillable = [
        self::SERVICE_TITLE,
        self::ICON,
        self::SHORT_DESCRIPTION,
        self::LONG_DESCRIPTION,
        self::STATUS,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];
}
