<?php

namespace App\Http\Controllers\Api\V1\Settings;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Settings\BrandingRequest;
use App\Http\Resources\Settings\BrandingResource;
use App\Repositories\Settings\BrandingRepository;
use Illuminate\Validation\ValidationException;

class BrandingController extends BaseController
{
    protected $repository;

    public function __construct(BrandingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        $branding = $this->repository->listing();

        if (!$branding) {
            $message = trans('message.not_found', ['attribute' => trans('message.branding')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        $message = trans('message.fetched', ['attribute' => trans('message.branding')]);

        $data['data'] = new BrandingResource($branding);

        return apiSuccess($message, $data);
    }

    public function show($id)
    {
        $branding = $this->repository->find($id);

        if (!$branding) {
            $message = trans('message.not_found', ['attribute' => trans('message.branding')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        return apiSuccess(
            trans('message.fetched', ['attribute' => trans('message.branding')]),
            new BrandingResource($branding)
        );
    }

    public function store(BrandingRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();
            $branding = $this->repository->getModel($request->id);
            if (empty($branding)) {
                $title = trans('message.success');
                $message = trans('message.created', ['attribute' => __('message.branding')]);
                $branding = $this->repository->store($inputs);
            } else {
                $oldWebsiteLogo = $branding->website_logo;
                $oldWebsiteFavicon = $branding->website_favicon;
                $oldMetaFavicon = $branding->meta_favicon;
                $oldAppFavicon = $branding->app_favicon;
                $title = trans('message.success');
                $message = trans('message.updated', ['attribute' => __('message.branding')]);
                $branding = $this->repository->update($branding, $inputs);

                if ($oldWebsiteLogo && $oldWebsiteLogo != $branding->website_logo) {
                    deleteFile($oldWebsiteLogo);
                }
                if ($oldWebsiteFavicon && $oldWebsiteFavicon != $branding->website_favicon) {
                    deleteFile($oldWebsiteFavicon);
                }
                if ($oldMetaFavicon && $oldMetaFavicon != $branding->meta_favicon) {
                    deleteFile($oldMetaFavicon);
                }
                if ($oldAppFavicon && $oldAppFavicon != $branding->app_favicon) {
                    deleteFile($oldAppFavicon);
                }
            }

            if ($request->hasFile('website_logo')) {
                $branding->website_logo = uploadFile($request->file('website_logo'), 'branding');
                $branding->save();
            }

            if ($request->hasFile('website_favicon')) {
                $branding->website_favicon = uploadFile($request->file('website_favicon'), 'branding');
                $branding->save();
            }

            if ($request->hasFile('meta_favicon')) {
                $branding->meta_favicon = uploadFile($request->file('meta_favicon'), 'branding');
                $branding->save();
            }

            if ($request->hasFile('app_favicon')) {
                $branding->app_favicon = uploadFile($request->file('app_favicon'), 'branding');
                $branding->save();
            }

            $data['data'] = new BrandingResource($branding);
        } catch (ValidationException $e) {
            $data = [];
            $title = __('message.error');
            $message = $e->getMessage() ?: __('messages.something_went_wrong');
        }
        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }
}
