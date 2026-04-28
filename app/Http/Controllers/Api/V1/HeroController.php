<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\HeroRequest;
use App\Http\Resources\HeroResource;
use App\Repositories\HeroRepository;
use Illuminate\Http\Request;
use League\Config\Exception\ValidationException;

class HeroController extends Controller
{
    protected $repository;

    public function __construct(HeroRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        [$heroes, $total] = $this->repository->listing();

        if ($heroes->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => 'Hero']);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => 'Hero']);

        $data['data'] = HeroResource::collection($heroes);
        $data['total'] = $total;

        return apiSuccess($message, $data);
    }

    public function activeHeroes()
    {
        $heroes = $this->repository->activeHeroes();

        if ($heroes->isEmpty()) {
            $message = trans('message.not_found', ['attribute' => 'Hero']);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => 'Hero']);
        $data['data'] = HeroResource::collection($heroes);

        return apiSuccess($message, $data);
    }

    public function store(HeroRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $hero = $this->repository->getModel($request->id);
            if (empty($hero) && !$hero) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => 'Hero']);
                $hero = $this->repository->store($inputs);
            } else {
                $oldImage = $hero->image;
                $title = trans('message.success');
                $message = trans('message.updated', ['attribute' => 'Hero']);
                $hero = $this->repository->update($hero, $inputs);
                if ($oldImage && $oldImage !== $hero->image) {
                    deleteFile($oldImage);
                }
            }

            if ($request->hasFile('image')) {
                $hero->image = uploadFile($request->file('image'), 'hero');
                $hero->save();
            }

            $data['data'] = new HeroResource($hero);
        } catch (ValidationException $e) {
            $data = [];
            $title = __('words.error');
            $message = $e->getMessage() ?: __('words.something_went_wrong');
        }
        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }

    public function show($id)
    {
        $hero = $this->repository->find($id);

        if (!$hero) {
            $message = trans('message.not_found', ['attribute' => 'Hero']);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        return apiSuccess(trans('message.fetched', ['attribute' => 'Hero']), new HeroResource($hero));
    }

    public function destroy($id)
    {
        $hero = $this->repository->find($id);

        if (!$hero) {
            $message = trans('message.not_found', ['attribute' => 'Hero']);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $this->repository->delete($id);

        return apiSuccess(trans('message.deleted', ['attribute' => 'Hero']), null, HttpStatusCodeEnum::OK, 'Success');
    }

    public function statusChange(Request $request)
    {
        $id = $request->input('id');

        if (empty($id)) {
            return apiError(trans('message.invalid', ['attribute' => 'Hero']), HttpStatusCodeEnum::BAD_REQUEST, 'Bad Request');
        }

        $response = $this->repository->statusChange($id, 'hero');

        if (!$response['status']) {
            return apiError($response['message'], $response['code'], 'Not Found');
        }

        return apiSuccess(
            $response['message'],
            new HeroResource($response['data']),
            $response['code']
        );
    }
}
