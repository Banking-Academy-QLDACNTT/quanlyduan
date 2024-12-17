@extends('layouts.admin')
@section('content')
<div class="container py-5">
    <!-- Page Title -->
    <div class="text-center mb-4">
        <h3 class="text-center mt-3" style="font-weight: 600; color:rgb(4, 70, 135); font-size: 20px;">CHI TIẾT ĐƠN HÀNG</h3>
    </div>

    <!-- Order Information Section -->
    <div class="card shadow-sm rounded-lg">
        <div class="card-body">
            <h4 class="text-secondary mb-4">Thông tin đơn hàng</h4>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Mã đơn hàng:</strong> {{ $order->orderId }}</p>
                    <p><strong>Tên đơn hàng:</strong> {{ $order->orderName }}</p>
                    <p><strong>Trạng thái:</strong> {{ $order->orderStatusName }}</p>
                    <p><strong>Ngày tạo:</strong> {{ $order->orderDate }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tổng giá trị đơn hàng:</strong> {{ number_format($order->totalOrderValue, 0, ',', '.') }} VNĐ</p>
                    <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
                    <p><strong>Tên khách hàng:</strong> {{ $order->customerName }}</p>
                    <p><strong>Tên nhân viên:</strong> {{ $order->employeeName }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Products List Table -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h4 class="text-secondary mb-4">Danh sách sản phẩm trong đơn hàng</h4>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Kích cỡ</th>
                            <th>Màu sắc</th>
                            <th>Chất liệu</th>
                            <th>Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order_details as $detail)
                        <tr>
                            <td>{{ $detail->productName }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ number_format($detail->price, 0, ',', '.') }} VNĐ</td>
                            <td>{{ $detail->sizeName }}</td>
                            <td>{{ $detail->colorName }}</td>
                            <td>{{ $detail->materialName }}</td>
                            <td>{{ number_format($detail->quantity * $detail->price, 0, ',', '.') }} VNĐ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .card {
        border-radius: 15px;
    }

    .card-body {
        padding: 2rem;
    }

    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }

    .table-light {
        background-color: #f9f9f9;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .table th {
        background-color: #007bff;
        color: white;
    }

    h3.text-primary {
        font-size: 2.5rem;
    }

    h4.text-secondary {
        font-size: 1.5rem;
    }
</style>
@endpush
