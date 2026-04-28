<?php

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Service::ID,
            Service::SERVICE_TITLE,
            Service::ICON,
            Service::SHORT_DESCRIPTION,
            Service::LONG_DESCRIPTION,
            Service::CREATED_AT,
            Service::UPDATED_AT,
        ], $this[Service::STATUS]);

        return $element;
    }
}
