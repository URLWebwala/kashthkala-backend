<?php

namespace App\Repositories;

use App\Models\SeoSettings;
use App\Repositories\BaseRepository;

class SeoRepository extends BaseRepository
{
    public function __construct(SeoSettings $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return SeoSettings::TABLE_NAME;
    }

    public function modelQuery()
    {
        return SeoSettings::query();
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
                $query->orWhere($this->tableName() . '.' . SeoSettings::META_TITLE, 'like', "%{$search}%")
                    ->orWhere($this->tableName() . '.' . SeoSettings::PAGE_TITLE, 'like', "%{$search}%")
                    ->orWhere($this->tableName() . '.' . SeoSettings::PAGE_NAME, 'like', "%{$search}%");
            });
        }
        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . SeoSettings::STATUS, request('status'));
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
            SeoSettings::META_TITLE,
            SeoSettings::META_DESCRIPTION,
            SeoSettings::META_KEYWORDS,
            SeoSettings::SLUG,
            SeoSettings::CANONICAL_URL,
            SeoSettings::ROBOTS,
            SeoSettings::NOINDEX,
            SeoSettings::NOFOLLOW,
            SeoSettings::LANGUAGE,
            SeoSettings::WEBSITE_H1,
            SeoSettings::CONTENT,
            SeoSettings::OG_TITLE,
            SeoSettings::OG_DESCRIPTION,
            SeoSettings::OG_IMAGE,
            SeoSettings::OG_URL,
            SeoSettings::OG_TYPE,
            SeoSettings::OG_LOCALE,
            SeoSettings::TWITTER_CARD,
            SeoSettings::TWITTER_TITLE,
            SeoSettings::TWITTER_DESCRIPTION,
            SeoSettings::TWITTER_IMAGE,
            SeoSettings::TWITTER_SITE,
            SeoSettings::TWITTER_CREATOR,
            SeoSettings::SCHEMA_JSON,
            SeoSettings::PAGE_PRIORITY,
            SeoSettings::CHANGEFREQ,
            SeoSettings::META_AUTHOR,
            SeoSettings::PAGE_TITLE,
            SeoSettings::PAGE_NAME,
        ]);

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(SeoSettings::ID);
        }
        return SeoSettings::find($id);
    }

    public function store($inputs): SeoSettings
    {
        return SeoSettings::create($inputs);
    }

    public function update(SeoSettings $model, $inputs): SeoSettings
    {
        $model->update($inputs);
        return $model;
    }

    public function getExportQuery($request)
    {
        $query = $this->modelQuery();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(SeoSettings::META_TITLE, 'like', "%{$search}%")
                ->orWhere(SeoSettings::PAGE_TITLE, 'like', "%{$search}%")
                ->orWhere(SeoSettings::PAGE_NAME, 'like', "%{$search}%");
        }
        if ($request->has('status') && $request->status !== '') {
            $query->where(SeoSettings::STATUS, $request->status);
        }
        return $query;
    }
}
