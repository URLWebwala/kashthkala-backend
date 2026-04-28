<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginHistory extends BaseModel
{
    use HasFactory;

    protected $table = 'login_history';

    const TABLE = 'login_history';
    const ID = 'id';
    const USER_ID = 'user_id';
    const IP_ADDRESS = 'ip_address';
    const USER_AGENT = 'user_agent';
    const LOGIN_AT = 'login_at';
    const LOGOUT_AT = 'logout_at';
    const DEVICE_TYPE = 'device_type';
    const LOCATION = 'location';
    const TOKEN = 'token';

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
            'name' => self::USER_ID,
            'label' => 'User',
            'value' => self::USER_ID,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::IP_ADDRESS,
            'label' => 'IP Address',
            'value' => self::IP_ADDRESS,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::DEVICE_TYPE,
            'label' => 'Device',
            'value' => self::DEVICE_TYPE,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::LOCATION,
            'label' => 'Location',
            'value' => self::LOCATION,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::LOGIN_AT,
            'label' => 'Login Time',
            'value' => self::LOGIN_AT,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::LOGOUT_AT,
            'label' => 'Logout Time',
            'value' => self::LOGOUT_AT,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
    ];

    protected $fillable = [
        self::USER_ID,
        self::IP_ADDRESS,
        self::USER_AGENT,
        self::LOGIN_AT,
        self::LOGOUT_AT,
        self::DEVICE_TYPE,
        self::LOCATION,
        self::TOKEN,
    ];

    protected $casts = [
        self::LOGIN_AT => 'datetime',
        self::LOGOUT_AT => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, self::USER_ID);
    }
}
