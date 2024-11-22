@extends("layouts.admin")
@section("content")
<!-- Bootstrap DatePicker JavaScript -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" type="text/javascript"></script>




 <style>
.custom-text-right {
    text-align: right;
    font-weight: bold;
    font-size: 1.2em; /* Điều chỉnh kích thước chữ theo mong muốn */
}

</style>
<div class="container py-2">
        <div class=" mb-4">
            <div class="">
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
                    <form  action="{{ route('admin.changepassword.account', ['id' => $admin->id]) }}"  method="post" enctype="multipart/form-data">
                        @csrf

                            <h4 class="text-uppercase text-title-form">ĐỔI MẬT KHẨU TÀI KHOẢN</h4>
                            <div class="form-group col-lg-12">
                                <label class="form-label mb-1 text-2 required">Nhập mật khẩu cũ</label>
                                <input type="password" value="" class="form-control text-3 h-auto py-2" placeholder="Nhập mật khẩu cũ" name="current_password" >
                                @error('current_password')
                                <small class = "help-block">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class ="row">
                            <div class="form-group col-lg-6">
                                <label class="form-label mb-1 text-2 required">Nhập mật khẩu mới</label>
                                <input type="password" value="" class="form-control text-3 h-auto py-2" placeholder="Nhập mật khẩu mới" name="new_password" >
                                @error('new_password')
                                <small class = "help-block">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="form-label mb-1 text-2 required">Nhập lại mật khẩu mới</label>
                                <input type="password" value="" class="form-control text-3 h-auto py-2" placeholder="Nhập mật khẩu mới" name="confirm_password">
                                @error('confirm_password')
                                <small class = "help-block">{{ $message }}</small>
                                @enderror
                            </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                        <button type="submit" name="changePassword" class="btn btn-info">Cập nhật mật khẩu</button>

                    </div>
                    </form>
                </div>
            </div>
        </div>

</div>

@endsection
