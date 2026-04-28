<?php

namespace App\Http\Resources;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            Contact::ID,
            Contact::FIRST_NAME,
            Contact::LAST_NAME,
            Contact::EMAIL,
            Contact::PHONE,
            Contact::MESSAGE,
            Contact::SERVICE_ID,
            Contact::PRODUCT_ID,
            Contact::CATEGORY_ID,
            Contact::TYPE,
            Contact::CREATED_AT,
            Contact::UPDATED_AT,
        ], $this[Contact::STATUS]);

        $element[Contact::ATTACHMENT] = getUploadImgUrl($this[Contact::ATTACHMENT]);

        if ($this->service) {
            $element['service'] = new ServiceResource($this->service);
        } else {
            $element['service'] = null;
        }

        if ($this->product) {
            $element['product'] = new ProductResource($this->product);
        } else {
            $element['product'] = null;
        }

        if ($this->category) {
            $element['category'] = new CategoryResource($this->category);
        } else {
            $element['category'] = null;
        }

        return $element;
    }
}
