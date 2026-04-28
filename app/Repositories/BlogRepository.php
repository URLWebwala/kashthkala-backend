<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Enums\StatusEnum;

class BlogRepository extends BaseRepository
{
    public function __construct(Blog $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Blog::TABLE;
    }

    public function modelQuery()
    {
        return Blog::query();
    }

    public function query($paginate = false)
    {
        $selections = [
            $this->tableName() . '.*',
        ];

        $model = $this->modelQuery()->select($selections)->with('blogCategory');

        if (request('search')) {
            $model->where(function ($query) {
                $search = request('search');
                $query->orWhere($this->tableName() . '.' . Blog::TITLE, 'like', "%{$search}%")
                    ->orWhere($this->tableName() . '.' . Blog::CONTENT, 'like', "%{$search}%")
                    ->orWhere($this->tableName() . '.' . Blog::AUTHOR_NAME, 'like', "%{$search}%");
            });
        }

        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . Blog::STATUS, request('status'));
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
            Blog::TITLE,
            Blog::CONTENT,
            Blog::IMAGE,
            Blog::AUTHOR_NAME,
            Blog::BLOG_CATEGORY_ID,
            Blog::SLUG,
            Blog::VISIBILITY,
            Blog::PUBLISHED_AT,
            Blog::META_TITLE,
            Blog::META_KEYWORD,
            Blog::META_DESCRIPTION,
            Blog::TOTAL_VIEW,
        ]);

        $inputs[Blog::STATUS] = StatusEnum::INACTIVE;
        if (request(Blog::STATUS) && (request(Blog::STATUS) == 'true' || request(Blog::STATUS) == 1)) {
            $inputs[Blog::STATUS] = StatusEnum::ACTIVE;
        }

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Blog::ID);
        }

        return Blog::where(Blog::ID, $id)
            ->first();
    }

    public function store($inputs): Blog
    {
        return Blog::create($inputs);
    }

    public function update(Blog $model, $inputs): Blog
    {
        $model->update($inputs);
        return $model;
    }

    public function getExportQuery($request)
    {
        $query = $this->modelQuery();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where(Blog::TITLE, 'like', "%{$search}%")
                    ->orWhere(Blog::CONTENT, 'like', "%{$search}%")
                    ->orWhere(Blog::AUTHOR_NAME, 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function activeBlogs()
    {
        return $this->modelQuery()
            ->with('blogCategory')
            ->where(Blog::STATUS, StatusEnum::ACTIVE)
            ->where('visibility', 'Public')
            ->whereNotNull(Blog::PUBLISHED_AT)
            ->where(Blog::PUBLISHED_AT, '<=', now())
            ->orderBy(Blog::PUBLISHED_AT, 'DESC')
            ->get();
    }

    public function getBySlug($slug)
    {
        return $this->modelQuery()
            ->with('blogCategory')
            ->where(Blog::SLUG, $slug)
            ->where(Blog::STATUS, StatusEnum::ACTIVE)
            ->where('visibility', 'Public')
            ->whereNotNull(Blog::PUBLISHED_AT)
            ->where(Blog::PUBLISHED_AT, '<=', now())
            ->first();
    }
}
