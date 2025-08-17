<header class="header-area">
    <div class="container">
        <div class="header-large-device">
            <div class="header-top header-top-ptb-1 border-bottom-1">
                <div class="row">
                    <div class="col-xl-4 col-lg-5">
                        <div class="header-offer-wrap">
                            <p><i class="icon-phone"></i> Hotline : <span>{{$HOTLINE}} </span></p>

                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-7">
                        <div class="header-top-right">
{{--                            <div class="same-style-wrap">--}}
{{--                                <div class="same-style same-style-border track-order">--}}
{{--                                    <a href="order-tracking.html">Track Your Order</a>--}}
{{--                                </div>--}}
{{--                                <div class="same-style same-style-border language-wrap">--}}
{{--                                    <a class="language-dropdown-active" href="#">English <i class="icon-arrow-down"></i></a>--}}
{{--                                    <div class="language-dropdown">--}}
{{--                                        <ul>--}}
{{--                                            <li><a href="#">English</a></li>--}}
{{--                                            <li><a href="#">French</a></li>--}}
{{--                                            <li><a href="#">German</a></li>--}}
{{--                                            <li><a href="#">Spanish</a></li>--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="same-style same-style-border currency-wrap">--}}
{{--                                    <a class="currency-dropdown-active" href="#">US Dollar <i class="icon-arrow-down"></i></a>--}}
{{--                                    <div class="currency-dropdown">--}}
{{--                                        <ul>--}}
{{--                                            <li><a href="#">USD</a></li>--}}
{{--                                            <li><a href="#">EUR</a></li>--}}
{{--                                            <li><a href="#">Real</a></li>--}}
{{--                                            <li><a href="#">BDT</a></li>--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="social-style-1 social-style-1-mrg">
                                <a href="{{$TWITTER}}"><i class="icon-social-twitter"></i></a>
                                <a href="{{$FACEBOOK}}"><i class="icon-social-facebook"></i></a>
                                <a href="{{$INSTAGRAM}}"><i class="icon-social-instagram"></i></a>
                                <a href="{{$PINTEREST}}"><i class="icon-social-pinterest"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom">
                <div class="row align-items-center">
                    <div class="col-xl-2 col-lg-2">
                        <div class="logo">
                            <a href="<?php echo url('/');?>">
                                <img src="{{url('/storage/uploads').'/'.$LOGO}}" alt="{{$META_TITLE}}" height="60px">
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-7">
                        <div class="main-menu main-menu-padding-1 main-menu-lh-1">
                            <nav>
                                <ul>
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
                                            <li class="dropdown {{$active}}">
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
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3">
                        <div class="header-action header-action-flex header-action-mrg-right">
                            <div class="same-style-2 header-search-1">
                                <a class="search-toggle" href="#">
                                    <i class="icon-magnifier s-open"></i>
                                    <i class="icon_close s-close"></i>
                                </a>
                                <div class="search-wrap-1">
                                    <form action="#">
                                        <input placeholder="Tìm sản phẩm" type="text">
                                        <button class="button-search"><i class="icon-magnifier"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="same-style-2">
                                <a href="{{route('frontend.user.login')}}"><i class="icon-user"></i></a>
                            </div>
                            <div class="same-style-2 header-cart">
                                <a href="">
                                    <i class="icon-basket-loaded"></i><span class="pro-count red">{{number_format(\App\Utils\Cart::get_total_items())}}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-small-device small-device-ptb-1">
            <div class="row align-items-center">
                <div class="col-5">
                    <div class="mobile-logo">
                        <a href="/">
                            <img width="90px" alt="" src="{{url('/storage/uploads').'/'.$LOGO}}">
                        </a>
                    </div>
                </div>
                <div class="col-7">
                    <div class="header-action header-action-flex">
                        <div class="same-style-2">
                            <a href="login-register.html"><i class="icon-user"></i></a>
                        </div>
                        <div class="same-style-2">
                            <a href="wishlist.html"><i class="icon-heart"></i><span class="pro-count red">03</span></a>
                        </div>
                        <div class="same-style-2 header-cart">
                            <a class="cart-active" href="#">
                                <i class="icon-basket-loaded"></i><span class="pro-count red">02</span>
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
