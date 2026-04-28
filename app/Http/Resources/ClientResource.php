<?php

namespace App\Http\Resources;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Client::ID,
            Client::CLIENT_NAME,
            Client::IMAGE,
            Client::WEBURL,
            Client::PHONE,
            Client::EMAIL,
            Client::CREATED_AT,
            Client::UPDATED_AT,
        ], $this[Client::STATUS]);

        $element[Client::IMAGE] = getUploadImgUrl($this[Client::IMAGE]);

        return $element;
    }
}
