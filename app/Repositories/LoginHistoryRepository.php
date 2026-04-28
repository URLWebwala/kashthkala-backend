<?php

namespace App\Repositories;

use App\Enums\UserTypeEnum;
use App\Models\LoginHistory;

class LoginHistoryRepository extends BaseRepository
{
    public function __construct(LoginHistory $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return LoginHistory::TABLE;
    }

    public function modelQuery()
    {
        return LoginHistory::query();
    }

    public function query($paginate = false)
    {
        $user = request()->user();

        $selections = [
            $this->tableName() . '.*',
        ];

        $model = $this->modelQuery()
            ->select($selections)
            ->with(['user'])
            ->orderBy('login_at', 'desc');

        if ($user && !$user->user_type == UserTypeEnum::ADMIN) {
            $model->where($this->tableName() . '.user_id', $user->id);
        }

        if (request('search')) {
            $search = request('search');
            $model->where(function ($query) use ($search) {
                $query->where(LoginHistory::IP_ADDRESS, 'like', "%{$search}%")
                    ->orWhere(LoginHistory::DEVICE_TYPE, 'like', "%{$search}%")
                    ->orWhere(LoginHistory::LOCATION, 'like', "%{$search}%");
            });
        }

        if ( request('user_id') ) {
            $model->where($this->tableName() . '.user_id', request('user_id'));
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

    public function getModel($id = null)
    {
        $user = request()->user();

        if ($id == null) {
            $id = request(LoginHistory::ID);
        }

        $query = $this->modelQuery()->with(['user']);

        if ($user && !$user->user_type == UserTypeEnum::ADMIN) {
            $query->where(LoginHistory::USER_ID, $user->id);
        }

        return $query->find($id);
    }

    public function getExportQuery($request, $user)
    {
        $query = LoginHistory::with(['user'])
            ->orderBy('login_at', 'desc');

        if (!$user->user_type == UserTypeEnum::ADMIN) {
            $query->where(LoginHistory::USER_ID, $user->id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where(LoginHistory::IP_ADDRESS, 'like', "%{$search}%")
                    ->orWhere(LoginHistory::DEVICE_TYPE, 'like', "%{$search}%")
                    ->orWhere(LoginHistory::LOCATION, 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
