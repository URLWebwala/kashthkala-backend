<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        [$products, $total] = $this->repository->listing();

        if ($products->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => 'Product']);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => 'Product']);

        $data['data'] = ProductResource::collection($products);
        $data['total'] = $total;

        return apiSuccess($message, $data);
    }

    public function getActiveProducts()
    {
        $products = $this->repository->getActiveProducts();

        if ($products->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => 'Product']);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => 'Product']);

        $data['data'] = ProductResource::collection($products);

        return apiSuccess($message, $data);
    }

    public function store(ProductRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $product = $this->repository->getModel($request->id);

            if (empty($product)) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => 'Product']);
                $product = $this->repository->store($inputs);
            } else {
                $title = trans('message.success');
                $message = trans('message.updated', ['attribute' => 'Product']);
                $product = $this->repository->update($product, $inputs);
            }

            $data['data'] = new ProductResource($product);
        } catch (\Exception $e) {
            return apiError($e->getMessage(), HttpStatusCodeEnum::INTERNAL_SERVER_ERROR);
        }
        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }

    public function show($id)
    {
        $product = $this->repository->find($id);

        if (!$product) {
            $message = trans('message.not_found', ['attribute' => 'Product']);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $data = [
            'data' => new ProductResource($product),
            'product' => new ProductResource($product) // Added for frontend compatibility
        ];

        return apiSuccess(trans('message.fetched', ['attribute' => 'Product']), $data);
    }

    public function destroy($id)
    {
        $product = $this->repository->find($id);

        if (!$product) {
            $message = trans('message.not_found', ['attribute' => 'Product']);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        // Delete images from storage
        foreach ($product->images as $image) {
            deleteFile($image->image_path);
        }

        $this->repository->delete($id);

        return apiSuccess(trans('message.deleted', ['attribute' => 'Product']), null, HttpStatusCodeEnum::OK, 'Success');
    }

    public function statusChange(Request $request)
    {
        $id = $request->input('id');

        if (empty($id)) {
            return apiError(trans('message.invalid', ['attribute' => 'Product']), HttpStatusCodeEnum::BAD_REQUEST, 'Bad Request');
        }

        $response = $this->repository->statusChange($id, 'Product');

        if (!$response['status']) {
            return apiError($response['message'], $response['code'], 'Not Found');
        }

        return apiSuccess(
            $response['message'],
            new ProductResource($response['data']),
            $response['code']
        );
    }

    public function getBySlug($slug)
    {
        $product = $this->repository->findBySlug($slug);

        if (!$product) {
            $message = trans('message.not_found', ['attribute' => 'Product']);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $data = [
            'data' => new ProductResource($product)
        ];

        return apiSuccess(trans('message.fetched', ['attribute' => 'Product']), $data);
    }
}
