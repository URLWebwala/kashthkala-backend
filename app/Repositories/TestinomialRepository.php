<?php

namespace App\Repositories;

use App\Models\Testinomial;

class TestinomialRepository extends BaseRepository
{
    public function __construct(Testinomial $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Testinomial::TABLE;
    }

    public function modelQuery()
    {
        return Testinomial::query();
    }

    public function query($paginate = false)
    {
        $model = $this->modelQuery()->select($this->tableName() . '.*');

        if (request('search')) {
            $search = request('search');
            $model->where(Testinomial::CLIENT_NAME, 'like', "%{$search}%")
                ->orWhere(Testinomial::RATING, 'like', "%{$search}%");
        }

        if (request('status') !== null) {
            $model->where(Testinomial::STATUS, request('status'));
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
            Testinomial::CLIENT_NAME,
            Testinomial::RATING,
            Testinomial::DESIGNATION,
            Testinomial::STATES,
            Testinomial::IMAGE,
        ]);
    }

    public function getModel($id = null)
    {
        $id = $id ?? request(Testinomial::ID);
        return Testinomial::find($id);
    }

    public function store($inputs)
    {
        return Testinomial::create($inputs);
    }

    public function update(Testinomial $model, $inputs)
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
                $q->where(Testinomial::CLIENT_NAME, 'like', "%{$search}%")
                    ->orWhere(Testinomial::RATING, 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function getActiveTestimonials()
    {
        return $this->modelQuery()->where(Testinomial::STATUS, 1)->get();
    }
}
