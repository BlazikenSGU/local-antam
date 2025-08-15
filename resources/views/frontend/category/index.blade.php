@extends('frontend.layouts.frontend')

@section('content')
    @include('frontend.parts.breadcrumbs')


    <div class="shop-area pt-20 pb-80">
        <div class="container">
            <div class="row flex-row-reverse">
                <div class="col-lg-9">
                    <div class="shop-topbar-wrapper">
                        <div class="shop-topbar-left">
                            <div class="view-mode nav">
                                <a class="active" href="#shop-1" data-toggle="tab"><i class="icon-grid"></i></a>
                                <a href="#shop-2" data-toggle="tab"><i class="icon-menu"></i></a>
                            </div>
                            <p>Hiển thị {{$products->firstItem()}}–{{$products->lastItem()}}
                                trong {{$products->total()}} kết quả</p>
                        </div>
                        <div class="product-sorting-wrapper">
                            <div class="product-show shorting-style">
                                <label>Sắp xếp: </label>
                                <form action="" method="get" id="form-sort">
                                    <select class="sort_product" name="sort"
                                            onchange="$('#form-sort').submit()">
                                        <option value="">Mặc định</option>
                                        <option value="name_a_z">Tên (A - Z)</option>
                                        <option value="name_z_a">Tên (Z - A)</option>
                                        <option value="price_low_high">Giá thấp nhất</option>
                                        <option value="price_high_low">Giá cao nhất</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="shop-bottom-area">
                        <div class="tab-content jump">
                            <div id="shop-1" class="tab-pane active">
                                <div class="row">
                                    @foreach($products as $k => $v)
                                        @php
                                            $link = product_link($v->slug,$v->id,$v->product_type_id);
                                        @endphp

                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-6">
                                            <div class="single-product-wrap mb-35">
                                                <div class="product-img product-img-zoom mb-15">
                                                    <a href="{{$link}}">
                                                        <img src="{{$v->thumbnail->file_src}}" alt="">
                                                    </a>
                                                    <div class="product-action-2 tooltip-style-2">
                                                        {{--                                                        <button title="Wishlist"><i class="icon-heart"></i></button>--}}
                                                        {{--                                                        <button title="Quick View" data-toggle="modal"--}}
                                                        {{--                                                                data-target="#exampleModal"><i--}}
                                                        {{--                                                                class="icon-size-fullscreen icons"></i></button>--}}
                                                        {{--                                                        <button title="Compare"><i class="icon-refresh"></i></button>--}}
                                                    </div>
                                                </div>
                                                <div class="product-content-wrap-2 text-center">
                                                    {{--                                                    <div class="product-rating-wrap">--}}
                                                    {{--                                                        <div class="product-rating">--}}
                                                    {{--                                                            <i class="icon_star"></i>--}}
                                                    {{--                                                            <i class="icon_star"></i>--}}
                                                    {{--                                                            <i class="icon_star"></i>--}}
                                                    {{--                                                            <i class="icon_star"></i>--}}
                                                    {{--                                                            <i class="icon_star gray"></i>--}}
                                                    {{--                                                        </div>--}}
                                                    {{--                                                        <span>(2)</span>--}}
                                                    {{--                                                    </div>--}}
                                                    <h3><a href="{{$link}}">{{$v->title}}</a></h3>
                                                    <div class="product-price-2">
{{--                                                        @if(\Illuminate\Support\Facades\Auth::guard('web')->check())--}}
                                                            <span class="new-price">{{number_format($v->price)}}đ</span>
                                                            @if($v->price_old>0&&$v->price_old>$v->price)

                                                                <span class="old-price"><del>{{number_format($v->price_old)}}đ</del></span>
                                                            @endif
{{--                                                        @else--}}
{{--                                                            <a href="{{route('frontend.user.login')}}"--}}
{{--                                                               class="text-danger">Đăng nhập để xem giá</a>--}}
{{--                                                        @endif--}}
                                                    </div>
                                                </div>
                                                <div
                                                    class="product-content-wrap-2 product-content-position text-center">
                                                    {{--                                                    <div class="product-rating-wrap">--}}
                                                    {{--                                                        <div class="product-rating">--}}
                                                    {{--                                                            <i class="icon_star"></i>--}}
                                                    {{--                                                            <i class="icon_star"></i>--}}
                                                    {{--                                                            <i class="icon_star"></i>--}}
                                                    {{--                                                            <i class="icon_star"></i>--}}
                                                    {{--                                                            <i class="icon_star gray"></i>--}}
                                                    {{--                                                        </div>--}}
                                                    {{--                                                        <span>(2)</span>--}}
                                                    {{--                                                    </div>--}}
                                                    <h3><a href="{{$link}}">{{$v->title}}</a></h3>
                                                    <div class="product-price-2">
{{--                                                        @if(\Illuminate\Support\Facades\Auth::guard('web')->check())--}}

                                                            <span class="new-price">{{number_format($v->price)}}đ</span>
                                                            @if($v->price_old>0&&$v->price_old>$v->price)
                                                                <span class="old-price"><del>{{number_format($v->price_old)}}đ</del></span>
                                                            @endif
{{--                                                        @else--}}
{{--                                                            <a href="{{route('frontend.user.login')}}"--}}
{{--                                                               class="text-danger">Đăng nhập để xem giá</a>--}}
{{--                                                        @endif--}}
                                                    </div>
                                                    <div class="pro-add-to-cart">
                                                        <a class="detail-button" href="{{$link}}">Chi tiết</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div id="shop-2" class="tab-pane">
                                @foreach($products as $k => $v)
                                    @php
                                        $link = product_link($v->slug,$v->id,$v->product_type_id);
                                    @endphp
                                    <div class="shop-list-wrap mb-30">
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-6">
                                                <div class="product-list-img">
                                                    <a href="{{$link}}">
                                                        <img src="{{$v->thumbnail->file_src}}"
                                                             alt="{{$v->title}}">
                                                    </a>
                                                    <div class="product-list-quickview">
                                                        <button title="Quick View" data-toggle="modal"
                                                                data-target="#exampleModal"><i
                                                                class="icon-size-fullscreen icons"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-8 col-lg-7 col-md-6 col-sm-6">
                                                <div class="shop-list-content">
                                                    <h3><a href="{{$link}}">{{$v->title}}</a></h3>
                                                    <div class="pro-list-price">
{{--                                                        @if(\Illuminate\Support\Facades\Auth::guard('web')->check())--}}

                                                            <span class="new-price">{{number_format($v->price)}}đ</span>
                                                            @if($v->price_old>0&&$v->price_old>$v->price)

                                                                <span class="old-price"><del>{{number_format($v->price_old)}}đ</del></span>
                                                            @endif
{{--                                                        @else--}}
{{--                                                            <a href="{{route('frontend.user.login')}}"--}}
{{--                                                               class="text-danger">Đăng nhập để xem giá</a>--}}
{{--                                                        @endif--}}
                                                    </div>
                                                    <div class="product-list-rating-wrap">
                                                        {{--                                                        <div class="product-list-rating">--}}
                                                        {{--                                                            <i class="icon_star"></i>--}}
                                                        {{--                                                            <i class="icon_star"></i>--}}
                                                        {{--                                                            <i class="icon_star"></i>--}}
                                                        {{--                                                            <i class="icon_star gray"></i>--}}
                                                        {{--                                                            <i class="icon_star gray"></i>--}}
                                                        {{--                                                        </div>--}}
                                                        {{--                                                        <span>(3)</span>--}}
                                                    </div>
                                                    <p>{{$v->description}}</p>
                                                    <div class="product-list-action">
                                                        <a class="detail-button" href="{{$link}}">Chi tiết</a>

                                                        {{--                                                        <button title="Wishlist"><i class="icon-heart"></i></button>--}}
                                                        {{--                                                        <button title="Compare"><i class="icon-refresh"></i></button>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @include('frontend.parts.pagination', ['paginator' => $products])
                        </div>

                    </div>
                </div>
                @include('frontend.parts.sidebar')
            </div>
        </div>
    </div>


@endsection
