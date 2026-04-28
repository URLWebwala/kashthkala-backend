<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HttpStatusCodeEnum;
use App\Enums\UserTypeEnum;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\Exportable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends BaseController
{
    use Exportable;

    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    // testing purpose only
    public function list()
    {
        [$users, $total] = $this->repository->listing();

        if (empty($users)) {
            $message = trans('message.not_found', ['attribute' => __('message.users')]);
            return apiError($message, HttpStatusCodeEnum::OK, 'Not Found');
        }

        $data['data'] = UserResource::collection($users);
        $data['total'] = $total;

        return apiSuccess(trans('message.fetched', ['attribute' => __('message.users')]), $data, HttpStatusCodeEnum::OK, 'Success');
    }

    public function show($id)
    {
        $user = $this->repository->find($id);

        if (!$user) {
            $message = trans('message.not_found', ['attribute' => __('message.user')]);
            return apiError($message, HttpStatusCodeEnum::NOT_FOUND, 'Not Found');
        }

        return apiSuccess(trans('message.fetched', ['attribute' => __('message.user')]), new UserResource($user), HttpStatusCodeEnum::OK, 'Success');
    }

    public function destroy($id)
    {
        $user = $this->repository->find($id);

        if (!$user) {
            return apiError(
                trans('message.not_found', ['attribute' => __('message.user')]),
                HttpStatusCodeEnum::NOT_FOUND,
                'Not Found'
            );
        }

        if (auth()->id() == $user->id) {
            return apiError(
                'You cannot delete your own account while logged in.',
                HttpStatusCodeEnum::BAD_REQUEST,
                'Bad Request'
            );
        }

        if ($user->user_type == UserTypeEnum::ADMIN) {
            return apiError(
                'Admin account cannot be deleted.',
                HttpStatusCodeEnum::BAD_REQUEST,
                'Bad Request'
            );
        }

        $this->repository->delete($id);

        return apiSuccess(
            trans('message.deleted', ['attribute' => __('message.user')]),
            null,
            HttpStatusCodeEnum::OK,
            'Success'
        );
    }

    public function status(Request $request)
    {
        $id = $request->input('id');

        if (empty($id)) {
            return apiError(
                trans('message.invalid', ['attribute' => __('message.user')]),
                HttpStatusCodeEnum::BAD_REQUEST,
                'Bad Request'
            );
        }

        $user = $this->repository->find($id);

        if (!$user) {
            return apiError(
                trans('message.not_found', ['attribute' => __('message.user')]),
                HttpStatusCodeEnum::NOT_FOUND,
                'Not Found'
            );
        }

        if (auth()->id() == $user->id) {
            return apiError(
                'You cannot deactivate your own account.',
                HttpStatusCodeEnum::BAD_REQUEST,
                'Bad Request'
            );
        }

        if ($user->user_type == UserTypeEnum::ADMIN) {
            return apiError(
                'Admin account cannot be deactivated.',
                HttpStatusCodeEnum::BAD_REQUEST,
                'Bad Request'
            );
        }

        $response = $this->repository->statusChange($id, 'user');

        if (!$response['status']) {
            return apiError($response['message'], $response['code'], 'Not Found');
        }

        return apiSuccess(
            $response['message'],
            new UserResource($response['data']),
            $response['code'],
        );
    }

    public function store(UserRequest $request)
    {
        try {
            $inputs = $this->repository->getInputs();

            if (!empty($inputs['password'])) {
                $inputs['password'] = trim($inputs['password']);
            }

            $plainPassword = null;
            $user = null;

            if (!empty($request->id)) {
                $user = $this->repository->getModel($request->id);
            }

            if (empty($user)) {

                if (!empty($inputs['password'])) {
                    $plainPassword = $inputs['password'];
                } else {
                    $plainPassword = \Str::random(8);
                    $inputs['password'] = $plainPassword;
                }

                $inputs['password'] = Hash::make($inputs['password']);

                $user = $this->repository->store($inputs);

                $body = view('emails.user-created', [
                    'company'  => 'URLWEBWALA',
                    'user'     => $user,
                    'password' => $plainPassword,
                ])->render();

                sendMailSMTP([
                    'to' => $user->email,
                    'subject' => 'Welcome to URLWEBWALA',
                    'html' => $body
                ]);

                $title = trans('words.success');
                $message = trans('message.created', ['attribute' => __('message.user')]);
            } else {

                if (!empty($inputs['password'])) {
                    $inputs['password'] = Hash::make($inputs['password']);
                } else {
                    unset($inputs['password']);
                }

                $oldImage = $user->image;

                $user = $this->repository->update($user, $inputs);

                if ($oldImage && $oldImage !== $user->image) {
                    deleteFile($oldImage);
                }

                $title = trans('words.success');
                $message = trans('message.updated', ['attribute' => __('message.user')]);
            }

            if ($request->hasFile('image')) {
                $user->image = uploadFile($request->file('image'), 'users');
                $user->save();
            }

            $data['data'] = new UserResource($user);
        } catch (ValidationException $e) {
            $data = [];
            $title = __('words.error');
            $message = $e->getMessage() ?: __('messages.something_went_wrong');
        }

        return apiSuccess($message, $data, HttpStatusCodeEnum::OK, $title);
    }

    public function export(Request $request)
    {
        $query = $this->repository->getExportQuery($request);
        $exportConfig = $this->getExportableColumns(User::class);

        return $this->exportData(
            $request,
            $query,
            $exportConfig['columns'],
            $exportConfig['headings'],
            'users'
        );
    }

    public  function profile(Request $request)
    {
        $user = new \App\Http\Resources\UserResource($request->user());
        return apiSuccess('User profile retrieved successfully', ['user' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'image' => 'sometimes|nullable|image|max:2048',
            'address' => 'sometimes|nullable|string|max:255',
        ]);

        if ($request->has('email') && $request->email !== $request->user()->email) {
            return apiError(
                'Email address cannot be changed. For security reasons, updating the email is not allowed.',
                HttpStatusCodeEnum::BAD_REQUEST,
                'Bad Request'
            );
        }

        // only accept name, phone, image and address for profile update
        $inputs = $request->only(['name', 'phone', 'image', 'address']);

        try {
            if ($request->hasFile('image')) {
                $inputs['image'] = uploadFile($request->file('image'), 'users');
            }
            $user = $this->repository->update($request->user(), $inputs);
            return apiSuccess('Profile updated successfully', ['user' => new \App\Http\Resources\UserResource($user)]);
        } catch (ValidationException $e) {
            return apiError('Profile update failed', 422, 'Error', $e->errors());
        } catch (\Throwable $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return apiError('Something went wrong', 500);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $this->repository->changePassword(
                $request->user(),
                $request->current_password,
                $request->new_password
            );

            return apiSuccess('Password changed successfully');
        } catch (ValidationException $e) {
            return apiError('Password change failed', 422, 'Error', $e->errors());
        } catch (\Throwable $e) {
            \Log::error('Change password error: ' . $e->getMessage());
            return apiError('Something went wrong', 500);
        }
    }
}
