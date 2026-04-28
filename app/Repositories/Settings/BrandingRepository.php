<?php

namespace App\Repositories\Settings;

use App\Models\Settings\Branding;
use App\Models\Settings\Smtp;
use App\Repositories\BaseRepository;

class BrandingRepository extends BaseRepository
{
    public function __construct(Branding $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Branding::TABLE;
    }

    public function modelQuery()
    {
        return Branding::query();
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
            Branding::ID,
            Branding::WEBSITE_LOGO,
            Branding::WEBSITE_FAVICON,
            Branding::META_FAVICON,
            Branding::APP_FAVICON,
        ]);

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Branding::ID);
        }

        return Branding::where(Branding::ID, $id)
            ->first();
    }

    public function store($inputs): Branding
    {
        return Branding::create($inputs);
    }

    public function update(Branding $model, $inputs): Branding
    {
        if ($model->id != $inputs[Branding::ID]) {
            throw new \Exception('ID mismatch');
        }
        $model->update($inputs);
        return $model;
    }
}
