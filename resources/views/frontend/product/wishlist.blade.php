@extends('frontend.layouts.frontend')
@section('content')

    @include('frontend.parts.breadcrumbs')
    <div class="cart-main-area pt-50 pb-120">
        <div class="container">
            <h3 class="cart-page-title">DANH SÁCH YÊU THÍCH</h3>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <form action="#">
                        <div class="table-content table-responsive cart-table-content" style="padding: 40px">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Hình ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wish_list as $item)

                                        <tr>
                                            @php
                                            $link = product_link($item->product->slug,$item->product->id,$item->product->product_type_id);
                                                $thumbnail = \App\Models\Files::find($item->product->thumbnail_id);
                                            @endphp
                                            <td class="product-thumbnail">
                                                <a href="{{$link}}"><img width="150px"
                                                                 src="{{url('storage/uploads/'.$thumbnail->file_path)}}"
                                                                 alt=""></a>
                                            </td>
                                            <td class="product-name"><a href="{{$link}}">{{$item->product->title}}</a></td>
                                            <td class="product-price-cart"><span class="amount">{{number_format($item->product->price)}}đ</span>
                                                <p><span class="amount"> <del>{{number_format($item->product->price)}}đ</del></span>
                                                </p>
                                            </td>
                                            <td class="product-wishlist-cart">
                                                <a href="#" data-id="{{$item->product->id}}"
                                                   class="deleteWishList target{{$item->product->id}}"><i
                                                        class="fa fa-close"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.deleteWishList').click(function () {
            var product_id = $(this).data('id');
            Swal.fire({
                title: 'Bạn có chắc muốn xóa sản phẩm khỏi danh sách yêu thích?',
                // text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: BASE_URL + '/ajax/wishlist/delete',
                        type: 'post',
                        data: {product_id: product_id},
                        dataType: 'json',
                        success: function (response) {
                            if (response.code == 200) {
                                swal.fire(
                                    'Thông báo',
                                    'Đã xóa khỏi danh sách',
                                    'success'
                                );
                                $('.target' + product_id).parent().parent().remove();
                            } else {
                                swal.fire(
                                    'Thông báo',
                                    response.message,
                                    'error',
                                )
                            }
                        }
                    });
                }
            })
        });
    </script>
@endsection
