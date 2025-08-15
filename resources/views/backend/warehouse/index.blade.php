@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{ $subtitle }}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.warehouses.index') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">
                    @if (auth()->guard('backend')->user()->can('posts.add'))
                        <div class="row">

                            <div class="col-md-2 pull-right">
                                <a href="{{ Route('backend.warehouses.create') }}"
                                    class="btn waves-effect waves-light btn-block btn-info">
                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Thêm mới
                                </a>

                            </div>

                            <!-- todo : choose_branch -->
                            <div class="col-md-2 ">
                                <div class="form-group">
                                    <select class="form-control form-control-sm" name="choose_branch" id="choose_branch">
                                        <option value="">Chi nhánh</option>
                                        @foreach ($list_branch as $st)
                                            <option value="{{ $st['id'] }}" {!! request('status') === $st['id'] ? 'selected="selected"' : '' !!}>{{ $st['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- todo : choose_branch -->

                            <div class="col-md-12">
                                <a href="#" class="btn btn-light">Bàn trống</a>
                                <a href="orders?status=1" class="btn btn-primary">Mới đặt </a>
                                <a href="orders?status=2" class="btn btn-info">Đã xác nhận </a>
                                <a href="orders?status=3" class="btn btn-warning">Đã làm xong</a>
                                <a href="orders?status=4" class="btn btn-success">Hoàn thành</a>
                                <a href="orders?status=5" class="btn btn-danger">Đã hủy</a>
                            </div>

                        </div>
                    @endif

                    <br>
                    <hr>

                    @include('backend.partials.msg')
                    @include('backend.partials.errors')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="ajax-result">
                                @include('backend.warehouse.ajaxList')
                            </div>
                        </div>
                    </div>

                    {{-- add san pham --}}
                    <div class="row">
                        <div class="col-md-4 position-sticky">
                            <table class="table table-striped">
                                <thead>
<!--                                    <tr>
                                        <th scope="col">Tên sản phẩm</th>
                                    </tr>-->
                                </thead>
                                <tbody id="contentProductSearch">

                                <tr>
                                    <th scope="col">
                                        <label class="form-control-label">Chọn menu</label>
                                        <select class="form-control form-control-sm dm123"  id="dm123"
                                                name="product_type_id">
                                            <option value="">Menu</option>
                                            {!! $product_type_html !!}}
                                        </select>

                                    </th>
                                </tr>

                                    <tr id="product_id_12">
                                        <td>
                                            <input type="text" class="form-control searchProduct" id="searchProduct"
                                                placeholder="Nhập tên sản phẩm để tìm nhanh">
                                        </td>
                                    </tr>
                                    <tr id="product_list">

                                        <td>
                                            <div class="container mt-1" style="height: 500px;overflow-y: scroll;">
                                                <div class="row gx-4  row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center"
                                                    id="showAllSearchProduct">

                                                    @foreach ($list_product as $key => $value)
                                                        <div class="col col-md-6 mb-4">
                                                            <div class="card h-100">
                                                                @if ($value->thumbnail->file_src)
                                                                    <img class="card-img-top"
                                                                        src="{{ $value->thumbnail->file_src }}"
                                                                        alt="..." />
                                                                @else
                                                                    <img class="card-img-top"
                                                                        src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg"
                                                                        alt="..." />
                                                                @endif

                                                                <div class="card-body p-2">
                                                                    <div class="text-center">
                                                                        <h5 class="fw-bolder">
                                                                            {{ $value->title }}</h5>
                                                                        {{ $value->price }}
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="card-footer p-2 pt-0 border-top-0 bg-transparent">
                                                                    <div class="text-center">
                                                                        <a class="btn btn-outline-dark mt-auto chooseProduct"
                                                                            id="chooseProduct_{{ $value->id }}"
                                                                            data-price="{{ $value->price }}"
                                                                            data-title="{{ $value->title }}"
                                                                            data-id="{{ $value->id }}"
                                                                            href="#">Thêm</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>

                                    </tr>




                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th scope="col">Tên sản phẩm</th>
                                                <th scope="col" class="text-center" colspan="2">Số lượng
                                                </th>
                                                <th scope="col" class="text-center">Giá bán</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody class="contentProduct" id="contentProduct">
                                            {{-- <tr>
                                                <td></td>
                                                <td></td>
                                                <td colspan="2" class="text-right">Giảm
                                                    giá
                                                </td>
                                                <td colspan="1" class="text-right">
                                                    <strong
                                                        id="total_discount_{{ $order->tab_id }}">{{ $order->total_reduce ?? 0 }}
                                                    </strong> <sup>đ</sup>
                                                </td>
                                            </tr> --}}
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td colspan="2" class="text-right">
                                                    <strong>Tổng tiền trước chiết khấu</strong>
                                                </td>
                                                <td colspan="1" class="text-right">
                                                    <strong id="total1">0
                                                    </strong> <sup>đ</sup>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td colspan="2" class="text-right">
                                                </td>
                                                <td colspan="1" class="text-right">
                                                    <div class="input-group mt-2">
                                                        <input type="text"
                                                               class="text-right form-control discountOrderTotal"
                                                               id="discount_order_total"
                                                               placeholder="Chiết khấu tổng đơn" />

                                                        <div class="input-group-append">
                                                            <span class="input-group-text bg-info text-white">đ</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td colspan="2" class="text-right">
                                                    <strong>Tổng tiền sau chiết khấu</strong>
                                                </td>
                                                <td colspan="1" class="text-right">
                                                    <strong id="total">0
                                                    </strong> <sup>đ</sup>

                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="vertical-align: middle">Bàn đã chọn</td>
                                                <td colspan="5" style="vertical-align: middle">
                                                    <span id="wareHouseSelected"></span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="vertical-align: middle">Tên khách hàng</td>
                                                <td colspan="3" style="vertical-align: middle" id="CustomerPayment">

                                                </td>

                                                <td class="d-flex">
                                                    <button class="btn btn-sm btn-secondary AddCustomerPayment"
                                                            id="AddCustomerPayment">
                                                        Thêm khách hàng
                                                    </button>
                                                </td>
                                            </tr>



                                            <tr>
                                                <td colspan="5">
                                                    <input type="text" class="form-control paymentNote"
                                                           id="paymentNote" placeholder="Nhập ghi chú đơn hàng"
                                                           value="">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="5">
                                                    <input type="text" class="form-control paymentCouponCode"
                                                           id="paymentCouponCode" placeholder="Nhập coupon code">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col mb-2">
                                    <div class="row">

                                        <div class="col-sm-12 col-md-12 text-right"  >
                                            <button class="btn btn-lg btn-block btn-success text-uppercase paymentSuccess"
                                                    id="paymentSuccess">Lên Đơn
                                            </button>
                                        </div>

                                    </div>
                                    <br>
                                    <div class="btn-werehouse-new-order">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @include('backend.branch.modal')
    @include('backend.warehouse.modalCustomer', [
        'provinces' => $provinces,
    ])
@endsection

@section('script')
    <!--todo : add lib thong bao va socket -->
    <script src="https://anocha.me/storage/frontend/js/socket.io.min.js" type="text/javascript"></script>
    <script src="https://anocha.me/storage/backend/js/jquery.toast.js"></script>
    <link href="https://anocha.me/storage/backend/main/css/jquery.toast.css" rel="stylesheet">
    <!--todo : add lib thong bao va socket -->
    <script type="text/javascript">
        var socket = io.connect('wss://chat.thietke24h.vn', {
            transports: ["websocket"]
        });

        socket.on("accept_order", function(data) {
            if (data.api_key === api_key) {

                var data = $('select').find('option:selected').val();
                $.ajax({
                    type: 'get',
                    url: '{{ Route('backend.warehouses.index') }}',
                    data: {
                        branch_id: data
                    },
                    success: function(data) {
                        if (data.e == 0) {
                            $('div.ajax-result').html(data.r);
                            $('html, body').animate({
                                scrollTop: 0
                            }, 0);
                        }
                    }
                });
                /*   if (data.type === 1) {
                       if (data.status_order === 2) {
                           var data= $('select').find('option:selected').val();
                           $.ajax({
                               type: 'get',
                               url: '{{ Route('backend.warehouses.index') }}',
                               data: {
                                   branch_id: data
                               },
                               success: function (data) {
                                   if (data.e == 0) {
                                       $('div.ajax-result').html(data.r);
                                       $('html, body').animate({scrollTop: 0}, 0);
                                   }
                               }
                           });

                       }
                       if(data.status_order === 1){
                           var data= $('select').find('option:selected').val();
                           $.ajax({
                               type: 'get',
                               url: '{{ Route('backend.warehouses.index') }}',
                               data: {
                                   branch_id: data
                               },
                               success: function (data) {
                                   if (data.e == 0) {
                                       $('div.ajax-result').html(data.r);
                                       $('html, body').animate({scrollTop: 0}, 0);
                                   }
                               }
                           });
                       }
                       // alert(data.api_key);
                   }*/
                // alert(data.api_key);
            }
            // console.log(data.api_key);
        });



        $(document).ready(function() {

            $(document).on('click', 'a.btnShowModal', function(e) {
                let delete_id = $(this).data('id');
                $('#delete_id').val(delete_id);
                e.preventDefault();
                $('#deleteModal').modal('show');
            });

            $(document).on('click', 'button.btnDelete', function(e) {
                let delete_id = $('#delete_id').val();
                let data = {
                    _token: '{{ csrf_token() }}',
                    id: delete_id
                }
                $.ajax({
                    type: 'POST',
                    url: '{{ Route('backend.warehouses.delete') }}',
                    dataType: 'json',
                    data: data,
                    success: function(json) {
                        window.location.reload();
                    }
                })
            });
            //    $(document).on( "change",'#dm123',function(event){
            //   $(document).on("change", '.choose_branch', function(event) {
            $(document).on( "change",'#choose_branch',function(event){
           // $('select').on('change', function() {

                $.ajax({
                    type: 'get',
                    url: '{{ Route('backend.warehouses.index') }}',
                    data: {
                        branch_id: this.value
                    },
                    success: function(data) {
                        if (data.e == 0) {
                            $('div.ajax-result').html(data.r);
                            $('html, body').animate({
                                scrollTop: 0
                            }, 0);
                        }
                    }
                });
                // alert( this.value );
            });

            let productsSelected = [];
            let customerSelected;
            let tempPrice  = 0;
            let tempProduct_id  = 0;
            // Xử lý thêm sản phẩm
            $(document).on("click", '.chooseProduct', function(event) {
                event.preventDefault();
                const product_id = $(this).data('id');
                const product_name = $(this).data('title');
                const price = $(this).data('price');
                tempPrice += price;
                tempProduct_id = product_id;
                if (productsSelected.includes(product_id)) {
                    alert('Sản phẩm đã được thêm !!');

                } else {
                    productsSelected.push(product_id);
                    $('#contentProduct').prepend(`<tr id="product_id_show_${product_id}" class="product_id_show">
                            <td>${product_name}</td>
                            <td colspan="2">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary downPrice"
                                            id="downPrice_${product_id}" data-id=${product_id} type="button">-
                                        </button>
                                    </div>
                                    <input type="text" class="form-control updownPrice"
                                        data-id=${product_id}
                                        id="updownPrice_${product_id}" value=1 pattern="[0-9]*"
                                        aria-describedby="basic-addon1">

                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary upPrice"
                                            id="upPrice_${product_id}" data-id=${product_id} type="button">+
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <input type="text" class="form-control noteProduct"
                                        id="noteProduct_${product_id}" placeholder="Nhập ghi chú">
                                </div>

                                <div class="input-group mt-2">
                                    <input type="text" class="form-control discountProduct"
                                        data-id=${product_id} data-price=${price}
                                        id="discountProduct_${product_id}" placeholder="Nhập chiết khấu" />
                                    <div class="input-group-append">
                                        <span class="input-group-text discount-icon bg-info text-white">đ</span>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <div>
                                    <span class="TotalPriceAfterDiscount"
                                        data-id=${product_id}
                                        id="totalPriceAfterDiscount_${product_id}">${price}</span>
                                    <sup>đ</sup>
                                </div>
                            </td>

                            <td class="text-center">
                                <div style="width:auto;margin: auto">
                                    <div style="float: right">
                                        <button class="btn btn-sm btn-danger deleteProduct" data-id=${product_id}><i
                                                class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>`)
                    getTotal();
                }

                // console.log(productsSelected);

                // $('#contentProduct').html(returnHTML);
                // $.ajax({
                //     url: "{{ Route('backend.warehouses.ajaxInsertProduct') }}",
                //     type: "POST",
                //     dataType: "json",
                //     data: data,
                //     success: function(json) {
                //         if (json.status == 'success') {
                //             console.log('json: ', json);
                //             // var showProduct = product_id;
                //             // if ($("#product_id_show_" + showProduct).length > 0) {
                //             //     $('#totalPrice_' + product_id).text(json.data
                //             //         .product.total_price);
                //             //     $('#updownPrice_' + product_id).val(json.data
                //             //         .product.quantity);
                //             // } else {
                //             //     $('#contentProduct').prepend(json.data.returnHTML)
                //             // }

                //             // getTotal();
                //         }
                //     }
                // });

            });

            $(document).on("click", '.downPrice', function(event) {
                const idProduct = $(this).attr('data-id');

                const quantityProduct = $(`#updownPrice_${idProduct}`).val();
                if (Number(quantityProduct) <= 1) {
                    alert('Số lượng tối thiểu là 1');
                } else {
                    $(`#updownPrice_${idProduct}`).val(Number(quantityProduct) - 1)
                    getTotal();
                }

            })

            $(document).on("click", '.upPrice', function(event) {
                const idProduct = $(this).attr('data-id');
                const quantityProduct = $(`#updownPrice_${idProduct}`).val();

                $(`#updownPrice_${idProduct}`).val(Number(quantityProduct) + 1);
                getTotal();
            })

            $(document).on("change", '.updownPrice', function(event) {
                const idProduct = $(this).attr('data-id');
                $(this).val(Number(event.target.value));
                getTotal();
            })

            $(document).on("click", '.deleteProduct', function(event) {
                const idProduct = $(this).attr('data-id');
                //alert(idProduct)
                productsSelected = productsSelected.filter(product => Number(product) !== Number(
                    idProduct));
                $(`#contentProduct #product_id_show_${idProduct}`).remove();
                getTotal();

                $.ajax({
                    url: "{{ Route('backend.orders.deleteproduct') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        id: idProduct,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(json) {

                    }
                });




            })

            $(document).on("change", '.discountProduct', function(event) {
                const idProduct = $(this).attr('data-id');
                const priceProduct = $(this).attr('data-price');
                const amountDiscount = Number(event.target.value);

                if (Number(priceProduct) < Number(amountDiscount)) {
                    alert('Số tiền giảm giá không hợp lệ.')
                    $(this).val('');
                } else {
                    $(`#totalPriceAfterDiscount_${idProduct}`).html(Number(priceProduct) - Number(amountDiscount));

                }

                getTotal();
            })

            $(document).on("change", '#discount_order_total', function(event) {
                const amountTotalDiscount = Number(event.target.value);
                getTotal();
            })

            $(document).on("change", '.paymentCouponCode', function(event) {
                let code = $('#paymentCouponCode').val();
                let data = {
                    'totalprice': tempPrice,
                    'code': code,
                    _token: '{{ csrf_token() }}',
                }

                $.ajax({
                    url: "{{ Route('backend.warehouses.checkCoupon') }}",
                    type: "POST",
                    dataType: "json",
                    data: data,
                    success: function(json) {

                        if (json.status == 'fail') {
                            alert(json.message);
                            /*$('#errorMessage').html(json.message);
                            $("#dialog-message").dialog({
                                modal: true,
                                buttons: {
                                    Ok: function() {
                                        $(this).dialog("close");
                                    }
                                }
                            });*/
                        } else {
                            //alert(tempProduct_id);

                            if (Number(tempPrice) <= Number(json.data.total_reduce)) {
                                alert('Số tiền giảm giá không hợp lệ.')
                                //$(this).val('');
                            } else {
                                let temp = 'Số tiền giảm giá : ' + json.data.total_reduce + 'đ';
                                alert(temp);
                                $('#total').html(Number(tempPrice) - Number(json.data.total_reduce));
                                $('#discount_order_total').val(Number(json.data.total_reduce));

                            }

                        }
                    }
                });

            });

            $(document).on("click", '.selectWarehouse', function(event) {

                $('#paymentSuccess').show()
                $('.btn-werehouse-new-order').hide()
                const item = JSON.parse($(this).attr('data-item'));
                $('#wareHouseSelected').html(`<span data-id=${item.id} data-branch=${item.branch_id}
                class="alert alert-primary py-1">${item.name} - ${item.namebranch}</span>`)
            })

            var temp_long = 0;
            var temp_long1 = '';

            $(document).on("click", '.selectedWarehouse1', function(event) {
                var ids = $(this).attr('data-id_item');
                $('#paymentSuccess').hide()
                // var order_id_src = $(this).attr('data-order-id');
                // alert(order_id_src)
                {{--var src ="{{ Route('backend.warehouses.detail') }}";--}}





                const item = JSON.parse($(this).attr('data-item'));
                $('#wareHouseSelected').html(`<span data-id=${item.id} data-branch=${item.branch_id}
                class="alert alert-primary py-1">${item.name} - ${item.namebranch}</span>`)
                // $('#paymentNote').html(`<span data-id=${item.id} data-branch=${item.branch_id}
                // class="alert alert-primary py-1">${item.name} - ${item.namebranch}</span>`)
                //alert(item.name);
                let data = {
                    'table': ids,
                    _token: '{{ csrf_token() }}',
                }
                $.ajax({
                    url: "{{ Route('backend.warehouses.ajaxResult') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        id : ids,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(json) {
                        console.table(json.r)
                        temp_long = json.r.id
                        $('#CustomerPayment').html(json.u.fullname)
                        $('#paymentNote').val(json.r.note)
                        $('#total').html(json.r.total_price)

                        $('.product_id_show').html("");
                        productsSelected = [];

                       /// $('#contentProduct').prepend("");
                       //$('#22222').html('');$( "p" ).empty();

                         $('.product_id_show').empty();
                         productsSelected = [];
                        for(var k in json.i) {


                            const quantitys = json.i[k].quantity;
                            event.preventDefault();
                            const product_id = json.i[k].id;
                            const product_name = $(this).data('title');
                            const price = json.i[k].price;
                            tempPrice += price;
                            tempProduct_id = product_id;
                            if (productsSelected.includes(product_id)) {
                                alert('Sản phẩm đã được thêm !!');

                            } else {
                                ///alert(product_id);
                                productsSelected.push(product_id);
                              //  $('#contentProduct').prepend("");
                                if (json.r.warehouse_id == ids && json.r.id == json.i[k].order_id  ) {
                                   // $('.product_id_show').empty();
                                    $('#contentProduct').prepend(`<tr id="product_id_show_${json.i[k].id}" class="product_id_show">
                            <td>${json.i[k].title} </td>

                            <td  colspan="2">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary downPrice"
                                            id="downPrice_${json.i[k].id}" data-id=${json.i[k].id} type="button">-
                                        </button>
                                    </div>
                                    <input type="text" class="form-control updownPrice"
                                        data-id=${json.i[k].id}
                                        id="updownPrice_${json.i[k].id}" value="${quantitys}" pattern="[0-9]*"
                                        aria-describedby="basic-addon1">

                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary upPrice"
                                            id="upPrice_${json.i[k].id}" data-id=${json.i[k].id} type="button">+
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <input type="text" class="form-control noteProduct"
                                        id="noteProduct_${json.i[k].id}" placeholder="Nhập ghi chú">
                                </div>

                                <div class="input-group mt-2">
                                    <input type="text" class="form-control discountProduct"
                                        data-id=${json.i[k].id} data-price=${json.i[k].price}
                                        id="discountProduct_${json.i[k].id}" placeholder="Nhập chiết khấu" />
                                    <div class="input-group-append">
                                        <span class="input-group-text discount-icon bg-info text-white">đ</span>
                                    </div>
                                </div>

                            </td>

                            <td class="text-center">
                                <div>
                                    <span class="TotalPriceAfterDiscount"
                                        data-id=${json.i[k].id}
                                        id="totalPriceAfterDiscount_${json.i[k].id}">${json.i[k].price}</span>
                                    <sup>đ</sup>
                                </div>
                            </td>

                            <td class="text-center">
                                <div style="width:auto;margin: auto">
                                    <div style="float: right">
                                        <button class="btn btn-sm btn-danger deleteProduct" data-id=${json.i[k].id}><i
                                                class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>

                        </tr>`)
                                    getTotal();
                                } else {
                                    window.location.reload(true)
                                }

                            }

                        }
                        var order_id_src = json.r.id;
                        var url = 'https://thorvina.com/admin/orders/'+order_id_src+'?_ref=https://thorvina.com/admin/orders';
                        $('.btn-werehouse-new-order').html(`<div class="row">
                                            <div class="col-md-6">
                                                <button class="btn btn-lg btn-block btn-info text-uppercase update_paymentSuccess"
                                                        id="update_paymentSuccess">Cập Nhập
                                                </button>
                                            </div>
                                            <div class="col-md-6">
                                               <a href="${url}">
                                                     <button class="btn btn-lg btn-block btn-warning text-uppercase pay_paymentSuccess"
                                                                                                            id="pay_paymentSuccess">Thanh Toán
                                                                                                    </button>
                                                    </a>
                                            </div>
                                        </div>`)


                    }

                });



            })

            // Long update ban moi dat
            // $(document).on("click", '.update_paymentSuccess', function(event) {
            //     var id_order = temp_long;
            //     var id_product1 = temp_long1;
            //     let arr =[1,2,3];
            //     console.log(id_product1)
            //     alert(id_product1.length)
            //
            //
            // });
            $(document).on("click", '.update_paymentSuccess', function(event) {
                var id_order = temp_long;
                //     var id_product1 = temp_long1;
                const wareHouseSelectedEle = $('#wareHouseSelected').html();
                const customerEle = $('#CustomerPayment > span');

                if (wareHouseSelectedEle === '') {
                    alert('Vui lòng chọn bàn');
                    return;
                }


                const idWareHouse = $('#wareHouseSelected > span').attr('data-id');
                const idBranch = $('#wareHouseSelected > span').attr('data-branch');
                const totalOrder = $('#total').html();
                const discountOrderTotal = $('#discount_order_total').val();
                const notePayment = $('#paymentNote').val();

                const newProductsSelected = productsSelected.map(idProduct => {
                    return {
                        id: idProduct,
                        amount_discount: $(`#discountProduct_${idProduct}`).val(),
                        price_after_discount: $(`#totalPriceAfterDiscount_${idProduct}`).html(),
                        quantity: $(`#updownPrice_${idProduct}`).val(),
                    }
                });

                console.log('discountOrderTotal: ', discountOrderTotal);


                if (customerEle.length === 0) {
                    customerSelected = {
                        fullname: "Khách vãng lai",
                        email:  "N/A",
                        phone:  "N/A",
                        address:  "N/A",
                        street:  "N/A",
                        province_id: 0,
                        district_id: 0,
                        ward_id: 0,
                        note_customer:  "N/A",
                    }
                }

                let data = {
                    id_order: id_order,
                    fullname: customerSelected.fullname,
                    phone: customerSelected.phone,
                    email: customerSelected.email,
                    address: customerSelected.address,
                    street: customerSelected.street,
                    province_id: customerSelected.province_id,
                    district_id: customerSelected.district_id,
                    ward_id: customerSelected.ward_id,
                    note_customer: customerSelected.note_customer,
                    status: 1,
                    company_id: 6,
                    note: notePayment || '',
                    total_price: totalOrder,
                    warehouse_id: idWareHouse,
                    branch_id: idBranch,
                    newProductsSelected: newProductsSelected,
                    discount_order_total: discountOrderTotal,
                    _token: '{{ csrf_token() }}',
                }
               console.log(data)

                $.ajax({
                    url: "{{ Route('backend.orders.updateOrder') }}",
                    type: "POST",
                    dataType: "json",
                    data: data,
                    success: function(json) {
                        console.log(json)
                        alert('Cập nhập thành công !')
                    }
                });
            })




            $(document).on("click", '.pay_paymentSuccess', function(event) {
                var id_order = temp_long;
                //     var id_product1 = temp_long1;
                const wareHouseSelectedEle = $('#wareHouseSelected').html();
                const customerEle = $('#CustomerPayment > span');

                if (wareHouseSelectedEle === '') {
                    alert('Vui lòng chọn bàn');
                    return;
                }


                const idWareHouse = $('#wareHouseSelected > span').attr('data-id');
                const idBranch = $('#wareHouseSelected > span').attr('data-branch');
                const totalOrder = $('#total').html();
                const discountOrderTotal = $('#discount_order_total').val();
                const notePayment = $('#paymentNote').val();

                const newProductsSelected = productsSelected.map(idProduct => {
                    return {
                        id: idProduct,
                        amount_discount: $(`#discountProduct_${idProduct}`).val(),
                        price_after_discount: $(`#totalPriceAfterDiscount_${idProduct}`).html(),
                        quantity: $(`#updownPrice_${idProduct}`).val(),
                    }
                });

                console.log('discountOrderTotal: ', discountOrderTotal);


                if (customerEle.length === 0) {
                    customerSelected = {
                        fullname: "Khách vãng lai",
                        email:  "N/A",
                        phone:  "N/A",
                        address:  "N/A",
                        street:  "N/A",
                        province_id: 0,
                        district_id: 0,
                        ward_id: 0,
                        note_customer:  "N/A",
                    }
                }

                let data = {
                    id_order: id_order,
                    fullname: customerSelected.fullname,
                    phone: customerSelected.phone,
                    email: customerSelected.email,
                    address: customerSelected.address,
                    street: customerSelected.street,
                    province_id: customerSelected.province_id,
                    district_id: customerSelected.district_id,
                    ward_id: customerSelected.ward_id,
                    note_customer: customerSelected.note_customer,
                    status: 1,
                    company_id: 6,
                    note: notePayment || '',
                    total_price: totalOrder,
                    warehouse_id: idWareHouse,
                    branch_id: idBranch,
                    newProductsSelected: newProductsSelected,
                    discount_order_total: discountOrderTotal,
                    _token: '{{ csrf_token() }}',
                }
                console.log(data)

                $.ajax({
                    url: "{{ Route('backend.orders.updateOrder') }}",
                    type: "POST",
                    dataType: "json",
                    data: data,
                    success: function(json) {
                        console.log(json)
                    }
                });

                window.location.reload()
            });






            $(document).on("click", '.upPrice1', function(event) {
                const idProduct = $(this).attr('data-id');
                var product_price = $(this).attr('data-product-price');
                var price = $(this).attr('data-price');
                const quantityProduct = $(`#updownPrice_${idProduct}`).val();
                //alert(price*quantityProduct)

                alert(parseInt(price)+parseInt(product_price))
            });
            function getTotal() {
                let totalOrder = 0;
                let totalOrder1 = 0;

                productsSelected.forEach(idProduct => {

                    const quantityProduct = $(`#updownPrice_${idProduct}`).val();
                    const priceAfterDiscount = $(`#totalPriceAfterDiscount_${idProduct}`).html();

                    totalOrder += Number(quantityProduct) * Number(priceAfterDiscount);
                    totalOrder1 += Number(quantityProduct) * Number(priceAfterDiscount);
                });


                const orderTotalDiscount = $('#discount_order_total').val();

                if (orderTotalDiscount > totalOrder) {
                    alert('Số tiền chiết khấu không hợp lệ.')
                    $('#discount_order_total').val('');
                } else {
                    totalOrder -= Number(orderTotalDiscount);
                }

                $('#total').html(totalOrder);
                $('#total1').html(totalOrder1);
            }

            $(document).on("click", '#AddCustomerPayment', function(event) {

                $('#customerModal').modal('show')
                $('#province_id').select2({
                    dropdownParent: $('#customerModal')
                });

                $('#district_id').select2({
                    dropdownParent: $('#customerModal')
                });
                $('#ward_id').select2({
                    dropdownParent: $('#customerModal')
                });
            });

            $('#customerModal').on('show.bs.modal', function(event) {
                $('#province_id').val(0).trigger('change');
                $('#district_id').val(0).trigger('change');
                $('#ward_id').val(0).trigger('change');
            });

            $('#customerModal').on('hidden.bs.modal', function(e) {
                $('#province_id').val(0).trigger('change');
                $('#district_id').val(0).trigger('change');
                $('#ward_id').val(0).trigger('change');
            });

            $('#province_id').on('select2:select', function(e) {
                let province_id = parseInt(e.target.value);
                if (!isNaN(province_id)) {
                    ajaxLoadDistrict(province_id, 0)
                    ajaxLoadWard(0, 0)
                    $('#ward_id').val(0).trigger('change');
                }
            })


            $('#district_id').on('select2:select', function(e) {
                let district_id = parseInt(e.target.value);
                if (!isNaN(district_id)) {
                    ajaxLoadWard(district_id, 0)
                }
            })


            function ajaxLoadDistrict(province_id, district_id = 0, ward_id = 0) {
                let data = {
                    'province_id': province_id,
                    _token: '{{ csrf_token() }}',
                }

                $.ajax({
                    url: "{{ Route('backend.warehouses.ajaxLoadDistrict') }}",
                    type: "POST",
                    dataType: "json",
                    data: data,
                    success: function(json) {
                        if (json.status == 'success') {
                            if (json.data) {
                                $('#district_id').html(json.data)
                                $('#district_id').val(district_id).trigger('change');
                                if (parseInt(ward_id) > 0) {
                                    ajaxLoadWard(district_id, ward_id)
                                }
                            }
                        }
                    }
                });
            }

            function ajaxLoadWard(district_id, ward_id = 0) {
                let data = {
                    'district_id': district_id,
                    'ward_id': ward_id,
                    _token: '{{ csrf_token() }}',
                }
                $.ajax({
                    url: "{{ Route('backend.warehouses.ajaxLoadWard') }}",
                    type: "POST",
                    dataType: "json",
                    data: data,
                    success: function(json) {
                        if (json.status == 'success') {
                            if (json.data) {
                                $('#ward_id').html(json.data)
                                $('#ward_id').val(ward_id).trigger('change');
                            }
                        }
                    }
                });
            }

            // Tìm kiếm sản phẩm
            $(document).on("keyup", '.searchProduct', function(event) {
                var keywords = $('#searchProduct').val();

                let data = {
                    'keywords': keywords,
                    _token: '{{ csrf_token() }}',
                }

                $.ajax({
                    url: "{{ Route('backend.warehouses.searchProduct') }}",
                    type: "POST",
                    dataType: "json",
                    data: data,
                    success: function(json) {
                        if (json.status == 'success') {
                            $('#showAllSearchProduct').html(json.data.returnHTML);
                        }
                    }
                });
            });

            // Lấy sản phẩm theo danh mục
            $(document).on( "change",'#dm123',function(event){
                var id = $(this).val();
                let data = {
                    id_user: id,
                    _token: '{{ csrf_token() }}',
                }

                $.ajax({
                    url: "{{ Route('backend.warehouses.getDanhMuc') }}",
                    type: "POST",
                    dataType: "json",
                    data: data,
                    success: function(json) {
                        if (json.status == 'success') {
                            $('#showAllSearchProduct').html(json.data.returnHTML);
                        }
                    }
                });
            });


            $(document).on("click", '.updateCustomer', function(event) {

                let Tempfullname = $('#fullname').val();
                if (!Tempfullname) {
                    Tempfullname = "Khách vãng lai";
                }
                let Tempemail = $('#email').val();
                if (!Tempemail) {
                    Tempemail = "N/A";
                }

                let Tempphone = $('#phone').val();
                if (!Tempphone) {
                    Tempphone = "N/A";
                }
                let Tempaddress = $('#address').val();
                if (!Tempaddress) {
                    Tempaddress = "N/A";
                }
                let Tempstreet = $('#street').val();
                if (!Tempstreet) {
                    Tempstreet = "N/A";
                }

                customerSelected = {
                    fullname: Tempfullname,
                    email: Tempemail,
                    phone: Tempphone,
                    address: Tempstreet,
                    street: $('#street').val(),
                    province_id: $('#province_id').val(),
                    district_id: $('#district_id').val(),
                    ward_id: $('#ward_id').val(),
                    note_customer: $('#note_customer').val(),
                }

                $('#CustomerPayment').html(
                    `<span class="alert alert-primary py-1">${customerSelected.fullname}</span>`
                );

                $('#customerModal').modal('hide');
            });

            $(document).on("click", '#paymentSuccess', function(event) {
                const wareHouseSelectedEle = $('#wareHouseSelected').html();
                const customerEle = $('#CustomerPayment > span');

                if (wareHouseSelectedEle === '') {
                    alert('Vui lòng chọn bàn');
                    return;
                }

                /*if (customerEle.length === 0) {
                    ///alert('Vui lòng  thông tin khách hàng');
                   // return;
                    customerSelected.fullname = "Khách vãng lai";
                    customerSelected.email = "N/A";
                    customerSelected.phone = "N/A";
                    customerSelected.address = "N/A";
                    customerSelected.street = "N/A";
                }*/

                const idWareHouse = $('#wareHouseSelected > span').attr('data-id');
                const idBranch = $('#wareHouseSelected > span').attr('data-branch');
                const totalOrder = $('#total').html();
                const discountOrderTotal = $('#discount_order_total').val();
                const notePayment = $('#paymentNote').val();

                const newProductsSelected = productsSelected.map(idProduct => {
                    return {
                        id: idProduct,
                        amount_discount: $(`#discountProduct_${idProduct}`).val(),
                        price_after_discount: $(`#totalPriceAfterDiscount_${idProduct}`).html(),
                        quantity: $(`#updownPrice_${idProduct}`).val(),
                    }
                });

                console.log('discountOrderTotal: ', discountOrderTotal);


                if (customerEle.length === 0) {
                    customerSelected = {
                        fullname: "Khách vãng lai",
                        email:  "N/A",
                        phone:  "N/A",
                        address:  "N/A",
                        street:  "N/A",
                        province_id: 0,
                        district_id: 0,
                        ward_id: 0,
                        note_customer:  "N/A",
                    }
                }

                let data = {
                    fullname: customerSelected.fullname,
                    phone: customerSelected.phone,
                    email: customerSelected.email,
                    address: customerSelected.address,
                    street: customerSelected.street,
                    province_id: customerSelected.province_id,
                    district_id: customerSelected.district_id,
                    ward_id: customerSelected.ward_id,
                    note_customer: customerSelected.note_customer,
                    status: 1,
                    company_id: 6,
                    note: notePayment || '',
                    total_price: totalOrder,
                    warehouse_id: idWareHouse,
                    branch_id: idBranch,
                    newProductsSelected: newProductsSelected,
                    discount_order_total: discountOrderTotal,
                    _token: '{{ csrf_token() }}',
                }

                $.ajax({
                    url: "{{ Route('backend.orders.updatePayment') }}",
                    type: "POST",
                    dataType: "json",
                    data: data,
                    success: function(json) {
                        if (json.status === 'success') {
                            // reset data
                            // $('#fullname').val();
                            // $('#email').val();
                            // $('#phone').val();
                            // $('#recommender').val();
                            // $('#address').val();
                            // $('#province_id').val();
                            // $('#district_id').val();
                            // $('#ward_id').val();
                            // $('#note_customer').val()
                            // customerSelected = null;
                            // $('#wareHouseSelected > span').remove();
                            // $('#CustomerPayment > span').remove();
                            window.location.reload(true);
                        } else {
                            alert(json.message);
                        }
                    }
                });
            })
        });
    </script>
@stop
