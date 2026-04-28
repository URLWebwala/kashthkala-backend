<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Exception;

class BaseController extends Controller
{

    protected function paginated($collection, $message = 'Data fetched'): JsonResponse
    {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $collection->items(),
            'meta'    => [
                'current_page' => $collection->currentPage(),
                'last_page'    => $collection->lastPage(),
                'per_page'     => $collection->perPage(),
                'total'        => $collection->total(),
            ]
        ]);
    }

    protected function transaction(callable $callback)
    {
        DB::beginTransaction();

        try {
            $result = $callback();
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return apiError('Transaction failed', 500);
        }
    }

    protected function callProcedure($procedureName, array $params = [])
    {
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $sql = "CALL {$procedureName}({$placeholders})";

        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare($sql);

        $stmt->execute($params);

        $results = [];

        do {
            $results[] = $stmt->fetchAll(\PDO::FETCH_OBJ);
        } while ($stmt->nextRowset());

        return count($results) === 1 ? $results[0] : $results;
    }

    protected function updateSequence($model, array $ids)
    {
        return $this->transaction(function () use ($model, $ids) {

            foreach ($ids as $index => $id) {
                $model::where('id', $id)
                    ->update(['sequence' => $index + 1]);
            }

            return apiSuccess('Sequence updated successfully');
        });
    }

    protected function logData($data)
    {
        Log::info(print_r($data, true));
    }
}
