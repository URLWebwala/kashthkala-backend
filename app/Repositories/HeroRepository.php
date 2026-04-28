<?php

namespace App\Repositories;

use App\Enums\StatusEnum;
use App\Models\Hero;

class HeroRepository extends BaseRepository
{
    public function __construct(Hero $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Hero::TABLE_NAME;
    }

    public function modelQuery()
    {
        return Hero::query();
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
                $query->orWhere($this->tableName() . '.' . Hero::MAIN_TITLE, 'like', "%{$search}%");
                $query->orWhere($this->tableName() . '.' . Hero::TAGLINE, 'like', "%{$search}%");
            });
        }
        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . Hero::STATUS, request('status'));
        }

        if (request('start_date')) {
            $model->whereDate($this->tableName() . '.' . Hero::CREATED_AT, '>=', request('start_date'));
        } elseif (request('end_date')) {
            $model->whereDate($this->tableName() . '.' . Hero::CREATED_AT, '<=', request('end_date'));
        } elseif (request('start_date') && request('end_date')) {
            $model->whereDate($this->tableName() . '.' . Hero::CREATED_AT, '>=', request('start_date'))
                ->whereDate($this->tableName() . '.' . Hero::CREATED_AT, '<=', request('end_date'));
        }

        $model->orderBy($this->tableName() . '.' . Hero::DISPLAY_ORDER, 'asc')->orderBy($this->tableName() . '.' . Hero::CREATED_AT, 'desc');

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
            Hero::TAGLINE,
            Hero::MAIN_TITLE,
            Hero::DESCRIPTION,
            Hero::PRIMARY_BUTTON_TEXT,
            Hero::PRIMARY_LINK,
            Hero::SECONDARY_BUTTON_TEXT,
            Hero::SECONDARY_LINK,
            Hero::IMAGE,
            Hero::DISPLAY_ORDER,
        ]);

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Hero::ID);
        }
        return Hero::find($id);
    }

    public function store($inputs): Hero
    {
        return Hero::create($inputs);
    }

    public function update(Hero $model, $inputs): Hero
    {
        $model->update($inputs);
        return $model;
    }

    public function activeHeroes()
    {
        return $this->modelQuery()
            ->where(Hero::STATUS, StatusEnum::ACTIVE)
            ->orderBy(Hero::DISPLAY_ORDER, 'ASC')
            ->orderBy(Hero::CREATED_AT, 'DESC')
            ->get();
    }
}
