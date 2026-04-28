<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Requests\TestinomialRequest;
use App\Http\Resources\TestinomialResource;
use App\Traits\Exportable;
use Illuminate\Http\Request;
use App\Models\Testinomial;
use App\Repositories\TestinomialRepository;
use Illuminate\Validation\ValidationException;

class TestinomialController extends BaseController
{
    use Exportable;

    protected $repository;

    public function __construct(TestinomialRepository $repository)
    {
        $this->repository = $repository;
    }


    public function list()
    {
        [$testinomial, $total] = $this->repository->listing();

        if( $testinomial->isEmpty() ) {
            $message = trans('message.not_found', ['attribute' => trans('message.testinomial')]);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => trans('message.testinomial')]);

        $data['data'] = TestinomialResource::collection($testinomial);
        $data['total'] = $total;

        return apiSuccess($message, $data);
    }

    public function show($id)
    {
        $service = $this->repository->find($id);

        if (!$service) {
            $message = trans('message.not_found', ['attribute' => trans('message.testinomial')]);
            return apiError($message, 404, 'Not Found');
        }

        return apiSuccess(
            trans('message.fetched', ['attribute' => trans('message.testinomial')]),
            new TestinomialResource($service)
        );
    }

    public function store(TestinomialRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $service = $request->id ? $this->repository->getModel($request->id) : null;

            if (!$service) {
                $title = trans('words.success');
                $message = trans('message.created', ['attribute' => __('message.testinomial')]);
                $service = $this->repository->store($inputs);
            } else {
                $oldImage = $service->image;

                $title = trans('words.success');
                $message = trans('message.updated', ['attribute' => __('message.testinomial')]);
                $service = $this->repository->update($service, $inputs);

                if ($request->hasFile('image')) {
                    deleteFile($oldImage);
                }
            }

            if ($request->hasFile('image')) {
                $service->image = uploadFile($request->file('image'), 'testinomials');
                $service->save();
            }

            $data['data'] = new TestinomialResource($service);
        } catch (ValidationException $e) {
            $data = [];
            $title = __('words.error');
            $message = $e->getMessage() ?: __('messages.something_went_wrong');
        }

        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }

    public function status(Request $request)
    {
        $id = $request->input('id');

        if (empty($id)) {
            return apiError(trans('message.invalid', ['attribute' => __('message.testinomial')]), HttpStatusCodeEnum::BAD_REQUEST, 'Bad Request');
        }

        $response = $this->repository->statusChange($id, 'testinomial');

        if (!$response['status']) {
            return apiError($response['message'], $response['code'], 'Not Found');
        }

        return apiSuccess(
            $response['message'],
            new TestinomialResource($response['data']),
            $response['code']
        );
    }

    /**
     * Delete testinomial
     */
    public function destroy($id)
    {
        $service = $this->repository->find($id);

        if (!$service) {
            $message = trans('message.not_found', ['attribute' => __('message.testinomial')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $this->repository->delete($id);

        return apiSuccess(trans('message.deleted', ['attribute' => __('message.testinomial')]), null, HttpStatusCodeEnum::OK, 'Success');
    }

    /**
     * Export testinomials
     */
    public function export(Request $request)
    {
        $query = $this->repository->getExportQuery($request);
        $exportConfig = $this->getExportableColumns(Testinomial::class);

        return $this->exportData(
            $request,
            $query,
            $exportConfig['columns'],
            $exportConfig['headings'],
            'testinomials'
        );
    }

    public function getActiveTestimonials()
    {
        $testinomial = $this->repository->getActiveTestimonials();

        if ($testinomial->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => trans('message.testinomial')]);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => trans('message.testinomial')]);

        $data['data'] = TestinomialResource::collection($testinomial);

        return apiSuccess($message, $data);
    }
}
