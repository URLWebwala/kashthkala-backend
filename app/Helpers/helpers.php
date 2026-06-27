<?php

use App\Enums\StatusEnum;
use App\Models\BaseModel;
use Illuminate\Database\Schema\Blueprint;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repositories\Settings\SmtpRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

if (!function_exists('defaultColumns')) {

    function defaultColumns(Blueprint $table, ?Closure $callback = null)
    {
        $table->bigIncrements('id');

        if ($callback) {
            $callback($table);
        }

        $table->tinyInteger('status')->default(1)->comment('1 = Active, 0 = Inactive');

        $table->unsignedBigInteger('created_by')->nullable();

        $table->timestamps();

        $table->unsignedBigInteger('updated_by')->nullable();

        $table->softDeletes();

        $table->unsignedBigInteger('deleted_by')->nullable();

        $table->ipAddress('ip_address')->nullable();
    }
}

if (!function_exists('createElements')) {
    function createElements($model, $types, $status = null, $statuses = StatusEnum::STATUSES)
    {
        $element = [];
        foreach ($types as $type) {
            if ($type == BaseModel::CREATED_AT) {
                $element[$type] = $model[$type];
            } else {
                $element[$type] = $model[$type];
            }
        }
        if ($status != null) {
            $element[BaseModel::STATUS] = $status;
            if (!isset($element[BaseModel::STATUS_NAME])) {
                $element[BaseModel::STATUS_NAME] = getStatusName($status, $statuses);
            }
        }
        return $element;
    }
}

if (!function_exists('getStatusName')) {
    function getStatusName($status = null, $statuses = StatusEnum::STATUSES)
    {
        $status_name = '';
        if (isset($statuses[$status])) {
            $status_name = __($statuses[$status]);
        }
        return $status_name;
    }
}

if (!function_exists('apiSuccess')) {
    function apiSuccess(string $message = 'Success', $data = [], int $code = 200, string $title = 'Success'): JsonResponse
    {
        return response()->json([
            'status' => true,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}

if (!function_exists('apiError')) {
    function apiError(string $message = 'Something went wrong', int $code = 400, string $title = 'Error', array $errors = []): JsonResponse
    {
        return response()->json([
            'status' => false,
            'title' => $title,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}

if (!function_exists('validationError')) {
    function validationError($errors = [], int $code = 422, string $title = 'Validation Error'): JsonResponse
    {
        return response()->json([
            'status' => false,
            'title' => $title,
            'message' => __('message.validation_error'),
            'errors' => $errors
        ], $code);
    }
}


function uploadFile($file, $directory)
{
    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    $destinationPath = public_path('uploads/' . $directory);

    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    $file->move($destinationPath, $filename);

    return 'uploads/' . $directory . '/' . $filename;
}


if (!function_exists('deleteFile')) {
    function deleteFile($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }
}


if (!function_exists('getUploadImgUrl')) {
    function getUploadImgUrl($path)
    {
        return $path ? asset('public/' . $path) : null;
    }
}

if (!function_exists('loadSMTPConfig')) {

    function loadSMTPConfig()
    {
        $repo = app(SmtpRepository::class);
        $smtp = $repo->query()->where('status', 1)->latest()->first();

        if (!$smtp) {
            return false;
        }

        $password = $smtp->password;

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $smtp->host);
        Config::set('mail.mailers.smtp.port', $smtp->port);
        Config::set('mail.mailers.smtp.username', $smtp->username);
        Config::set('mail.mailers.smtp.password', $password);
        Config::set('mail.mailers.smtp.encryption', $smtp->encryption);
        Config::set('mail.from.address', $smtp->from_address);
        Config::set('mail.from.name', $smtp->from_name);

        return $smtp;
    }
}


if (!function_exists('sendMailSMTP')) {

    function sendMailSMTP($data)
    {
        $smtp = loadSMTPConfig();

        try {

            Mail::send([], [], function ($message) use ($data, $smtp) {

                $message->to($data['to'])
                    ->subject($data['subject'] ?? 'Notification');

                $fromAddress = $data['from'] ?? ($smtp->from_address ?? config('mail.from.address'));
                $fromName = $data['from_name'] ?? ($smtp->from_name ?? config('mail.from.name'));
                $message->from($fromAddress, $fromName);

                $cc = $data['cc'] ?? ($smtp->cc_address ?? null);
                if ($cc)
                    $message->cc(explode(',', $cc));

                $bcc = $data['bcc'] ?? ($smtp->bcc_address ?? null);
                if ($bcc)
                    $message->bcc(explode(',', $bcc));

                $replyTo = $data['reply_to'] ?? ($smtp->reply_to_address ?? null);
                if ($replyTo)
                    $message->replyTo($replyTo);

                if (!empty($data['html'])) {
                    $message->html($data['html']);
                } else {
                    $message->text('SMTP Test Email');
                }

                if (!empty($data['attachments'])) {
                    foreach ($data['attachments'] as $file) {
                        $message->attach($file);
                    }
                }
            });

            return ['status' => true];
        } catch (\Exception $e) {
            Log::error('SMTP ERROR: ' . $e->getMessage());
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}

if (!function_exists('sendWhatsAppMessage')) {
    function sendWhatsAppMessage($contact)
    {
        try {
            $setting = \App\Models\Settings\WhatsappSetting::latest()->first();

            if (!$setting) {
                \Illuminate\Support\Facades\Log::warning('WhatsApp ERROR: Settings not found in DB (WhatsappSetting is null)');
                return ['status' => false, 'error' => 'WhatsApp not configured or disabled'];
            }
            if (!$setting->status) {
                \Illuminate\Support\Facades\Log::warning('WhatsApp ERROR: Settings status is inactive');
                return ['status' => false, 'error' => 'WhatsApp not configured or disabled'];
            }
            if (empty($setting->api_endpoint_url) || empty($setting->api_access_token) || empty($setting->instance_id) || empty($setting->whatsapp_number)) {
                \Illuminate\Support\Facades\Log::warning('WhatsApp ERROR: Missing configuration values (URL, Token, Instance ID, or Number)');
                return ['status' => false, 'error' => 'WhatsApp not configured or disabled'];
            }

            $firstName = $contact->first_name ?? 'N/A';
            $lastName = $contact->last_name ?? '';
            $email = $contact->email ?? 'N/A';
            $phone = $contact->phone ?? 'N/A';
            $messageText = $contact->message ?? 'N/A';
            $type = $contact->type ?? 'Contact';

            $message = "*New $type Enquiry*\n";
            $message .= "Name: $firstName $lastName\n";
            $message .= "Email: $email\n";
            $message .= "Phone: $phone\n";
            $message .= "Message: $messageText\n";

            $endpoint = rtrim($setting->api_endpoint_url, '/');

            // Meta Cloud API format: https://graph.facebook.com/v23.0/{Phone-Number-ID}/messages
            // Hum yahan man kar chal rahe hain ki admin panel me 'Instance ID' wale field me 'Phone Number ID' dala gaya hai.
            $url = $endpoint . '/' . $setting->instance_id . '/messages';

            \Illuminate\Support\Facades\Log::info("WhatsApp INFO: Sending Meta message to $url for number {$setting->whatsapp_number}");

            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->withToken($setting->api_access_token) // Ye automatically 'Authorization: Bearer <token>' add kar dega
                ->post($url, [
                    'messaging_product' => 'whatsapp',
                    'to' => $setting->whatsapp_number,
                    'type' => 'text',
                    'text' => [
                        'body' => $message
                    ]
                ]);

            if ($response->successful()) {
                \Illuminate\Support\Facades\Log::info('WhatsApp SUCCESS: Message sent', ['response' => $response->json()]);
                return ['status' => true, 'response' => $response->json()];
            }

            \Illuminate\Support\Facades\Log::error('WhatsApp ERROR: API Response Failed', ['body' => $response->body()]);
            return ['status' => false, 'error' => $response->body()];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('WhatsApp EXCEPTION: ' . $e->getMessage());
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
}
