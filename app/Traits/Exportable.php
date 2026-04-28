<?php

namespace App\Traits;

use App\Exports\BaseExport;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait Exportable
{
    protected function getExportableColumns(string $modelClass): array
    {
        if (!defined("$modelClass::COLUMN")) {
            return ['columns' => [], 'headings' => []];
        }

        $columns = [];
        $headings = [];

        foreach ($modelClass::COLUMN as $column) {
            if (!empty($column['show'])) {
                $columns[] = $column['value'];
                $headings[] = str_replace(':attribute', $column['name'], ':attribute');
            }
        }

        return compact('columns', 'headings');
    }

    protected function exportData(
        Request $request,
        Builder $query,
        array $columns,
        array $headings = [],
        string $filename = 'export'
    ): BinaryFileResponse {

        $format = $this->getExportFormat($request);

        $export = new BaseExport($query, $columns, $headings);

        return Excel::download($export, $this->generateFileName($filename, $format));
    }

    protected function getExportFormat(Request $request): string
    {
        $allowed = ['xlsx', 'csv', 'xls'];

        return in_array($request->get('format'), $allowed) ? $request->get('format') : 'xlsx';
    }

    protected function generateFileName(string $name, string $format): string
    {
        return $name . '_' . now()->format('Ymd_His') . '.' . $format;
    }
}
