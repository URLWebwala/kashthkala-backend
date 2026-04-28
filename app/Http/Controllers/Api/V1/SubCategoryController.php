<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Http\Resources\SubCategoryResource;
use App\Lib\Api;
use App\Models\ProductCategory;
use App\Repositories\SubCategoryRepository;
use Illuminate\Http\Request;
use League\Config\Exception\ValidationException;

class SubCategoryController extends Controller
{
    protected $repository;

    public function __construct(SubCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        [$subcategory, $total] = $this->repository->listing();

        if ($subcategory->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => trans('message.subcategory')]);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => trans('message.subcategory')]);

        $data['data'] = SubCategoryResource::collection($subcategory);
        $data['total'] = $total;

        return apiSuccess($message, $data);
    }

    public function activeSubCategories()
    {
        $subcategories = $this->repository->activeSubCategories();

        if ($subcategories->isEmpty()) {
            $message =  trans('message.not_found', ['attribute' => trans('message.subcategory')]);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => trans('message.subcategory')]);
        $data['data'] = SubCategoryResource::collection($subcategories);

        return apiSuccess($message, $data);
    }

    public function store(SubCategoryRequest $request)
    {
        try {
            $data = [];
            $inputs = $this->repository->getInputs();
            $subcategory = $this->repository->getModel($request->id);
            if (empty($subcategory) && !$subcategory) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => __('message.subcategory')]);
                $subcategory = $this->repository->store($inputs);
            } else {
                $title = __('words.success');
                $message = trans('message.updated', ['attribute' => __('message.subcategory')]);
                $oldImage = $subcategory->subcategory_image;
                $oldIcon = $subcategory->subcategory_icon;
                $subcategory = $this->repository->update($subcategory, $inputs);
                if ($oldImage && $oldImage !== $subcategory->subcategory_image) {
                    deleteFile($oldImage);
                }
                if ($oldIcon && $oldIcon !== $subcategory->subcategory_icon) {
                    deleteFile($oldIcon);
                }
            }

            if ($request->hasFile('subcategory_image')) {
                $subcategory->subcategory_image = uploadFile($request->file('subcategory_image'), 'subcategory');
                $subcategory->save();
            }

            if ($request->hasFile('subcategory_icon')) {
                $subcategory->subcategory_icon = uploadFile($request->file('subcategory_icon'), 'subcategory');
                $subcategory->save();
            }

            $subcategory->load('category');

            $data['data'] = new SubCategoryResource($subcategory);
        } catch (ValidationException $e) {
            $data = [];
            $status = false;
            $title = __('words.error');
            $message = $e->getMessage() ?: __('words.something_went_wrong');
        }
        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }

    public function show($id)
    {
        $subcategory = $this->repository->find($id);

        if (!$subcategory) {
            $message = trans('message.not_found', ['attribute' => __('message.subcategory')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        return apiSuccess(trans('message.fetched', ['attribute' => __('message.subcategory')]), new SubCategoryResource($subcategory));
    }

    public function destroy($record_id)
    {
        $subcategory = $this->repository->getModel($record_id);
        if (!$subcategory) {
            $message =  trans('message.not_found', ['attribute' => __('message.subcategory')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $subcategory->delete();
        $data = [];
        $message = __('words.subcategory_deleted_successfully');
        $title = __('words.success');
        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }

    public function statusChange(Request $request)
    {
        $id = $request->input('id');

        if (empty($id)) {
            return apiError(trans('message.invalid', ['attribute' => __('message.subcategory')]), HttpStatusCodeEnum::BAD_REQUEST, 'Bad Request');
        }

        $response = $this->repository->statusChange($id, 'subcategory');

        if (!$response['status']) {
            return apiError($response['message'], $response['code'], 'Not Found');
        }

        return apiSuccess(
            $response['message'],
            new SubCategoryResource($response['data']),
            $response['code']
        );
    }
}
