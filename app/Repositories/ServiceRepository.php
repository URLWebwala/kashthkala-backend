<?php

namespace App\Repositories;

use App\Models\Service;

class ServiceRepository extends BaseRepository
{
    public function __construct(Service $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Service::TABLE;
    }

    public function modelQuery()
    {
        return Service::query();
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
                $query->orWhere($this->tableName() . '.' . Service::SERVICE_TITLE, 'like', "%{$search}%");
            });
        }

        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . Service::STATUS, request('status'));
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
            Service::SERVICE_TITLE,
            Service::ICON,
            Service::SHORT_DESCRIPTION,
            Service::LONG_DESCRIPTION,
        ]);

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Service::ID);
        }

        return Service::where(Service::ID, $id)
            ->first();
    }

    public function store($inputs): Service
    {
        return Service::create($inputs);
    }

    public function update(Service $model, $inputs): Service
    {
        $model->update($inputs);
        return $model;
    }

    public function getExportQuery($request)
    {
        $query = $this->modelQuery();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where(Service::SERVICE_TITLE, 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function activeServices()
    {
        return $this->modelQuery()
            ->where(Service::STATUS, \App\Enums\StatusEnum::ACTIVE)
            ->orderBy(Service::CREATED_AT, 'DESC')
            ->get();
    }
}
