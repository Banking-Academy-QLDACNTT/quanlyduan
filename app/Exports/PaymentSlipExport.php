<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentSlipExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $paymentSlips;

    // Constructor nhận dữ liệu
    public function __construct($paymentSlips)
    {
        $this->paymentSlips = $paymentSlips;
    }

    // Trả về tiêu đề cho file Excel
    public function headings(): array
    {
        return [
            'Mã phiếu thanh toán',
            'Mã đơn hàng',
            'Số tiền đặt cọc',
            'Ngày đặt cọc',
            'Ngày thanh toán',
            'Thành tiền',
            'Phương thức thanh toán',
            'Trạng thái phiếu',
            'Ghi chú',
            'Ngày cập nhật'
        ];
    }

    // Định nghĩa dữ liệu sẽ được xuất (chỉ các trường cần thiết)
    public function map($paymentSlip): array
    {
        return [
            $paymentSlip->paymentSlipId,
            $paymentSlip->orderId,
            $paymentSlip->deposit,
            $paymentSlip->depositDate,
            $paymentSlip->paymentDate,
            $paymentSlip->orderValue,
            $paymentSlip->paymentMethodName,
            $paymentSlip->paymentStatusName,
            $paymentSlip->note,
            $paymentSlip->updateAt, // Cột ngày cập nhật
        ];
    }

    // Trả về dữ liệu
    public function collection()
    {
        return $this->paymentSlips;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Làm đậm dòng đầu tiên (tiêu đề)
            1 => ['font' => ['bold' => true]],
        ];
    }
}
