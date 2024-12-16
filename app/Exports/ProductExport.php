<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $products;

    // Constructor nhận dữ liệu
    public function __construct($products)
    {
        $this->products = $products;
    }

    // Trả về tiêu đề cho file Excel
    public function headings(): array
    {
        return [
            'Mã sản phẩm',
            'Tên sản phẩm',
            'Loại sản phẩm',
            'Giá'
        ];
    }

    // Định nghĩa dữ liệu sẽ được xuất (chỉ các trường cần thiết)
    public function map($product): array
    {
        return [
            $product->productId,
            $product->productName,
            $product->productTypeName,
            $product->price,
        ];
    }

    // Trả về dữ liệu
    public function collection()
    {
        return $this->products;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Làm đậm dòng đầu tiên (tiêu đề)
            1 => ['font' => ['bold' => true]],
        ];
    }
}
