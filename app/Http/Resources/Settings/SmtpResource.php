<?php

namespace App\Http\Resources\Settings;

use App\Models\Settings\Smtp;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmtpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Smtp::ID,
            Smtp::HOST,
            Smtp::PORT,
            Smtp::USERNAME,
            Smtp::PASSWORD,
            Smtp::ENCRYPTION,
            Smtp::FROM_ADDRESS,
            Smtp::FROM_NAME,
            Smtp::REPLY_TO_ADDRESS,
            Smtp::REPLY_TO_NAME,
            Smtp::CC_ADDRESS,
            Smtp::BCC_ADDRESS,
            Smtp::STATUS,
        ]);

        return $element;
    }
}
