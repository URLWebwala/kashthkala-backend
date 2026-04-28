<?php

namespace App\Http\Controllers\Api\V1\Settings;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Settings\SmtpRequest;
use App\Http\Resources\Settings\SmtpResource;
use App\Repositories\Settings\SmtpRepository;
use App\Traits\Exportable;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SmtpController extends BaseController
{
    use Exportable;

    protected $repository;

    public function __construct(SmtpRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        $smtp = $this->repository->listing();

        if (!$smtp) {
            $message = trans('message.not_found', ['attribute' => trans('message.smtp')]);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => trans('message.smtp')]);

        $data['data'] = new SmtpResource($smtp);

        return apiSuccess($message, $data);
    }

    public function show($id)
    {
        $smtp = $this->repository->find($id);

        if (!$smtp) {
            $message = trans('message.not_found', ['attribute' => trans('message.smtp')]);
            return apiError($message, 404, 'Not Found');
        }

        return apiSuccess(
            trans('message.fetched', ['attribute' => trans('message.smtp')]),
            new SmtpResource($smtp)
        );
    }

    public function store(SmtpRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $smtp = $this->repository->getModel($request->id);
            if (empty($smtp)) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => __('message.smtp')]);
                $smtp = $this->repository->store($inputs);
            } else {
                $title = trans('message.success');
                $message = trans('message.updated', ['attribute' => __('message.smtp')]);
                $smtp = $this->repository->update($smtp, $inputs);
            }

            $data['data'] = new SmtpResource($smtp);
        } catch (ValidationException $e) {
            $data = [];
            $title = __('message.error');
            $message = $e->getMessage() ?: __('messages.something_went_wrong');
        }
        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }

    public function testMail(Request $request)
    {
        try {
            $inputs = $request->validate([
                'email' => 'required|email',
            ]);

            $subject = "SMTP Test Email - URLWEBWALA";

            $body = view('emails.smtp-test', [
                'company' => 'URLWEBWALA',
                'email'   => $inputs['email'],
            ])->render();

            $mailData = [
                'to'      => $inputs['email'],
                'subject' => $subject,
                'html'    => $body,
            ];

            $result = sendMailSMTP($mailData);

            if (!$result['status']) {
                return apiError($result['error'] ?? 'SMTP Failed', 500);
            }

            return apiSuccess(trans('message.test_email_sent'), [], 200, trans('message.success'));
        } catch (ValidationException $e) {

            return validationError($e->errors());
        } catch (\Exception $e) {

            return apiError($e->getMessage(), 500);
        }
    }
}
