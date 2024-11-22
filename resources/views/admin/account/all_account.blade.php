@extends("layouts.admin")
@section("content")
<!-- Bootstrap DatePicker JavaScript -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" type="text/javascript"></script>

<div style="min-height:48vh">
					<h3 class="text-center mt-3">DANH SÁCH TÀI KHOẢN</h3>
<div class="container">
<div class="row w3-res-tb">
        <form action="" method="get" class="w-100">
          <div class="d-flex justify-content-start mb-6">
            <div class="col-sm-10">
              <div class="input-group">
                <input type="search" name="keyword" class="form-control" placeholder="Tìm theo Tên" value="{{ request()->keyword ?? '' }}">
                <div class="input-group-append">
                  
                  @php
                    $roles = DB::table('accounts')->select('role')->distinct()->pluck('role');
                    $statuss = DB::table('accounts')->select('status')->distinct()->pluck('status');
                  @endphp
                  <select name="role" class="form-control ml-6 col-sm-5">
                    <option value="">Tất cả vai trò</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ request()->role == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                    @endforeach
                  </select>
                  {{-- Select status --}}
                  {{-- <select name="status" class="form-control ml-6 col-sm-5">
                      <option value="">Tất cả trạng thái</option>
                      @foreach($statuss as $status)
                          <option value="{{ $status }}" {{ request()->status == $status ? 'selected' : '' }}>
                            {{ $status == 1 ? 'Activate' : 'Deactivate' }}
                          </option>
                      @endforeach
                  </select> --}}
                  <select name="status" class="form-control ml-6 col-sm-5">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" {{ request()->status == '1' ? 'selected' : '' }}>Activate</option>
                    <option value="0" {{ request()->status == '0' ? 'selected' : '' }}>Deactivate</option>
                </select>
                <button type="submit" id="apply_button" class="btn btn-primary col-sm-2">Lọc</button>
                  <a href="{{ route('admins.export', ['keyword' => request()->keyword, 'role' => request()->role, 'status' => request()->status]) }}" class="btn btn-success col-sm-3">Xuất Excel</a>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
          <thead>
            <tr  class="text-center table-primary">
            <th>ID</th>
            <th>UserName</th>
            <th>Role</th>
            <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @foreach($all_accounts as $key => $account)
            <tr>
                <td>{{$account->id}}</td>
                <td>{{$account->username}}</td>
                <td>{{$account->role}}</td>
                <td>
                <a href="{{ route('admin.edit.account', ['id' => $account->id]) }}" class="active styling-edit" ui-toggle-class="" title="Sửa">
                  <i class="fa fa-pencil-square-o text-success text-active"></i></a>
                <a title="Đổi mật khẩu" href="{{ route('admin.password.account', ['id' => $account->id]) }}" class="active styling-edit" ui-toggle-class="">
                    <i class="fa fa-plus"></i></a>
                <a title="Xóa" onclick="return confirm('Bạn có muốn xóa không?')" href="{{ route('admin.delete.account', ['id' => $account->id]) }}" class="active styling-edit" ui-toggle-class="">
                  <i class="fa fa-times text-danger text"></i></a>
              </td>
            </tr>

            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-center">
    {{ $all_accounts->links('pagination::bootstrap-4') }}
</div>
@endsection
