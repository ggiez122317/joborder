<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AnalyticsReportExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        private readonly Collection $employees,
        private readonly array $filters
    ) {
    }

    public function collection(): Collection
    {
        return $this->employees->values();
    }

    public function headings(): array
    {
        return [
            'Full Name',
            'Employee Code',
            'Job Order',
            'Position Title',
            'Office',
            'Record Status',
            'Sex',
            'Submitted By',
            'Mobile No.',
            'Email Address',
            'Created At',
            'Updated At',
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->full_name,
            $employee->employee_code,
            $employee->job_order,
            $employee->position_title,
            $employee->office,
            ucfirst((string) $employee->record_status),
            $employee->sex_at_birth,
            $employee->submission_source === 'user'
                ? ($employee->user?->email ?: 'User Portal')
                : 'Admin / Office',
            $employee->personalInformation?->mobile_no,
            $employee->personalInformation?->email_address,
            optional($employee->created_at)->format('Y-m-d H:i:s'),
            optional($employee->updated_at)->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $rowCount = max($this->employees->count() + 1, 1);

        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'E8F5EC'],
                ],
            ],
            "A1:L{$rowCount}" => [
                'alignment' => [
                    'vertical' => 'center',
                    'wrapText' => true,
                ],
            ],
        ];
    }
}
