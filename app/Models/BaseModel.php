<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

abstract class BaseModel extends Model
{
    use SoftDeletes;

    const ID          = 'id';
    const NAME        = 'name';

    const CREATED_BY  = 'created_by';
    const CREATED_AT  = 'created_at';

    const UPDATED_BY  = 'updated_by';
    const UPDATED_AT  = 'updated_at';

    const DELETED_BY  = 'deleted_by';
    const DELETED_AT  = 'deleted_at';

    const STATUS      = 'status';
    const STATUS_NAME = 'status_name';

    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = 2;

    const STATUSES = [
        self::STATUS_ACTIVE => [
            'name'  => 'active',
            'color' => 'success',
        ],
        self::STATUS_INACTIVE => [
            'name'  => 'inactive',
            'color' => 'secondary',
        ],
    ];

    protected $guarded = [];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->{self::CREATED_BY} = Auth::id();
        });

        static::updating(function ($model) {
            $model->{self::UPDATED_BY} = Auth::id();
        });

        static::deleting(function ($model) {
            $model->{self::DELETED_BY} = Auth::id();
        });
    }

    public function scopeActive($query)
    {
        return $query->where(self::STATUS, self::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where(self::STATUS, self::STATUS_INACTIVE);
    }

    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status]['name'] ?? null;
    }

    public function getStatusColorAttribute()
    {
        return self::STATUSES[$this->status]['color'] ?? null;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, self::CREATED_BY);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, self::UPDATED_BY);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, self::DELETED_BY);
    }
}
