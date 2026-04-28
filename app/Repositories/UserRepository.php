<?php

namespace App\Repositories;

use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return User::TABLE_NAME;
    }

    public function modelQuery()
    {
        return User::query();
    }

    public function query($paginate = false)
    {
        $selections = [
            $this->tableName() . '.*',
        ];

        $model = $this->modelQuery()->select($selections);

        if (request('search')) {
            $model->where(function ($query) {
                $search = request('search');
                $query->orWhere($this->tableName() . '.' . User::NAME, 'like', "%{$search}%")
                    ->orWhere($this->tableName() . '.' . User::EMAIL, 'like', "%{$search}%");
            });
        }

        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . User::STATUS, request('status'));
        }

        if ($paginate) {
            if (request('limit') && request('limit') !== 'All') {
                $start = (request('page') - 1) * request('limit');
                $model->offset($start)->limit(request('limit'));
            }
        }
        return $model;
    }

    public function listing()
    {
        return [$this->query(true)->get(), $this->query()->count()];
    }

    public function getInputs()
    {

        $inputs = request()->only([
            User::ID,
            User::NAME,
            User::EMAIL,
            User::USER_TYPE,
            User::STATUS,
            User::PHONE,
            User::IMAGE,
            User::ADDRESS,
            User::PASSWORD,
        ]);

        if (!request()->filled('password')) {
            unset($inputs[User::PASSWORD]);
        } else {
            $inputs[User::PASSWORD] = trim($inputs[User::PASSWORD]);
        }

        $inputs[User::USER_TYPE] = UserTypeEnum::USER;
        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(User::ID);
        }

        return User::where(User::ID, $id)
            ->whereIn(User::USER_TYPE, [
                UserTypeEnum::USER,
            ])
            ->first();
    }

    public function store($inputs): User
    {
        return User::create($inputs);
    }

    public function update(User $model, $inputs): User
    {
        if (!empty($inputs['password'])) {
            $inputs['password'] = bcrypt($inputs['password']);
        } else {
            unset($inputs['password']);
        }
        $model->update($inputs);
        return $model;
    }

    public function updateLastLogin($userId)
    {
        return $this->model->where(User::ID, $userId)->update([
            'last_login_at' => now()
        ]);
    }

    public function getExportQuery($request)
    {
        $query = $this->modelQuery();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where(User::NAME, 'like', "%{$search}%")
                    ->orWhere(User::EMAIL, 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where(User::STATUS, $request->status);
        }

        return $query;
    }

    public function changePassword($user, $currentPassword, $newPassword)
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect']
            ]);
        }

        $changesNewPassword = trim($newPassword);
        $user->password = Hash::make($changesNewPassword);
        $user->save();

        return true;
    }
}
