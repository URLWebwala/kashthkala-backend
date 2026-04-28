<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Newslatter extends BaseModel
{
    use HasFactory;

    protected $table = 'newslatter';

    const TABLE = 'newslatter';
    const ID = 'id';
    const EMAIL = 'email';
    const PHONE = 'phone';
    const SERVICE_ID = 'service_id';

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
            'label' => 'Email',
            'value' => self::EMAIL,
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
            'name' => self::SERVICE_ID,
            'label' => 'Service ID',
            'value' => self::SERVICE_ID,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
    ];

    protected $fillable = [
        self::EMAIL,
        self::PHONE,
        self::SERVICE_ID,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, self::SERVICE_ID);
    }
}
