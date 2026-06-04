<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappSetting extends Model
{
    use HasFactory;

    public const TABLE = 'whatsapp_settings';

    protected $table = self::TABLE;

    // Attributes
    public const ID = 'id';
    public const API_ENDPOINT_URL = 'api_endpoint_url';
    public const API_ACCESS_TOKEN = 'api_access_token';
    public const SECRET_SIGNATURE = 'secret_signature';
    public const INSTANCE_ID = 'instance_id';
    public const WEBHOOK_URL = 'webhook_url';
    public const STATUS = 'status';
    
    public const WHATSAPP_NUMBER = 'whatsapp_number';
    public const HOVER_TEXT = 'hover_text';
    public const WINDOW_HEADER = 'window_header';
    public const WINDOW_SUBTITLE = 'window_subtitle';
    public const WELCOME_MESSAGE = 'welcome_message';
    public const BUTTON_COLOR = 'button_color';
    public const HEADER_COLOR = 'header_color';
    public const POSITION = 'position';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::API_ENDPOINT_URL,
        self::API_ACCESS_TOKEN,
        self::SECRET_SIGNATURE,
        self::INSTANCE_ID,
        self::WEBHOOK_URL,
        self::STATUS,
        self::WHATSAPP_NUMBER,
        self::HOVER_TEXT,
        self::WINDOW_HEADER,
        self::WINDOW_SUBTITLE,
        self::WELCOME_MESSAGE,
        self::BUTTON_COLOR,
        self::HEADER_COLOR,
        self::POSITION,
    ];

    protected $casts = [
        self::STATUS => 'boolean',
    ];
}
