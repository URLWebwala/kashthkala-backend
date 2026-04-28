<?php

namespace App\Http\Resources;

use App\Models\Newslatter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewslatterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Newslatter::ID,
            Newslatter::EMAIL,
            Newslatter::PHONE,
            Newslatter::SERVICE_ID,
        ], $this[Newslatter::STATUS]);

        if ($this->service) {
           $element['service'] = new ServiceResource($this->service);
        } else {
           $element['service'] = null;
        }

        return $element;
    }
}
