<?php

namespace App\Http\Resources\Settings;

use App\Models\Settings\EmailTemaplate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailTemaplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            EmailTemaplate::ID,
            EmailTemaplate::NAME,
            EmailTemaplate::FROM_EMAIL,
            EmailTemaplate::SUBJECT,
            EmailTemaplate::BODY,
            EmailTemaplate::CREATED_AT,
            EmailTemaplate::UPDATED_AT,
        ]);

        return $element;
    }
}
