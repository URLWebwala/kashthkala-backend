<?php

namespace App\Repositories\Settings;

use App\Models\Settings\EmailTemaplate;
use App\Repositories\BaseRepository;

class EmailTemaplateRepository extends BaseRepository
{
    public function __construct(EmailTemaplate $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return EmailTemaplate::TABLE;
    }

    public function modelQuery()
    {
        return EmailTemaplate::query();
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
                $query->orWhere($this->tableName() . '.' . EmailTemaplate::NAME, 'like', "%{$search}%")
                    ->orWhere($this->tableName() . '.' . EmailTemaplate::FROM_EMAIL, 'like', "%{$search}%");
            });
        }

        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . EmailTemaplate::STATUS, request('status'));
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
        return [
            $this->query(true)->get(),
            $this->query()->count(),
        ];
    }

    public function getInputs()
    {

        $inputs = request()->only([
            EmailTemaplate::ID,
            EmailTemaplate::NAME,
            EmailTemaplate::FROM_EMAIL,
            EmailTemaplate::SUBJECT,
            EmailTemaplate::BODY,
        ]);

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(EmailTemaplate::ID);
        }

        return EmailTemaplate::where(EmailTemaplate::ID, $id)
            ->first();
    }

    public function store($inputs): EmailTemaplate
    {
        return EmailTemaplate::create($inputs);
    }

    public function update(EmailTemaplate $model, $inputs): EmailTemaplate
    {
        if ($model->id != $inputs[EmailTemaplate::ID]) {
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
                $q->where(EmailTemaplate::NAME, 'like', "%{$search}%")
                    ->orWhere(EmailTemaplate::FROM_EMAIL, 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where(EmailTemaplate::STATUS, $request->status);
        }

        return $query;
    }
}
