<?php

namespace App\Exports;

use App\Models\Admin;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdminExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $accounts;

    // Constructor nhận dữ liệu
    public function __construct($accounts)
    {
        $this->accounts = $accounts;
    }

    // Trả về dữ liệu để xuất Excel
    public function headings(): array
    {
        return ['ID', 'Username', 'Status', 'Role'];
    }

    // Định nghĩa dữ liệu sẽ được xuất (chỉ các trường cần thiết)
    public function map($account): array
    {
        return [
            $account->id,
            $account->username,
            $account->status == 1 ? 'Activate' : 'Deactivate', // Trạng thái
            $account->role,
        ];
    }

    // Trả về dữ liệu
    public function collection()
    {
        return $this->accounts;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Làm đậm dòng đầu tiên (tiêu đề)
            1 => ['font' => ['bold' => true]],
        ];
    }
}
