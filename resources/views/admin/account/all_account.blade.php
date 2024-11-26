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
            <div class="col-sm-5">
              <div class="input-group">
                <input type="search" name="keyword" class="form-control" placeholder="Tìm theo Tên" value="{{ request()->keyword ?? '' }}">
                <select name="department" class="form-control ml-2">
                  <option value="">Phòng ban</option>
                  @foreach ( $departments as $key => $department) )
                  <option value="{{ $department->departmentId }}" {{ request()->department == $department->departmentId ? 'selected' : '' }}>
                    {{ $department->departmentName }}
                  </option>
                  @endforeach
                </select>
                <div class="input-group-append ml-2">
                  <button type="submit" id="apply_button" class="btn btn-primary col-sm-5 form-control">Lọc</button>
                  <a href="{{ route('admins.export', ['keyword' => request()->keyword, 'department' =>request()->department]) }}" class="btn btn-success col-sm-9 form-control">Xuất Excel</a>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      </div>
      <div class="table-responsive">
        {{-- <table class="table table-hover table-bordered align-middle">
          <thead>
            <tr class="text-center table-primary">
            <th>Tài khoản</th>
            <th>Họ tên</th>
            <th>Ngày sinh</th>
            <th>SĐT</th>
            <th>Phòng ban</th>
            <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @foreach($all_accounts as $key => $account)
            <tr>
                <td>{{$account->username}}</td>
                <td>{{$account->name}}</td>
                <td>{{$account->dateOfBirth}}</td>
                <td>{{$account->phoneNumber}}</td>
                <td>{{$account->departmentName}}</td>
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
        </table> --}}
        <table class="table table-hover table-bordered align-middle">
          <thead>
            <tr class="text-center table-primary">
              <th>
                Tài khoản
                <a href="{{ route('admin.accounts', ['sort' => 'username', 'order' => 'asc', 'keyword' => request()->keyword, 'department' => request()->department]) }}" 
                   class="text-decoration-none {{ request('sort') == 'username' && request('order') == 'asc' ? 'text-success' : '' }}">&#9650;</a>
                <a href="{{ route('admin.accounts', ['sort' => 'username', 'order' => 'desc', 'keyword' => request()->keyword, 'department' => request()->department]) }}" 
                   class="text-decoration-none {{ request('sort') == 'username' && request('order') == 'desc' ? 'text-success' : '' }}">&#9660;</a>
              </th>
              <th>
                Họ tên
                <a href="{{ route('admin.accounts', ['sort' => 'name', 'order' => 'asc', 'keyword' => request()->keyword, 'department' => request()->department]) }}" 
                   class="text-decoration-none {{ request('sort') == 'name' && request('order') == 'asc' ? 'text-success' : '' }}">&#9650;</a>
                <a href="{{ route('admin.accounts', ['sort' => 'name', 'order' => 'desc', 'keyword' => request()->keyword, 'department' => request()->department]) }}" 
                   class="text-decoration-none {{ request('sort') == 'name' && request('order') == 'desc' ? 'text-success' : '' }}">&#9660;</a>
              </th>
              <th>
                Ngày sinh
                <a href="{{ route('admin.accounts', ['sort' => 'dateOfBirth', 'order' => 'asc', 'keyword' => request()->keyword, 'department' => request()->department]) }}" 
                   class="text-decoration-none {{ request('sort') == 'dateOfBirth' && request('order') == 'asc' ? 'text-success' : '' }}">&#9650;</a>
                <a href="{{ route('admin.accounts', ['sort' => 'dateOfBirth', 'order' => 'desc', 'keyword' => request()->keyword, 'department' => request()->department]) }}" 
                   class="text-decoration-none {{ request('sort') == 'dateOfBirth' && request('order') == 'desc' ? 'text-success' : '' }}">&#9660;</a>
              </th>
              <th>
                SĐT
                <a href="{{ route('admin.accounts', ['sort' => 'phoneNumber', 'order' => 'asc', 'keyword' => request()->keyword, 'department' => request()->department]) }}" 
                   class="text-decoration-none {{ request('sort') == 'phoneNumber' && request('order') == 'asc' ? 'text-success' : '' }}">&#9650;</a>
                <a href="{{ route('admin.accounts', ['sort' => 'phoneNumber', 'order' => 'desc', 'keyword' => request()->keyword, 'department' => request()->department]) }}" 
                   class="text-decoration-none {{ request('sort') == 'phoneNumber' && request('order') == 'desc' ? 'text-success' : '' }}">&#9660;</a>
              </th>
              <th>
                Phòng ban
                <a href="{{ route('admin.accounts', ['sort' => 'departmentName', 'order' => 'asc', 'keyword' => request()->keyword, 'department' => request()->department]) }}" 
                   class="text-decoration-none {{ request('sort') == 'departmentName' && request('order') == 'asc' ? 'text-success' : '' }}">&#9650;</a>
                <a href="{{ route('admin.accounts', ['sort' => 'departmentName', 'order' => 'desc', 'keyword' => request()->keyword, 'department' => request()->department]) }}" 
                   class="text-decoration-none {{ request('sort') == 'departmentName' && request('order') == 'desc' ? 'text-success' : '' }}">&#9660;</a>
              </th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @foreach($all_accounts as $account)
            <tr>
              <td>{{$account->username}}</td>
              <td>{{$account->name}}</td>
              <td>{{$account->dateOfBirth}}</td>
              <td>{{$account->phoneNumber}}</td>
              <td>{{$account->departmentName}}</td>
              <td>
                <a href="{{ route('admin.edit.account', ['id' => $account->id]) }}" class="active styling-edit" title="Sửa">
                  <i class="fa fa-pencil-square-o text-success"></i></a>
                <a title="Đổi mật khẩu" href="{{ route('admin.password.account', ['id' => $account->id]) }}" class="active styling-edit">
                  <i class="fa fa-plus"></i></a>
                <a title="Xóa" onclick="return confirm('Bạn có muốn xóa không?')" href="{{ route('admin.delete.account', ['id' => $account->id]) }}" class="active styling-edit">
                  <i class="fa fa-times text-danger"></i></a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        
        
      </div>
    </div>
    <div class="d-flex justify-content-center">
      {{ $all_accounts->links('pagination::bootstrap-4') }}
    </div>
  </div>
</div>
@endsection
