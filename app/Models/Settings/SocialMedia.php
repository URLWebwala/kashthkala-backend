<?php

namespace App\Models\Settings;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialMedia extends BaseModel
{
    use HasFactory;

    protected $table = 'social_media';

    const TABLE = 'social_media';
    const ID = 'id';
    const WHATSAPP_URL = 'whatsapp_url';
    const FACEBOOK_URL = 'facebook_url';
    const TWITTER_URL = 'twitter_url';
    const INSTAGRAM_URL = 'instagram_url';
    const LINKEDIN_URL = 'linkedin_url';
    const YOUTUBE_URL = 'youtube_url';
    const WHATSAPP_ICON = 'whatsapp_icon';
    const FACEBOOK_ICON = 'facebook_icon';
    const TWITTER_ICON = 'twitter_icon';
    const INSTAGRAM_ICON = 'instagram_icon';
    const LINKEDIN_ICON = 'linkedin_icon';
    const YOUTUBE_ICON = 'youtube_icon';
    const MOBILE = 'mobile';
    const EMAIL = 'email';
    const ADDRESS = 'address';

    protected $fillable = [
        self::WHATSAPP_URL,
        self::FACEBOOK_URL,
        self::TWITTER_URL,
        self::INSTAGRAM_URL,
        self::LINKEDIN_URL,
        self::YOUTUBE_URL,
        self::WHATSAPP_ICON,
        self::FACEBOOK_ICON,
        self::TWITTER_ICON,
        self::INSTAGRAM_ICON,
        self::LINKEDIN_ICON,
        self::YOUTUBE_ICON,
        self::MOBILE,
        self::EMAIL,
        self::ADDRESS
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];
}
