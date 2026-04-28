<?php

namespace App\Repositories;

use App\Models\Subscriber;

class SubscriberRepository extends BaseRepository
{
    public function __construct(Subscriber $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Subscriber::TABLE;
    }

    public function modelQuery()
    {
        return Subscriber::query();
    }

    public function query($paginate = false)
    {
        $model = $this->modelQuery()->select($this->tableName() . '.*');

        if (request('search')) {
            $search = request('search');
            $model->where(Subscriber::EMAIL, 'like', "%{$search}%");
        }

        if (request('status') !== null && request('status') !== '') {
            $model->where(Subscriber::STATUS, request('status'));
        }

        if ($paginate && request('limit') && request('limit') !== 'All') {
            $start = (request('page') - 1) * request('limit');
            $model->offset($start)->limit(request('limit'));
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
            Subscriber::EMAIL,
            Subscriber::STATUS,
        ]);
    }

    public function getModel($id = null)
    {
        $id = $id ?? request(Subscriber::ID);
        return Subscriber::find($id);
    }

    public function store($inputs)
    {
        return Subscriber::create($inputs);
    }

    public function update(Subscriber $model, $inputs)
    {
        $model->update($inputs);
        return $model;
    }

    public function getExportQuery($request)
    {
        $query = $this->modelQuery();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(Subscriber::EMAIL, 'like', "%{$search}%");
        }

        return $query;
    }
}
