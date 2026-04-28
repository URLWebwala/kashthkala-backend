<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepository extends BaseRepository
{
    public function __construct(Client $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Client::TABLE;
    }

    public function modelQuery()
    {
        return Client::query();
    }

    public function query($paginate = false)
    {
        $model = $this->modelQuery()->select($this->tableName() . '.*');

        if (request('search')) {
            $search = request('search');
            $model->where(Client::CLIENT_NAME, 'like', "%{$search}%")
                ->orWhere(Client::WEBURL, 'like', "%{$search}%")
                ->orWhere(Client::PHONE, 'like', "%{$search}%")
                ->orWhere(Client::EMAIL, 'like', "%{$search}%");
        }

        if (request('status') !== null) {
            $model->where(Client::STATUS, request('status'));
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
            Client::CLIENT_NAME,
            Client::IMAGE,
            Client::WEBURL,
            Client::EMAIL,
            Client::PHONE,
        ]);
    }

    public function getModel($id = null)
    {
        $id = $id ?? request(Client::ID);
        return Client::find($id);
    }

    public function store($inputs)
    {
        return Client::create($inputs);
    }

    public function update(Client $model, $inputs)
    {
        $model->update($inputs);
        return $model;
    }

    public function getExportQuery($request)
    {
        $query = $this->modelQuery();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where(Client::CLIENT_NAME, 'like', "%{$search}%")
                    ->orWhere(Client::WEBURL, 'like', "%{$search}%")
                    ->orWhere(Client::PHONE, 'like', "%{$search}%")
                    ->orWhere(Client::EMAIL, 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
