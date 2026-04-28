<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Traits\Exportable;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Repositories\ServiceRepository;
use Illuminate\Validation\ValidationException;

class ServicesController extends BaseController
{
    use Exportable;

    protected $repository;

    public function __construct(ServiceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all services
     */
    public function list()
    {
        [$services, $total] = $this->repository->listing();

        $message = trans('message.fetched', ['attribute' => trans('message.service')]);

        $data['data'] = ServiceResource::collection($services);
        $data['total'] = $total;

        return apiSuccess($message, $data);
    }

    /**
     * Get active services for frontend
     */
    public function activeServices()
    {
        $services = $this->repository->activeServices();

        if ($services->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => trans('message.service')]);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => trans('message.service')]);
        $data['data'] = ServiceResource::collection($services);

        return apiSuccess($message, $data);
    }

    /**
     * Show single service
     */
    public function show($id)
    {
        $service = $this->repository->find($id);

        if (!$service) {
            $message = trans('message.not_found', ['attribute' => trans('message.service')]);
            return apiError($message, 404, 'Not Found');
        }

        return apiSuccess(
            trans('message.fetched', ['attribute' => trans('message.service')]),
            new ServiceResource($service)
        );
    }

    /**
     * Store or update service
     */
    public function store(ServiceRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $service = $this->repository->getModel($request->id);
            if (empty($service)) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => __('message.service')]);
                $service = $this->repository->store($inputs);
            } else {
                $title = trans('message.success');
                $message = trans('message.updated', ['attribute' => __('message.service')]);
                $service = $this->repository->update($service, $inputs);
            }

            $data['data'] = new ServiceResource($service);
        } catch (ValidationException $e) {
            $data = [];
            $title = __('words.error');
            $message = $e->getMessage() ?: __('messages.something_went_wrong');
        }

        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }

    /**
     * Change status
     */
    public function status(Request $request)
    {
        $id = $request->input('id');

        if (empty($id)) {
            return apiError(trans('message.invalid', ['attribute' => __('message.service')]), HttpStatusCodeEnum::BAD_REQUEST, 'Bad Request');
        }

        $response = $this->repository->statusChange($id, 'service');

        if (!$response['status']) {
            return apiError($response['message'], $response['code'], 'Not Found');
        }

        return apiSuccess(
            $response['message'],
            new ServiceResource($response['data']),
            $response['code']
        );
    }

    /**
     * Delete service
     */
    public function destroy($id)
    {
        $service = $this->repository->find($id);

        if (!$service) {
            $message = trans('message.not_found', ['attribute' => __('message.service')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $this->repository->delete($id);

        return apiSuccess(trans('message.deleted', ['attribute' => __('message.service')]), null, HttpStatusCodeEnum::OK, 'Success');
    }

    /**
     * Export services
     */
    public function export(Request $request)
    {
        $query = $this->repository->getExportQuery($request);
        $exportConfig = $this->getExportableColumns(Service::class);

        return $this->exportData(
            $request,
            $query,
            $exportConfig['columns'],
            $exportConfig['headings'],
            'services'
        );
    }
}
