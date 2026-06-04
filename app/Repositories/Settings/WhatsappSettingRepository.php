<?php

namespace App\Repositories\Settings;

use App\Models\Settings\WhatsappSetting;
use App\Repositories\BaseRepository;

class WhatsappSettingRepository extends BaseRepository
{
    public function __construct(WhatsappSetting $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return WhatsappSetting::TABLE;
    }

    public function modelQuery()
    {
        return WhatsappSetting::query();
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
            WhatsappSetting::API_ENDPOINT_URL,
            WhatsappSetting::API_ACCESS_TOKEN,
            WhatsappSetting::SECRET_SIGNATURE,
            WhatsappSetting::INSTANCE_ID,
            WhatsappSetting::WEBHOOK_URL,
            WhatsappSetting::STATUS,
            WhatsappSetting::WHATSAPP_NUMBER,
            WhatsappSetting::HOVER_TEXT,
            WhatsappSetting::WINDOW_HEADER,
            WhatsappSetting::WINDOW_SUBTITLE,
            WhatsappSetting::WELCOME_MESSAGE,
            WhatsappSetting::BUTTON_COLOR,
            WhatsappSetting::HEADER_COLOR,
            WhatsappSetting::POSITION,
        ]);
        
        $inputs[WhatsappSetting::STATUS] = request(WhatsappSetting::STATUS) ? 1 : 0;

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(WhatsappSetting::ID);
        }

        return WhatsappSetting::where(WhatsappSetting::ID, $id)->first();
    }

    public function store($inputs): WhatsappSetting
    {
        return WhatsappSetting::create($inputs);
    }

    public function update(WhatsappSetting $model, $inputs): WhatsappSetting
    {
        if ($model->id != request(WhatsappSetting::ID)) {
            throw new \Exception('ID mismatch');
        }
        $model->update($inputs);
        return $model;
    }
}
