<?php

namespace App\Http\Resources;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return createElements($this, [
            Subscriber::ID,
            Subscriber::EMAIL,
            Subscriber::CREATED_AT,
            Subscriber::UPDATED_AT,
        ], $this[Subscriber::STATUS]);
    }
}
