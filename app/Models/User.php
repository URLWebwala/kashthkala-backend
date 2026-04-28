<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    const TABLE_NAME = 'users';

    const SINGLE_NAME = 'user';

    const ID = 'id';

    const USER_ID = 'user_id';

    const NAME = 'name';

    const EMAIL = 'email';

    const EMAIL_VERIFIED_AT = 'email_verified_at';

    const PASSWORD = 'password';

    const USER_TYPE = 'user_type';

    const STATUS = 'status';

    const PHONE = 'phone';

    const IMAGE = 'image';

    const ADDRESS = 'address';

    const PHONE_VERIFIED_AT = 'phone_verified_at';

    const LAST_LOGIN_AT = 'last_login_at';

    const IS_VERIFIED = 'is_verified';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        self::NAME,
        self::EMAIL,
        self::PASSWORD,
        self::USER_TYPE,
        self::STATUS,
        self::PHONE,
        self::USER_ID,
        self::IMAGE,
        self::ADDRESS,
    ];

    const COLUMN = [
        [
            'name' => self::ID,
            'value' => self::ID,
            'column' => self::TABLE_NAME . '.' . self::ID,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::NAME,
            'value' => self::NAME,
            'column' => self::TABLE_NAME . '.' . self::NAME,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::USER_TYPE,
            'value' => self::USER_TYPE,
            'column' => self::TABLE_NAME . '.' . self::USER_TYPE,
            'show' => false,
            'sortable' => true,
        ],
        [
            'name' => self::EMAIL,
            'value' => self::EMAIL,
            'column' => self::TABLE_NAME . '.' . self::EMAIL,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::PHONE,
            'value' => self::PHONE,
            'column' => self::TABLE_NAME . '.' . self::PHONE,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::STATUS,
            'value' => self::STATUS,
            'column' => self::TABLE_NAME . '.' . self::STATUS,
            'show' => true,
            'sortable' => true,
        ],
        [
            'name' => self::LAST_LOGIN_AT,
            'value' => self::LAST_LOGIN_AT,
            'column' => self::TABLE_NAME . '.' . self::LAST_LOGIN_AT,
            'show' => false,
            'sortable' => true,
        ],
        [
            'name' => self::CREATED_AT,
            'value' => self::CREATED_AT,
            'column' => self::TABLE_NAME . '.' . self::CREATED_AT,
            'show' => false,
            'sortable' => true,
        ],
    ];

    protected $hidden = [
        self::PASSWORD,
        'remember_token',
    ];

    protected $casts = [
        self::EMAIL_VERIFIED_AT => 'datetime',
        self::PHONE_VERIFIED_AT => 'datetime',
        self::PASSWORD => 'hashed',
        self::LAST_LOGIN_AT => 'datetime',
        self::IS_VERIFIED => 'boolean',
    ];

    public function loginHistories()
    {
       return $this->hasMany(LoginHistory::class);
    }
}
