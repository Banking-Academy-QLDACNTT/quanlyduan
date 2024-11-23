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
        return ['Tài khoản', 'Họ tên', 'Ngày sinh', 'SĐT', 'Phòng ban'];
    }

    // Định nghĩa dữ liệu sẽ được xuất (chỉ các trường cần thiết)
    public function map($account): array
    {
        return [
            $account->username,
            $account->name,
            $account->dateOfBirth,
            $account->phoneNumber,
            $account->departmentName,
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
