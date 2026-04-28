<?php

namespace App\Repositories;

use App\Models\BlogCategory;
use App\Enums\StatusEnum;

class BlogCategoryRepository extends BaseRepository
{
    public function __construct(BlogCategory $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return BlogCategory::TABLE;
    }

    public function modelQuery()
    {
        return BlogCategory::query();
    }

    public function query($paginate = false)
    {
        $model = $this->modelQuery()->select($this->tableName() . '.*');

        if (request('search')) {
            $search = request('search');
            $model->where(BlogCategory::CATEGORY_NAME, 'like', "%{$search}%");
        }

        if (request('status') !== null) {
            $model->where(BlogCategory::STATUS, request('status'));
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
        $inputs = request()->only([
            BlogCategory::CATEGORY_NAME,
            BlogCategory::SLUG,
            BlogCategory::IMAGE,
            BlogCategory::ICON,
        ]);

        $inputs[BlogCategory::STATUS] = StatusEnum::INACTIVE;
        if (request(BlogCategory::STATUS) && (request(BlogCategory::STATUS) == 'true' || request(BlogCategory::STATUS) == 1)) {
            $inputs[BlogCategory::STATUS] = StatusEnum::ACTIVE;
        }

        return $inputs;
    }

    public function getModel($id = null)
    {
        $id = $id ?? request(BlogCategory::ID);
        return BlogCategory::find($id);
    }

    public function store($inputs)
    {
        return BlogCategory::create($inputs);
    }

    public function update(BlogCategory $model, $inputs)
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
                $q->where(BlogCategory::CATEGORY_NAME, 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function activeBlogCategories()
    {
        return $this->modelQuery()
            ->where(BlogCategory::STATUS, StatusEnum::ACTIVE)
            ->orderBy(BlogCategory::CREATED_AT, 'DESC')
            ->get();
    }
}
