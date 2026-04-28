<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends BaseModel
{
    use HasFactory;

    protected $table = 'contacts';

    const TABLE = 'contacts';
    const ID = 'id';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const EMAIL = 'email';
    const PHONE = 'phone';
    const MESSAGE = 'message';
    const SERVICE_ID = 'service_id';
    const PRODUCT_ID = 'product_id';
    const CATEGORY_ID = 'category_id';
    const ATTACHMENT = 'attachment';
    const COUNTRY = 'country';
    const STATE = 'state';
    const CITY = 'city';
    const ENQUIRY = 'enquiry';
    const TYPE = 'type';
    const STATUS = 'status';

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
            'name' => self::FIRST_NAME,
            'label' => 'First Name',
            'value' => self::FIRST_NAME,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::LAST_NAME,
            'label' => 'Last Name',
            'value' => self::LAST_NAME,
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
        [
            'name' => self::PRODUCT_ID,
            'label' => 'Product',
            'value' => self::PRODUCT_ID,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::CATEGORY_ID,
            'label' => 'Category',
            'value' => self::CATEGORY_ID,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::COUNTRY,
            'label' => 'Country',
            'value' => self::COUNTRY,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::STATE,
            'label' => 'State',
            'value' => self::STATE,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::CITY,
            'label' => 'City',
            'value' => self::CITY,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::ENQUIRY,
            'label' => 'Enquiry',
            'value' => self::ENQUIRY,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::TYPE,
            'label' => 'Type',
            'value' => self::TYPE,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::STATUS,
            'label' => 'Status',
            'value' => self::STATUS,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
    ];

    protected $fillable = [
        self::FIRST_NAME,
        self::LAST_NAME,
        self::EMAIL,
        self::PHONE,
        self::MESSAGE,
        self::SERVICE_ID,
        self::PRODUCT_ID,
        self::CATEGORY_ID,
        self::ATTACHMENT,
        self::COUNTRY,
        self::STATE,
        self::CITY,
        self::ENQUIRY,
        self::TYPE,
        self::STATUS,
    ];

    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, self::SERVICE_ID);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, self::PRODUCT_ID);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, self::CATEGORY_ID);
    }
}
