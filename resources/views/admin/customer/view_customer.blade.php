@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="text-center mt-4">Thông tin Khách hàng: {{ $customer->customerName }}</h3>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <table class="table">
                <tr>
                    <th>Mã khách hàng</th>
                    <td>{{ $customer->customerId }}</td>
                </tr>
                <tr>
                    <th>Tên khách hàng</th>
                    <td>{{ $customer->customerName }}</td>
                </tr>
                <tr>
                    <th>Loại khách hàng</th>
                    <td>
                        @php
                            $customer_type = DB::table('customer_type')->where('customerTypeId', $customer->customerTypeId)->first();
                            if($customer_type) {
                                echo $customer_type->customerTypeName;
                            }
                        @endphp
                    </td>
                </tr>
                <!-- Kiểm tra loại khách hàng là 1 để hiển thị trường giới tính -->
                @if($customer->customerTypeId == 1)
                <tr>
                    <th>Giới tính</th>
                    <td>
                        @if($customer->sex === 0)
                            Nam
                        @elseif($customer->sex === 1)
                            Nữ
                        @else
                            Chưa xác định
                        @endif
                    </td>
                </tr>
                @endif
                <tr>
                    <th>Thẻ khách hàng</th>
                    <td>
                        @php
                            $ranking_type = DB::table('ranking_type')->where('rankingTypeId', $customer->rankingTypeId)->first();
                            if($ranking_type) {
                                echo $ranking_type->rankingTypeName;
                            }
                        @endphp
                    </td>
                </tr>
                <tr>
                    <th>Địa chỉ</th>
                    <td>{{ $customer->address }}</td>
                </tr>
                <tr>
                    <th>Số điện thoại</th>
                    <td>{{ $customer->phoneNumber }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $customer->email }}</td>
                </tr>
                <tr>
                    <th>Ghi chú</th>
                    <td>{{ $customer->note }}</td>
                </tr>
                
            </table>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-between">
                <h5>Đơn hàng của khách hàng:</h5>
            </div>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên đơn hàng</th>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $key => $order)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $order->orderName }}</td>
                        <td>{{ $order->orderId }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->orderDate)->format('d/m/Y') }}</td>
                        <td>{{ number_format($order->totalOrderValue, 0, ',', '.') }} VND</td>
                        <td>
                            @php
                                $order_status = DB::table('order_status')->where('orderStatusId', $order->orderStatusId)->first();
                                if ($order_status) {
                                    echo $order_status->orderStatusName;
                                }
                            @endphp
                        </td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm">
                                Xem
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }} <!-- Phân trang nếu có nhiều đơn hàng -->
            </div>
        </div>
    </div>

    <a href="{{ route('admin.customer') }}" class="btn btn-primary mt-4">Quay lại</a>
</div>
@endsection
