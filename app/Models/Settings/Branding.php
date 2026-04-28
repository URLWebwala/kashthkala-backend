<?php

namespace App\Models\Settings;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branding extends BaseModel
{
    use HasFactory;

    protected $table = 'branding_settings';

    const TABLE = 'branding_settings';
    const ID = 'id';
    const WEBSITE_LOGO = 'website_logo';
    const WEBSITE_FAVICON = 'website_favicon';
    const META_FAVICON = 'meta_favicon';
    const APP_FAVICON = 'app_favicon';

    protected $fillable = [
        self::WEBSITE_LOGO,
        self::WEBSITE_FAVICON,
        self::META_FAVICON,
        self::APP_FAVICON,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];
}
