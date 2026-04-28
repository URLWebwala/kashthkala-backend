<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expert extends BaseModel
{
    use HasFactory;

    protected $table = 'expert';

    const TABLE = 'expert';
    const ID = 'id';
    const NAME = 'name';
    const DESCRIPTION = 'description';
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
            'name' => self::DESCRIPTION,
            'label' => 'Description',
            'value' => self::DESCRIPTION,
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
        ]
    ];

    protected $fillable = [
        self::NAME,
        self::DESCRIPTION,
        self::IMAGE,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];
}
