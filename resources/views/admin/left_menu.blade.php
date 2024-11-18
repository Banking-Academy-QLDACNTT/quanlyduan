<div class="navbar nav_title" style="border: 0;">
    <a href="#" class="site_title"><i class="fa fa-paw"></i> <span>ADMIN</span></a>
  </div>

  <div class="clearfix"></div>

  <!-- menu profile quick info -->
  <div class="profile clearfix">
    <div class="profile_pic">
      <img src="{{ asset('public/backend/images/img.jpg')}}" alt="" class="img-circle profile_img">
    </div>
    <div class="profile_info">
      <span>Welcome,</span>
      <h2>{{ $user->username }}</h2>
    </div>
  </div>
  <!-- /menu profile quick info -->

  <br />

  <!-- sidebar menu -->
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
      <h3>Quản trị</h3>
      <ul class="nav side-menu">
          <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-table"></i>Thống kê</a>
          </li>
        <li><a><i class="fa fa-table"></i>Quản lý đăng ký thi <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="">Danh sách đăng ký thi</a></li>
            <li><a href="">Thêm đăng ký thi</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-bar-chart-o"></i> Quản lý lịch thi <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
          <li><a href="">Danh sách lịch thi</a></li>
          <li><a href="">Thêm mới lịch thi</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-bar-chart-o"></i> Quản lý địa điểm thi <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
          <li><a href="">Danh sách địa điểm thi</a></li>
          <li><a href="">Thêm mới địa điểm thi</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-bar-chart-o"></i> Quản lý bài thi <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
          <li><a href="">Danh sách bài thi</a></li>
          <li><a href="">Thêm mới bài thi</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-table"></i>Quản lý thí sinh <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="">Danh sách tài khoản thí sinh</a></li>
            <li><a href="">Danh sách thí sinh</a></li>
            <li><a href="">Thêm mới tài khoản</a></li>
          </ul>
        </li>
        <li><a><i class="fa fa-table"></i>Quản lý admin <span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            <li><a href="">Danh sách tài khoản admin</a></li>
            <li><a href="">Danh sách admin</a></li>
            <li><a href="">Thêm tài khoản admin</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="menu_section">

    </div>

  </div>
  <!-- /sidebar menu -->

  <!-- /menu footer buttons -->
  <div class="sidebar-footer hidden-small">
    <a data-toggle="tooltip" data-placement="top" title="Settings">
      <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
      <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Lock">
      <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
      <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
    </a>
  </div>
  <!-- /menu footer buttons -->
