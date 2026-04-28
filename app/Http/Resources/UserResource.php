<?php

namespace App\Http\Resources;

use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $element = createElements($this, [
            User::ID,
            User::NAME,
            User::EMAIL,
            User::PHONE,
            User::ADDRESS,
            User::USER_TYPE,
            User::LAST_LOGIN_AT,
            User::IS_VERIFIED,
            User::CREATED_AT,
            User::UPDATED_AT,
        ], $this[User::STATUS]);

        $element[User::IMAGE] = getUploadImgUrl($this[User::IMAGE]);

        if ($this[User::USER_TYPE] == UserTypeEnum::ADMIN) {
            $element['user_type'] = 'Admin';
        } else {
            $element['user_type'] = 'User';
        }

        return $element;
    }
}
