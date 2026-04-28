<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Traits\Exportable;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Repositories\ContactRepository;
use Illuminate\Validation\ValidationException;

class ContactController extends BaseController
{
    use Exportable;

    protected $repository;

    public function __construct(ContactRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        [$contacts, $total] = $this->repository->listing();

        if ($contacts->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => trans('message.contact_us')]);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => trans('message.contact_us')]);

        $data['data'] = ContactResource::collection($contacts);
        $data['total'] = $total;

        return apiSuccess($message, $data);
    }

    public function show($id)
    {
        $contact = $this->repository->find($id);

        if (!$contact) {
            $message = trans('message.not_found', ['attribute' => trans('message.contact_us')]);
            return apiError($message, 404, 'Not Found');
        }

        return apiSuccess(
            trans('message.fetched', ['attribute' => trans('message.contact_us')]),
            new ContactResource($contact)
        );
    }

    public function store(ContactRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $contact = $this->repository->getModel($request->id);
            if (empty($contact)) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => __('message.contact_us')]);
                $contact = $this->repository->store($inputs);
            } else {
                $title = trans('message.success');
                $message = trans('first_name.updated', ['attribute' => __('message.contact_us')]);
                $contact = $this->repository->update($contact, $inputs);
            }
            $data['data'] = new ContactResource($contact);
        } catch (ValidationException $e) {
            $data = [];
            $title = __('message.error');
            $message = $e->getMessage() ?: __('messages.something_went_wrong');
        }
        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }

    public function destroy($id)
    {
        $contact = $this->repository->find($id);

        if (!$contact) {
            $message = trans('message.not_found', ['attribute' => trans('message.contact_us')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $this->repository->delete($id);

        return apiSuccess(trans('message.deleted', ['attribute' => trans('message.contact_us')]), null, HttpStatusCodeEnum::OK, 'Success');
    }

    public function statusChange(Request $request)
    {
        $id = $request->input('id');

        if (empty($id)) {
            return apiError(trans('message.invalid', ['attribute' => trans('message.contact_us')]), HttpStatusCodeEnum::BAD_REQUEST, 'Bad Request');
        }

        $response = $this->repository->statusChange($id, 'contacts');

        if (!$response['status']) {
            return apiError($response['message'], $response['code'], 'Not Found');
        }

        return apiSuccess(
            $response['message'],
            new ContactResource($response['data']),
            $response['code']
        );
    }

    public function export(Request $request)
    {
        $query = $this->repository->getExportQuery($request, $request->user());
        $exportConfig = $this->getExportableColumns(Contact::class);

        return $this->exportData(
            $request,
            $query,
            $exportConfig['columns'],
            $exportConfig['headings'],
            'contact_us'
        );
    }
}
