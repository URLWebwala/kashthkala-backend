<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogCategoryRequest;
use App\Http\Resources\BlogCategoryResource;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Http\Request;
use League\Config\Exception\ValidationException;

class BlogCategoryController extends Controller
{
    protected $repository;

    public function __construct(BlogCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        [$categories, $total] = $this->repository->listing();

        if ($categories->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => 'Blog Category']);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => 'Blog Category']);

        $data['data'] = BlogCategoryResource::collection($categories);
        $data['total'] = $total;

        return apiSuccess($message, $data);
    }

    public function activeBlogCategories()
    {
        $categories = $this->repository->activeBlogCategories();

        if ($categories->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => 'Blog Category']);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => 'Blog Category']);
        $data['data'] = BlogCategoryResource::collection($categories);

        return apiSuccess($message, $data);
    }

    public function store(BlogCategoryRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $category = $this->repository->getModel($request->id);

            if (empty($category) && !$category) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => 'Blog Category']);
                $category = $this->repository->store($inputs);
            } else {
                $oldImage = $category->image;
                $oldIcon = $category->icon;
                $title = trans('message.success');
                $message = trans('message.updated', ['attribute' => 'Blog Category']);
                $category = $this->repository->update($category, $inputs);
                
                if ($oldImage && $oldImage !== $category->image) {
                    deleteFile($oldImage);
                }
                if ($oldIcon && $oldIcon !== $category->icon) {
                    deleteFile($oldIcon);
                }
            }

            if ($request->hasFile('image')) {
                $category->image = uploadFile($request->file('image'), 'blog_category');
                $category->save();
            }

            if ($request->hasFile('icon')) {
                $category->icon = uploadFile($request->file('icon'), 'blog_category');
                $category->save();
            }

            $data['data'] = new BlogCategoryResource($category);
        } catch (ValidationException $e) {
            $data = [];
            $title = __('words.error');
            $message = $e->getMessage() ?: __('words.something_went_wrong');
        }
        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }

    public function show($id)
    {
        $category = $this->repository->find($id);

        if (!$category) {
            $message = trans('message.not_found', ['attribute' => 'Blog Category']);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        return apiSuccess(trans('message.fetched', ['attribute' => 'Blog Category']), new BlogCategoryResource($category));
    }

    public function destroy($id)
    {
        $category = $this->repository->find($id);

        if (!$category) {
            $message = trans('message.not_found', ['attribute' => 'Blog Category']);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $this->repository->delete($id);

        return apiSuccess(trans('message.deleted', ['attribute' => 'Blog Category']), null, HttpStatusCodeEnum::OK, 'Success');
    }

    public function statusChange(Request $request)
    {
        $id = $request->input('id');

        if (empty($id)) {
            return apiError(trans('message.invalid', ['attribute' => 'Blog Category']), HttpStatusCodeEnum::BAD_REQUEST, 'Bad Request');
        }

        $response = $this->repository->statusChange($id, 'blog_category');

        if (!$response['status']) {
            return apiError($response['message'], $response['code'], 'Not Found');
        }

        return apiSuccess(
            $response['message'],
            new BlogCategoryResource($response['data']),
            $response['code']
        );
    }
}
