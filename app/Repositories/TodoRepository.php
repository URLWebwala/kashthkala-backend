<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Repositories\BaseRepository;

class TodoRepository extends BaseRepository
{
    public function __construct(Todo $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Todo::TABLE_NAME;
    }

    public function modelQuery()
    {
        return Todo::query();
    }

    public function query($paginate = false)
    {
        $selections = [
            $this->tableName() . '.*',
        ];

        $model = $this->modelQuery()->select($selections);

        $user = auth()->user();

        $model->where($this->tableName() . '.' . Todo::USER_ID, $user->id);

        if (request('search')) {
            $model->where(function ($query) {
                $search = request('search');
                $query->orWhere($this->tableName() . '.' . Todo::TITLE, 'like', "%{$search}%")
                    ->orWhere($this->tableName() . '.' . Todo::DESCRIPTION, 'like', "%{$search}%");
            });
        }
        if (request('priority') !== null && request('priority') !== '') {
            $model->where($this->tableName() . '.' . Todo::PRIORITY, request('priority'));
        }

        if (request('form_date') && request('form_date') && request('to_date') && request('to_date')) {
            $model->whereDate(Todo::DUE_DATE, '>=', request('form_date'))->whereDate(Todo::DUE_DATE, '<=', request('to_date'));
        } else if (request('form_date') && request('form_date')) {
            $model->whereDate(Todo::DUE_DATE, '>=', request('form_date'));
        } else if (request('to_date') && request('to_date')) {
            $model->whereDate(Todo::DUE_DATE, '<=', request('to_date'));
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
            Todo::TITLE,
            Todo::DESCRIPTION,
            Todo::USER_ID,
            Todo::IS_COMPLETED,
            Todo::PRIORITY,
            Todo::DUE_DATE,
        ]);

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Todo::ID);
        }
        return Todo::find($id);
    }

    public function store($inputs): Todo
    {
        return Todo::create($inputs);
    }

    public function update(Todo $model, $inputs): Todo
    {
        $model->update($inputs);
        return $model;
    }

    public function getExportQuery($request)
    {
        $query = $this->modelQuery();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(Todo::TITLE, 'like', "%{$search}%")
                ->orWhere(Todo::DESCRIPTION, 'like', "%{$search}%");
        }
        if ($request->has('priority') && $request->priority !== '') {
            $query->where(Todo::PRIORITY, $request->priority);
        }

        if ($request->has('form_date') && $request->form_date && $request->has('to_date') && $request->to_date) {
            $query->whereDate(Todo::DUE_DATE, '>=', $request->form_date)
                ->whereDate(Todo::DUE_DATE, '<=', $request->to_date);
        } else if ($request->has('form_date') && $request->form_date) {
            $query->whereDate(Todo::DUE_DATE, '>=', $request->form_date);
        } else if ($request->has('to_date') && $request->to_date) {
            $query->whereDate(Todo::DUE_DATE, '<=', $request->to_date);
        }
        return $query;
    }
}
