<?php

namespace App\Repositories;

use App\Enums\StatusEnum;
use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Category::TABLE_NAME;
    }

    public function modelQuery()
    {
        return Category::query();
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
                $query->orWhere($this->tableName() . '.' . Category::CATEGORY_NAME, 'like', "%{$search}%");
            });
        }
        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . Category::STATUS, request('status'));
        }

        $model->orderBy($this->tableName() . '.' . Category::IS_COMMING, 'asc')->orderBy($this->tableName() . '.' . Category::CREATED_AT, 'desc');

        if (request('start_date')) {
            $model->whereDate($this->tableName() . '.' . Category::CREATED_AT, '>=', request('start_date'));
        } elseif (request('end_date')) {
            $model->whereDate($this->tableName() . '.' . Category::CREATED_AT, '<=', request('end_date'));
        } elseif (request('start_date') && request('end_date')) {
            $model->whereDate($this->tableName() . '.' . Category::CREATED_AT, '>=', request('start_date'))
                ->whereDate($this->tableName() . '.' . Category::CREATED_AT, '<=', request('end_date'));
        }

        $model->orderBy($this->tableName() . '.' . Category::IS_COMMING, 'asc')->orderBy($this->tableName() . '.' . Category::CREATED_AT, 'desc');

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
            Category::CATEGORY_NAME,
            Category::CATEGORY_IMAGE,
            Category::CATEGORY_ICON,
            Category::CATEGORY_SLUG,
            Category::IS_COMMING,
        ]);

        $inputs[Category::STATUS] = StatusEnum::INACTIVE;
        if (request(Category::STATUS) && (request(Category::STATUS) == 'true' || request(Category::STATUS) == 1)) {
            $inputs[Category::STATUS] = StatusEnum::ACTIVE;
        }

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Category::ID);
        }
        return Category::find($id);
    }

    public function store($inputs): Category
    {
        return Category::create($inputs);
    }

    public function update(Category $model, $inputs): Category
    {
        $model->update($inputs);
        return $model;
    }

    public function activeCategories()
    {
        return $this->modelQuery()
            ->where(Category::STATUS, StatusEnum::ACTIVE)
            ->orderBy(Category::IS_COMMING, 'ASC')
            ->orderBy(Category::CREATED_AT, 'DESC')
            ->get();
    }
}
