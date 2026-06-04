<?php

namespace App\Http\Controllers\Api\V1\Settings;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Settings\WhatsappSettingRequest;
use App\Http\Resources\Settings\WhatsappSettingResource;
use App\Repositories\Settings\WhatsappSettingRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WhatsappSettingController extends BaseController
{
    protected $repository;

    public function __construct(WhatsappSettingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        $setting = $this->repository->listing();

        if (!$setting) {
            $message = trans('message.not_found', ['attribute' => 'WhatsApp Setting']);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => 'WhatsApp Setting']);
        $data['data'] = new WhatsappSettingResource($setting);

        return apiSuccess($message, $data);
    }

    public function store(WhatsappSettingRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $setting = $this->repository->getModel($request->id);

            if (empty($setting)) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => 'WhatsApp Setting']);
                $setting = $this->repository->store($inputs);
            } else {
                $title = trans('message.success');
                $message = trans('message.updated', ['attribute' => 'WhatsApp Setting']);
                $setting = $this->repository->update($setting, $inputs);
            }

            $data['data'] = new WhatsappSettingResource($setting);
        } catch (ValidationException $e) {
            $data = [];
            $title = __('message.error');
            $message = $e->getMessage() ?: __('messages.something_went_wrong');
            return apiError($message, HttpStatusCodeEnum::UNPROCESSABLE_ENTITY, $title);
        } catch (\Exception $e) {
            $data = [];
            $title = __('message.error');
            $message = $e->getMessage() ?: __('messages.something_went_wrong');
            return apiError($message, HttpStatusCodeEnum::INTERNAL_SERVER_ERROR, $title);
        }

        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }

    public function show($id)
    {
        $setting = $this->repository->find($id);

        if (!$setting) {
            $message = trans('message.not_found', ['attribute' => 'WhatsApp Setting']);
            return apiError($message, 404, 'Not Found');
        }

        return apiSuccess(
            trans('message.fetched', ['attribute' => 'WhatsApp Setting']),
            new WhatsappSettingResource($setting)
        );
    }

    public function getWidgetConfig()
    {
        $setting = $this->repository->listing();

        if (!$setting || !$setting->status) {
            return apiError('WhatsApp Widget not active', 404, 'Not Found');
        }

        $data = [
            'whatsapp_number' => $setting->whatsapp_number,
            'hover_text' => $setting->hover_text,
            'window_header' => $setting->window_header,
            'window_subtitle' => $setting->window_subtitle,
            'welcome_message' => $setting->welcome_message,
            'button_color' => $setting->button_color,
            'header_color' => $setting->header_color,
            'position' => $setting->position,
        ];

        return apiSuccess('Widget config fetched', ['data' => $data]);
    }
}
