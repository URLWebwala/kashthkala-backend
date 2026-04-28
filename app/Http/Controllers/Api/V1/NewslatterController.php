<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Requests\NewslatterRequest;
use App\Http\Resources\NewslatterResource;
use App\Traits\Exportable;
use Illuminate\Http\Request;
use App\Models\Newslatter;
use App\Repositories\NewslatterRepository;
use Illuminate\Validation\ValidationException;

class NewslatterController extends BaseController
{
    use Exportable;

    protected $repository;

    public function __construct(NewslatterRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        [$newslatter, $total] = $this->repository->listing();

        $message = trans('message.fetched', ['attribute' => trans('message.newslatter')]);

        $data['data'] = NewslatterResource::collection($newslatter);
        $data['total'] = $total;

        return apiSuccess($message, $data);
    }

    public function show($id)
    {
        $newslatter = $this->repository->find($id);

        if (!$newslatter) {
            $message = trans('message.not_found', ['attribute' => trans('message.newslatter')]);
            return apiError($message, 404, 'Not Found');
        }

        return apiSuccess(
            trans('message.fetched', ['attribute' => trans('message.newslatter')]),
            new NewslatterResource($newslatter)
        );
    }

    public function store(NewslatterRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $newslatter = $this->repository->getModel($request->id);
            if (empty($newslatter)) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => __('message.newslatter')]);
                $newslatter = $this->repository->store($inputs);
            } else {
                $title = trans('message.success');
                $message = trans('first_name.updated', ['attribute' => __('message.newslatter')]);
                $newslatter = $this->repository->update($newslatter, $inputs);
            }
            $data['data'] = new NewslatterResource($newslatter);
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
            return apiError(trans('message.invalid', ['attribute' => __('message.newslatter')]), HttpStatusCodeEnum::BAD_REQUEST, 'Bad Request');
        }

        $response = $this->repository->statusChange($id, 'newslatter');

        if (!$response['status']) {
            return apiError($response['message'], $response['code'], 'Not Found');
        }

        return apiSuccess(
            $response['message'],
            new NewslatterResource($response['data']),
            $response['code'],
        );
    }

    public function destroy($id)
    {
        $newslatter = $this->repository->find($id);

        if (!$newslatter) {
            $message = trans('message.not_found', ['attribute' => __('message.newslatter')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $this->repository->delete($id);

        return apiSuccess(trans('message.deleted', ['attribute' => __('message.newslatter')]), null, HttpStatusCodeEnum::OK, 'Success');
    }

    public function export(Request $request)
    {
        $query = $this->repository->getExportQuery($request);
        $exportConfig = $this->getExportableColumns(Newslatter::class);

        return $this->exportData(
            $request,
            $query,
            $exportConfig['columns'],
            $exportConfig['headings'],
            'newslatter'
        );
    }
}
