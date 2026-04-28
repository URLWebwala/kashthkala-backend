<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Career extends BaseModel
{
    use HasFactory;

    protected $table = 'career';

    const TABLE = 'career';

    const ID = 'id';
    const JOB_TITLE = 'job_title';
    const JOB_SLUG = 'job_slug';
    const DEPARTMENT = 'department';
    const OPENINGS = 'openings';
    const COMPANY_NAME = 'company_name';
    const COMPANY_LOGO = 'company_logo';
    const JOB_TYPE = 'job_type';
    const WORK_MODE = 'work_mode';
    const LOCATION = 'location';
    const MIN_EXPERIENCE = 'min_experience';
    const MAX_EXPERIENCE = 'max_experience';
    const SALARY_TYPE = 'salary_type';
    const MIN_SALARY = 'min_salary';
    const MAX_SALARY = 'max_salary';
    const SHORT_DESCRIPTION = 'short_description';
    const FULL_DESCRIPTION = 'full_description';
    const RESPONSIBILITIES = 'responsibilities';
    const REQUIREMENTS = 'requirements';
    const BENEFITS = 'benefits';
    const SKILLS = 'skills';
    const START_DATE = 'start_date';
    const LAST_DATE = 'last_date';
    const JOB_STATUS = 'job_status';
    const FEATURED = 'featured';
    const SHOW_HOMEPAGE = 'show_homepage';
    const META_TITLE = 'meta_title';
    const META_DESCRIPTION = 'meta_description';
    const META_KEYWORDS = 'meta_keywords';
    const APPLICATION_TYPE = 'application_type';
    const APPLY_URL = 'apply_url';
    const JOB_PDF = 'job_pdf';
    const ALLOW_MULTIPLE = 'allow_multiple';
    const EMAIL_NOTIFICATION = 'email_notification';

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
            'name' => self::JOB_TITLE,
            'label' => 'Job Title',
            'value' => self::JOB_TITLE,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::COMPANY_NAME,
            'label' => 'Company',
            'value' => self::COMPANY_NAME,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::JOB_TYPE,
            'label' => 'Job Type',
            'value' => self::JOB_TYPE,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::WORK_MODE,
            'label' => 'Work Mode',
            'value' => self::WORK_MODE,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::LOCATION,
            'label' => 'Location',
            'value' => self::LOCATION,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::JOB_STATUS,
            'label' => 'Status',
            'value' => self::JOB_STATUS,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
        [
            'name' => self::FEATURED,
            'label' => 'Featured',
            'value' => self::FEATURED,
            'show' => true,
            'sortable' => false,
            'export' => true,
        ],
        [
            'name' => self::CREATED_AT,
            'label' => 'Created At',
            'value' => self::CREATED_AT,
            'show' => true,
            'sortable' => true,
            'export' => true,
        ],
    ];

    protected $fillable = [
        self::JOB_TITLE,
        self::JOB_SLUG,
        self::DEPARTMENT,
        self::OPENINGS,
        self::COMPANY_NAME,
        self::COMPANY_LOGO,
        self::JOB_TYPE,
        self::WORK_MODE,
        self::LOCATION,
        self::MIN_EXPERIENCE,
        self::MAX_EXPERIENCE,
        self::SALARY_TYPE,
        self::MIN_SALARY,
        self::MAX_SALARY,
        self::SHORT_DESCRIPTION,
        self::FULL_DESCRIPTION,
        self::RESPONSIBILITIES,
        self::REQUIREMENTS,
        self::BENEFITS,
        self::SKILLS,
        self::START_DATE,
        self::LAST_DATE,
        self::JOB_STATUS,
        self::FEATURED,
        self::SHOW_HOMEPAGE,
        self::META_TITLE,
        self::META_DESCRIPTION,
        self::META_KEYWORDS,
        self::APPLICATION_TYPE,
        self::APPLY_URL,
        self::JOB_PDF,
        self::ALLOW_MULTIPLE,
        self::EMAIL_NOTIFICATION,
    ];

    protected $casts = [
        self::START_DATE => 'date',
        self::LAST_DATE => 'date',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
    ];
}
