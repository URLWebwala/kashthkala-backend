<?php

namespace App\Repositories;

use App\Models\Newslatter;

class NewslatterRepository extends BaseRepository
{
    public function __construct(Newslatter $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Newslatter::TABLE;
    }

    public function modelQuery()
    {
        return Newslatter::query();
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
                $query->orWhere($this->tableName() . '.' . Newslatter::EMAIL, 'like', "%{$search}%")
                    ->orWhere($this->tableName() . '.' . Newslatter::PHONE, 'like', "%{$search}%");
            });
        }

        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . Newslatter::STATUS, request('status'));
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
            Newslatter::ID,
            Newslatter::EMAIL,
            Newslatter::PHONE,
            Newslatter::SERVICE_ID,
        ]);

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Newslatter::ID);
        }

        return Newslatter::where(Newslatter::ID, $id)
            ->first();
    }

    public function store($inputs): Newslatter
    {
        return Newslatter::create($inputs);
    }

    public function update(Newslatter $model, $inputs): Newslatter
    {
        if ($model->id != $inputs[Newslatter::ID]) {
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
                $q->where(Newslatter::EMAIL, 'like', "%{$search}%")
                    ->orWhere(Newslatter::PHONE, 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
