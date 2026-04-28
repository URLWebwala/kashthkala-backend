<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'todos';

    const TABLE_NAME = 'todos';
    const SINGLE_NAME = 'Todo';

    const ID = 'id';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const USER_ID = 'user_id';
    const IS_COMPLETED = 'is_completed';
    const PRIORITY = 'priority';
    const DUE_DATE = 'due_date';

    protected $fillable = [
        self::TITLE,
        self::DESCRIPTION,
        self::USER_ID,
        self::IS_COMPLETED,
        self::PRIORITY,
        self::DUE_DATE,
    ];

    protected $casts = [
        self::IS_COMPLETED => 'boolean',
        self::DUE_DATE     => 'datetime',
        self::CREATED_AT   => 'datetime',
        self::UPDATED_AT   => 'datetime',
        self::DELETED_AT   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, self::USER_ID);
    }

    const COLUMN = [
        [
            'name' => self::ID,
            'label' => 'ID',
            'value' => self::ID,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::TITLE,
            'label' => 'Title',
            'value' => self::TITLE,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::DESCRIPTION,
            'label' => 'Description',
            'value' => self::DESCRIPTION,
            'show' => true,
            'sortable' => false,
        ],
        [
            'name' => self::PRIORITY,
            'label' => 'Priority',
            'value' => self::PRIORITY,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::IS_COMPLETED,
            'label' => 'Completed',
            'value' => self::IS_COMPLETED,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::DUE_DATE,
            'label' => 'Due Date',
            'value' => self::DUE_DATE,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::STATUS,
            'label' => 'Status',
            'value' => self::STATUS,
            'show' => true,
            'sortable' => true,
        ],
    ];
}
