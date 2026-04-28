<?php

namespace App\Repositories;

use App\Models\Partner;

class PartnerRepository extends BaseRepository
{
    public function __construct(Partner $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Partner::TABLE;
    }

    public function modelQuery()
    {
        return Partner::query();
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
                $query->orWhere($this->tableName() . '.' . Partner::NAME, 'like', "%{$search}%");
            });
        }

        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . Partner::STATUS, request('status'));
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
            Partner::ID,
            Partner::NAME,
            Partner::DESCRIPTION,
            Partner::IMAGE,
            Partner::LINK,
            Partner::DESIGNATION,
        ]);

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Partner::ID);
        }

        return Partner::where(Partner::ID, $id)
            ->first();
    }

    public function store($inputs): Partner
    {
        return Partner::create($inputs);
    }

    public function update(Partner $model, $inputs): Partner
    {
        if ($model->id != $inputs[Partner::ID]) {
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
                $q->where(Partner::NAME, 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where(Partner::STATUS, $request->status);
        }

        return $query;
    }
}
