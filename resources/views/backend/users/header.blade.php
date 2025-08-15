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
                <li class="nav-item" style="display: none">
                    ádsa
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
                                src="{{ config('constants.upload_dir.url') }}/{{ Auth()->guard('backend')->user()->avatar_file_path }}"
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
                                                src="{{ config('constants.upload_dir.url') }}/{{ Auth()->guard('backend')->user()->avatar_file_path }}"
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
{{--                <h2 class="p-2" style="color: #fff; font-weight: bold">Vip Shop</h2>--}}
            </div>
        </div>
        <!-- Sidebar navigation-->
{{--    <div class="waves-effect waves-dark">--}}
{{--        <div class="">--}}
{{--            @if(!empty( Auth()->guard('backend')->user()->avatar_file_path))--}}
{{--                <img--}}
{{--                    src="{{ config('constants.upload_dir.url') }}/{{ Auth()->guard('backend')->user()->avatar_file_path }}"--}}
{{--                    alt="user" style="width: 30px; height: 30px; object-fit: contain">--}}
{{--            @else--}}
{{--                <img src="{{ asset('/storage/backend/assets/images/users/5.jpg') }}"--}}
{{--                     alt="user" style="width: 30px; height: 30px; object-fit: contain">--}}
{{--            @endif--}}
{{--        </div>--}}
{{--        <div class="">--}}
{{--            <span>{{Auth()->guard('backend')->user()->name}}</span>--}}
{{--        </div>--}}
{{--    </div>--}}
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
                {{--                @if(auth()->guard('backend')->user()->can('products.index'))--}}

                {{--                    <li>--}}
                {{--                        <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i--}}
                {{--                                class="mdi mdi-briefcase-check"></i><span class="hide-menu">Sản phẩm</span></a>--}}
                {{--                        <ul aria-expanded="false" class="collapse">--}}

                {{--                            @if(auth()->guard('backend')->user()->can('products.index'))--}}
                {{--                                <li><a href="{{Route('backend.products.index')}}">Tất cả</a></li>--}}
                {{--                            @endif--}}


                {{--                            @if(auth()->guard('backend')->user()->can('products.add'))--}}
                {{--                                <li><a href="{{Route('backend.products.add',1)}}">Thêm mới sản phẩm</a></li>--}}
                {{--                            @endif--}}

                {{--                           --}}{{-- @if(auth()->guard('backend')->user()->can('products.add'))--}}
                {{--                                <li><a href="{{Route('backend.products.add',2)}}">Thêm mới đồ ăn vặt</a></li>--}}
                {{--                            @endif--}}

                {{--                            @if(auth()->guard('backend')->user()->can('products.type.index'))--}}
                {{--                                <li><a href="{{Route('backend.products.type.index')}}">Danh mục sản--}}
                {{--                                        phẩm</a></li>--}}
                {{--                            @endif--}}

                {{--                        </ul>--}}
                {{--                    </li>--}}
                {{--                @endif--}}



{{--                @if(auth()->guard('backend')->user()->can('orders.index') && (auth()->guard('backend')->user()->id == 168))--}}
{{--                    <li>--}}
{{--                        <a class="waves-effect waves-dark" href="{{Route('backend.orders.index')}}"--}}
{{--                           aria-expanded="false">--}}
{{--                            <i class="mdi mdi-cart"></i>--}}
{{--                            <span class="hide-menu">Đơn hàng</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                @endif--}}
                @if(auth()->guard('backend')->user()->can('orders.report') )
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
                        <a class="waves-effect waves-dark" href="{{ url('/admin/orders?key=1') }}"
                           aria-expanded="false">
                            <i class="mdi mdi-calendar-text"></i>
                            <span class="hide-menu">Quản lý đơn hàng</span>
                        </a>
                    </li>
                @endif
                @if(auth()->guard('backend')->user()->can('orders.excel') )
                    <li>
                        <a class="waves-effect waves-dark" href="{{Route('backend.orders.create.excel')}}"
                           aria-expanded="false">
                            <i class="mdi mdi-file-excel"></i>
                            <span class="hide-menu">Lên đơn Excel</span>
                        </a>
                    </li>
                @endif
                @if(auth()->guard('backend')->user()->can('orders.excel') )
                    <li>
                        <a class="waves-effect waves-dark" href="{{ route('backend.users.index') }}{{--Route('backend.users.profile')--}}"
                           aria-expanded="false">
                            <i class="mdi mdi-shopping"></i>
                            <span class="hide-menu">Quản lý cửa hàng</span>
                        </a>
                    </li>
                @endif
{{--                @if(auth()->guard('backend')->user()->can('backend.cod') )--}}
{{--                    <li>--}}
{{--                        <a class="waves-effect waves-dark" href="{{Route('backend.shop.cod')}}"--}}
{{--                           aria-expanded="false">--}}

{{--                            <i class="mdi mdi-credit-card"></i>--}}
{{--                            <span class="hide-menu">Cod - Đối soát</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                @endif--}}
                @if(auth()->guard('backend')->user()->can('backend.report') )
                    <li>
                        <a class="waves-effect waves-dark" href="{{Route('backend.shop.ticket')}}"
                           aria-expanded="false">

                            <i class="mdi mdi-alert"></i>
                            <span class="hide-menu">Khiếu nại</span>
                        </a>
                    </li>
                @endif


                {{--                @if(auth()->guard('backend')->user()->can('discount.index')||auth()->guard('backend')->user()->can('discount.add'))--}}
                {{--                    <li>--}}
                {{--                        <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">--}}
                {{--                            <i class="mdi mdi-tag"></i>--}}
                {{--                            <span class="hide-menu">Mã giảm giá</span>--}}
                {{--                        </a>--}}
                {{--                        <ul aria-expanded="false" class="collapse">--}}
                {{--                            @if(auth()->guard('backend')->user()->can('discount.index'))--}}
                {{--                                <li><a href="{{Route('backend.discount.index')}}">Danh sách</a></li>--}}
                {{--                            @endif--}}

                {{--                            @if(auth()->guard('backend')->user()->can('discount.add'))--}}
                {{--                                <li><a href="{{Route('backend.discount.add')}}">Thêm mới</a></li>--}}
                {{--                            @endif--}}
                {{--                        </ul>--}}
                {{--                    </li>--}}
                {{--                @endif--}}
                {{--                @if(auth()->guard('backend')->user()->can('posts.index'))--}}
                {{--                    <li>--}}
                {{--                        <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">--}}
                {{--                            <i class="mdi mdi-file-document"></i>--}}
                {{--                            <span class="hide-menu">Bài viết</span>--}}
                {{--                        </a>--}}
                {{--                        <ul aria-expanded="false" class="collapse">--}}
                {{--                            @if(auth()->guard('backend')->user()->can('posts.index'))--}}
                {{--                                <li><a href="{{Route('backend.posts.index')}}">Danh sách</a></li>--}}
                {{--                            @endif--}}

                {{--                            @if(auth()->guard('backend')->user()->can('posts.index'))--}}
                {{--                                <li><a href="{{Route('backend.posts.add')}}">Thêm mới</a></li>--}}
                {{--                            @endif--}}

                {{--                            @if(auth()->guard('backend')->user()->can('posts.category.index'))--}}
                {{--                                <li><a href="{{Route('backend.posts.category.index')}}">Danh mục bài viết</a></li>--}}
                {{--                            @endif--}}
                {{--                        </ul>--}}
                {{--                    </li>--}}
                {{--                @endif--}}
                {{--                @if(auth()->guard('backend')->user()->can('email.index'))--}}
                {{--                    <li>--}}
                {{--                        <a class="waves-effect waves-dark" href="{{Route('backend.subscribers.index')}}"--}}
                {{--                           aria-expanded="false">--}}
                {{--                            <i class="mdi mdi-email"></i>--}}
                {{--                            <span class="hide-menu">Email nhận tin</span>--}}
                {{--                        </a>--}}
                {{--                    </li>--}}
                {{--                @endif--}}
                {{--                @if(auth()->guard('backend')->user()->can('setting.index'))--}}
                {{--                    <li>--}}
                {{--                        <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">--}}
                {{--                            <i class="mdi mdi-settings"></i>--}}
                {{--                            <span class="hide-menu">Cài đặt</span></a>--}}
                {{--                        <ul aria-expanded="false" class="collapse">--}}
                {{--                            <li><a href="{{Route('backend.setting.index')}}">Chung </a></li>--}}
                {{--                            <li><a href="{{Route('backend.menu.index')}}">Menu website </a></li>--}}
                {{--                        </ul>--}}
                {{--                    </li>--}}
                {{--                @endif--}}
                {{--                @if(auth()->guard('backend')->user()->can('banner.index'))--}}
                {{--                    <li>--}}
                {{--                        <a class="waves-effect" href="{{Route('backend.banner.index')}}">--}}
                {{--                            <i class="mdi mdi-file-image"></i><span class="hide-menu">Banner</span>--}}
                {{--                        </a>--}}
                {{--                    </li>--}}
                {{--                @endif--}}

                @if(auth()->guard('backend')->user()->can('staff.index'))
                    <li>
                        <a class="waves-effect" href="{{Route('backend.staff.index')}}">
                            <i class="mdi mdi-lock"></i><span class="hide-menu">Phân quyền</span>
                        </a>
                    </li>
                @endif

                {{--                @if(auth()->guard('backend')->user()->can('notification.index'))--}}
                {{--                    <li>--}}
                {{--                        <a class="waves-effect" href="{{Route('backend.notification.index')}}">--}}
                {{--                            <i class="mdi mdi-bell"></i><span class="hide-menu">Thông báo</span>--}}
                {{--                        </a>--}}
                {{--                    </li>--}}
                {{--                @endif--}}

                {{--                @if(auth()->guard('backend')->user()->can('brands.index'))--}}
                {{--                    <li>--}}
                {{--                        <a class="waves-effect" href="{{Route('backend.brands.index')}}">--}}
                {{--                            <i class="mdi mdi-file-image"></i><span class="hide-menu">Chi Nhánh</span>--}}
                {{--                        </a>--}}
                {{--                    </li>--}}
                {{--                @endif--}}

                {{-- @if(auth()->guard('backend')->user()->can('warehouses.index'))
                     <li>
                         <a class="waves-effect" href="{{Route('backend.warehouses.index')}}">
                             <i class="mdi mdi-file-image"></i><span class="hide-menu"> Bàn </span>
                         </a>
                     </li>
                 @endif--}}

                {{--                @if(auth()->guard('backend')->user()->can('users.index'))--}}
                {{--                    <li>--}}
                {{--                        <a class="waves-effect" href="{{Route('backend.users.index')}}">--}}
                {{--                            <i class="mdi mdi-account-multiple"></i><span class="hide-menu">Tài khoản</span>--}}
                {{--                        </a>--}}
                {{--                    </li>--}}
                {{--                @endif--}}
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
