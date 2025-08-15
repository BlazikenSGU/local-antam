@extends('frontend.layouts.frontend')
@section('style')
    <style>
        body .swatch .swatch-element {
            min-width: 40px;
            height: 40px;
            float: left;
            margin-right: 8px;
            margin-bottom: 10px;
            border-radius: 0%;
            border-width: 1px;
            border-style: solid;
            border-color: transparent;
            -ms-transition: all 500ms ease;
            -webkit-transition: all 500ms ease;
            transition: all 500ms ease;
            background-position: -1px -1px;
            position: relative;
        }

        body .swatch .header {
            font-size: 16px;
            font-weight: 500;
            font-family: Montserrat;
            color: #222;
            text-transform: capitalize;
            width: 100%;
            line-height: 36px;
            margin-bottom: 7px;
            float: left;
        }

        body .swatch .swatch-element label {
            width: 100%;
            height: 100%;
            padding-left: 0px;
            padding-right: 0px;
            /* border: 1px solid #ebebeb; */
            background-color: #ebebeb;
            font-weight: normal;
            text-align: center;
            line-height: 40px;
            /* background-position: -1px -1px; */
            font-size: 23px;
            cursor: pointer;
        }

        body .swatch .swatch-element input {
            display: none;
        }
    </style>
@endsection
@section('content')

    @include('frontend.parts.breadcrumbs')
    <div class="product-details-area pt-20 pb-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="product-details-tab">

                        <div class="pro-dec-big-img-slider">
                            @foreach($product->images as $v)

                                <div class="easyzoom-style">
                                    <div class="easyzoom easyzoom--overlay">
                                        <a href="{{$v->file_src}}">
                                            <img src="{{$v->file_src}}" alt="">
                                        </a>
                                    </div>
                                    <a class="easyzoom-pop-up img-popup"
                                       href="{{$v->file_src}}"><i class="icon-size-fullscreen"></i></a>
                                </div>
                            @endforeach

                        </div>

                        <div class="product-dec-slider-small product-dec-small-style1">
                            @foreach($product->images as $v)

                                <div class="product-dec-small">
                                    <img src="{{$v->file_src}}" alt="">
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                    <div class="product-details-content pro-details-content-mrg">
                        <h2>{{$product->title}}</h2>
                        <div class="product-ratting-review-wrap">
                            {{--                            <div class="product-ratting-digit-wrap">--}}
                            {{--                                <div class="product-ratting">--}}
                            {{--                                    <i class="icon_star"></i>--}}
                            {{--                                    <i class="icon_star"></i>--}}
                            {{--                                    <i class="icon_star"></i>--}}
                            {{--                                    <i class="icon_star"></i>--}}
                            {{--                                    <i class="icon_star"></i>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="product-digit">--}}
                            {{--                                    <span>5.0</span>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="product-review-order">--}}
                            {{--                                <span>62 Reviews</span>--}}
                            {{--                                <span>242 orders</span>--}}
                            {{--                            </div>--}}
                        </div>
                        {{--                        <p>{!! html_entity_decode($product->description) !!}</p>--}}
                        <div class="pro-details-price">
                            {{--                            @if(\Illuminate\Support\Facades\Auth::guard('web')->check())--}}

                            <span class="new-price">{{number_format($product->price)}}đ</span>
                            @if($product->price_old>0&&$product->price_old>$product->price)

                                <span class="old-price"><del>{{number_format($product->price_old)}}đ</del></span>
                            @endif

                            {{--                            @endif--}}

                        </div>
                        <form action="#" method="post" id="add-item-form">
                            <input type="hidden" name="product_id" value="{{$product->id}}">
                            <input type="hidden" name="product_variation_id" class="product_variation_id" value="">
                            {{--                            @if(\Illuminate\Support\Facades\Auth::guard('web')->check())--}}
                            <div class="product-type">
                                @foreach ($arr_product_variants as $k => $v)
                                    <div class="swatch swatch clearfix" data-option-index="{{$k}}">
                                        <div class="header">{{ $v['name']}}</div>
                                        @foreach ($v['items'] as $k2 => $v2)
                                            <div class="swatch-element l available">


                                                <label for="variation_{{$v2['variation_value_id']}}">
                                                    <input id="variation_{{$v2['variation_value_id']}}" type="radio"
                                                           class="select_variation"
                                                           name="variation_id_{{$v['variation_id']}}"
                                                           value="{{$v2['variation_value_id']}}">
                                                    <span>{{$v2['value']}}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            {{--                            @endif--}}
                        </form>


                        <div class="pro-details-quality">
                            @if($product->product_code)
                                <p class="mt-10">Mã sản phẩm: <strong>{{$product->product_code}}</strong></p>
                            @endif
                            @if (!$product->out_of_stock)
                                <p class="inventory_status_parent"><span>Tình trạng: <b
                                            class="inventory_status text-success">Còn hàng</b></span>
                                </p>
                            @else
                                <p class="inventory_status_parent">
                                    <span>Tình trạng: <b
                                            class="inventory_status text-danger">Hết hàng</b></span>
                                </p>
                            @endif
                        </div>
                        {{--                        @if(\Illuminate\Support\Facades\Auth::guard('web')->check())--}}

                        <div class="pro-details-quality">
                            <span>Số lượng: @if(!empty($product->specifications))<sup class="text-danger">({{$product->specifications}})</sup>@endif</span>
                            <div class="cart-plus-minus">
                                <input id="quantity" class="cart-plus-minus-box" type="text" value="1">

                            </div>
                        </div>
                        {{--                        @endif--}}
                        <div class="product-details-meta">
                            <ul>
                                <li><span>Danh mục:</span><a
                                        href="{{url('/'.$product->product_type->slug)}}">{{$product->product_type->name}}</a>
                                </li>
                            </ul>
                        </div>

                        <div class="pro-details-action-wrap">
                            {{--                            @if(\Illuminate\Support\Facades\Auth::guard('web')->check())--}}

                            <div class="pro-details-add-to-cart">
                                <a class="add_to_cart btn-bg-success" data-id="{{$product->id}}"><i
                                        class="fa fa-shopping-cart"></i> Thêm vào giỏ</a>
                                <a class="add_to_cart btn-bg-primary" data-id="{{$product->id}}" data-act="buy"><i
                                        class="fa fa-check"></i> Mua ngay</a>
                            </div>
                            {{--                            @else--}}
                            {{--                                <p class=""><a class="text-danger" href="{{route('frontend.user.login')}}">Đăng nhập để--}}
                            {{--                                        xem giá</a></p>--}}
                            {{--                            @endif--}}

                            {{--                            <div class="pro-details-action">--}}
                            {{--                                <a title="Add to Wishlist" href="#"><i class="icon-heart"></i></a>--}}
                            {{--                                <a title="Add to Compare" href="#"><i class="icon-refresh"></i></a>--}}
                            {{--                                <a class="social" title="Social" href="#"><i class="icon-share"></i></a>--}}
                            {{--                                <div class="product-dec-social">--}}
                            {{--                                    <a class="facebook" title="Facebook" href="#"><i--}}
                            {{--                                            class="icon-social-facebook"></i></a>--}}
                            {{--                                    <a class="twitter" title="Twitter" href="#"><i class="icon-social-twitter"></i></a>--}}
                            {{--                                    <a class="instagram" title="Instagram" href="#"><i--}}
                            {{--                                            class="icon-social-instagram"></i></a>--}}
                            {{--                                    <a class="pinterest" title="Pinterest" href="#"><i--}}
                            {{--                                            class="icon-social-pinterest"></i></a>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-xs-12 pd-none-box-service mb15">
                    <div class="box-service-product">
                        {{--                        <div class="header-box-service-product text-center">--}}
                        {{--                            <div class="title">Sẽ có tại nhà bạn</div>--}}

                        {{--                            <div class="content">từ 1-4 ngày làm việc</div>--}}
                        {{--                        </div>--}}

                        <div class="content-box-service-product row">
                            <div class="col-lg-12 col-sm-3 col-xs-12">
                                <div class="border-service-product">
                                    <div class="flexbox-grid-default">
                                        <div class="flexbox-auto-45px flexbox-align-self-center"><img
                                                src="//st.app1h.com/themes/03/assets/icon-service-1.png?v=1961"></div>

                                        <div class="flexbox-content des-service-product">
                                            <div class="title">Giao hàng tận nơi&nbsp;tại TP HCM</div>

                                            <div class="content">Phí ship thật ưu đãi</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--                            <div class="col-lg-12 col-sm-3 col-xs-12">--}}
                            {{--                                <div class="border-service-product">--}}
                            {{--                                    <div class="flexbox-grid-default">--}}
                            {{--                                        <div class="flexbox-auto-45px flexbox-align-self-center"><img--}}
                            {{--                                                src="https://inside.app1h.com/uploads/images/company52/images/%C4%91%E1%BB%95i%20tr%E1%BA%A3%281%29.png"--}}
                            {{--                                                style="width: 36px; height: 36px;"></div>--}}

                            {{--                                        <div class="flexbox-content des-service-product">--}}
                            {{--                                            <div class="title">Đổi trả dễ dàng</div>--}}

                            {{--                                            <div class="content">Được đổi trả miễn phí trong vòng 7&nbsp;ngày</div>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}

                            <div class="col-lg-12 col-sm-3 col-xs-12">
                                <div class="border-service-product">
                                    <div class="flexbox-grid-default">
                                        <div class="flexbox-auto-45px flexbox-align-self-center"><img
                                                src="//st.app1h.com/themes/03/assets/icon-service-3.png?v=1961"></div>

                                        <div class="flexbox-content des-service-product">
                                            <div class="title">Thanh toán</div>

                                            <div class="content">Thanh toán khi nhận hàng hoặc chuyển khoản (COD)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-sm-3 col-xs-12">
                                <div class="border-service-product">
                                    <div class="flexbox-grid-default">
                                        <div class="flexbox-auto-45px flexbox-align-self-center"><img
                                                src="//st.app1h.com/themes/03/assets/icon-service-4.png?v=1961"></div>

                                        <div class="flexbox-content des-service-product">
                                            <div class="title">Hỗ trợ online</div>

                                            <div class="content">{{$HOTLINE}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="description-review-wrapper pb-110">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dec-review-topbar nav mb-45">
                        <a class="active" data-toggle="tab" href="#des-details1">Nội dung chi tiết</a>
                        {{--                        <a data-toggle="tab" href="#des-details2" class="">Hướng dẫn bảo quản</a>--}}
                        <a data-toggle="tab" href="#des-details3">Chính sách giao nhận - đổi trả </a>
                        <a data-toggle="tab" href="#des-details4" class="">Bình Luận </a>
                    </div>
                    <div class="tab-content dec-review-bottom">
                        <div id="des-details1" class="tab-pane active">
                            <div class="description-wrap table-responsive">
                                {!! html_entity_decode($product->detail) !!}
                            </div>
                        </div>
                        {{--                        <div id="des-details2" class="tab-pane">--}}
                        {{--                            <div class="specification-wrap table-responsive">--}}
                        {{--                                {!! html_entity_decode($STORAGE_INSTRUCTIONS) !!}--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <div id="des-details3" class="tab-pane">
                            <div class="specification-wrap table-responsive">
                                {!! html_entity_decode($RETURN_AND_EXCHANGE) !!}
                            </div>
                        </div>
                        <div id="des-details4" class="tab-pane">

                            <div class="fb-comments" data-href="https://ruavangfood.com" data-width="100%" data-numposts="5"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(count($related_products))
        <div class="related-product pb-115">
            <div class="container">
                <div class="section-title mb-45 text-center">
                    <h2>Sản phẩm tương tự</h2>
                </div>
                <div class="related-product-active">
                    @foreach($related_products as $k => $product)
                        @php
                            $link = product_link($product->slug,$product->id,$product->product_type_id);
                        @endphp
                        <div class="product-plr-1">
                            <div class="single-product-wrap">
                                <div class="product-img product-img-zoom mb-15">
                                    <a href="{{$link}}">
                                        <img src="{{$product->thumbnail->file_src}}" alt="">
                                    </a>
                                    {{--                                    <div class="product-action-2 tooltip-style-2">--}}
                                    {{--                                        <button title="Wishlist"><i class="icon-heart"></i></button>--}}
                                    {{--                                    </div>--}}
                                </div>
                                <div class="product-content-wrap-2 text-center">
                                    <div class="product-rating-wrap">
                                        {{--                                <div class="product-rating">--}}
                                        {{--                                    <i class="icon_star"></i>--}}
                                        {{--                                    <i class="icon_star"></i>--}}
                                        {{--                                    <i class="icon_star"></i>--}}
                                        {{--                                    <i class="icon_star"></i>--}}
                                        {{--                                    <i class="icon_star gray"></i>--}}
                                        {{--                                </div>--}}
                                        {{--                                    <span>(2)</span>--}}
                                    </div>
                                    <h3><a href="{{$link}}">{{$product->title}}</a></h3>
                                    <div class="product-price-2">
                                        {{--                                        @if(\Illuminate\Support\Facades\Auth::guard('web')->check())--}}

                                        <span class="new-price">{{number_format($product->price)}}đ</span>
                                        @if($product->price_old>0&&$product->price_old>$product->price)

                                            <span class="old-price"><del>{{number_format($product->price_old)}}đ</del></span>
                                        @endif
                                        {{--                                        @else--}}
                                        {{--                                            <p class=""><a class="text-danger" href="{{route('frontend.user.login')}}">Đăng--}}
                                        {{--                                                    nhập để--}}
                                        {{--                                                    xem giá</a></p>--}}
                                        {{--                                        @endif--}}
                                    </div>
                                </div>
                                <div class="product-content-wrap-2 product-content-position text-center">
                                    <div class="product-rating-wrap">
                                        {{--                                    <span>(2)</span>--}}
                                    </div>
                                    <h3><a href="{{$link}}">{{$product->title}}</a></h3>
                                    <div class="product-price-2">
                                        {{--                                        @if(\Illuminate\Support\Facades\Auth::guard('web')->check())--}}
                                        <span class="new-price">{{number_format($product->price)}}đ</span>

                                        @if($product->price_old>0&&$product->price_old>$product->price)

                                            <span class="old-price"><del>{{number_format($product->price_old)}}đ</del></span>
                                        @endif
                                        {{--                                        @else--}}
                                        {{--                                            <p class=""><a class="text-danger" href="{{route('frontend.user.login')}}">Đăng--}}
                                        {{--                                                    nhập để--}}
                                        {{--                                                    xem giá</a></p>--}}
                                        {{--                                        @endif--}}
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
        </div>
    @endif

@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('/storage/frontend')}}/js/plugins/fancybox/dist/jquery.fancybox.min.css">

@stop

@section('script')

    <script src="{{ asset('/storage/frontend')}}/js/plugins/fancybox/dist/jquery.fancybox.min.js"></script>
@stop
