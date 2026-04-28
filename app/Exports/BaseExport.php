<?php

namespace App\Exports;

use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\{
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithEvents
};
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BaseExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $query;
    protected array $columns;
    protected array $headings;

    public function __construct($query, array $columns, array $headings = [])
    {
        $this->query    = $query;
        $this->columns  = $columns;
        $this->headings = $headings ?: $this->generateHeadings($columns);
    }

    /**
     * Return the query for export
     */
    public function query()
    {
        // Ensure only selected columns are returned
        return $this->query->select($this->columns);
    }

    /**
     * Generate headings automatically if not provided
     */
    protected function generateHeadings(array $columns): array
    {
        return array_map(fn($col) => ucwords(str_replace('_', ' ', $col)), $columns);
    }

    public function headings(): array
    {
        return $this->headings;
    }

    /**
     * Map row data to array for Excel
     */
    public function map($row): array
    {
        return array_map(fn($col) => $this->formatValue(data_get($row, $col)), $this->columns);
    }

    /**
     * Format cell values for Excel
     */
    protected function formatValue($value): string
    {
        if (is_null($value)) return '';

        if (is_bool($value)) return $value ? 'Yes' : 'No';

        if ($value instanceof \Illuminate\Support\Collection) {
            return $value->pluck('name')->implode(', ');
        }

        if (is_array($value)) {
            return implode(', ', Arr::flatten($value));
        }

        if (is_object($value)) {
            return $value->name ?? (string) $value;
        }

        return (string) $value;
    }

    /**
     * Styling after sheet is created
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Bold Header Row
                $sheet->getStyle('1:1')->getFont()->setBold(true);

                // Background Color for Header Row
                $sheet->getStyle('1:1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E2E8F0');

                // Optional: Auto width for all columns
                foreach (range('A', $sheet->getHighestColumn()) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
