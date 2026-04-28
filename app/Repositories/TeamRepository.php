<?php

namespace App\Repositories;

use App\Models\Teams;
use App\Repositories\BaseRepository;

class TeamRepository extends BaseRepository
{
    public function __construct(Teams $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Teams::TABLE;
    }

    public function modelQuery()
    {
        return Teams::query();
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
                $query->where($this->tableName() . '.' . Teams::NAME, 'like', "%{$search}%");
            });
        }

        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . Teams::STATUS, request('status'));
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
        return request()->only([
            Teams::ID,
            Teams::NAME,
            Teams::ROLE,
            Teams::PHONE,
            Teams::EMAIL,
            Teams::DESCRIPTION,
            Teams::FACEBOOK_LINK,
            Teams::TWITTER_LINK,
            Teams::LINKEDIN_LINK,
            Teams::INSTAGRAM_LINK,
        ]);
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Teams::ID);
        }

        return Teams::where(Teams::ID, $id)->first();
    }

    public function store($inputs): Teams
    {
        return Teams::create($inputs);
    }

    public function update(Teams $model, $inputs): Teams
    {
        if ($model->id != $inputs[Teams::ID]) {
            throw new \Exception('ID mismatch');
        }

        $model->update($inputs);
        return $model;
    }

    public function getExportQuery($request)
    {
        $query = $this->modelQuery();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where(Teams::NAME, 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where(Teams::STATUS, $request->status);
        }

        return $query;
    }
}
