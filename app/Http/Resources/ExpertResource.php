<?php

namespace App\Http\Resources;

use App\Models\Expert;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpertResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Expert::ID,
            Expert::NAME,
            Expert::DESCRIPTION,
            Expert::IMAGE,
            Expert::CREATED_AT,
            Expert::UPDATED_AT,
        ], $this[Expert::STATUS]);

        $element[Expert::IMAGE] = getUploadImgUrl($this[Expert::IMAGE]);
        return $element;
    }
}
