<?php

namespace App\Models;


class Testinomial extends BaseModel
{
    protected $table = 'testinomial';

    const TABLE = 'testinomial';

    const CLIENT_NAME = 'client_name';
    const RATING = 'rating';
    const DESIGNATION = 'designation';
    const STATES = 'states';
    const IMAGE = 'image';
    const STATUS = 'status';

    protected $fillable = [
        self::CLIENT_NAME,
        self::RATING,
        self::DESIGNATION,
        self::STATES,
        self::IMAGE,
        self::STATUS
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
            'name' => self::CLIENT_NAME,
            'label' => 'Client Name',
            'value' => self::CLIENT_NAME,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::RATING,
            'label' => 'Rating',
            'value' => self::RATING,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::DESIGNATION,
            'label' => 'Designation',
            'value' => self::DESIGNATION,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::STATES,
            'label' => 'States',
            'value' => self::STATES,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::IMAGE,
            'label' => 'Image',
            'value' => self::IMAGE,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];
}
