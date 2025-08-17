@extends('frontend.layouts.frontend')

@section('content')
    @include('frontend.parts.breadcrumbs')
    <div class="cart-main-area  pt-20 pb-20">
        <div class="container">
            <h3 class="cart-page-title">Giỏ hàng</h3>
            @if(!count($arr_products))
                <p>Chưa có sản phẩm nào trong giỏ hàng</p>
            @else
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <form action="" method="post" id="cartformpage">
                            @csrf
                            <div class="table-content table-responsive cart-table-content">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Hình ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Giá</th>
                                            <th>ĐVT</th>
                                            <th>Số lượng</th>
                                            <th>Tổng tiền</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_cart =  $total = 0;
                                        @endphp
                                        @foreach($arr_products as $k => $product)
                                            @php
                                                $total = $product['price']*$product['quantity'];
                                                $total_cart +=$total;
                                            @endphp
                                            @csrf
                                            <tr>
                                                <td class="product-thumbnail">
                                                    <a href="#"><img width="150px" src="{{$product['picture']}}"
                                                                     alt=""></a>
                                                </td>
                                                <td class="product-name"><a href="#">{{$product['title']}}</a>
                                                    @if($product['product_code'])
                                                        <p>
                                                            <span>SKU: {{$product['product_code']}} | {!! $product['product_variation_name']?"{$product['product_variation_name']}":'' !!}</span>
                                                        </p>@endif</td>
                                                <td class="product-price-cart">
                                                    <p><span
                                                            class="amount">{{number_format($product['price'])}}₫</span>
                                                    </p>

                                                    @if($product['price_old']>0&&$product['price_old']>$product['price'])
                                                        <p>
                                                            <del
                                                                style="display: inline;">{{number_format($product['price_old'])}}
                                                                ₫
                                                            </del>
                                                        </p>
                                                    @endif</td>
                                                <td>{{$product['specifications']}}</td>
                                                <td class="product-quantity pro-details-quality">
                                                    <div class="cart-plus-minus">
                                                        <div class="dec qtybutton">-</div>
                                                        <input
                                                            class="qty p-input quantity-number form-control line-item-qty cart-plus-minus-box"
                                                            type="text" name="cart_data[{{$k}}]" min="1"
                                                            value="{{$product['quantity']}}"
                                                            data-temp="{{$product['quantity']}}">
                                                        <div class="inc qtybutton">+</div>
                                                    </div>
                                                </td>
                                                <td class="product-subtotal"
                                                    style="color: #ff475a">{{number_format($total)}}₫
                                                </td>
                                                <td class="product-remove">
                                                    <a class="cart-remove remove" href="#" data-item_id="{{$k}}" style="font-size: 16px"><i
                                                            class="icon_trash" ></i> Xóa</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="grand-totall">
                                        <div class="title-wrap">
                                            <h4 class="cart-bottom-title section-bg-gary-cart">Thông tin đơn hàng</h4>
                                        </div>
                                        <h5>Tạm tính<span>{{number_format($total_cart)}}đ</span></h5>
                                        <div class="total-shipping">
                                            <h5>Phí ship</h5>
                                            <ul>
                                                <li>
                                                    <span>Thỏa thuận</span></li>
                                                <li></li>
                                            </ul>
                                        </div>
                                        <h4 class="grand-totall-title">Tổng
                                            cộng<span>{{number_format($total_cart)}}đ</span></h4>
                                        <a href="">Thanh toán</a>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="cart-shiping-update-wrapper">
                                        <div class="cart-shiping-update">
                                        </div>
                                        <div class="cart-clear">
                                            <button type="submit" id="update-cart" name="update" style="display: none">
                                                CẬP NHẬT GIỎ HÀNG
                                            </button>
                                            <a>Mua thêm sản phẩm khác</a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.qtybutton').click(function () {
            $('#update-cart').click();
        });
    </script>
@endsection
