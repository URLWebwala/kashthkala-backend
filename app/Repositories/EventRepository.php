<?php

namespace App\Repositories;

use App\Models\Event;

class EventRepository extends BaseRepository
{
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Event::TABLE;
    }

    public function modelQuery()
    {
        return Event::query();
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
                $query->orWhere($this->tableName() . '.' . Event::TITLE, 'like', "%{$search}%")->orWhere($this->tableName() . '.' . Event::CAPTION, 'like', "%{$search}%");
            });
        }

        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . Event::STATUS, request('status'));
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
            Event::ID,
            Event::TITLE,
            Event::CAPTION,
            Event::IMAGE,
            Event::SIZE,
        ]);

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Event::ID);
        }

        return Event::where(Event::ID, $id)
            ->first();
    }

    public function store($inputs): Event
    {
        return Event::create($inputs);
    }

    public function update(Event $model, $inputs): Event
    {
        if ($model->id != $inputs[Event::ID]) {
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
                $q->where(Event::TITLE, 'like', "%{$search}%")
                ->orWhere(Event::CAPTION, 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function getCardCounts(){
        $totalImages = Event::where(Event::DELETED_AT, null)->count();
        $recentUploads = Event::where(Event::CREATED_AT, '>=', now()->subDays(7))->count();
        $storageUsed = Event::where(Event::DELETED_AT, null)->sum(Event::SIZE);
        return [
            'total_images' => $totalImages,
            'recent_uploads' => $recentUploads,
            'storage_used' => round($storageUsed / (1024 * 1024), 2) . 'MB',
        ];
    }
}
