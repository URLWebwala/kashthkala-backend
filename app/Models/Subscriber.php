<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscriber extends BaseModel
{
    use HasFactory;

    protected $table = 'subscribers';

    const TABLE = 'subscribers';
    const ID = 'id';
    const EMAIL = 'email';
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
            'name' => self::EMAIL,
            'label' => 'Email Address',
            'value' => self::EMAIL,
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
            'label' => 'Subscribed At',
            'value' => self::CREATED_AT,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
    ];

    protected $fillable = [
        self::EMAIL,
        self::STATUS,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];
}
