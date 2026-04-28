<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Http\Resources\BlogResource;
use App\Repositories\BlogRepository;
use Illuminate\Http\Request;
use League\Config\Exception\ValidationException;

class BlogController extends Controller
{
    protected $repository;

    public function __construct(BlogRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        [$blogs, $total] = $this->repository->listing();

        if ($blogs->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => 'Blog']);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => 'Blog']);

        $data['data'] = BlogResource::collection($blogs);
        $data['total'] = $total;

        return apiSuccess($message, $data);
    }

    public function activeBlogs()
    {
        $blogs = $this->repository->activeBlogs();

        if ($blogs->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => 'Blog']);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => 'Blog']);
        $data['data'] = BlogResource::collection($blogs);

        return apiSuccess($message, $data);
    }


    public function getBySlug($slug)
    {
        $blog = $this->repository->getBySlug($slug);

        if (!$blog) {
            $message = trans('message.not_found', ['attribute' => 'Blog']);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => 'Blog']);
        $data['data'] = new BlogResource($blog);
        return apiSuccess($message, $data);
    }

    public function store(BlogRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $blog = $this->repository->getModel($request->id);

            if (empty($blog) && !$blog) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => 'Blog']);
                $blog = $this->repository->store($inputs);
            } else {
                $oldImage = $blog->image;
                $title = trans('message.success');
                $message = trans('message.updated', ['attribute' => 'Blog']);
                $blog = $this->repository->update($blog, $inputs);
                
                if ($request->hasFile('image') && $oldImage && $oldImage !== $blog->image) {
                    deleteFile($oldImage);
                }
            }

            if ($request->hasFile('image')) {
                $blog->image = uploadFile($request->file('image'), 'blog');
                $blog->save();
            }

            $data['data'] = new BlogResource($blog);
        } catch (ValidationException $e) {
            $data = [];
            $title = __('words.error');
            $message = $e->getMessage() ?: __('words.something_went_wrong');
        }
        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }

    public function show($id)
    {
        $blog = $this->repository->find($id);

        if (!$blog) {
            $message = trans('message.not_found', ['attribute' => 'Blog']);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        return apiSuccess(trans('message.fetched', ['attribute' => 'Blog']), new BlogResource($blog));
    }

    public function destroy($id)
    {
        $blog = $this->repository->find($id);

        if (!$blog) {
            $message = trans('message.not_found', ['attribute' => 'Blog']);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $this->repository->delete($id);

        return apiSuccess(trans('message.deleted', ['attribute' => 'Blog']), null, HttpStatusCodeEnum::OK, 'Success');
    }

    public function statusChange(Request $request)
    {
        $id = $request->input('id');

        if (empty($id)) {
            return apiError(trans('message.invalid', ['attribute' => 'Blog']), HttpStatusCodeEnum::BAD_REQUEST, 'Bad Request');
        }

        $response = $this->repository->statusChange($id, 'blog');

        if (!$response['status']) {
            return apiError($response['message'], $response['code'], 'Not Found');
        }

        return apiSuccess(
            $response['message'],
            new BlogResource($response['data']),
            $response['code']
        );
    }
}
