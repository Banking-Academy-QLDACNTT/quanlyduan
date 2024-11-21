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
                    <a class="dropdown-item"  href="{{URL::to('admin/info-admin')}}"> Profile
{{--                        @if ($user)--}}
{{--                            @php--}}
{{--                                $all_inforadmin = DB::table('info_admins')->where('id', $user->admin_id)->get();--}}
{{--                            @endphp--}}
{{--                        @else--}}
{{--                            {{ "" }}--}}
{{--                        @endif--}}
{{--                        @if ($user)--}}
{{--                            @php--}}
{{--                                session(['admin_id' => $user->id]);--}}
{{--                            @endphp--}}
{{--                        @endif--}}
                    </a>
                    <a class="dropdown-item"  href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                    </a>
                    <a class="dropdown-item"  href="javascript:;">Help</a>
                    <form id="logoutForm" method="POST" action="{{ route('api.logout') }}">
                        @csrf
                        <input type="hidden" name="token-sanctum" value="{{ session('token') }}">
                        <input type="hidden" name="username" value="{{ session('account')->username }}">
                        <button type="submit" class="dropdown-item">
                            <i class="fa fa-sign-out pull-right"></i> Log Out
                        </button>
                    </form>
                    
                    {{-- <script>
                        // Listen for the form submit event
                        document.getElementById('logoutForm').addEventListener('submit', function(e) {
                            e.preventDefault(); // Prevent the default form submission
                    
                            // Get the token from the hidden input field
                            const token = document.querySelector('[name="token-sanctum"]').value;
                    
                            // Make the AJAX request with Axios, passing the token in the Authorization header
                            axios.post("{{ route('api.logout') }}", {
                                _token: document.querySelector('[name="_token"]').value,
                                token-sanctum: token
                            }, {
                                headers: {
                                    'Authorization': 'Bearer ' + token // Pass token in the Authorization header
                                    'Accept': 'application/json',
                                }
                            }).then(response => {
                                // Handle success
                                console.log(response.data.message);
                                window.location.href = "{{ route('admin.login') }}"; // Redirect to login page
                            }).catch(error => {
                                // Handle error
                                console.error(error.response.data);
                            });
                        });
                    </script> --}}
                    
                </div>
            </li>

            <li role="presentation" class="nav-item dropdown open">
                <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green">6</span>
                </a>
                <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1">
                    <li class="nav-item">
                        <a class="dropdown-item">
                            <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                            <span>
                <span>John Smith</span>
                <span class="time">3 mins ago</span>
              </span>
                            <span class="message">
                Film festivals used to be do-or-die moments for movie makers. They were where...
              </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="dropdown-item">
                            <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                            <span>
                <span>John Smith</span>
                <span class="time">3 mins ago</span>
              </span>
                            <span class="message">
                Film festivals used to be do-or-die moments for movie makers. They were where...
              </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="dropdown-item">
                            <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                            <span>
                <span>John Smith</span>
                <span class="time">3 mins ago</span>
              </span>
                            <span class="message">
                Film festivals used to be do-or-die moments for movie makers. They were where...
              </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="dropdown-item">
                            <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                            <span>
                <span>John Smith</span>
                <span class="time">3 mins ago</span>
              </span>
                            <span class="message">
                Film festivals used to be do-or-die moments for movie makers. They were where...
              </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <div class="text-center">
                            <a class="dropdown-item">
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
