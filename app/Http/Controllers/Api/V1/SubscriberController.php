<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Requests\SubscriberRequest;
use App\Http\Resources\SubscriberResource;
use App\Traits\Exportable;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Repositories\SubscriberRepository;
use Illuminate\Validation\ValidationException;

class SubscriberController extends BaseController
{
    use Exportable;

    protected $repository;

    public function __construct(SubscriberRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        [$subscribers, $total] = $this->repository->listing();

        if ($subscribers->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => 'Subscriber']);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => 'Subscriber']);

        $data['data'] = SubscriberResource::collection($subscribers);
        $data['total'] = $total;

        return apiSuccess($message, $data);
    }

    public function store(SubscriberRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $subscriber = $this->repository->getModel($request->id);
            if (empty($subscriber)) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => 'Subscriber']);
                $subscriber = $this->repository->store($inputs);
            } else {
                $title = trans('message.success');
                $message = trans('message.updated', ['attribute' => 'Subscriber']);
                $subscriber = $this->repository->update($subscriber, $inputs);
            }
            $data['data'] = new SubscriberResource($subscriber);
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
            return apiError(trans('message.invalid', ['attribute' => 'Subscriber']), HttpStatusCodeEnum::BAD_REQUEST, 'Bad Request');
        }

        $response = $this->repository->statusChange($id, 'subscribers');

        if (!$response['status']) {
            return apiError($response['message'], $response['code'], 'Not Found');
        }

        return apiSuccess(
            $response['message'],
            new SubscriberResource($response['data']),
            $response['code'],
        );
    }

    public function destroy($id)
    {
        $subscriber = $this->repository->find($id);

        if (!$subscriber) {
            $message = trans('message.not_found', ['attribute' => 'Subscriber']);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $this->repository->delete($id);

        return apiSuccess(trans('message.deleted', ['attribute' => 'Subscriber']), null, HttpStatusCodeEnum::OK, 'Success');
    }

    public function export(Request $request)
    {
        $query = $this->repository->getExportQuery($request);
        $exportConfig = $this->getExportableColumns(Subscriber::class);

        return $this->exportData(
            $request,
            $query,
            $exportConfig['columns'],
            $exportConfig['headings'],
            'subscribers'
        );
    }
}
