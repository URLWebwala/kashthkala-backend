<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class WhatsappSettingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'api_endpoint_url' => 'nullable|url',
            'api_access_token' => 'nullable|string',
            'secret_signature' => 'nullable|string',
            'instance_id' => 'nullable|string',
            'webhook_url' => 'nullable|url',
            'status' => 'nullable|boolean',
            'whatsapp_number' => 'nullable|string',
            'hover_text' => 'nullable|string',
            'window_header' => 'nullable|string',
            'window_subtitle' => 'nullable|string',
            'welcome_message' => 'nullable|string',
            'button_color' => 'nullable|string',
            'header_color' => 'nullable|string',
            'position' => 'nullable|string|in:Left,Right,left,right',
        ];
    }
}
