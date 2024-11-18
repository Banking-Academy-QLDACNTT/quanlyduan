@extends('layout')
@section('content')
<div class="untree_co-hero inner-page overlay" style="background-image: url('{{ asset('public/frontend/images/home_toeic.jpg') }}');">
    <div class="container">
      <div class="row align-items-center justify-content-center">
        <div class="col-12">
          <div class="row justify-content-center ">
            <div class="col-lg-6 text-center ">
              <h1 class="mb-4 heading text-white" data-aos="fade-up" data-aos-delay="100">Đăng nhập trang quản trị</h1>
            </div>
          </div>
        </div>
      </div> <!-- /.row -->
    </div> <!-- /.container -->
</div> <!-- /.untree_co-hero -->
<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="untree_co-section">
    <div class="container">
      <div class="row mb-5 justify-content-center">
        <div class="col-lg-5 mx-auto order-1" data-aos="fade-up" data-aos-delay="200">
          <form action="{{ route('admin.loginPost') }}" class="form-box" method="POST" id="admin-login">
            @csrf
            <div class="row">
              <div class="col-12 mb-3">
                <input type="text" class="form-control" placeholder="Username" name="username">
              </div>
              <div class="col-12 mb-3">
                <input type="password" class="form-control" placeholder="Mật khẩu" name="password">
              </div>
              <div class="col-12 mb-3">
                <label class="control control--checkbox">
                  <span class="caption">Nhớ mật khẩu</span>
                  <input type="checkbox" checked="checked" />
                  <div class="control__indicator"></div>
                </label>
              </div>
              <div class="col-12 mb-3 d-flex">
                <div class="captcha-img">
                  <img src="{{ captcha_src() }}" alt="Captcha" style="margin-top: 10px;">
                </div>
                <div class="captcha-input">
                  <input type="text" name="captcha" class="form-control" placeholder="Nhập captcha" required>
                </div>
              </div>
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary btn-flat btn-login">Đăng nhập</button>
              </div>
            </div>
          </form>
          @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div> <!-- /.untree_co-section -->
@endsection

<style>
  .captcha-img {
    margin-right: 10px;
  }
  .captcha-input {
    flex: 1;
  }
  .btn-login {
    width: 50%; /* Chỉnh độ rộng của nút đăng nhập */
    margin: 0 auto; /* Căn giữa nút */
    display: block; /* Đảm bảo nút là một phần tử khối để căn giữa */
  }
</style>
