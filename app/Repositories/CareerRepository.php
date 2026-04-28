<?php

namespace App\Repositories;

use App\Models\Career;

class CareerRepository extends BaseRepository
{
    public function __construct(Career $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Career::TABLE;
    }

    public function modelQuery()
    {
        return Career::query();
    }

    public function query($paginate = false)
    {
        $selections = [
            $this->tableName() . '.*',
        ];

        $model = $this->modelQuery()->select($selections);

        if (request('search')) {
            $search = request('search');
            $model->where(function ($query) use ($search) {
                $query->orWhere(Career::JOB_TITLE, 'like', "%{$search}%")
                    ->orWhere(Career::COMPANY_NAME, 'like', "%{$search}%")
                    ->orWhere(Career::LOCATION, 'like', "%{$search}%");
            });
        }

        if (request('job_status') !== null && request('job_status') !== '') {
            $model->where(Career::JOB_STATUS, request('job_status'));
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
            Career::ID,
            Career::JOB_TITLE,
            Career::JOB_SLUG,
            Career::DEPARTMENT,
            Career::OPENINGS,
            Career::COMPANY_NAME,
            Career::JOB_TYPE,
            Career::WORK_MODE,
            Career::LOCATION,
            Career::MIN_EXPERIENCE,
            Career::MAX_EXPERIENCE,
            Career::SALARY_TYPE,
            Career::MIN_SALARY,
            Career::MAX_SALARY,
            Career::SHORT_DESCRIPTION,
            Career::FULL_DESCRIPTION,
            Career::RESPONSIBILITIES,
            Career::REQUIREMENTS,
            Career::BENEFITS,
            Career::SKILLS,
            Career::START_DATE,
            Career::LAST_DATE,
            Career::JOB_STATUS,
            Career::FEATURED,
            Career::SHOW_HOMEPAGE,
            Career::APPLICATION_TYPE,
            Career::APPLY_URL,
            Career::ALLOW_MULTIPLE,
            Career::EMAIL_NOTIFICATION,
        ]);

        if (request()->has('skills')) {
            $inputs[Career::SKILLS] = json_encode(request('skills'));
        }

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Career::ID);
        }

        return Career::where(Career::ID, $id)->first();
    }

    public function store($inputs): Career
    {
        return Career::create($inputs);
    }

    public function update(Career $model, $inputs): Career
    {
        if ($model->id != $inputs[Career::ID]) {
            throw new \Exception('ID mismatch');
        }

        $model->update($inputs);
        return $model;
    }

    public function getExportQuery($request)
    {
        $query = $this->modelQuery();

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where(Career::JOB_TITLE, 'like', "%{$search}%")
                    ->orWhere(Career::COMPANY_NAME, 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function getCardCounts()
    {
        return [
            'total_jobs' => Career::count(),
            'active_jobs' => Career::where(Career::JOB_STATUS, 'active')->count(),
            'featured_jobs' => Career::where(Career::FEATURED, true)->count(),
        ];
    }
}
