<?php

namespace App\Http\Controllers\Api\V1\Settings;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Settings\SocialMediaRequest;
use App\Http\Resources\Settings\SocialMediaResource;
use App\Repositories\Settings\SocialMediaRepository;
use Illuminate\Validation\ValidationException;

class SocialMediaController extends BaseController
{
    protected $repository;

    public function __construct(SocialMediaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        $socialMedia = $this->repository->listing();

        if (!$socialMedia) {
            $message = trans('message.not_found', ['attribute' => trans('message.social_media')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => trans('message.social_media')]);

        $data['data'] = new SocialMediaResource($socialMedia);

        return apiSuccess($message, $data);
    }

    public function show($id)
    {
        $branding = $this->repository->find($id);

        if (!$branding) {
            $message = trans('message.not_found', ['attribute' => trans('message.social_media')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        return apiSuccess(
            trans('message.fetched', ['attribute' => trans('message.social_media')]),
            new SocialMediaResource($branding)
        );
    }

    public function store(SocialMediaRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $socialMedia = $this->repository->getModel($request->id);
            if (empty($socialMedia)) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => __('message.social_media')]);
                $socialMedia = $this->repository->store($inputs);
            } else {
                $title = trans('message.success');
                $message = trans('message.updated', ['attribute' => __('message.social_media')]);
                $socialMedia = $this->repository->update($socialMedia, $inputs);
            }

            $data['data'] = new SocialMediaResource($socialMedia);
        } catch (ValidationException $e) {
            $data = [];
            $title = __('message.error');
            $message = $e->getMessage() ?: __('messages.something_went_wrong');
        }
        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }
}
