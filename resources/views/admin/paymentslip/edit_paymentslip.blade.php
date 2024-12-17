@extends("layouts.admin")
@section("content")
<!-- Bootstrap DatePicker JavaScript -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

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

            <form action="{{ route('admin.update.paymentslip', ['id' => $paymentslip->paymentSlipId]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="col-lg-6">
                    <h4 class="text-uppercase text-title-form">CHỈNH SỬA THÔNG TIN PHIẾU THANH TOÁN</h4>
                    <div class="row">
                    <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Mã đơn hàng</label>
                           
                            <input type="text" class="form-control text-3 h-auto py-2" name="orderId" value="{{$paymentslip->orderId}}" readonly>
                        </div>

                        <div class="form-group col-lg-12">
                           
                            <label class="form-label mb-1 text-2 required">Tiền đặt cọc</label>
                            <input type="number" id = "tiendatcoc" class="form-control text-3 h-auto py-2" name="tiendatcoc" value="{{$paymentslip->deposit}}">
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2 required">Ngày đặt cọc</label>
                            <input type="date" class="form-control text-3 h-auto py-2" name="ngaydatcoc" value="{{$paymentslip->depositDate}}">
                            </div>

                            <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2 required">Ngày thanh toán</label>
                            <input type="date" class="form-control text-3 h-auto py-2" name="ngaythanhtoan" value="{{$paymentslip->paymentDate}}">
                        </div>

                      
                        <div class="form-group col-lg-12">
                                            <label class="form-label mb-1 text-2 required">Chọn phương thức thanh toán</label>
                                           @php
                                                $all_pttt= DB::table('payment_method')->get();
                                            @endphp
                                            <select class="form-select form-control h-auto py-2" id="diadiemthi_id" name="pttt">
                                                @foreach($all_pttt as $key => $pttt)
                                                    <option value="{{ $pttt->paymentMethodId }}" {{ $paymentslip->paymentMethodId == $pttt->paymentMethodId ? 'selected' : '' }}>{{ $pttt -> paymentMethodName }}</option>
                                                @endforeach
                                            </select>
                                        </div>



                        <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2 required">Ghi chú</label>
                            <input type="text" class="form-control text-3 h-auto py-2" placeholder="Nhập ghi chú" name="ghichu" value="{{$paymentslip->note}}">
                        </div>
                       
                        <div class="form-group col-lg-12">
                                            <label class="form-label mb-1 text-2 required">Loại trạng thái phiếu</label>
                                           @php
                                                $all_ttp= DB::table('payment_status')->get();
                                            @endphp
                                            <select class="form-select form-control h-auto py-2" id="diadiemthi_id" name="ttp">
                                                @foreach($all_ttp as $key => $ttp)
                                                    <option value="{{ $ttp->paymentStatusId }}" {{ $paymentslip->paymentStatusId == $ttp->paymentStatusId ? 'selected' : '' }}>{{ $ttp -> paymentStatusName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12">
                    <button type="submit" name="edit_paymentslip" class="btn btn-info">Cập nhật thông tin phiếu thanh toán</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Khi loại khách hàng thay đổi
    $('#paymentslipType').on('change', function() {
        var paymentslipType = $(this).val();

        // Hiển thị/ẩn trường giới tính dựa trên loại khách hàng
        if (paymentslipType == '1') {
            $('#genderField').show();
        } else {
            $('#genderField').hide();
        }
    });
});
</script>
@endsection