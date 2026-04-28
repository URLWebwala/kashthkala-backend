<?php

namespace App\Repositories;

use App\Models\Life;

class LifeRepository extends BaseRepository
{
    public function __construct(Life $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Life::TABLE;
    }

    public function modelQuery()
    {
        return Life::query();
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
                $query->orWhere($this->tableName() . '.' . Life::TITLE, 'like', "%{$search}%")->orWhere($this->tableName() . '.' . Life::CAPTION, 'like', "%{$search}%");
            });
        }

        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . Life::STATUS, request('status'));
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
            Life::ID,
            Life::TITLE,
            Life::CAPTION,
            Life::IMAGE,
            Life::SIZE,
        ]);

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Life::ID);
        }

        return Life::where(Life::ID, $id)
            ->first();
    }

    public function store($inputs): Life
    {
        return Life::create($inputs);
    }

    public function update(Life $model, $inputs): Life
    {
        if ($model->id != $inputs[Life::ID]) {
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
                $q->where(Life::TITLE, 'like', "%{$search}%")
                ->orWhere(Life::CAPTION, 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function getCardCounts()
    {
        $totalImages = Life::whereNotNull(Life::IMAGE)->count();
        $recentUploads = Life::where(Life::CREATED_AT, '>=', now()->subDays(7))->count();
        $storageUsed = Life::whereNotNull(Life::SIZE)->sum(Life::SIZE);
        return [
            'total_images' => $totalImages,
            'recent_uploads' => $recentUploads,
            'storage_used' => round($storageUsed / (1024 * 1024), 2) . 'MB',
        ];
    }
}
