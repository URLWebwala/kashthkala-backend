<?php

namespace App\Repositories;

use App\Models\Portfolio;

class PortfolioRepository extends BaseRepository
{
    public function __construct(Portfolio $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Portfolio::TABLE;
    }

    public function modelQuery()
    {
        return Portfolio::query();
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
                $query->orWhere($this->tableName() . '.' . Portfolio::TITLE, 'like', "%{$search}%");
            });
        }

        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . Portfolio::STATUS, request('status'));
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
            Portfolio::TITLE,
            Portfolio::WEBSITE_URL,
            Portfolio::ADMIN_URL,
            Portfolio::ANDROID_APP_URL,
            Portfolio::IOS_APP_URL,
            Portfolio::IMAGE,
            Portfolio::ICON,
            Portfolio::DESCRIPTION,
            Portfolio::VISIBLE_ON_SITE,
            Portfolio::FEATURE_PROJECT,
            Portfolio::PORTFOLIO_CATEGORY_ID,
        ]);

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Portfolio::ID);
        }

        return Portfolio::where(Portfolio::ID, $id)
            ->first();
    }

    public function store($inputs): Portfolio
    {
        return Portfolio::create($inputs);
    }

    public function update(Portfolio $model, $inputs): Portfolio
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
                $q->where(Portfolio::TITLE, 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
