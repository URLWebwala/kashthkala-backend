<?php

namespace App\Http\Resources;

use App\Models\Testinomial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestinomialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Testinomial::ID,
            Testinomial::CLIENT_NAME,
            Testinomial::RATING,
            Testinomial::DESIGNATION,
            Testinomial::STATES,
            Testinomial::CREATED_AT,
            Testinomial::UPDATED_AT,
        ], $this[Testinomial::STATUS]);

        $element[Testinomial::IMAGE] = getUploadImgUrl($this[Testinomial::IMAGE]);

        return $element;
    }
}
