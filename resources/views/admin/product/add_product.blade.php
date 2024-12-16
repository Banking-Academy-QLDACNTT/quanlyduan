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

            <form action="{{ route('admin.save.product') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="col-lg-6">
                    <h4 class="text-uppercase text-title-form">THÔNG TIN SẢN PHẨM</h4>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label class="form-label mb-1 text-2 required">Tên sản phẩm</label>
                            <input type="text" class="form-control text-3 h-auto py-2" placeholder="Nhập tên sản phẩm" name="tensanpham" required="">
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Loại sản phẩm</label>
                                @php
                                    $all_product_type = DB::table('product_type')->get();
                                @endphp
                                <select class="form-select form-control h-auto py-2" id="productType" data-msg-required="Chọn loại sản phẩm" required="" name="loaisanpham">
                                    <option value="" disabled selected>Chọn loại sản phẩm</option>
                                    @foreach($all_product_type as $key => $product_type)
                                        <option value="{{ $product_type -> productTypeId }}">{{ $product_type -> productTypeName }}</option>
                                    @endforeach
                                </select>
                        </div>


                        <div class="form-group col-lg-12">
    <label class="form-label mb-1 text-2 required">Giá bán</label>
    <input type="text" id="giaban" class="form-control text-3 h-auto py-2" 
           placeholder="Nhập giá bán" name="giaban" required="" 
           oninput="validateGiaBan(this)">
    <small id="error-message" style="color: red; display: none;">Vui lòng nhập giá bán là số lớn hơn 0</small>
</div>

<script>
    function validateGiaBan(input) {
        const value = input.value.trim();
        const errorMessage = document.getElementById("error-message");

        // Kiểm tra giá trị nhập
        if (!/^\d*\.?\d+$/.test(value) || parseFloat(value) <= 0) {
            errorMessage.style.display = "block"; // Hiển thị thông báo lỗi
            input.setCustomValidity("Giá bán phải là số lớn hơn 0"); // Cảnh báo khi submit
        } else {
            errorMessage.style.display = "none"; // Ẩn thông báo lỗi
            input.setCustomValidity(""); // Không có lỗi
        }
    }
</script>

                <div class="form-group col-lg-12">
                    <button type="submit" name="add_product" class="btn btn-info">Thêm mới sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection