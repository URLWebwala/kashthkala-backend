<?php

namespace App\Repositories\Settings;

use App\Models\Settings\Smtp;
use App\Repositories\BaseRepository;

class SmtpRepository extends BaseRepository
{
    public function __construct(Smtp $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Smtp::TABLE;
    }

    public function modelQuery()
    {
        return Smtp::query();
    }

    public function query($paginate = false)
    {
        $selections = [
            $this->tableName() . '.*',
        ];
        $query = $this->modelQuery()->select($selections);

        if ($paginate) {
            $query->latest();
        }
        return $query;
    }

    public function listing()
    {
        return $this->query()->latest()->first();
    }

    public function getInputs()
    {

        $inputs = request()->only([
            Smtp::ID,
            Smtp::HOST,
            Smtp::PORT,
            Smtp::USERNAME,
            Smtp::PASSWORD,
            Smtp::ENCRYPTION,
            Smtp::FROM_ADDRESS,
            Smtp::FROM_NAME,
            Smtp::REPLY_TO_ADDRESS,
            Smtp::REPLY_TO_NAME,
            Smtp::CC_ADDRESS,
            Smtp::BCC_ADDRESS,
            Smtp::STATUS,
        ]);

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Smtp::ID);
        }

        return Smtp::where(Smtp::ID, $id)
            ->first();
    }

    public function store($inputs): Smtp
    {
        return Smtp::create($inputs);
    }

    public function update(Smtp $model, $inputs): Smtp
    {
        if ($model->id != $inputs[Smtp::ID]) {
            throw new \Exception('ID mismatch');
        }
        $model->update($inputs);
        return $model;
    }
}
