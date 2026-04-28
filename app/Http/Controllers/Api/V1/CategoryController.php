<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use League\Config\Exception\ValidationException;

class CategoryController extends Controller
{
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        [$category, $total] = $this->repository->listing();

        if ($category->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => trans('message.category')]);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => trans('message.category')]);

        $data['data'] = CategoryResource::collection($category);
        $data['total'] = $total;

        return apiSuccess($message, $data);
    }

    public function activeCategories()
    {
        $categories = $this->repository->activeCategories();

        if ($categories->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => trans('message.category')]);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => 'message.category']);
        $data['data'] = CategoryResource::collection($categories);

        return apiSuccess($message, $data);
    }

    public function store(CategoryRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $category = $this->repository->getModel($request->id);
            if (empty($category) && !$category) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => __('message.category')]);
                $category = $this->repository->store($inputs);
            } else {
                $oldImage = $category->category_image;
                $oldTwitterImage = $category->category_icon;
                $title = trans('message.success');
                $message = trans('message.updated', ['attribute' => __('message.category')]);
                $category = $this->repository->update($category, $inputs);
                if ($oldImage && $oldImage !== $category->category_image) {
                    deleteFile($oldImage);
                }
                if ($oldTwitterImage && $oldTwitterImage !== $category->category_icon) {
                    deleteFile($oldTwitterImage);
                }
            }

            if ($request->hasFile('category_image')) {
                $category->category_image = uploadFile($request->file('category_image'), 'category');
                $category->save();
            }

            if ($request->hasFile('category_icon')) {
                $category->category_icon = uploadFile($request->file('category_icon'), 'category');
                $category->save();
            }

            $data['data'] = new CategoryResource($category);
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
        $category = $this->repository->find($id);

        if (!$category) {
            $message = trans('message.not_found', ['attribute' => __('message.category')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        return apiSuccess(trans('message.fetched', ['attribute' => __('message.category')]), new CategoryResource($category));
    }

    public function destroy($id)
    {
        $category = $this->repository->find($id);

        if (!$category) {
            $message = trans('message.not_found', ['attribute' => __('message.category')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $this->repository->delete($id);

        return apiSuccess(trans('message.deleted', ['attribute' => __('message.category')]), null, HttpStatusCodeEnum::OK, 'Success');
    }

    public function statusChange(Request $request)
    {
        $id = $request->input('id');

        if (empty($id)) {
            return apiError(trans('message.invalid', ['attribute' => __('message.category')]), HttpStatusCodeEnum::BAD_REQUEST, 'Bad Request');
        }

        $response = $this->repository->statusChange($id, 'category');

        if (!$response['status']) {
            return apiError($response['message'], $response['code'], 'Not Found');
        }

        return apiSuccess(
            $response['message'],
            new CategoryResource($response['data']),
            $response['code']
        );
    }

    public function changeIsCommingStatus(Request $request)
    {
        $data = [];
        $title = __('message.success');
        $message =  trans('message.updated', ['attribute' => __('message.category')]);
        $category = $this->repository->find($request->id);
        if ($category) {
            $category->is_comming = !$category->is_comming;
            $category->save();
        } else {
            $title = __('words.error');
            $message = trans('message.not_found', ['attribute' => __('message.category')]);
            $data = [];
        }
        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }
}
