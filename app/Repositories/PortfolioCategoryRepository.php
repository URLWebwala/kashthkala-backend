<?php

namespace App\Repositories;

use App\Models\PortfolioCategory;

class PortfolioCategoryRepository extends BaseRepository
{
    public function __construct(PortfolioCategory $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return PortfolioCategory::TABLE;
    }

    public function modelQuery()
    {
        return PortfolioCategory::query();
    }

    public function query($paginate = false)
    {
        $model = $this->modelQuery()->select($this->tableName() . '.*');

        if (request('search')) {
            $search = request('search');
            $model->where(PortfolioCategory::CATEGORY_NAME, 'like', "%{$search}%");
        }

        if (request('status') !== null) {
            $model->where(PortfolioCategory::STATUS, request('status'));
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
            PortfolioCategory::CATEGORY_NAME,
            PortfolioCategory::IMAGE,
            PortfolioCategory::SLUG,
        ]);
    }

    public function getModel($id = null)
    {
        $id = $id ?? request(PortfolioCategory::ID);
        return PortfolioCategory::find($id);
    }

    public function store($inputs)
    {
        return PortfolioCategory::create($inputs);
    }

    public function update(PortfolioCategory $model, $inputs)
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
                $q->where(PortfolioCategory::CATEGORY_NAME, 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
