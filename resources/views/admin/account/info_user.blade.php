@extends("layouts.admin")
@section("content")
<!-- Bootstrap DatePicker JavaScript -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div class="container py-2">
        @php
            $all_inforadmin = DB::table('users')->where('id', $user->user_id)->get();
        @endphp
                <form action="{{ route('admin.save.info') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @foreach($all_inforadmin as $inforadmin)
                    <div class="col-lg-6">
                        <h4 class="text-uppercase text-title-form">THÔNG TIN CÁ NHÂN</h4>
                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Họ và tên</label>
                            <input type="text" class="form-control text-3 h-auto py-2" placeholder="Nhập tên người dùng" required="" name="name" value="{{ $inforadmin->name }}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="form-label mb-1 text-2 required">Email</label>
                            <input type="text" class="form-control text-3 h-auto py-2" name="email" value="{{ $inforadmin->email }}">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <button type="submit" name="update_info" class="btn btn-info">Cập nhật thông tin</button>
                    </div>
                    @endforeach
    </form>
</div>
 


</div>

@endsection
