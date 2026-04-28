<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends BaseModel
{
    use HasFactory;

    protected $table = 'clients';

    const TABLE = 'clients';
    const ID = 'id';
    const CLIENT_NAME = 'client_name';
    const IMAGE = 'image';
    const WEBURL = 'weburl';
    const EMAIL = 'email';
    const PHONE = 'phone';

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
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::IMAGE,
            'label' => 'Image',
            'value' => self::IMAGE,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::WEBURL,
            'label' => 'Web URL',
            'value' => self::WEBURL,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::PHONE,
            'label' => 'Phone',
            'value' => self::PHONE,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::EMAIL,
            'label' => 'Email',
            'value' => self::EMAIL,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
    ];

    protected $fillable = [
        self::CLIENT_NAME,
        self::IMAGE,
        self::WEBURL,
        self::EMAIL,
        self::PHONE,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];
}
