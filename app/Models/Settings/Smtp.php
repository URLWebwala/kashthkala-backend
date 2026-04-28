<?php

namespace App\Models\Settings;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Smtp extends BaseModel
{
    use HasFactory;

    protected $table = 'smtp_settings';

    const TABLE = 'smtp_settings';
    const ID = 'id';
    const HOST = 'host';
    const PORT = 'port';
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const ENCRYPTION = 'encryption';
    const FROM_ADDRESS = 'from_address';
    const FROM_NAME = 'from_name';
    const REPLY_TO_ADDRESS = 'reply_to_address';
    const REPLY_TO_NAME = 'reply_to_name';
    const CC_ADDRESS = 'cc_address';
    const BCC_ADDRESS = 'bcc_address';

    protected $fillable = [
        self::HOST,
        self::PORT,
        self::USERNAME,
        self::PASSWORD,
        self::ENCRYPTION,
        self::FROM_ADDRESS,
        self::FROM_NAME,
        self::REPLY_TO_ADDRESS,
        self::REPLY_TO_NAME,
        self::CC_ADDRESS,
        self::BCC_ADDRESS,
        self::STATUS,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];
}
