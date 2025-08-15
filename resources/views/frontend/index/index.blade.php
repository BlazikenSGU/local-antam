@extends('frontend.layouts.frontend')
@section('style')
    <style>
        .single-product-wrap-2{
            border-left: 1px solid #eee;
            border-top: 1px solid #eee;
            border-right: 1px solid #eee;
            border-bottom: 1px solid #eee;
            box-shadow: 0 0 2px 5px #ebebeb;
            border-radius: 5px;
        }

    </style>

@endsection
@section('content')
    <div class="container-fluid ">
        <div class="slider-area">
            <div class="col-xl-12  ml-auto no-padding">

                <div class="hero-slider-active-2 nav-style-1 nav-style-1-modify-2 nav-style-1-blue" >
                    @foreach($banners as $k=>$item)

                        <div class="single-hero-slider single-hero-slider-hm9 single-animation-wrap " style="height: 400px;">

                            <div class="row slider-animated-1">
                                <div class="col-md-12">
                                    <div class="hm9-hero-slider-img">
                                        <img class="animated"
                                             src="{{$item->file_src}}"
                                             alt="" style="height: 400px;" >
                                    </div>

                                </div>

                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @if($banners_ads)
        @if(count($banners_ads))
            <div class="banner-area padding-10-row-col pt-5">
                <div class="container">
                    <div class="row">
                        @foreach($banners_ads as $item)
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="banner-wrap mb-10">
                                    <div class="banner-img banner-img-border banner-img-zoom">
                                        <a href="{{$item->url}}"><img src="{{$item->file_src}}" alt="" ></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endif






    <div class="container-fluid" >
        <div class="row" >
            <div class="col-md-2 img_slider"
                 style="
                 height: 450px;
                 position: sticky;
                 top: 200px;
                 margin-top: 20px;
                 margin-bottom: 20px
                 ">
                @if(!$banner_left)
                    <img style="width: 100%;" src="{{url('storage/uploads/')}}" alt="">
                @else
                    <img style="width: 100%; height: 450px" src="{{url('storage/uploads/')}}/{{$banner_left->image_path }}" alt="">
                @endif
            </div>
            <div class="col-md-8">


                <div class="product-area pb-20 pt-20">
                    <div class="container" style="background: #ffffff">

                        <div class="section-title-tab-wrap border-bottom-3 mb-30 pb-15">
                            <div class="section-title-3">
                                <h2 style="color: #1e7d3e; padding-top: 10px">MENU</h2>
                            </div>

                            <div class="tab-style-3 nav">
                                @foreach($products_by_category_1 as $k1 => $products1)
                                    @php
                                        $type = \App\Models\ProductType::with('icon')->where('id',$k1)->first();
                                    @endphp
                                    <a class="{{$k1 ==min(array_keys($products_by_category_1)) ? 'active' : ''}}"
                                       href="#product-{{$k1}}"
                                       data-toggle="tab">{{$product_type_1[$k1]['name']}} </a>

                                @endforeach

                            </div>
                        </div>

                        <div class="tab-content jump">
                            @foreach($products_by_category_1 as $k=> $products)
                                <div id="product-{{$k}}"
                                     class="tab-pane {{$k == min(array_keys($products_by_category_1)) ? 'active' : ''}}">

                                    <div class="product-slider-active-2">
                                        @foreach($products as $product)
                                            @php
                                                $link = product_link($product->slug,$product->id,$product->product_type_id);
                                            @endphp
                                            <div class="product-plr-2">
                                                <a href="{{$link}}">

                                                    <div class="single-product-wrap-2 mb-25">
                                                        <div class="product-img-2">
                                                            <a href="{{$link}}"><img
                                                                    src="{{$product->thumbnail?$product->thumbnail->file_src:asset('/storage/frontend/images/no-image.jpg')}}"
                                                                    alt=""></a>
                                                            @if(!empty($product->percent_discount))
                                                                <span class="pro-badge right bg-red">{{!empty($product->percent_discount)?$product->percent_discount:''}}%</span>
                                                            @endif
                                                        </div>
                                                        <div class="product-content-3">
                                                            <span>{{$product->product_type->name}}</span>
                                                            <h4><a href="{{$link}}" tabindex="-1">{{$product->title}}</a>
                                                            </h4>

                                                            <div class="pro-price-action-wrap">
                                                                <div class="product-price-3">
                                                   <span
                                                       class="new-price">{{number_format($product->price)}}đ</span>
                                                                    @if($product->price_old>0&&$product->price_old>$product->price)
                                                                        <span class="old-price"><del>{{number_format($product->price_old)}}đ</del></span>
                                                                    @endif
                                                                </div>
                                                                <div class="product-action-3">
                                                                    <form action="{{$link}}" method="get">
                                                                        <button type="submit" title="Xem chi tiết"><i
                                                                                class="icon-eye"></i></button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach

                                    </div>

                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

              
            </div>
            <div class="col-md-2 img_slider"
                 style="
                 height: 450px;
                 position: sticky;
                 top: 200px;
                 margin-top: 20px;
                 margin-bottom: 20px">
                @if(!$banner_right)
                    <img style="width: 100%;" src="{{url('storage/uploads/')}}" alt="">
                @else
                    <img style="width: 100%;  height: 450px" src="{{url('storage/uploads/')}}/{{$banner_right->image_path }}" alt="">
                @endif
             </div>
        </div>
    </div>


@endsection
@section('script')
    <script>
        // window.onscroll = function () {
        //     myFunction()
        // };
        //
        // var header = document.getElementById("myHeader");
        // var category = document.getElementById("myCategory");
        // var sticky = header.offsetTop;
        //
        // function myFunction() {
        //     if (window.pageYOffset > sticky) {
        //         header.classList.add("sticky");
        //         category.style.display = 'none';
        //     } else {
        //         header.classList.remove("sticky");
        //         category.style.display = 'block';
        //     }
        // }

        $('.wish-list').click(function () {
            var product_id = $(this).data('id');
            $.ajax({
                url: BASE_URL + '/ajax/wishlist/add',
                type: 'post',
                data: {product_id: product_id},
                dataType: 'json',
                success: function (result) {
                    if (result.code == 200) {
                        swal.fire(
                            'Thông báo',
                            'Đã thêm sản phẩm vào danh sách yêu thích',
                            'success'
                        )
                    } else {
                        swal.fire(
                            'Thông báo',
                            result.message,
                            'error',
                        )
                    }
                }
            });
        });
    </script>
@endsection
