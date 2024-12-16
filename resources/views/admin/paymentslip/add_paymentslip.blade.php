@extends("layouts.admin")
@section("content")
<!-- Bootstrap DatePicker JavaScript -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<style>
    .form-select {
        color: black; /* Màu chữ */
        background-color: white; /* Nền trắng */
    }
</style>

<div class="container py-2">
    <div class="mb-4">
        <div class="row place-userInfor">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.save.paymentslip') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="col-lg-6">
                    <h4 class="text-uppercase text-title-form">THÔNG TIN PHIẾU THANH TOÁN</h4>
                    <div class="row">
                        

                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Mã đơn hàng</label>
                            @php
                                // Lấy các orderId chưa có trong bảng payment_slips
                                $ordersWithoutPaymentslip = DB::table('orders')
                                    ->leftJoin('payment_slips', 'orders.orderId', '=', 'payment_slips.orderId')
                                    ->whereNull('payment_slips.orderId') // Chỉ lấy các đơn hàng chưa có paymentslip
                                    ->select('orders.orderId as OrderID')
                                    ->get();
                            @endphp
                            <select class="form-select form-control h-auto py-2" id="paymentslipType" data-msg-required="Chọn mã đơn hàng" required="" name="orderId">
                                <option value="" disabled selected>Chọn mã đơn hàng</option>
                                @foreach($ordersWithoutPaymentslip as $order)
                                    <option value="{{ $order->OrderID }}">{{ $order->OrderID }}</option>
                                @endforeach
                                </select>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2 required">Tiền đặt cọc</label>
                            <input type="number" id = "tiendatcoc" class="form-control text-3 h-auto py-2" name="tiendatcoc" value="">
                        </div>
                        
                        

                        <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2 required">Ngày đặt cọc</label>
                            <input type="date" id ="ngaydatcoc" class="form-control text-3 h-auto py-2" name="ngaydatcoc" value="">
                            </div>

                        <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2 required">Ngày thanh toán</label>
                            <input type="date" id = "ngaythanhtoan" class="form-control text-3 h-auto py-2" name="ngaythanhtoan" value="">
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Loại phương thức thanh toán</label>
                                @php
                                    $all_payment_type = DB::table('payment_method')->get();
                                @endphp
                                <select class="form-select form-control h-auto py-2" data-msg-required="Chọn loại phương thức" required="" name="pttt">
                                    <option value="" disabled selected>Chọn loại phương thức</option>
                                    @foreach($all_payment_type as $key => $payment_type)
                                        <option value="{{ $payment_type -> paymentMethodId }}">{{ $payment_type -> paymentMethodName }}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2 required">Ghi chú</label>
                            <input type="text" class="form-control text-3 h-auto py-2" placeholder="Nhập ghi chú" name="ghichu">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Loại trạng thái phiếu</label>
                                @php
                                    $all_payment_type1 = DB::table('payment_status')->get();
                                @endphp
                                <select class="form-select form-control h-auto py-2" data-msg-required="Chọn loại trạng thái" required="" name="ttp">
                                    <option value="" disabled selected>Chọn loại trạng thái</option>
                                    @foreach($all_payment_type1 as $key => $payment_type1)
                                        <option value="{{ $payment_type1 -> paymentStatusId }}">{{ $payment_type1 -> paymentStatusName }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group col-lg-12">
                    <button type="submit" name="add_paymentslip" class="btn btn-info">Thêm mới phiếu thanh toán</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ngayDatCoc = document.getElementById('ngaydatcoc');
        const ngayThanhToan = document.getElementById('ngaythanhtoan');
        
        // Kiểm tra khi biểu mẫu được gửi
        document.querySelector('form').addEventListener('submit', function (e) {
            const ngayDatCocValue = new Date(ngayDatCoc.value);
            const ngayThanhToanValue = new Date(ngayThanhToan.value);

            if (ngayDatCocValue > ngayThanhToanValue) {
                e.preventDefault(); // Ngăn chặn gửi biểu mẫu
                alert('Ngày đặt cọc không được lớn hơn ngày thanh toán!');
            }
        });

        // Kiểm tra khi người dùng thay đổi giá trị
        ngayDatCoc.addEventListener('change', function () {
            validateDates();
        });

        ngayThanhToan.addEventListener('change', function () {
            validateDates();
        });

        function validateDates() {
            const ngayDatCocValue = new Date(ngayDatCoc.value);
            const ngayThanhToanValue = new Date(ngayThanhToan.value);

            if (ngayDatCocValue > ngayThanhToanValue) {
                ngayThanhToan.setCustomValidity('Ngày thanh toán phải lớn hơn hoặc bằng ngày đặt cọc!');
            } else {
                ngayThanhToan.setCustomValidity('');
            }
        }
    });
</script>


@endsection