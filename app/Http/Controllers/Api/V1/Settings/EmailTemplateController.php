<?php

namespace App\Http\Controllers\Api\V1\Settings;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Settings\EmailTemaplateRequest;
use App\Http\Resources\Settings\EmailTemaplateResource;
use App\Models\Settings\EmailTemaplate;
use App\Repositories\Settings\EmailTemaplateRepository;
use App\Traits\Exportable;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmailTemplateController extends BaseController
{
    use Exportable;

    protected $repository;

    public function __construct(EmailTemaplateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        [$emailTemplates, $total] = $this->repository->listing();

        if ($emailTemplates->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => trans('message.email_template')]);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => trans('message.email_template')]);

        $data['data'] = EmailTemaplateResource::collection($emailTemplates);
        $data['total'] = $total;

        return apiSuccess($message, $data);
    }

    public function show($id)
    {
        $emailTemplates = $this->repository->find($id);

        if (!$emailTemplates) {
            $message = trans('message.not_found', ['attribute' => trans('message.email_template')]);
            return apiError($message, 404, 'Not Found');
        }

        return apiSuccess(
            trans('message.fetched', ['attribute' => trans('message.email_template')]),
            new EmailTemaplateResource($emailTemplates)
        );
    }

    public function store(EmailTemaplateRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $emailTemplate = $this->repository->getModel($request->id);
            if (empty($emailTemplate)) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => __('message.email_template')]);
                $emailTemplate = $this->repository->store($inputs);
            } else {
                $title = trans('message.success');
                $message = trans('message.updated', ['attribute' => __('message.email_template')]);
                $emailTemplate = $this->repository->update($emailTemplate, $inputs);
            }

            $data['data'] = new EmailTemaplateResource($emailTemplate);
        } catch (ValidationException $e) {
            $data = [];
            $title = __('message.error');
            $message = $e->getMessage() ?: __('messages.something_went_wrong');
        }
        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }

    public function status(Request $request)
    {
        $id = $request->input('id');

        if (empty($id)) {
            return apiError(trans('message.invalid', ['attribute' => __('message.email_template')]), HttpStatusCodeEnum::BAD_REQUEST, 'Bad Request');
        }

        $response = $this->repository->statusChange($id, 'email_template');

        if (!$response['status']) {
            return apiError($response['message'], $response['code'], 'Not Found');
        }

        return apiSuccess(
            $response['message'],
            new EmailTemaplateResource($response['data']),
            $response['code']
        );
    }

    public function destroy($id)
    {
        $service = $this->repository->find($id);

        if (!$service) {
            $message = trans('message.not_found', ['attribute' => __('message.email_template')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $this->repository->delete($id);

        return apiSuccess(trans('message.deleted', ['attribute' => __('message.email_template')]), null, HttpStatusCodeEnum::OK, 'Success');
    }
    public function export(Request $request)
    {
        $query = $this->repository->getExportQuery($request);
        $exportConfig = $this->getExportableColumns(EmailTemaplate::class);

        return $this->exportData(
            $request,
            $query,
            $exportConfig['columns'],
            $exportConfig['headings'],
            'email_templates'
        );
    }
}
