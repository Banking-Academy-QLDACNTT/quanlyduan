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
                        <h4 class="text-uppercase text-title-form">THÔNG TIN TÀI KHOẢN</h4>
                        <div class="row col-lg-6">
                            <div class="form-group col-lg-6">
                                <label class="form-label mb-1 text-2 required">Họ và tên</label>
                                <input type="text" class="form-control text-3 h-auto py-2" placeholder="Nhập tên người dùng" required="" name="name" value="">
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="form-label mb-1 text-2 required">SĐT</label>
                                <input type="text" class="form-control text-3 h-auto py-2" required="" name="phoneNumber" value="">
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="form-label mb-1 text-2 required">Ngày sinh</label>
                                <input type="date" class="form-control text-3 h-auto py-2" name="dob" value="">
                            </div>
                            <div class="form-group col-lg-6">
                                @php
                                    $departments = DB::table('departments')->get();
                                @endphp
                                <label class="form-label mb-1 text-2 required">Phòng ban</label>
                                <select class="form-control text-3 h-auto py-2" name="department">
                                    <option value="" disabled selected>Chọn phòng ban</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->departmentId }}">
                                            {{ $department->departmentName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group col-lg-6">
                                    <label class="form-label mb-1 text-2 required">Username</label>
                                    <input type="text" value="" class="form-control text-3 h-auto py-2" name="username">
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="form-label mb-1 text-2 required">Mật khẩu</label>
                                <input type="password" class="form-control text-3 h-auto py-2" placeholder="Nhập mật khẩu" required="" name="password" value="">
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="form-label mb-1 text-2 required">Giới tính</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sex" id="1" value="1">
                                    <label class="form-check-label" for="1">Nam</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sex" id="0" value="0">
                                    <label class="form-check-label" for="0">Nữ</label>
                                </div>
                            </div>
                    
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
