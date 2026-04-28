<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\SeoRequest;
use App\Http\Resources\SeoResource;
use App\Models\SeoSettings;
use App\Repositories\SeoRepository;
use App\Traits\Exportable;
use Illuminate\Http\Request;
use League\Config\Exception\ValidationException;

class SeoController extends BaseController
{
    use Exportable;

    protected $repository;

    public function __construct(SeoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        [$seoSettings, $countData] = $this->repository->listing();

        if ($seoSettings->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => trans('message.seo_settings')]);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => trans('message.seo_settings')]);

        $data['data'] = SeoResource::collection($seoSettings);
        $data['total'] = $countData;

        return apiSuccess($message, $data);
    }

    public function store(SeoRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $seo = $this->repository->getModel($request->id);
            if (empty($seo)) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => __('message.seo_settings')]);
                $seo = $this->repository->store($inputs);
            } else {
                $oldImage = $seo->og_image;
                $oldTwitterImage = $seo->twitter_image;
                $title = trans('message.success');
                $message = trans('message.updated', ['attribute' => __('message.seo_settings')]);
                $seo = $this->repository->update($seo, $inputs);
                if ($oldImage && $oldImage !== $seo->og_image) {
                    deleteFile($oldImage);
                }
                if ($oldTwitterImage && $oldTwitterImage !== $seo->twitter_image) {
                    deleteFile($oldTwitterImage);
                }
            }

            if ($request->hasFile('og_image')) {
                $seo->image = uploadFile($request->file('image'), 'seo');
                $seo->save();
            }

            if ($request->hasFile('twitter_image')) {
                $seo->twitter_image = uploadFile($request->file('twitter_image'), 'seo');
                $seo->save();
            }

            $data['data'] = new SeoResource($seo);
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
            return apiError(trans('message.invalid', ['attribute' => __('message.seo_settings')]), HttpStatusCodeEnum::BAD_REQUEST, 'Bad Request');
        }

        $response = $this->repository->statusChange($id, 'seo_settings');

        if (!$response['status']) {
            return apiError($response['message'], $response['code'], 'Not Found');
        }

        return apiSuccess(
            $response['message'],
            new SeoResource($response['data']),
            $response['code']
        );
    }

    public function show($id)
    {
        $seo = $this->repository->find($id);

        if (!$seo) {
            $message = trans('message.not_found', ['attribute' => __('message.seo_settings')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        return apiSuccess(trans('message.fetched', ['attribute' => __('message.seo_settings')]), new SeoResource($seo));
    }

    public function destroy($id)
    {
        $service = $this->repository->find($id);

        if (!$service) {
            $message = trans('message.not_found', ['attribute' => __('message.seo_settings')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $this->repository->delete($id);

        return apiSuccess(trans('message.deleted', ['attribute' => __('message.seo_settings')]), null, HttpStatusCodeEnum::OK, 'Success');
    }
    public function export(Request $request)
    {
        $query = $this->repository->getExportQuery($request);
        $exportConfig = $this->getExportableColumns(SeoSettings::class);

        return $this->exportData(
            $request,
            $query,
            $exportConfig['columns'],
            $exportConfig['headings'],
            'seo_settings'
        );
    }
}
