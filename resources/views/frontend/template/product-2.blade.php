@php
    $link = product_link($product->slug,$product->id,$product->product_type_id);
@endphp

<figure class="product-thumb">
    <a href="{{$link}}" title="{{$product->title}}" rel="nofollow">
        @if($product->thumbnail)
            <img src="{{$product->thumbnail->file_src}}" alt="{{$product->title}}" class="pri-img"/>
            <img src="{{$product->thumbnail->file_src}}" alt="{{$product->title}}" class="sec-img"/>
        @endif
    </a>

    <div class="product-badge">
        @if($product->price_old&&$product->price)
            <div class="product-label discount">
                - {{round((($product->price_old-$product->price)/$product->price_old)*100)}}%
            </div>
        @endif
    </div>

    <div class="button-group d-none">
        <a href="wishlist.html" data-toggle="tooltip" data-placement="left" title="Add to wishlist" rel="nofollow"><i class="pe-7s-like"></i></a>
        <a href="compare.html" data-toggle="tooltip" data-placement="left" title="Add to Compare" rel="nofollow"><i class="pe-7s-refresh-2"></i></a>
        <a href="#" data-toggle="modal" data-target="#quick_view"><span data-toggle="tooltip" data-placement="left" title="Quick View"><i class="pe-7s-search"></i></span></a>
    </div>

    <div class="cart-hover">
        <button class="btn btn-cart" onclick="location.href='{{$link}}'">Chi tiết</button>
    </div>
</figure>

<div class="product-content-list">

    <div class="manufacturer-name d-none">
        <a href="product-details.html" rel="nofollow">Silver</a>
    </div>
    <ul class="color-categories d-none">
        <li>
            <a class="c-lightblue" href="#" title="LightSteelblue"></a>
        </li>
        <li>
            <a class="c-darktan" href="#" title="Darktan"></a>
        </li>
        <li>
            <a class="c-grey" href="#" title="Grey"></a>
        </li>
        <li>
            <a class="c-brown" href="#" title="Brown"></a>
        </li>
    </ul>

    <p>
        {!! $product->total_color>1?"Có {$product->total_color} màu":'&nbsp;' !!}
    </p>

    <h5 class="product-name">
        <a href="{{$link}}" title="{{$product->title}}" rel="nofollow">{{$product->title}}</a>
    </h5>

    <div class="price-box">
        <span class="price-regular">{{number_format($product->price)}}đ</span>
        @if($product->price_old>0&&$product->price_old>$product->price)
            <span class="price-old"><del>{{number_format($product->price_old)}}đ</del></span>
        @endif
    </div>

    <p>{{$product->title}}</p>
</div>
