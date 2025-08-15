<!-- Start Header Area -->
<!-- end Header Area -->
<header class="header-area">
    <div class="header-large-device">
        <div class="header-top header-top-ptb-1 border-bottom-1">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7 col-lg-7">
                        <div class="social-offer-wrap">
                            <div class="social-style-1">
                                <a href="{{$YOUTUBE}}"><i class="icon-social-youtube"></i></a>
                                <a href="{{$FACEBOOK}}"><i class="icon-social-facebook"></i></a>
                                <a href="{{$INSTAGRAM}}"><i class="icon-social-instagram"></i></a>
                                {{--                                <a href="{{$PINTEREST}}"><i class="icon-social-pinterest"></i></a>--}}
                            </div>
                            <div class="header-offer-wrap-2">
                                <p><span>{{$COMPANY_DETAIL}}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-5">
                        <div class="header-top-right">
                            <div class="same-style-wrap">

                                <div class="same-style same-style-mrg-2 track-order">
                                    <div class="icon">
                                        <i class="fa fa-bars" style="font-size: 18px;
    line-height: 30px;
    background: #f7f7f7;
    display: block;
    width: 30px;
    height: 30px;
    float: right;
    text-align: center;
    border-radius: 100%;
    margin-right: 5px;"></i>
                                    </div>
                                    <a href="{{url('/tra-cuu-don-hang.html')}}"> Tra cứu đơn hàng</a>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-middle ">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-1 col-lg-3">
                        <div class="logo">
                            <a href="<?php echo url('/');?>">
                                <img src="{{url('/storage/uploads').'/'.$LOGO}}" alt="{{$META_TITLE}}">
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-3">
                        <div class="categori-search-wrap categori-search-wrap-modify-3">
                            <div class="search-wrap-3">
                                <form action="{{route('frontend.product.main')}}" method="get">
                                    <input placeholder="Nhập nội dung tìm kiếm sản phẩm..." type="text" name="q">
                                    <button type="submit" class="blue"><i class="lnr lnr-magnifier"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">

{{--                        <div class="header-block-item hidden-sm hidden-xs">--}}
{{--                            <div class="icon">--}}
{{--                                <i class="fa fa-phone"></i>--}}
{{--                            </div>--}}
{{--                            <div class="info">--}}
{{--                                <strong style="text-transform: uppercase">Hotline </strong>--}}
{{--                                <p>--}}
{{--                                    Gọi ngay: <a style="font-weight: bold" href="tel:{{$HOTLINE}}">{{$HOTLINE}} </a>--}}
{{--                                </p>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="header-block-item hidden-sm hidden-xs">
                            @if(empty(\Illuminate\Support\Facades\Auth::guard('web')->user()))
                                <div class="icon">
                                    <i class="fa fa-user fa fa-sign-in"></i>
                                </div>
                                <div class="info acccout">
                                    <strong style="text-transform: uppercase" class="block">Tài khoản</strong>
                                    <a class="hidden-xs" href="{{route('frontend.user.login')}}">Đăng nhập</a>&nbsp;/&nbsp;
                                    <a class="hidden-sm " href="{{route('frontend.user.register')}}">Đăng
                                        ký</a>
                                </div>
                            @else
                                <div class="icon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="info acccout">
                                    <strong style="text-transform: uppercase" class="block">{{\Illuminate\Support\Facades\Auth::guard('web')->user()->fullname?\Illuminate\Support\Facades\Auth::guard('web')->user()->fullname:
                                    \Illuminate\Support\Facades\Auth::guard('web')->user()->email}}</strong>
                                    {{--                                  Số dư:  <a style="color: red; font-weight: bold">{{number_format(auth('web')->user()->balance)}}VND</a>--}}
                                    <p><a class="hidden-xs" href="{{Route('frontend.user.profile')}}">Tài khoản</a>&nbsp;/&nbsp;
                                        <a class=" hidden-sm " href="{{route('frontend.user.logout')}}">Đăng xuất</a>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="header-bottom bg-blue" id="myHeader">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-1 no-padding">
                    <!--       15-12-22 tam thoi an menu danh muc   tyle="display: none"  -->
                        <div style="display: none" class="main-categori-wrap main-categori-wrap-modify-2">
                            <a class="categori-show categori-blue" href="#"> <i class="lnr lnr-menu"></i>Danh mục sản
                                phẩm <i class="icon-arrow-down icon-right"></i>
                                <div id="myCategory" class="category-menu categori-hide"
                                     style="width:336px; display: none">
                                    <nav>
                                        <ul>
                                            <h5 style="padding: 13px; border-bottom: 1px solid #ccc">THỰC PHẨM</h5>
                                            @foreach($array_tree_categories as $category)
                                                @if(empty($category['child']))
                                                    <li class="cr-dropdown">
                                                        <a href="{{\App\Utils\Links::CategoryLink($category)}}">
                                                            <img width="35px"
                                                                 src="{{$category['icon']?$category['icon']['file_src']:'https://bizweb.dktcdn.net/100/332/579/articles/thang-nhom-han-quoc-san-pham-sieu-hot.gif?v=1565602126393'}}"> {{$category['name']}}
                                                        </a>

                                                    </li>
                                                @else
                                                    <li class="cr-dropdown">
                                                        <a href="{{\App\Utils\Links::CategoryLink($category)}}">
                                                            <img width="35px"
                                                                 src="{{$category['icon']?$category['icon']['file_src']:'https://bizweb.dktcdn.net/100/332/579/articles/thang-nhom-han-quoc-san-pham-sieu-hot.gif?v=1565602126393'}}">
                                                            {{$category['name']}}
                                                            <span class="icon-arrow-right"></span></a>

                                                        <div
                                                            class="category-menu-dropdown ct-menu-res-height-2">
                                                            @foreach($category['child'] as $item)

                                                                <div
                                                                    class="single-category-menu ct-menu-mrg-bottom category-menu-border">
                                                                    <h4>
                                                                        <a href="{{\App\Utils\Links::CategoryLinkHasChild($item)}}">{{$item['name']}}</a>
                                                                    </h4>
                                                                    @if(!empty($item['child']))
                                                                        <ul>

                                                                            @foreach($item['child'] as $i)
                                                                                <li>
                                                                                    <a href="{{\App\Utils\Links::CategoryLinkHasChild($i)}}">{{$i['name']}}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>

                                                                    @endif
                                                                </div>
                                                            @endforeach

                                                            {{--                                                                <li><a href="shop.html">Cotton T-shirt</a></li>--}}
                                                            {{--                                                                <li><a href="shop.html">Trench coat</a></li>--}}
                                                            {{--                                                                <li><a href="shop.html">Cargo pants</a></li>--}}
                                                        </div>

                                                    </li>
                                                @endif
                                            @endforeach
                                            <li style="padding: 10px">SẢN PHẨM ĂN VẶT</li>
                                            @foreach($array_tree_categories_2 as $category)
                                                @if(empty($category['child']))
                                                    <li class="cr-dropdown">
                                                        <a href="{{\App\Utils\Links::CategoryLink($category)}}">
                                                            <img width="35px"
                                                                 src="{{$category['icon']?$category['icon']['file_src']:'https://bizweb.dktcdn.net/100/332/579/articles/thang-nhom-han-quoc-san-pham-sieu-hot.gif?v=1565602126393'}}"> {{$category['name']}}
                                                        </a>
                                                        {{--                                                        <div class="single-category-menu ct-menu-mrg-bottom category-menu-border">--}}
                                                        {{--                                                            <h4>Men Clothing</h4>--}}
                                                        {{--                                                            <ul>--}}
                                                        {{--                                                                <li><a href="shop.html">Sleeveless shirt</a></li>--}}
                                                        {{--                                                                <li><a href="shop.html">Cotton T-shirt</a></li>--}}
                                                        {{--                                                                <li><a href="shop.html">Trench coat</a></li>--}}
                                                        {{--                                                                <li><a href="shop.html">Cargo pants</a></li>--}}
                                                        {{--                                                            </ul>--}}
                                                        {{--                                                        </div>--}}
                                                    </li>
                                                @else
                                                    <li class="cr-dropdown">
                                                        <a href="{{\App\Utils\Links::CategoryLink($category)}}">
                                                            <img width="35px"
                                                                 src="{{$category['icon']?$category['icon']['file_src']:'https://bizweb.dktcdn.net/100/332/579/articles/thang-nhom-han-quoc-san-pham-sieu-hot.gif?v=1565602126393'}}">
                                                            {{$category['name']}}
                                                            <span class="icon-arrow-right"></span></a>

                                                        <div
                                                            class="category-menu-dropdown ct-menu-res-height-2">
                                                            @foreach($category['child'] as $item)

                                                                <div
                                                                    class="single-category-menu ct-menu-mrg-bottom category-menu-border">
                                                                    <h4>
                                                                        <a href="{{\App\Utils\Links::CategoryLinkHasChild($item)}}">{{$item['name']}}</a>
                                                                    </h4>
                                                                    @if(!empty($item['child']))
                                                                        <ul>

                                                                            @foreach($item['child'] as $i)
                                                                                <li>
                                                                                    <a href="{{\App\Utils\Links::CategoryLinkHasChild($i)}}">{{$i['name']}}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>

                                                                    @endif
                                                                </div>
                                                            @endforeach

                                                            {{--                                                                <li><a href="shop.html">Cotton T-shirt</a></li>--}}
                                                            {{--                                                                <li><a href="shop.html">Trench coat</a></li>--}}
                                                            {{--                                                                <li><a href="shop.html">Cargo pants</a></li>--}}
                                                        </div>

                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </nav>
                                </div>
                            </a>

                        </div>
                    </div>
                    <div class="col-lg-10 no-padding">
                        <div
                            class="main-menu main-menu-white main-menu-padding-1 main-menu-font-size-14 main-menu-lh-5">
                            <nav>
                                <ul style="text-align: center;">
                                    @php  $html = $active = ''; @endphp
                                    @foreach($header_menu_tree as $menu)
                                        @php
                                            $link = $menu['link'];
                                            $title = $menu['name'];
                                        @endphp
                                        @if (empty($menu['child']))
                                            <li class="bold-li {{$active}}">
                                                <a href="{{$link}}" title="{{$title}}">{{$title}}</a>
                                            </li>
                                        @else
                                            <li class="{{$active}}">
                                                <a href="{{$link}}" title="{{$title}}">{{$title}}<i
                                                        class="icon-arrow-down icon-right"></i></a>
                                                <ul class="mega-menu-style mega-menu-mrg-2">
                                                    <li>
                                                        <ul>
                                                            @foreach($menu['child'] as $child)
                                                                @if(!empty($child['child']))

                                                                    <li>
                                                                        <a class="dropdown-title"
                                                                           href="{{$child['link']}}">{{$child['name']}}</a>
                                                                        <ul style="padding-bottom: 15px">
                                                                            @foreach($child['child'] as $i)
                                                                                <li>
                                                                                    <a href="{{$i['link']}}">{{$i['name']}}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </li>
                                                                @else
                                                                    <li><a class=""
                                                                           href="{{$child['link']}}">{{$child['name']}}</a>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        @endif
                                    @endforeach

                                </ul>
                            </nav>
                        </div>
                    </div>

                    <div class="col-lg-1 no-padding">
                        <div class="header-action header-action-flex pr-20">
                            @if(\Illuminate\Support\Facades\Auth::guard('web')->check())
                                @php
                                    $count_wishlist = \App\Models\Wishlist::where('user_id', Auth::guard('web')->user()->id)->count();

                                @endphp
                                <div class="same-style-2 same-style-2-white same-style-2-font-dec">
                                    <a href="{{route('frontend.product.wishlist')}}"><i class="icon-heart"></i>
                                        <span
                                            class="pro-count red">{{$count_wishlist}}</span>
                                    </a>
                                </div>
                            @endif
                            <div class="same-style-2 same-style-2-white same-style-2-font-dec header-cart">
                                <a href="{{route('frontend.cart.index')}}"><i class="icon-basket-loaded" style="font-size: 35px"></i>

                                    <span
                                        class="pro-count count-cart red">{{number_format(\App\Utils\Cart::get_total_items())}}</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-small-device sticky small-device-ptb-1">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-5">
                    <div class="mobile-logo">
                        <a href="/">
                            <img width="100px" alt="" src="{{url('/storage/uploads').'/'.$LOGO}}">
                        </a>
                    </div>
                </div>
                <div class="col-7">
                    <div class="header-action header-action-flex">
                        <div class="same-style-2 same-style-2-font-inc">
                            <a href="{{route('frontend.user.login')}}"><i class="icon-user"></i></a>
                        </div>
                        @if(\Illuminate\Support\Facades\Auth::guard('web')->check())
                            @php
                                $count_wishlist = \App\Models\Wishlist::where('user_id', Auth::guard('web')->user()->id)->count();

                            @endphp
                            <div class="same-style-2 same-style-2-font-inc header-cart">
                                <a href="{{route('frontend.product.wishlist')}}">
                                    <i class="icon-heart"></i><span
                                        class="pro-count red">{{$count_wishlist}}</span>
                                </a>
                            </div>
                        @endif
                        <div class="same-style-2 same-style-2-font-inc header-cart">
                            <a href="{{route('frontend.cart.index')}}">
                                <i class="icon-basket-loaded"></i><span
                                    class="pro-count red">{{number_format(\App\Utils\Cart::get_total_items())}}</span>
                            </a>
                        </div>
                        <div class="same-style-2 main-menu-icon">
                            <a class="mobile-header-button-active" href="#"><i class="icon-menu"></i> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="mobile-header-active mobile-header-wrapper-style">
    <div class="clickalbe-sidebar-wrap">
        <a class="sidebar-close"><i class="icon_close"></i></a>
        <div class="mobile-header-content-area">
            <div class="mobile-menu-wrap mobile-header-padding-border-2">
                <nav>
                    <hr>
                    <div class="mobile-search mobile-header-padding-border-1">
                        <form class="search-form" action="{{route('frontend.product.main')}}">
                            <input type="text" placeholder="Tìm kiếm ở đây..." name="q">
                            <button class="button-search"><i class="icon-magnifier"></i></button>
                        </form>
                    </div>
                    <ul class="mobile-menu">
                        @php  $html = $active = ''; @endphp
                        @foreach($header_menu_tree as $menu)
                            @php
                                $link = $menu['link'];
                                $title = $menu['name'];
                            @endphp
                            @if (empty($menu['child']))
                                <li class="{{$active}}">
                                    <a href="{{$link}}" title="{{$title}}">{{$title}}</a>
                                </li>
                            @else
                                <li class="menu-item-has-children{{$active}}">
                                    <a href="{{$link}}" title="{{$title}}">{{$title}}</a>
                                    <ul class="dropdown">
                                        @foreach ($menu['child'] as $child)
                                            @php
                                                $link = $child['link'];
                                                $title = $child['name'];
                                            @endphp
                                            <li><a href="{{$link}}" title="{{$title}}">{{$title}}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach

                    </ul>
                    <hr>
                    <ul class="mobile-menu">
                        @php  $html = $active = ''; @endphp
                        @foreach($array_tree_categories as $menu)
                            @php
                                $link = $menu['link'];
                                $title = $menu['name'];
                            @endphp
                            @if (empty($menu['child']))
                                <li class="{{$active}}">
                                    <a href="{{$link}}" title="{{$title}}">{{$title}}</a>
                                </li>
                            @else
                                <li class="menu-item-has-children{{$active}}">
                                    <a href="{{$link}}" title="{{$title}}">{{$title}}</a>
                                    <ul class="dropdown">
                                        @foreach ($menu['child'] as $child)
                                            @php
                                                $link = $child['link'];
                                                $title = $child['name'];
                                            @endphp
                                            <li><a href="{{$link}}" title="{{$title}}">{{$title}}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach

                    </ul>
                </nav>
                <!-- mobile menu end -->
            </div>
            <hr>

            <div class="mobile-contact-info mobile-header-padding-border-4">
                <ul>
                    <li><i class="icon-phone "></i> {{$HOTLINE}}</li>
                    <li style="    font-size: 13px;"><i class="icon-envelope-open "></i> {{$EMAIL}}</li>
                </ul>
            </div>
            <div class="mobile-social-icon" style="position: fixed;
    bottom: 0;">
                <a href="{{$TWITTER}}"><i class="icon-social-twitter"></i></a>
                <a href="{{$FACEBOOK}}"><i class="icon-social-facebook"></i></a>
                <a href="{{$INSTAGRAM}}"><i class="icon-social-instagram"></i></a>
                <a href="{{$PINTEREST}}"><i class="icon-social-pinterest"></i></a>
            </div>
        </div>
    </div>
</div>
