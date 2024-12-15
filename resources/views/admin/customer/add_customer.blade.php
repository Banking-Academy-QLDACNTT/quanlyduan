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

            <form action="{{ route('admin.save.customer') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="col-lg-6">
                    <h4 class="text-uppercase text-title-form">THÔNG TIN KHÁCH HÀNG</h4>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2 required">Tên khách hàng</label>
                            <input type="text" class="form-control text-3 h-auto py-2" placeholder="Nhập tên khách hàng" name="tenkhachhang" required="">
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Loại khách hàng</label>
                                @php
                                    $all_customer_type = DB::table('customer_type')->get();
                                @endphp
                                <select class="form-select form-control h-auto py-2" id="customerType" data-msg-required="Chọn loại khách hàng" required="" name="loaikhachhang">
                                    <option value="" disabled selected>Chọn loại khách hàng</option>
                                    @foreach($all_customer_type as $key => $customer_type)
                                        <option value="{{ $customer_type -> customerTypeId }}">{{ $customer_type -> customerTypeName }}</option>
                                    @endforeach
                                </select>
                        </div>

                        <!-- Giới tính (hiện khi loại khách hàng là 1) -->
                        <div class="form-group col-lg-6" id="genderField" style="display: none;">
                            <label class="form-label mb-1 text-2">Giới tính</label>
                            <select class="form-select form-control h-auto py-2" name="gioitinh">
                                <option value="" disabled selected>Vui lòng chọn giới tính</option>
                                <option value="0">Nam</option>
                                <option value="1">Nữ</option>
                            </select>
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2 required">Địa chỉ</label>
                            <input type="text" class="form-control text-3 h-auto py-2" placeholder="Nhập địa chỉ" name="diachi" required="">
                        </div>

                        <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2 required">Ghi chú</label>
                            <input type="text" class="form-control text-3 h-auto py-2" placeholder="Nhập ghi chú" name="ghichu">
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Số điện thoại</label>
                            <input type="tel" class="form-control text-3 h-auto py-2" placeholder="Số điện thoại" name="sodienthoai" pattern="^0\d{9}$" maxlength="10" required="" value="0">
                            <small class="form-text text-muted">Nhập số điện thoại</small>
                        </div>


                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Email</label>
                            <input type="email" class="form-control text-3 h-auto py-2" placeholder="Nhập email" name="email" required="">
                        </div>
                    </div>
                </div>
                
                <div class="form-group col-lg-12">
                    <button type="submit" name="add_customer" class="btn btn-info">Thêm mới khách hàng</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Khi loại khách hàng thay đổi
    $('#customerType').on('change', function() {
        var customerType = $(this).val();
        
        // Hiển thị/ẩn trường giới tính dựa trên loại khách hàng
        if (customerType == '1') {
            $('#genderField').show();
        } else {
            $('#genderField').hide();
        }
    });

    // Khởi tạo sự kiện khi trang được tải lần đầu
    var initialCustomerType = $('#customerType').val();
    if (initialCustomerType == '1') {
        $('#genderField').show();
    } else {
        $('#genderField').hide();
    }
});
</script>
@endsection
