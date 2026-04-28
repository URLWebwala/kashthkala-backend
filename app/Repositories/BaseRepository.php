<?php

namespace App\Repositories;

use App\Enums\HttpStatusCodeEnum;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    public function statusChange($id, $attribute = 'user', $column = 'status')
    {
        $record = $this->find($id);

        if (!$record) {
            return [
                'status'  => false,
                'code'    => HttpStatusCodeEnum::NOT_FOUND,
                'message' => trans('message.not_found', ['attribute' => __('message.' . $attribute)]),
                'data'    => null
            ];
        }

        $record->$column = !$record->$column;
        $record->save();

        $message = $record->$column
            ? trans('message.activated', ['attribute' => __('message.' . $attribute)])
            : trans('message.deactivated', ['attribute' => __('message.' . $attribute)]);

        return [
            'status'  => true,
            'code'    => HttpStatusCodeEnum::OK,
            'message' => $message,
            'data'    => $record
        ];
    }

    public function paginate($perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    function paginateQuery($query, $resourceClass, $page = 1, $limit = 15)
    {
        $page = max((int) $page, 1);
        $limit = max((int) $limit, 1);
        $skip = ($page - 1) * $limit;

        $total = $query->count();
        $results = $query->skip($skip)->take($limit)->get();

        $data = $resourceClass::collection($results);

        return [
            'results' => $data,
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'current_page' => $page,
                'last_page' => (int) ceil($total / $limit),
            ]
        ];
    }
}
