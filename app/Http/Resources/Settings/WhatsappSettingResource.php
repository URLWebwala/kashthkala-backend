<?php

namespace App\Http\Resources\Settings;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Settings\WhatsappSetting;

class WhatsappSettingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            WhatsappSetting::ID => $this->id,
            WhatsappSetting::API_ENDPOINT_URL => $this->api_endpoint_url,
            WhatsappSetting::API_ACCESS_TOKEN => $this->api_access_token,
            WhatsappSetting::SECRET_SIGNATURE => $this->secret_signature,
            WhatsappSetting::INSTANCE_ID => $this->instance_id,
            WhatsappSetting::WEBHOOK_URL => $this->webhook_url,
            WhatsappSetting::STATUS => (bool)$this->status,
            WhatsappSetting::WHATSAPP_NUMBER => $this->whatsapp_number,
            WhatsappSetting::HOVER_TEXT => $this->hover_text,
            WhatsappSetting::WINDOW_HEADER => $this->window_header,
            WhatsappSetting::WINDOW_SUBTITLE => $this->window_subtitle,
            WhatsappSetting::WELCOME_MESSAGE => $this->welcome_message,
            WhatsappSetting::BUTTON_COLOR => $this->button_color,
            WhatsappSetting::HEADER_COLOR => $this->header_color,
            WhatsappSetting::POSITION => $this->position,
            WhatsappSetting::CREATED_AT => $this->created_at,
            WhatsappSetting::UPDATED_AT => $this->updated_at,
        ];
    }
}
