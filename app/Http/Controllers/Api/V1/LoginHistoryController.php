<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\LoginHistory;
use App\Repositories\LoginHistoryRepository;
use App\Traits\Exportable;
use Illuminate\Http\Request;
use App\Http\Resources\LoginHistoryResource;

class LoginHistoryController extends BaseController
{
    use Exportable;

    protected $repository;

    public function __construct(LoginHistoryRepository $repository)
    {
        $this->repository = $repository;
    }
    public function list()
    {
        [$loginHistory, $total] = $this->repository->listing();

        $message = trans('message.fetched', ['attribute' => trans('message.login_history')]);

        $data['data'] = LoginHistoryResource::collection($loginHistory);
        $data['total'] = $total;
        return apiSuccess($message, $data);
    }

    public function show($id)
    {
        $loginHistory = $this->repository->find($id);

        if (!$loginHistory) {
            $message = trans('message.not_found', ['attribute' => trans('message.login_history')]);
            return apiError($message, 404, 'Not Found');
        }
        return apiSuccess(trans('message.fetched', ['attribute' => trans('message.login_history')]), new LoginHistoryResource($loginHistory));
    }

    public function export(Request $request)
    {

        $query = $this->repository->getExportQuery($request, $request->user());
        $exportConfig = $this->getExportableColumns(LoginHistory::class);

        return $this->exportData(
            $request,
            $query,
            $exportConfig['columns'],
            $exportConfig['headings'],
            'login_history'
        );
    }
}
