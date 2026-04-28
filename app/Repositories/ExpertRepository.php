<?php

namespace App\Repositories;

use App\Models\Event;
use App\Models\Expert;

class ExpertRepository extends BaseRepository
{
    public function __construct(Expert $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Expert::TABLE;
    }

    public function modelQuery()
    {
        return Expert::query();
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
                $query->orWhere($this->tableName() . '.' . Expert::NAME, 'like', "%{$search}%");
            });
        }

        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . Expert::STATUS, request('status'));
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
            Expert::ID,
            Expert::NAME,
            Expert::DESCRIPTION,
            Expert::IMAGE,
        ]);

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Expert::ID);
        }

        return Expert::where(Expert::ID, $id)
            ->first();
    }

    public function store($inputs): Expert
    {
        return Expert::create($inputs);
    }

    public function update(Expert $model, $inputs): Expert
    {
        if ($model->id != $inputs[Expert::ID]) {
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
                $q->where(Expert::NAME, 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where(Expert::STATUS, $request->status);
        }

        return $query;
    }
}
