<?php

namespace App\Http\Resources;

use App\Models\Career;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CareerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Career::ID,
            Career::JOB_TITLE,
            Career::JOB_SLUG,
            Career::DEPARTMENT,
            Career::OPENINGS,
            Career::COMPANY_NAME,
            Career::COMPANY_LOGO,
            Career::JOB_TYPE,
            Career::WORK_MODE,
            Career::LOCATION,
            Career::MIN_EXPERIENCE,
            Career::MAX_EXPERIENCE,
            Career::SALARY_TYPE,
            Career::MIN_SALARY,
            Career::MAX_SALARY,
            Career::SHORT_DESCRIPTION,
            Career::FULL_DESCRIPTION,
            Career::RESPONSIBILITIES,
            Career::REQUIREMENTS,
            Career::BENEFITS,
            Career::SKILLS,
            Career::START_DATE,
            Career::LAST_DATE,
            Career::JOB_STATUS,
            Career::FEATURED,
            Career::SHOW_HOMEPAGE,
            Career::META_TITLE,
            Career::META_DESCRIPTION,
            Career::META_KEYWORDS,
            Career::APPLICATION_TYPE,
            Career::APPLY_URL,
            Career::JOB_PDF,
            Career::ALLOW_MULTIPLE,
            Career::EMAIL_NOTIFICATION,
            Career::CREATED_AT,
            Career::UPDATED_AT,
        ]);
        $element[Career::COMPANY_LOGO] = $this[Career::COMPANY_LOGO]  ? getUploadImgUrl($this[Career::COMPANY_LOGO]) : null;
        $element[Career::JOB_PDF] = $this[Career::JOB_PDF]  ? getUploadImgUrl($this[Career::JOB_PDF]) : null;
        return $element;
    }
}