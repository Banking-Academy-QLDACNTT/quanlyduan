@extends("layouts.admin")
@section("content")
<!-- Bootstrap DatePicker JavaScript -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" type="text/javascript"></script>

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
                    <form  action="{{ route('admin.save.account') }}"  method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="col-lg-6">
                            <h4 class="text-uppercase text-title-form">THÔNG TIN TÀI KHOẢN</h4>
                            <div class="form-group col-lg-6">
                                <label class="form-label mb-1 text-2 required">Tên người dùng</label>
                                <input type="text" value="" class="form-control text-3 h-auto py-2" placeholder="Nhập tên người dùng" required="" name="name">
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="form-label mb-1 text-2 required">Tên tài khoản</label>
                                <input type="text" value="" class="form-control text-3 h-auto py-2" placeholder="Nhập tên tài khoản" required="" name="username">
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="form-label mb-1 text-2 required">Mật khẩu</label>
                                <input type="password" class="form-control text-3 h-auto py-2" placeholder="Nhập mật khẩu" name="password" required="">
                            </div>
                            <div class="form-group col-lg-12">
                                    <label class="form-label mb-1 text-2 required">Email</label>
                                    <input type="text" value="" class="form-control text-3 h-auto py-2" name="email" data-msg-required="Vui lòng nhập Email" placeholder="Nhập Email" maxlength="255" data-rule-pattern="^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$" data-msg-pattern="Vui lòng nhập email đúng định dạng" required="">
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="form-label mb-1 text-2 required">Phân quyền</label>
                                <input type="text" class="form-control text-3 h-auto py-2" name="role" required="">
                            </div>
                            <div class="form-group">

                        </div>

                        </div>
                        <div class="form-group col-lg-12">
                        <button type="submit" name="add_account" class="btn btn-info">Thêm mới tài khoản</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                    </form>
</div>

@endsection
