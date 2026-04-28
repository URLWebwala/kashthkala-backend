<?php

namespace App\Repositories\Settings;

use App\Models\Settings\SocialMedia;
use App\Repositories\BaseRepository;

class SocialMediaRepository extends BaseRepository
{
    public function __construct(SocialMedia $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return SocialMedia::TABLE;
    }

    public function modelQuery()
    {
        return SocialMedia::query();
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
            SocialMedia::ID,
            SocialMedia::WHATSAPP_URL,
            SocialMedia::FACEBOOK_URL,
            SocialMedia::TWITTER_URL,
            SocialMedia::INSTAGRAM_URL,
            SocialMedia::LINKEDIN_URL,
            SocialMedia::YOUTUBE_URL,
            SocialMedia::WHATSAPP_ICON,
            SocialMedia::FACEBOOK_ICON,
            SocialMedia::TWITTER_ICON,
            SocialMedia::INSTAGRAM_ICON,
            SocialMedia::LINKEDIN_ICON,
            SocialMedia::YOUTUBE_ICON,
            SocialMedia::MOBILE,
            SocialMedia::EMAIL,
            SocialMedia::ADDRESS
        ]);

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(SocialMedia::ID);
        }

        return SocialMedia::where(SocialMedia::ID, $id)
            ->first();
    }

    public function store($inputs): SocialMedia
    {
        return SocialMedia::create($inputs);
    }

    public function update(SocialMedia $model, $inputs): SocialMedia
    {
        if ($model->id != $inputs[SocialMedia::ID]) {
            throw new \Exception('ID mismatch');
        }
        $model->update($inputs);
        return $model;
    }
}
