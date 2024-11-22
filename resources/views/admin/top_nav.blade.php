<div class="nav_menu">
    <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
    </div>
    <nav class="nav navbar-nav">
        <ul class=" navbar-right">
            <li class="nav-item dropdown open" style="padding-left: 15px;">
                <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('public/backend/images/img.jpg') }}">
                </a>
                <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item"  href="{{URL::to('admin/info-admin')}}"> Profile,
                       @if ($user)
                           @php
                           $all_user = DB::table('users')->where('id', $user->user_id)->select('name')->first();
                           $name = $all_user->name;
                            @endphp
                            <p>{{ $name }}</p>
                      @else
                           {{ "" }}
                     @endif
                       @if ($user)
                        @php
                               session(['admin_id' => $user->id]);
                        @endphp
                       @endif
                    </a>
                    
                    <a class="dropdown-item"  href="{{ route('admin.logout') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                </div>
            </li>
        </ul>
    </nav>
</div>
