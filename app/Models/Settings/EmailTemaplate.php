<?php

namespace App\Models\Settings;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailTemaplate extends BaseModel
{
    use HasFactory;

    protected $table = 'email_templates';

    const TABLE = 'email_templates';
    const ID = 'id';
    const NAME = 'name';
    const FROM_EMAIL = 'from_email';
    const SUBJECT = 'subject';
    const BODY = 'body';

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
            'name' => self::FROM_EMAIL,
            'label' => 'From Email',
            'value' => self::FROM_EMAIL,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::SUBJECT,
            'label' => 'Subject',
            'value' => self::SUBJECT,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::BODY,
            'label' => 'Body',
            'value' => self::BODY,
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
        self::FROM_EMAIL,
        self::SUBJECT,
        self::BODY,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];
}
