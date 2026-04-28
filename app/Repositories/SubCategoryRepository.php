<?php

namespace App\Repositories;

use App\Enums\StatusEnum;
use App\Models\SubCategory;

class SubCategoryRepository extends BaseRepository
{
    public function __construct(SubCategory $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return SubCategory::TABLE_NAME;
    }

    public function modelQuery()
    {
        return SubCategory::query()->with('category');
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
                $query->orWhere($this->tableName() . '.' . SubCategory::SUBCATEGORY_NAME, 'like', "%{$search}%");
            });
        }
        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . SubCategory::STATUS, request('status'));
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
            SubCategory::CATEGORY_ID,
            SubCategory::SUBCATEGORY_NAME,
            SubCategory::SUBCATEGORY_IMAGE,
            SubCategory::SUBCATEGORY_ICON,
            SubCategory::SUBCATEGORY_SLUG,
        ]);

        $inputs[SubCategory::STATUS] = StatusEnum::INACTIVE;
        if (request(SubCategory::STATUS) && (request(SubCategory::STATUS) == 'true' || request(SubCategory::STATUS) == 1)) {
            $inputs[SubCategory::STATUS] = StatusEnum::ACTIVE;
        }

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(SubCategory::ID);
        }
        return $this->modelQuery()->find($id);
    }

    public function store($inputs): SubCategory
    {
        return SubCategory::create($inputs);
    }

    public function update(SubCategory $model, $inputs): SubCategory
    {
        $model->update($inputs);
        return $model;
    }

    public function activeSubCategories()
    {
        return $this->modelQuery()
            ->where(SubCategory::STATUS, StatusEnum::ACTIVE)
            ->get();
    }
}
