@extends("layouts.admin")
@section("content")
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @php
        // Lấy tháng/năm được chọn
        $selectedMonth = request()->input('selected_month', now()->format('Y-m')); // Nếu không chọn tháng/năm, mặc định là tháng hiện tại

        // Kiểm tra dữ liệu tháng/năm được chọn
        // dd($selectedMonth); // Dùng để debug và kiểm tra tháng/năm

        // Tổng doanh thu trong tháng đã thanh toán (Dựa trên payment_slips và payment_status)
        $monthlyRevenue = DB::table('payment_slips')
            ->join('orders', 'payment_slips.orderID', '=', 'orders.orderId')
            ->join('payment_status', 'payment_slips.paymentStatusId', '=', 'payment_status.paymentStatusId')
            ->whereYear('orders.updateAt', '=', substr($selectedMonth, 0, 4)) // Dùng updateAt từ bảng orders
            ->whereMonth('orders.updateAt', '=', substr($selectedMonth, 5, 2)) // Dùng updateAt từ bảng orders
            ->whereIn('orders.orderStatusId', [2, 3]) // Lọc các đơn hàng có orderStatusId là 2 hoặc 3
            ->where('payment_status.paymentStatusName', '=', 'Đã thanh toán') // Giả sử trạng thái là "Hoàn thành"
            ->sum('payment_slips.orderValue');
        
        // Nếu không có dữ liệu doanh thu, set giá trị về 0
        if (is_null($monthlyRevenue)) {
            $monthlyRevenue = 0;
        }

        $revenueByProductType = DB::table('payment_slips')
    ->join('orders', 'payment_slips.orderID', '=', 'orders.orderId') // Kết nối bảng orders
    ->join('payment_status', 'payment_slips.paymentStatusId', '=', 'payment_status.paymentStatusId') // Kết nối bảng payment_status
    ->join('order_details', 'orders.orderId', '=', 'order_details.orderId') // Kết nối bảng order_details
    ->join('product', 'order_details.productId', '=', 'product.productId') // Kết nối bảng product
    ->join('product_type', 'product.productTypeId', '=', 'product_type.productTypeId') // Kết nối bảng product_type
    ->whereYear('orders.updateAt', '=', substr($selectedMonth, 0, 4)) // Dùng updateAt từ bảng orders
    ->whereMonth('orders.updateAt', '=', substr($selectedMonth, 5, 2)) // Dùng updateAt từ bảng orders
    ->whereIn('orders.orderStatusId', [2, 3]) // Lọc các đơn hàng có orderStatusId là 2 hoặc 3
    ->where('payment_status.paymentStatusName', '=', 'Đã thanh toán') // Điều kiện thanh toán là "Đã thanh toán"
    ->select(
        'product_type.productTypeName as type_name',
        DB::raw('SUM(payment_slips.orderValue) as revenue') // Tính tổng doanh thu từ payment_slips.orderValue
    )
    ->groupBy('product_type.productTypeName') // Nhóm theo loại sản phẩm
    ->get();

        // Doanh thu theo sản phẩm
        $revenueByProduct = DB::table('order_details')
            ->join('product', 'order_details.productId', '=', 'product.productId')
            ->join('orders', 'order_details.orderId', '=', 'orders.orderId') // Kết nối bảng orders
            ->whereYear('orders.updateAt', '=', substr($selectedMonth, 0, 4)) // Dùng updateAt từ bảng orders
            ->whereMonth('orders.updateAt', '=', substr($selectedMonth, 5, 2)) // Dùng updateAt từ bảng orders
            ->whereIn('orders.orderStatusId', [2, 3]) // Lọc các đơn hàng có orderStatusId là 2 hoặc 3
            ->select(
                'product.productName as product_name',
                DB::raw('SUM(order_details.quantity * order_details.unit_price) as revenue')
            )
            ->groupBy('product.productName')
            ->get();

            // Thống kê doanh thu theo loại khách hàng, tính dựa trên payment_slips
$revenueByCustomerType = DB::table('payment_slips')
    ->join('orders', 'payment_slips.orderID', '=', 'orders.orderId') // Kết nối với bảng orders
    ->join('customers', 'orders.customerId', '=', 'customers.customerId') // Kết nối với bảng customers
    ->join('customer_type', 'customers.customerTypeId', '=', 'customer_type.customerTypeId') // Kết nối với bảng customer_type
    ->join('payment_status', 'payment_slips.paymentStatusId', '=', 'payment_status.paymentStatusId') // Kết nối với bảng payment_status
    ->whereYear('orders.updateAt', '=', substr($selectedMonth, 0, 4)) // Lọc theo năm
    ->whereMonth('orders.updateAt', '=', substr($selectedMonth, 5, 2)) // Lọc theo tháng
    ->whereIn('orders.orderStatusId', [2, 3]) // Lọc các đơn hàng có orderStatusId là 2 hoặc 3
    ->where('payment_status.paymentStatusName', '=', 'Đã thanh toán') // Chỉ lấy các đơn hàng đã thanh toán
    ->select(
        'customer_type.customerTypeName as customer_type',
        DB::raw('SUM(payment_slips.orderValue) as revenue') // Tính tổng doanh thu dựa trên payment_slips.orderValue
    )
    ->groupBy('customer_type.customerTypeName') // Nhóm theo loại khách hàng
    ->get();


        // Chuẩn bị dữ liệu cho biểu đồ
        $productTypeLabels = $revenueByProductType->pluck('type_name');
        $productTypeRevenues = $revenueByProductType->pluck('revenue');

        $productLabels = $revenueByProduct->pluck('product_name');
        $productRevenues = $revenueByProduct->pluck('revenue');

        $customerTypeLabels = $revenueByCustomerType->pluck('customer_type');
        $customerTypeRevenues = $revenueByCustomerType->pluck('revenue');
    @endphp

    <div class="row">
        <div class="col-md-12">
            <h3>Thống kê doanh thu</h3>
            <form method="get" action="{{ url()->current() }}">
                <label for="month">Chọn tháng/năm:</label>
                <input type="month" id="month" name="selected_month" value="{{ $selectedMonth }}" class="form-control" onchange="this.form.submit()">
            </form>
            <div class="tile_count">
                <div class="col-md-4 col-sm-4 tile_stats_count">
                    <span class="count_top"><i class="fa fa-money"></i> Doanh thu trong tháng</span>
                    <div class="count">{{ number_format($monthlyRevenue) }} VND</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <canvas id="revenueByProductTypeChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="revenueByProductChart"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <canvas id="revenueByCustomerTypeChart"></canvas>
        </div>
    </div>

    <script>
        // Doanh thu theo loại sản phẩm
        var ctx1 = document.getElementById('revenueByProductTypeChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: {!! json_encode($productTypeLabels) !!},
                datasets: [{
                    label: 'Doanh thu theo loại sản phẩm',
                    data: {!! json_encode($productTypeRevenues) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var ctx3 = document.getElementById('revenueByCustomerTypeChart').getContext('2d');
if ({!! json_encode($customerTypeLabels) !!}.length === 0 || {!! json_encode($customerTypeRevenues) !!}.length === 0) {
    alert("Không có dữ liệu doanh thu theo loại khách hàng.");
} else {
    new Chart(ctx3, {
        type: 'pie',
        data: {
            labels: {!! json_encode($customerTypeLabels) !!},
            datasets: [{
                label: 'Doanh thu theo loại khách hàng',
                data: {!! json_encode($customerTypeRevenues) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

    </script>
@endsection
