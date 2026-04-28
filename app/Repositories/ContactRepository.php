<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactRepository extends BaseRepository
{
    public function __construct(Contact $model)
    {
        parent::__construct($model);
    }

    public function tableName()
    {
        return Contact::TABLE;
    }

    public function modelQuery()
    {
        return Contact::query();
    }

    public function query($paginate = false)
    {
        $selections = [
            $this->tableName() . '.*',
        ];

        $model = $this->modelQuery()->with(['product', 'category', 'service'])->select($selections);

        if (request('search')) {
            $model->where(function ($query) {
                $search = request('search');
                $query->orWhere($this->tableName() . '.' . Contact::FIRST_NAME, 'like', "%{$search}%")
                    ->orWhere($this->tableName() . '.' . Contact::EMAIL, 'like', "%{$search}%")
                    ->orWhere($this->tableName() . '.' . Contact::PHONE, 'like', "%{$search}%");
            });
        }

        if (request('status') !== null && request('status') !== '') {
            $model->where($this->tableName() . '.' . Contact::STATUS, request('status'));
        }

        if (request('type')) {
            $model->where($this->tableName() . '.' . Contact::TYPE, request('type'));
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
            Contact::ID,
            Contact::FIRST_NAME,
            Contact::LAST_NAME,
            Contact::EMAIL,
            Contact::PHONE,
            Contact::MESSAGE,
            Contact::SERVICE_ID,
            Contact::PRODUCT_ID,
            Contact::CATEGORY_ID,
            Contact::COUNTRY,
            Contact::STATE,
            Contact::CITY,
            Contact::ENQUIRY,
            Contact::TYPE,
        ]);
        
        if (request()->hasFile('attachment')) {
            $inputs[Contact::ATTACHMENT] = uploadFile(request()->file('attachment'), 'contacts');
        }

        return $inputs;
    }

    public function getModel($id = null)
    {
        if ($id == null) {
            $id = request(Contact::ID);
        }

        return Contact::where(Contact::ID, $id)
            ->first();
    }

    public function store($inputs): Contact
    {
        return Contact::create($inputs);
    }

    public function update(Contact $model, $inputs): Contact
    {
        if ($model->id != $inputs[Contact::ID]) {
            throw new \Exception('ID mismatch');
        }
        
        if (isset($inputs[Contact::ATTACHMENT]) && $model->attachment) {
            deleteFile($model->attachment);
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
                $q->where(Contact::FIRST_NAME, 'like', "%{$search}%")
                    ->orWhere(Contact::EMAIL, 'like', "%{$search}%")
                    ->orWhere(Contact::PHONE, 'like', "%{$search}%");
            });
        }

        if ($request->has('type') && $request->type) {
            $query->where(Contact::TYPE, $request->type);
        }

        return $query;
    }
}
