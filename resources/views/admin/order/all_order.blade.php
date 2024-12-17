@extends('layouts.admin')
@section('content')
<!-- Thêm CSS của iziToast -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

<!-- Thêm JS của iziToast -->
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
<div style="min-height:48vh">
    <h3 class="text-center mt-3" style="font-weight: 600; color:rgb(4, 70, 135); font-size: 20px;">DANH SÁCH ĐƠN HÀNG</h3>
    <div class="container">
        <div class="row w3-res-tb">
            <form action="" method="get">
                <div class="row">
                    <!-- Lọc theo trạng thái đơn hàng -->
                    <div class="col-sm-4 m-b-xs">
                        <select class="input-sm form-control w-sm inline v-middle" name="orderStatusId">
                            <option value="0" selected="selected">Chọn trạng thái đơn hàng</option>
                            @php
                                $all_order_status = DB::table('order_status')->get();
                            @endphp
                            @foreach($all_order_status as $order_status)
                                <option value="{{ $order_status->orderStatusId }}" {{ request()->orderStatusId == $order_status->orderStatusId ? 'selected' : '' }}>{{ $order_status->orderStatusName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tìm kiếm theo tên đơn hàng -->
                    <div class="col-sm-4 m-b-xs">
                        <div class="input-group">
                            <input type="search" name="keywords" class="form-control" placeholder="Từ khóa tìm kiếm">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Lọc</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            @php
                // Initialize filters array
                $filters = [];
                
                // Filter by order status
                if (request()->has('orderStatusId') && request()->orderStatusId != 0) {
                    $filters['orderStatusId'] = request()->orderStatusId;
                }

                // Filter by keywords (orderName)
                if (request()->has('keywords') && !empty(request()->keywords)) {
                    $keyword = request()->keywords;
                    $orderIds = DB::table('orders')
                        ->where('orderName', 'like', '%' . $keyword . '%')
                        ->pluck('orderId')
                        ->toArray();
                    if (!empty($orderIds)) {
                        $filters['orderId'] = $orderIds;
                    }
                }

                // Build the query for orders
                $ordersQuery = DB::table('orders');
                if (!empty($filters)) {
                    if (isset($filters['orderStatusId'])) {
                        $ordersQuery = $ordersQuery->where('orderStatusId', $filters['orderStatusId']);
                    }
                    if (isset($filters['orderId'])) {
                        $ordersQuery = $ordersQuery->whereIn('orderId', $filters['orderId']);
                    }
                }

                // Get orders with pagination (12 per page)
                $orders = $ordersQuery->paginate(12);
            @endphp

            <table class="table table-hover table-bordered align-middle">
                <thead>
                    <tr class="text-center table-primary">
                        <th>Mã ĐH</th>
                        <th>Tên đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Tổng giá trị</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="text-center">
                            <td>{{ $order->orderId }}</td>
                            <td>{{ $order->orderName }}</td>
                            <td>
                                @php
                                    // Fetch customer details based on customerId
                                    $customer = DB::table('customers')->where('customerId', $order->customerId)->first();
                                    echo $customer ? $customer->customerName : 'Chưa có khách hàng';
                                @endphp
                            </td>
                            <td>
                                @php
                                    // Fetch order status based on orderStatusId
                                    $order_status = DB::table('order_status')->where('orderStatusId', $order->orderStatusId)->first();
                                    echo $order_status ? $order_status->orderStatusName : 'Không xác định';
                                @endphp
                            </td>
                            <td>{{ $order->orderDate }}</td>
                            <td>{{ number_format($order->totalOrderValue, 0, ',', '.') }} VNĐ</td>
                            <td>
                                <a href="{{ route('admin.order.details', ['id' => $order->orderId]) }}" class="active styling-edit" ui-toggle-class="">
                                    <i class="fa fa-eye text-primary text-active"></i>
                                </a>
                                <a href="{{ route('admin.order.edit', ['id' => $order->orderId]) }}" class="active styling-edit" ui-toggle-class="">
                                    <i class="fa fa-pencil-square-o text-success text-active"></i>
                                </a>
                                <a href="{{ route('admin.delete.order', ['id' => $order->orderId]) }}" class="active styling-edit" ui-toggle-class="" onclick="confirmDelete(event, '{{ $order->orderId }}')">
                                    <i class="fa fa-times text-danger text"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center" style="justify-content: center; margin: 0 auto;">
                {{ $orders->links('pagination::bootstrap-4') }} <!-- Pagination centered -->
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(event, orderId) {
        event.preventDefault(); // Ngừng việc chuyển trang ngay lập tức

        // Hiển thị thông báo xác nhận
        iziToast.show({
            title: 'Xác nhận',
            message: 'Bạn có chắc chắn muốn hủy đơn hàng này?',
            position: 'center', // Hiển thị ở giữa màn hình
            closeOnClick: true,
            backgroundColor: '#f8d7da', // Màu nền đỏ nhạt cho popup
            color: 'red', // Màu chữ đỏ cho popup
            buttons: [
                // Nút Hủy với màu nền xanh dương
                ['<button style="background-color: #007bff; color: white;">Hủy</button>', function (instance, toast) {
                    instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                }],

                // Nút Xác nhận với màu nền đỏ
                ['<button style="background-color: #dc3545; color: white;">Xác nhận</button>', function (instance, toast) {
                    // Nếu người dùng xác nhận, thực hiện việc chuyển hướng đến route xóa đơn hàng
                    window.location.href = "{{ route('admin.delete.order', ['id' => '__orderId__']) }}".replace('__orderId__', orderId);
                }]
            ]
        });
    }
</script>


@endsection
