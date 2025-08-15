<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md " style="background: #fff">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-header " style="background: #000000; padding: 10px">
            <a class="navbar-brand" href="{{Route('backend.dashboard')}}">
                <!-- Logo icon -->
                <b>
                    <!-- Dark Logo icon -->
{{--                    <img src="{{ url('/logo_vipshop.png') }}" height="45" alt="homepage"--}}
{{--                         class="dark-logo logo-img" style="height: 180px; width: 180px; object-fit: contain"/>--}}
                    <!-- Light Logo icon -->

                </b>
            </a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse" style="box-shadow: 1px 4px 4px 2px #ccc">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav mr-auto mt-md-0" >
                <!-- This is  -->
                <li class="nav-item"  >
                    <input type="text" class="form-control ml-2 search_ordercode"  onkeypress="logInput(event)" placeholder="Tim kiếm mã đơn hoặc số điện thoại">
                </li>
                <li class="nav-item" style="display: none">
                    <a class="nav-link  nav-toggler hidden-md-up text-muted waves-effect waves-dark"
                       href="javascript:void(0)"><i class="mdi mdi-menu"></i></a>
                </li>
                <li class="nav-item m-l-10" style="display: none">
                    <a class="nav-link  sidebartoggler hidden-sm-down text-muted waves-effect waves-dark"
                       href="javascript:void(0)">
                        <i class="fa-solid fa-bars" style="color: #000"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav my-lg-0">

                <!-- Profile -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark"
                       href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{--<img src="{{ asset('storage/uploads/backend/' . Auth()->guard('backend')->user()->avatar) }}" alt="user" class="profile-pic"/></a>--}}
                        @if(!empty( Auth()->guard('backend')->user()->avatar_file_path))
                            <img
                                src="{{   Auth()->guard('backend')->user()->avatar_file_path }}"
                                alt="user"
                                class="profile-pic"/></a>
                    @else
                        <img src="{{ asset('/storage/backend/assets/images/users/5.jpg') }}" alt="user"
                             class="profile-pic"/></a>

                    @endif

                    <div class="dropdown-menu dropdown-menu-right scale-up">
                        <ul class="dropdown-user">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img">
                                        {{--<img src="{{ asset('storage/uploads/backend/' . Auth()->guard('backend')->user()->avatar) }}" alt="user">--}}
                                        @if(!empty( Auth()->guard('backend')->user()->avatar_file_path))
                                            <img
                                                src="{{ Auth()->guard('backend')->user()->avatar_file_path }}"
                                                alt="user">
                                        @else
                                            <img src="{{ asset('/storage/backend/assets/images/users/5.jpg') }}"
                                                 alt="user">
                                        @endif
                                    </div>
                                    <div class="u-text">

                                        <h4>{{Auth()->guard('backend')->user()->name}}</h4>
                                        <p class="text-dark font-weight-bold">{{Auth()->guard('backend')->user()->email}}</p>

                                        <a href="{{route('backend.users.profile')}}"
                                           class="btn btn-rounded btn-danger btn-sm">Thông tin tài khoản</a></div>
                                </div>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li><a class="font-weight-bold text-dark" href="{{route('backend.logout')}}"><i class="fa fa-power-off"></i> Đăng xuất</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>

<aside class="left-sidebar" style="background: #000000; color: #fff">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <div class="user-profile">
            <div class="profile-text p-0">

            </div>
        </div>

        <nav class="sidebar-nav" style="background: #000000">
            <ul id="sidebarnav" style="">
                <li class="text-center">
                    <img src="{{ url('/logo_vipshop.png') }}" height="45" alt="homepage"
                         class="dark-logo logo-img" style="height: 180px; width: 180px; object-fit: contain"/>
                </li>
                @if(auth()->guard('backend')->user()->can('index'))
                    <li>
                        <a href="{{route('backend.dashboard')}}" aria-expanded="false" class="">
                            <i class="mdi mdi-gauge"></i><span class="hide-menu">Bảng điều khiển</span>
                        </a>
                    </li>
                @endif

                @if(auth()->guard('backend')->user()->id != 168 )
                    <li>
                        <a class="waves-effect waves-dark" href="{{Route('backend.ops-live.index')}}"
                           aria-expanded="false">
                            <i class="mdi mdi-newspaper"></i>
                            <span class="hide-menu">Báo cáo - Live</span>
                        </a>
                    </li>
                @endif
                @if(auth()->guard('backend')->user()->can('orders.index') )
                    <li>
                        <a class="waves-effect waves-dark" href="{{ url('/admin/orders?key=2') }}"
                           aria-expanded="false">
                            <i class="mdi mdi-calendar-text"></i>
                            <span class="hide-menu">Quản lý đơn hàng</span>
                        </a>
                    </li>
                @endif

                @if(auth()->guard('backend')->user()->id != 168 )
                    <li>
                        <a class="waves-effect waves-dark" href="{{ route('backend.users.index') }}{{--Route('backend.users.profile')--}}"
                           aria-expanded="false">
                            <i class="mdi mdi-shopping"></i>
                            <span class="hide-menu">Quản lý cửa hàng</span>
                        </a>
                    </li>
                @endif

                    <li>
                        <a class="waves-effect" href="{{Route('backend.doi_soat.index')}}">
                            <i class="mdi mdi-rotate-3d"></i><span class="hide-menu">Đối soát</span>
                        </a>
                    </li>



                @if(auth()->guard('backend')->user()->can('staff.index'))
                    <li>
                        <a class="waves-effect" href="{{Route('backend.staff.index')}}">
                            <i class="mdi mdi-lock"></i><span class="hide-menu">Tài khoản</span>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
