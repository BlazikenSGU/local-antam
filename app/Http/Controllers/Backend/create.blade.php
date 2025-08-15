@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-dark" style="border-bottom: 1px solid #000; display: inline-block">Lên đơn hàng</h3>
        </div>
        {{--        <div class="col-md-7 align-self-center">--}}
        {{--            {{ Breadcrumbs::render('backend.orders.index') }}--}}
        {{--        </div>--}}
    </div>
    <div class="row p-3">
        <div class="col-md-12" style=" position: -webkit-sticky; /* Safari */
    position: sticky;
    top: 0; /* Chỉnh sửa giá trị top theo yêu cầu */
    z-index: 1000; /* Chỉnh sửa giá trị z-index nếu cần */
  ">
            <div class="card card-outline-info">
                <div class="card-body text-center">
                    @if(!empty($order->order_code))
                        <h3 class="font-weight-bold" style="color: #00467F">  {{$order->order_code}}
                            - {{ \App\Models\StatusName::where('key', $order->statusName)->first()->name }} </h3>

                    @else
                        <h3>Đang lên đơn</h3>

                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8 offset-md-2 ">
                            <form class="form-horizontal" action="" method="post" id="myForm">
                                @include('backend.partials.msg')
                                @include('backend.partials.errors')
                                @csrf
                                <?php $checkStatus = 'readonly';
                                if ($order->statusName != 'ready_to_pick' or $order->statusName != 'create_order')
                                    $checkStatus = '';
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2 class="font-weight-bold" style="color: #F26522"><i class="mdi mdi-send"></i>
                                            Bên Gửi
                                            @if(empty($order->order_code))
                                                <i class="fas fa-edit edit-address"
                                                   style="color: grey; cursor: pointer"></i>
                                            @endif
                                        </h2>

                                    </div>
                                    <div class="col-md-6">

                                        <ul>
                                            <li class="font-weight-bold" style="color: #F26522">{{$order->fullname}}
                                                - {{$order->phone}} </li>
                                            <li class="font-weight-bold" style="color: #00467F">{{$shopAddress}}</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        @if(!empty($order->return_address))
                                            <ul>
                                                <label for="html">Địa chỉ hoàn trả hàng</label><br>
                                                <li class="font-weight-bold" style="color: #00467F">
                                                    {{$order->return_phone}}
                                                </li>
                                                <li class="font-weight-bold" style="color: #00467F">
                                                    {{$order->return_address}}
                                                </li>

                                            </ul>

                                        @endif
                                        <ul style="text-decoration: none;     list-style: none;">
                                            <li>
                                                <input type="button" class="btn-info" id="add_address_return"
                                                       name="fav_language" value="+">
                                                <label for="html">Thêm địa chỉ hoàn trả hàng</label><br>
                                            </li>

                                        </ul>


                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">

                                        </div>

                                    </div>

                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <h2 class="font-weight-bold" style="color: #F26522"><i class="mdi mdi-send"></i>
                                            Bên Nhận</h2>
                                    </div>
                                    <?php
                                    $isCheckAddress = 'readonly';
                                    $arrays = ['create_order', 'ready_to_pick', 'picking', 'picked', 'storing', 'transporting', 'sorting', 'delivering', 'delivery_fail', 'waiting_to_return'];

                                    if (in_array($order->statusName, $arrays)) {
                                        $isCheckAddress = '';
                                    }


                                    // check user người nhận
                                    $isCheckUserAddress = 'readonly';
                                    $arrays = ['create_order', 'ready_to_pick', 'picking', 'money_collect_picking', 'picked', 'storing', 'transporting', 'sorting', 'delivering', 'delivery_fail', 'waiting_to_return'];

                                    if (in_array($order->statusName, $arrays)) {
                                        $isCheckUserAddress = '';
                                    }

                                    ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-12 font-weight-bold label1">Số điện thoại</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control form-control-line to_phone"
                                                       name="phone"  id="field1" data-required-after=""
                                                       onkeyup="UpdateOrder({{$order->id}})" {{ $isCheckAddress }}
                                                       required value="{{old('to_phone', $order->to_phone)}}">

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 font-weight-bold label2">Họ tên</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control form-control-line to_username"
                                                       name="username" {{$isCheckUserAddress}}
                                                       id="field2" data-required-after="field1"
                                                       onclick="checkValue(1)" {{ $isCheckAddress }}
                                                       required value="{{old('to_username', $order->to_name)}}">
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-6">


                                        <div class="form-group">
                                            <label class="col-md-12 font-weight-bold label3">Địa chỉ</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control form-control-line to_address"
                                                       name="address"
                                                       id="field3"
                                                       onclick="checkValue(2)" {{ $isCheckAddress }}
                                                       required value="{{old('address', $order->to_address)}}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold label4">Tỉnh thành <span
                                                        class="text-danger">*</span></label>
                                                <select
                                                    class="form-control form-control-line province to_province js-example-basic-single"
                                                    required
                                                    id="field4"
                                                    onchange="checkValue(3)" {{ $isCheckAddress }}
                                                    name="to_province">
                                                    @if($order->to_province != null)
                                                        <option value="{{$order->to_province}}">
                                                            {{ $order->to_province_name }}
                                                        </option>
                                                    @else
                                                        <option value="">Chọn</option>
                                                    @endif

                                                </select>
                                                <input type="text" hidden=""
                                                       class="form-control form-control-line province_name"
                                                       value="{{old('to_province_name', $order->to_province_name)}}"
                                                       name="to_province_name">
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold label5">Quận huyện <span
                                                        class="text-danger">*</span></label>
                                                <select
                                                    class="form-control form-control-line district to_district js-example-basic-single"
                                                    required
                                                    id="field5"
                                                    onchange="checkValue(4)" {{ $isCheckAddress }}
                                                    name="to_district"
                                                    value="{{old('to_district', $order->to_district)}}">

                                                    @if($order->to_district_name != null)
                                                        <option value="{{$order->to_district}}">
                                                            {{ $order->to_district_name }}
                                                        </option>
                                                    @else
                                                        <option value=""></option>
                                                    @endif

                                                </select>
                                                <input type="text" hidden="" {{ $isCheckAddress }}
                                                class="form-control form-control-line district_name"
                                                       value="{{old('to_district_name', $order->to_district_name)}}"
                                                       name="to_district_name">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold label6">Phường xã <span
                                                        class="text-danger">*</span></label>
                                                <select
                                                    class="form-control form-control-line ward to_ward js-example-basic-single"
                                                    required
                                                    id="field6"
                                                    onchange="checkValue(5)"
                                                    {{ $isCheckAddress }}
                                                    name="to_ward" value="{{old('to_ward', $order->to_ward)}}">

                                                    @if($order->to_ward_name != null)
                                                        <option value="{{$order->to_ward}}">
                                                            {{ $order->to_ward_name }}
                                                        </option>
                                                    @else
                                                        <option value="">
                                                            Chọn
                                                        </option>
                                                    @endif

                                                </select>
                                                <input type="text" hidden="" {{ $isCheckAddress }}
                                                class="form-control form-control-line ward_name"
                                                       value="{{old('to_ward_name', $order->to_ward_name)}}"
                                                       name="to_ward_name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <h2 class="font-weight-bold" style="color: #F26522"><i class="mdi mdi-send"></i>
                                            Thông tin hàng hóa</h2>
                                    </div>
                                    <?php
                                    $isCheckAddProduct = 'readonly';
                                    $arrays = ['create_order', 'ready_to_pick', 'picking', 'money_collect_picking', 'picked', 'storing', 'transporting', 'delivery_fail', 'waiting_to_return'];

                                    if (in_array($order->statusName, $arrays)) {
                                        $isCheckAddProduct = '';
                                    }
                                    ?>

                                    <div class="col-md-12">
                                        <h4 style="color: #F26522">| Sản phẩm</h4>
                                        <div class="col-md-1">
                                            @if(empty($isCheckAddProduct))
                                                <p class="btn_add_newProduct badge"
                                                   style="cursor: pointer; background-color: #00467F; color: #fff; padding: 10px 20px; font-size: 14px"
                                                   data-id="{{$order->id}}">
                                                    <i class="p-2 text-light  mdi mdi-library-plus align-items-center justify-content-center"></i>
                                                    Thêm sản phẩm
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-3 itemProduct">

                                        @foreach($orderDetails as $itemDetail)
                                            <div class="bill-item p-2 align-items-center mb-2"
                                                 style="height: auto; border: 1px solid #ccc; transition: border-color 0.2s;">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                            <span class="input-group-text font-weight-bold"
                                                                  id="basic-addon1">Tên SP</span>
                                                            </div>
                                                            <input type="text"
                                                                   class="form-control product_name_{{$itemDetail->id}}"
                                                                   name="product_name"
                                                                   placeholder="Tên Sản phẩm"
                                                                   value="{{$itemDetail->product_name}}"
                                                                   onchange="updateDetail({{$itemDetail->id}})"
                                                                   {{ $isCheckAddProduct }}
                                                                   aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                            <span class="input-group-text font-weight-bold"
                                                                  id="basic-addon1">Mã SP</span>
                                                            </div>
                                                            <input type="text"
                                                                   class="form-control product_code_{{$itemDetail->id}}"
                                                                   name="product_code"
                                                                   placeholder="Mã sản phẩm"
                                                                   value="{{ $itemDetail->product_code }}"
                                                                   {{ $isCheckAddProduct }}
                                                                   onchange="updateDetail({{$itemDetail->id}})"
                                                                   aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-1 desktop">
                                                        <p class="btn_delete_Product" data-id="{{$itemDetail->id}}">
                                                            <i class="p-2 text-light badge btn-danger mdi mdi-delete d-flex align-items-center justify-content-center ml-2"
                                                               style="cursor: pointer; border-radius: 20px; color: red; font-size: 14px"></i>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                            <span class="input-group-text font-weight-bold"
                                                                  id="basic-addon1">Khối lương (gam)</span>
                                                            </div>
                                                            <input type="number"
                                                                   class="form-control productVoluem product_voluem_{{$itemDetail->id}}"
                                                                   name="product_voluem"
                                                                   placeholder="Khối lượng sản phẩm"
                                                                   value="{{$itemDetail->product_voluem}}"
                                                                   onchange="updateDetail({{$itemDetail->id}})"
                                                                   {{ $isCheckAddProduct }}
                                                                   aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                            <span class="input-group-text font-weight-bold"
                                                                  id="basic-addon1">Số lượng</span>
                                                            </div>
                                                            <input type="number"
                                                                   class="form-control quantity product_quantity_{{$itemDetail->id}}"
                                                                   name="quantity"
                                                                   placeholder="Số lượng sản phẩm"
                                                                   value="{{ $itemDetail->quantity }}"
                                                                   onchange="updateDetail({{$itemDetail->id}})"
                                                                   {{ $isCheckAddProduct }}
                                                                   aria-describedby="basic-addon1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 mobile">
                                                        <p class="btn_delete_Product" data-id="{{$itemDetail->id}}">
                                                            <i class="p-2 text-light badge btn-danger mdi mdi-delete d-flex align-items-center justify-content-center ml-2"
                                                               style="cursor: pointer; border-radius: 20px; color: red; font-size: 14px"></i>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <?php
                                    $isCheckProduct = 'readonly';
                                    $arrays = ['create_order', 'ready_to_pick', 'picking', 'picked'];

                                    if (in_array($order->statusName, $arrays)) {
                                        $isCheckProduct = '';
                                    }


                                    ?>

                                    <div class="col-md-12 mb-2">
                                        <h4 style="color: #F26522">| Thông tin gói hàng</h4>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-md-12 font-weight-bold label7">Tổng KL</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control form-control-line weight"
                                                       name="weight" value="{{old('weight', $order->weight)}}"
                                                       id="field7"
                                                       onkeyup="checkValue(6)"
                                                        {{$isCheckProduct}}
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-md-12 font-weight-bold label8">Dài</label>
                                            <div class="col-md-12">
                                                <input type="number" class="form-control form-control-line length"
                                                       name="length" value="{{old('length', $order->length)}}"
                                                       {{ $isCheckProduct }}
                                                       id="field8"
                                                       onclick="checkValue(7)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-md-12 font-weight-bold label9">Rộng</label>
                                            <div class="col-md-12">
                                                <input type="number" class="form-control form-control-line width"
                                                       name="width" value="{{old('width', $order->width)}}"
                                                       {{ $isCheckProduct }}
                                                       id="field9"
                                                       onclick="checkValue(8)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-md-12 font-weight-bold label10">Cao</label>
                                            <div class="col-md-12">
                                                <input type="number" class="form-control form-control-line height"
                                                       name="height" value="{{old('height', $order->height)}}"
                                                       {{ $isCheckProduct }}
                                                       id="field10"
                                                       onclick="checkValue(9)">
                                            </div>
                                        </div>
                                    </div>
                                    @if(!empty($order->ConvertedWeight))
                                        Khối lượng quy đổi: {{ $order->ConvertedWeight }}
                                    @endif
                                    <?php
                                    $weight = $order->weight;
                                    $weight_old = $order->length*$order->width*$order->height/5;
                                    if ($weight < $weight_old) {
                                        $weight = $weight_old;
                                    }

                                    ?>
                                    <strong>Khối lượng tính cước: <span class="khoiluongCuoc"><?=$weight?></span>  </strong>

                                    <div class="col-md-12 mb-2">
                                        <h4 style="color: #F26522">| Lưu ý - Ghi chú</h4>
                                    </div>
                                    <?php
                                    $isCheckNote = 'readonly';
                                    $arrays = ['create_order', 'ready_to_pick', 'picking', 'money_collect_picking', 'picked', 'storing', 'transporting', 'sorting', 'delivering', 'delivery_fail', 'waiting_to_return'];

                                    if (in_array($order->statusName, $arrays)) {
                                        $isCheckNote = '';
                                    }


                                    ?>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-12 font-weight-bold label11">Thu hộ</label>
                                            <div class="col-md-12">
                                                <input type="text"
                                                       class="form-control form-control-line cod_amount"
                                                       name="cod_amount" id="field11"
                                                       value="{{old('cod_amount', $order->cod_amount)}}"
                                                       oninput="formatNumber(this)" onfocus="clearPlaceholder(this)"
                                                       onkeyup="checkValue(10)" {{ $isCheckNote }}
                                                       onblur="restorePlaceholder(this)">
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="col-md-12 font-weight-bold label12">Giá trị đơn hàng</label>
                                            <div class="col-md-12">

                                                <?php
                                                $isCheckGTDH = 'readonly';
                                                $arrays = ['create_order', 'ready_to_pick', 'picking'];

                                                if (in_array($order->statusName, $arrays)) {
                                                    $isCheckGTDH = '';
                                                }


                                                ?>
                                                <input type="text"
                                                       class="form-control form-control-line insurance_value"
                                                       name="insurance_value"
                                                       value="{{old('cod_amount', $order->insurance_value)}}"
                                                       oninput="formatNumber(this)" onfocus="clearPlaceholder(this)"
                                                       {{$isCheckGTDH}}
                                                       id="field12"
                                                       onclick="checkValue(11)"
                                                       onkeyup="UpdateOrder({{$order->id}})"
                                                       onblur="restorePlaceholder(this)">
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="col-md-12 font-weight-bold label13">Thu tiền khi giao hàng thất
                                                bại</label>
                                            <?php
                                            $isCheckFail = 'readonly';
                                            $arrays = ['create_order', 'ready_to_pick', 'picking', 'money_collect_picking', 'picked', 'storing', 'transporting', 'sorting', 'delivering'];

                                            if (in_array($order->statusName, $arrays)) {
                                                $isCheckFail = '';
                                            }


                                            ?>
                                            <div class="col-md-12">
                                                <input type="text"
                                                       class="form-control form-control-line cod_failed_amount"
                                                       name="cod_failed_amount"
                                                       value="{{old('cod_failed_amount', $order->cod_failed_amount)}}"
                                                       {{ $isCheckFail }}
                                                           id="field13"
                                                        onclick="checkValue(12)"
                                                       oninput="formatNumber(this)" onfocus="clearPlaceholder(this)"
                                                       onblur="restorePlaceholder(this)">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-12 font-weight-bold">Mã Đơn hàng <sup>Mã sop tự tạo
                                                    (không bắt buộc)</sup></label>
                                            <div class="col-md-12">
                                                <input type="text"
                                                       class="form-control form-control-line order_code_custom"
                                                       @if(!empty($order->order_code)) readonly @endif

                                                       name="order_code_custom"
                                                       value="{{old('order_code_custom', $order->order_code_custom)}}"
                                                       onkeyup="UpdateOrder({{$order->id}})">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-12 font-weight-bold">Ghi chú</label>
                                            <div class="col-md-12">
                                                <textarea class="form-control form-control-sm" name="note"
                                                          id="description" class="note"
                                                          {{ $isCheckNote }} onkeyup="UpdateOrder({{$order->id}})"
                                                          rows="5">{!! old('note', $order->note) !!}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12 font-weight-bold">Lưu ý giao hàng</label>
                                            <div class="col-md-12">
                                                <select class="form-control form-control-line required_note"
                                                        name="required_note" {{ $isCheckNote }}
                                                        onchange="UpdateOrder({{$order->id}})">
                                                    <option
                                                        value="KHONGCHOXEMHANG" {{ $order->required_note == 'KHONGCHOXEMHANG' ? 'selected' : '' }}>
                                                        Không cho xem hàng
                                                    </option>
                                                    <option
                                                        value="CHOXEMHANGKHONGTHU" {{ $order->required_note == 'CHOXEMHANGKHONGTHU' ? 'selected' : '' }}>
                                                        Cho xem hàng - Không cho thử
                                                    </option>
                                                    <option
                                                        value="CHOTHUHANG" {{ $order->required_note == 'CHOTHUHANG' ? 'selected' : '' }}>
                                                        Cho thử hàng
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold col-md-12">Tuỳ chọn thanh toán <span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <?php
                                                $isCheckPay = 'readonly';
                                                $arrays = ['create_order', 'ready_to_pick', 'picking'];

                                                if (in_array($order->statusName, $arrays)) {
                                                    $isCheckPay = '';
                                                }


                                                ?>
                                                <select class="form-control  form-control-line payment_method" required
                                                        onchange="UpdateOrder({{$order->id}})" {{ $isCheckPay }}
                                                        name="">
                                                    <option
                                                        value="2" {{ $order->payment_method == 2 ? 'selected' : '' }}>
                                                        Bên nhận trả phí
                                                    </option>
                                                    <option
                                                        value="1" {{ $order->payment_method == 1 ? 'selected' : '' }}>
                                                        Bên gủi trả phí
                                                    </option>

                                                </select>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
                                    </div>


                                </div>
                                <div class="col-md-12 text-center">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div
                                                class="form-group d-flex align-items-start flex-column justify-content-start">
                                                <input type="radio" id="ghn" checked>
                                                <label class="font-italic" for="ghn">Giao hàng nhanh</label>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mt-4">

                                                <h5 class="row">
                                                    <div class="col-8 col-md-7 row">
                                                        <span class="font-weight-bold col-10 col-md-10 text-left"
                                                              style="color: #F26522">Vận chuyển</span>
                                                        <span class="font-weight-bold col-2 col-md-2"
                                                              style="color: #F26522">:</span>
                                                    </div>
                                                    <div class="col-4 col-md-3 text-right">
                                                        <strong class="total_fee">
                                                            @if(!empty($order->total_fee))
                                                                {{ number_format((int) str_replace(['.', ','], '', $order->total_fee),0, ',', '.')  }}
                                                            @else
                                                                0
                                                            @endif
                                                        </strong>
                                                    </div>
                                                </h5>

                                                <h5 class="row">
                                                    <div class="col-8 col-md-7 row">
                                                        <span class="font-weight-bold col-10 col-md-10 text-left"
                                                              style="color: #F26522">Khai giá</span>
                                                        <span class="font-weight-bold col-2 col-md-2"
                                                              style="color: #F26522">:</span>
                                                    </div>
                                                    <div class="col-4 col-md-3 text-right">
                                                        <strong class="insurance_fee">
                                                            @if(!empty($order->insurance_fee))
                                                                {{ number_format((int) str_replace(['.', ','], '', $order->insurance_fee),0, ',', '.')  }}
                                                            @else
                                                                0
                                                            @endif
                                                        </strong>
                                                    </div>
                                                </h5>

                                                <h5 class="row">
                                                    <div class="col-8 col-md-7 row">
                                                        <span class="font-weight-bold col-10 col-md-10 text-left"
                                                              style="color: #F26522">Tổng phí</span>
                                                        <span class="font-weight-bold col-2 col-md-2"
                                                              style="color: #F26522">:</span>
                                                    </div>
                                                    <div class="col-4 col-md-3 text-right">
                                                        <strong class="total_cost">
                                                            @if(!empty($order->main_service))
                                                                {{ number_format((int) str_replace(['.', ','], '', $order->main_service),0, ',', '.')  }}
                                                            @else
                                                                0
                                                            @endif
                                                        </strong>
                                                    </div>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>


                                    @if($order->statusName != 'cancel')

                                        @if(empty($order->order_code))
                                            <button class="btn btn-info btnConfirmOrder font-20"
                                                    data-order-id="{{ $order->id }}" type="button">Tạo đơn
                                            </button>
                                        @else
                                            <a href="{{ route('backend.orders.updateOrder', $order->id ) }}">
                                                <button class="btn btn-primary btnupdateOrder" type="button">Cập nhập
                                                </button>
                                            </a>

                                        @endif
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('backend.orders.exampleModalAddress')
    @include('backend.orders.returnModalAddress')

    <style>

    </style>

@endsection
@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        let shopId = $('.product_type:checked').val();
        $(".product_type").click(function () {
            shopId = $(this).val();
            console.log(shopId)
        });
        $(document).ready(function () {


            $(".btn_add_newProduct").click(function () {


                let data = {
                    _token: '{{ csrf_token() }}',
                    order_id: $(this).attr('data-id')
                }
                $.ajax({
                    type: "POST",
                    url: '{{ route('backend.orders.addproduct.order') }}',
                    dataType: 'json',
                    data: data,
                    success: function (data) {
                        console.log(data)

                        // var totalAmount = data.r.weight
                        var weight = $('.weight').val()
                        var value = data.e.product_voluem
                        var totalweight = parseInt(value) + parseInt(weight);
                        var value = $('.insurance_value').val();
                        // changePriceCost(value, totalweight)
                        $('.weight').val(totalweight)
                        var htmlContent = `<div class="bill-item p-2 align-items-center mb-2 bill-item${data.e.id}"
                                             style="height: auto; border: 1px solid #ccc; transition: border-color 0.2s;">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"
                                                                  id="basic-addon1">Tên SP</span>
                                                        </div>
                                                        <input type="text" class="form-control product_name_${data.e.id}" name="product_name"
                                                               placeholder="Tên Sản phẩm"  onchange="updateDetail(${data.e.id})"
                                                               aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"
                                                                  id="basic-addon1">Mã SP</span>
                                                        </div>
                                                        <input type="text" class="form-control product_code_${data.e.id}" name="product_code"
                                                               placeholder="Mã sản phẩm" onchange="updateDetail(${data.e.id})"
                                                               aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                 <div class="col-md-1 desktop">
                                                    <p class="btn_delete_Product" data-id="${data.e.id}">
                                                        <i class="p-2 text-light badge btn-danger mdi mdi-delete d-flex align-items-center justify-content-center ml-2"
                                                           style="cursor: pointer; border-radius: 20px; color: red"></i>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"
                                                                  id="basic-addon1">Khối lương (gam)</span>
                                                        </div>
                                                        <input type="number" class="form-control productVoluem product_voluem_${data.e.id} " name="product_voluem"
                                                               placeholder="Khối lượng sản phẩm" onchange="updateDetail(${data.e.id})" value="1"
                                                               aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"
                                                                  id="basic-addon1">Số lượng</span>
                                                        </div>
                                                        <input type="number" class="form-control quantity product_quantity_${data.e.id}" name="product_quantity"
                                                               placeholder="Số lượng sản phẩm" onchange="updateDetail(${data.e.id})"
                                                               value="1"
                                                               aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 mobile">
                                                    <p class="btn_delete_Product" data-id="${data.e.id}">
                                                        <i class="p-2 text-light badge btn-danger mdi mdi-delete d-flex align-items-center justify-content-center ml-2"
                                                           style="cursor: pointer; border-radius: 20px; color: red"></i>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>`;
                        $('.itemProduct').append(htmlContent)

                    }
                });

            });
            $(document).on("click", ".btn_delete_Product", function () {

                var index1 = $(".btn_delete_Product").index(this);
                if (index1 == 0) {
                    Swal.fire({
                        title: "Cảnh báo!",
                        text: "Bạn không thể xóa. Đơn hàng tối thiểu phải có 1 sản phẩm .",
                        icon: "question"
                    });
                } else {
                    Swal.fire({
                        title: "Cảnh báo!",
                        text: "Xác nhận xóa sản phẩm!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var index = $(".btn_delete_Product").index(this);
                            var id = $(this).attr('data-id');
                            console.log(index)
                            $(".bill-item" + id).remove();


                            let data = {
                                _token: '{{ csrf_token() }}',
                                id: id,
                            }
                            $.ajax({
                                type: "POST",
                                url: '{{ route('backend.orders.deleteproduct.order') }}',
                                dataType: 'json',
                                data: data,
                                success: function (data) {


                                    var totalAmount = data.r.weight
                                    $('.weight').val(totalAmount)
                                    var value = $('.insurance_value').val();
                                    value = value.replace(/,/g, '');
                                    var intValue = parseInt(value);

                                    var weight = parseInt(totalAmount);
                                    changePriceCost(intValue, weight)
                                    Swal.fire({
                                        text: "Xóa thành công. Vui lòng cập nhập lại đơn hàng.",
                                        icon: "question"
                                    }).then((result) => {
                                        window.location.reload()
                                    });

                                }
                            });
                        }
                    });

                }


            });
            $(document).on("change", ".productVoluem", function () {
                var val = $(this).val();
                if (parseInt(val) < 200) {
                    Swal.fire({
                        text: "Vui lòng kiểm tra khối lượng nhỏ nhất là 200g.",
                        icon: "info",
                    }).then((result) => {
                        window.location.reload()
                    });

                }

            });
            $(".btnConfirmOrder").click(function () {
                // Hàm Validate kiểm tra dữ liệu nhập vào trước khi xác nhận đơn hàng
                let validate = Validate();
                if (validate.status === false) {
                    // Hiển thị thông báo lỗi nếu dữ liệu không hợp lệ
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: validate.msg,

                    });
                } else {
                    var id = $(this).attr('data-order-id');
                    let data = {
                        _token: '{{ csrf_token() }}',
                        id: id,
                    }
                    // Gửi yêu cầu AJAX để xem trước đơn hàng
                    $.ajax({
                        type: "POST",
                        url: '{{ route('backend.orders.preview') }}',
                        dataType: 'json',
                        data: data,
                        success: function (data) {
                            console.log(data)
                            //return;
                            if (data.e === 1) {
                                Swal.fire({
                                    text: "Mã đơn hàng đã tồn tại. Vui lòng tạo mã khác.",
                                    icon: "info",
                                }).then((result) => {
                                    $('.order_code_custom').val('');
                                    UpdateOrder({{ $order->id }});
                                   return;
                                });
                            } else {

                                // Kiểm tra kết quả trả về từ server
                                if (data.r.r === 1) {
                                    // Nếu có lỗi, hiển thị thông báo lỗi
                                    Swal.fire({
                                        text: "Có lỗi xảy ra khi xem trước đơn hàng." + data.r.msg,
                                        icon: "info",
                                    }).then((result) => {
                                        window.location.reload()
                                    });

                                } else {
                                    // Nếu không có lỗi, hiển thị xác nhận tạo vận đơn
                                    Swal.fire({
                                        title: "<i>Xác nhận đơn hàng</i>",
                                        html: `<div class="container">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            Vui lòng kiểm tra thông tin trước khi xác nhận tạo vận đơn.
                                        </div>
                                    </div>
                                </div>`,
                                        confirmButtonText: "Xác nhận tạo vận đơn",
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $('.btnConfirmOrder').hide()
                                            $.ajax({
                                                type: "POST",
                                                url: '{{ route('backend.orders.orderscheckout', $order->id ) }}',
                                                dataType: 'json',
                                                data: {
                                                    _token: '{{ csrf_token() }}',
                                                    insurance_value: $('.insurance_value').val(),
                                                    cod_amount: $('.cod_amount').val(),
                                                    cod_failed_amount: $('.cod_failed_amount').val(),
                                                },
                                                success: function (data) {
                                                    console.log(data);
                                                    Swal.fire({
                                                        text: data.r,
                                                        icon: "info",
                                                        showCancelButton: true, // Hiển thị nút cancel
                                                        confirmButtonColor: "#3085d6",
                                                        cancelButtonColor: "#999999",
                                                        allowOutsideClick: false,
                                                        confirmButtonText: "Tiếp tục tạo đơn",
                                                        cancelButtonText: "Không tạo đơn"
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            window.location.href = `{{ route('backend.orders.add', $order->id) }}`;
                                                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                                                            // window.location.reload(); // Reload trang khi nhấn "Không tạo đơn"
                                                            window.location.href = `{{ route('backend.orders.edit', $order->id) }}`;
                                                        }
                                                    });
                                                }

                                            });
                                        }
                                    });
                                }
                            }


                        }
                    });
                }
            });


            function Validate() {
                let to_phone = $(".to_phone").val();
                let to_username = $(".to_username").val();
                let to_address = $(".to_address").val();
                let to_province = $(".to_province").val();
                let to_district = $(".to_district").val();
                let to_ward = $(".to_ward").val();

                // Khởi tạo biến result mặc định
                let result = {
                    'status': true,
                    'msg': ''
                };

                // Kiểm tra điều kiện và cập nhật kết quả
                if (to_phone.length == "") {
                    result.status = false;
                    result.msg = 'Vui lòng nhập số điện thoại người nhận.';
                } else if (to_username.length == "") {
                    result.status = false;
                    result.msg = 'Vui lòng nhập tên người nhận.';
                } else if (to_address.length == "") {
                    result.status = false;
                    result.msg = 'Vui lòng nhập địa chỉ người nhận.';
                } else if (to_province.length == "") {
                    result.status = false;
                    result.msg = 'Vui lòng chọn địa chỉ Tỉnh/TP người nhận.';
                } else if (to_district.length == "") {
                    result.status = false;
                    result.msg = 'Vui lòng chọn địa chỉ Quận Huyện người nhận.';
                } else if (to_ward.length == "") {
                    result.status = false;
                    result.msg = 'Vui lòng chọn địa chỉ Phường xã người nhận.';
                }
                // Trả về kết quả cuối cùng
                return result;
            }


        });

        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });

        var typingTimer;                // Biến toàn cục để lưu setTimeout
        var doneTypingInterval = 1000;  // Khoảng thời gian chờ (1 giây)

        let timeout;
        function checkValue(id) {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                var label = $('.label' + id).text();
                var value = $('#field' + id).val();

                if (value.length == 0 && label && id > 0) {
                    $('#field' + id).val('');
                    alert("Vui lòng nhập " + label);
                    return false;
                } else {
                    UpdateOrder({{ $order->id }});
                }
            }, 2000);
        }

        function UpdateOrder(id) {


            let data = {
                _token: '{{ csrf_token() }}',
                id: id,
                caGiaohang: $('.caGiaohang:checked').val(),
                to_phone: $('.to_phone').val(),
                to_username: $('.to_username').val(),
                to_address: $('.to_address').val(),
                to_province: $('.to_province option:selected').val(),
                province_name: $('.to_province option:selected').text(),
                to_district: $('.to_district option:selected').val(),
                district_name: $('.to_district option:selected').text(),
                to_ward: $('.to_ward option:selected').val(),
                ward_name: $('.to_ward option:selected').text(),
                weight: $('.weight').val(),
                length: $('.length').val(),
                width: $('.width').val(),
                height: $('.height').val(),
                required_note: $('.required_note').val(),
                order_code: $('.order_code').val(),
                note: $('#description').val(),
                payment_method: $('.payment_method').val(),
                cod_amount: $('.cod_amount').val(),
                cod_failed_amount: $('.cod_failed_amount').val(),
                product_type: $('.product_type:checked').val(),
                product_type_cost: $('.product_type:checked').attr('data-price'),
                insurance_value: $('.insurance_value').val(),
                order_code_custom: $('.order_code_custom').val(),
                return_address: $('.return_address').val(),
                return_phone: $('.return_phone').val(),

            }
            console.log(data)

            $.ajax({
                type: "POST",
                url: '{{ route('backend.orders.update') }}',
                dataType: 'json',
                data: data,
                success: function (data)


                    console.log('Khoi Luong: ' + data.t +' ShopId: ' + data.j)
                    // if (data.r == 0) {
                    //     alert('Mã đơn hàng đã tồn tại. Vui long nhập mã đơn hàng khác');
                    //     $('.order_code_custom').val('');
                    //     return;
                    // }
                    var phiAdmin = data.r.product_type_cost  || 0
                    var khaiGia = data.e.data.insurance_fee  || 0
                    var PhivanCHuyen = (data.e.data.service_fee  || 0 )+ phiAdmin
                    var TOngPhi = khaiGia+PhivanCHuyen
                    console.log(phiAdmin,khaiGia,PhivanCHuyen,TOngPhi)
                    $('.total_cost').text(TOngPhi.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."))
                    $('.total_fee').text(PhivanCHuyen.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."))////
                    $('.insurance_fee').text(khaiGia.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."))

                    $('.khoiluongCuoc').text(data.t)

                }
            });
        }


        function updateDetail(orderId) {
            //var val = $(this).val()

            var product_name = $('.product_name_' + orderId).val();
            var product_code = $('.product_code_' + orderId).val();
            var product_voluem = $('.product_voluem_' + orderId).val();
            var a_voluem = $('.product_voluem_' + orderId).val();
            if (parseInt(a_voluem) < 200) {
                product_voluem = 200;
            }

            var product_quantity = $('.product_quantity_' + orderId).val();
            let data = {
                _token: '{{ csrf_token() }}',
                id: orderId,
                product_name: product_name,
                product_code: product_code,
                product_voluem: product_voluem,
                product_quantity: product_quantity,
            }
            $.ajax({
                type: "POST",
                url: '{{ route('backend.orders.editproduct.order') }}',
                dataType: 'json',
                data: data,
                success: function (data) {

                }
            });
        }

        $(".cod_amount").keyup(function () {
            var value = $(this).val();
            var value_a = $(this).val();
            value_a = value_a.replace(/,/g, '');

            var intValue = parseInt(value_a);
            var weight = parseInt($('.weight').val());
            // changePriceCost(intValue, weight);
            $('.insurance_value').val(value);
        });
        $(".insurance_value").keyup(function () {
            var value = $(this).val();
            var value_a = $(this).val();
            value_a = value_a.replace(/,/g, '');

            var intValue = parseInt(value_a);
            var weight = parseInt($('.weight').val());
            // changePriceCost(intValue, weight);
        });


        $(".weight").keyup(function () {
            var weight = parseInt($(this).val());
            var value = $('.insurance_value').val();
            var intValue = parseInt(value);
            // changePriceCost(intValue, weight);
        });


        // Gắn sự kiện keyup cho tất cả các phần tử '.productVoluem' hiện có và tương lai
        $(document).on('keyup', '.productVoluem', function () {
            var totalAmount = 0;

            // Đếm số lượng phần tử có class 'bill-item'
            var numberOfItems = $(".bill-item").length;

            // Lặp qua mỗi phần tử 'bill-item'
            $(".bill-item").each(function () {
                // Lấy giá trị của productVoluem và quantity từ phần tử hiện tại
                var productVoluem = parseInt($(this).find(".productVoluem").val()) || 200;
                var quantity = parseInt($(this).find(".quantity").val()) || 1;

                // Tính tổng giá trị cho mỗi 'bill-item'
                var subtotal = productVoluem * quantity;

                // Cộng dồn vào tổng
                totalAmount += subtotal;
            });

            // In ra tổng số lượng 'bill-item' và tổng giá trị
            // console.log("Số lượng 'bill-item': " + numberOfItems);
            // console.log("Tổng giá trị: " + totalAmount);
            $('.weight').val(totalAmount)
            var value = $('.insurance_value').val();
            value = value.replace(/,/g, '');
            var intValue = parseInt(value);

            var weight = parseInt(totalAmount);
            // changePriceCost(intValue, weight)

        });
        $(document).on('keyup', '.quantity', function () {
            var totalAmount = 0;

            // Đếm số lượng phần tử có class 'bill-item'
            var numberOfItems = $(".bill-item").length;

            // Lặp qua mỗi phần tử 'bill-item'
            $(".bill-item").each(function () {
                // Lấy giá trị của productVoluem và quantity từ phần tử hiện tại
                var productVoluem = parseInt($(this).find(".productVoluem").val()) || 200;
                var quantity = parseInt($(this).find(".quantity").val()) || 1;

                // Tính tổng giá trị cho mỗi 'bill-item'
                var subtotal = productVoluem * quantity;

                // Cộng dồn vào tổng
                totalAmount += subtotal;
            });

            // In ra tổng số lượng 'bill-item' và tổng giá trị
            // console.log("Số lượng 'bill-item': " + numberOfItems);
            // console.log("Tổng giá trị: " + totalAmount);
            $('.weight').val(totalAmount)
            var value = $('.insurance_value').val();
            value = value.replace(/,/g, '');
            var intValue = parseInt(value);

            var weight = parseInt(totalAmount);
            // changePriceCost(intValue, weight)
        });

        $(document).on('click', '.btncheckPrice', function () {
            var weight = $('.weight').val();
            var value = $('.insurance_value').val();

            //value = value.replace(/,/g, '');
            if (value.includes(',')) {
                value = value.replace(/,/g, '');
            } else if (value.includes('.')) {
                value = value.replace(/\./g, '');
            }

            var intValue = parseInt(value);


            // console.log(value)
            // console.log(intValue)
            changePriceCost(intValue, parseInt(weight))
        });
        $(document).on('click', '.btnupdateOrder', function () {
            var weight = $('.weight').val();
            var value = $('.insurance_value').val();

            //value = value.replace(/,/g, '');
            if (value.includes(',')) {
                value = value.replace(/,/g, '');
            } else if (value.includes('.')) {
                value = value.replace(/\./g, '');
            }

            var intValue = parseInt(value);


            // console.log(value)
            // console.log(intValue)
            changePriceCost(intValue, parseInt(weight))
        });


        function changePriceCost(val, weight) {
            // console.log('----------')
            // console.log(shopId)
            // console.log('----------')
            var settings = {
                "url": "https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "token": "7bb01781-4af7-11ed-b824-262f869eb1a7",
                    "shopId": shopId,
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "service_type_id": 2,
                    "from_district_id": {{$order->district_id}},
                    "to_district_id": {{$order->to_district ?? 1}},
                    "to_ward_code": $('.to_ward').val(),
                    "height": parseInt($('.height').val()),
                    "length": parseInt($('.length').val()),
                    "weight": weight,
                    "width": parseInt($('.width').val()),
                    "insurance_value": val || 0,
                    "coupon": null
                }),
            };
            console.log(JSON.stringify({
                "service_type_id": 2,
                "from_district_id": {{$order->district_id}},
                "to_district_id": {{$order->to_district ?? 1}},
                "to_ward_code": $('.to_ward').val(),
                "height": parseInt($('.height').val()),
                "length": parseInt($('.length').val()),
                "weight": weight,
                "width": parseInt($('.width').val()),
                "insurance_value": val || 0,
                "coupon": null
            }))


            $.ajax(settings).done(function (response) {
                // console.log('-----------------')
                // console.log(response)
                // console.log('-----------------')
                // console.log('Phi khai gia: ')
                // console.log(response.data.insurance_fee)
                // console.log('-----------------')

                var cost = $('.product_type:checked').attr('data-price');
                // console.log(cost);
                var sumPrice = parseInt(cost) + parseInt(response.data.service_fee);
                var price = sumPrice;
                var insurance_fee = response.data.insurance_fee;
                $('.total_fee').text(price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."))////
                $('.insurance_fee').text(insurance_fee.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."))////

                var value = $('.insurance_value').val();
                value = value.replace(/,/g, '');
                var intValue = parseInt(value) || 0;

                var total_cost = parseInt(cost) + response.data.total;
                total_cost = total_cost;
                $('.total_cost').text(total_cost.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."))

                var inputString = weight;
                // inputString = inputString.replace(/\./g, '');
                // inputString = inputString.replace(/\D/g,'');
                var outputInt = parseInt(inputString);

                let data = {
                    _token: '{{ csrf_token() }}',
                    id: `{{$order->id}}`,
                    total_fee: price,
                    weight: weight,
                    total_cost: total_cost,
                    insurance_fee: response.data.insurance_fee,

                }

                $.ajax({
                    type: "POST",
                    url: '{{ route('backend.orders.edit.order') }}',
                    dataType: 'json',
                    data: data,
                    success: function (data) {
                        // console.log('*********')
                        // console.log(data)
                        // console.log('*********')
                    }
                });

            });
        }
    </script>
    <script>

        $("#add_address_return").click(function () {

            $('#returnModalAddress').modal('show');
        });


    </script>

    <script>
        $(document).ready(function () {
            var settings = {
                "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/province",
                "method": "GET",
                "timeout": 0,
                "headers": {
                    "token": "{{ config('constants.ghn_api_token') }}",
                    "Content-Type": "application/json"
                },
            };

            $.ajax(settings).done(function (response) {
                //province
                var nameOption = '{{ $order->to_province_name != null ? $order->to_province_name : '' }}'
                var provinceIdOption = '{{ $order->to_province != null ? $order->to_province : '' }}'

                if (response.code === 200) {
                    let html = `<option value="${provinceIdOption}">${nameOption} </option>`;
                    let html1 = `<option value=""></option>`;
                    for (const element of response.data) {
                        html += ` <option value="${element.ProvinceID}">${element.ProvinceName}</option>`
                        html1 += ` <option value="${element.ProvinceID}">${element.ProvinceName}</option>`
                    }
                    $('.province').html(html)
                    $('.province1').html(html1)
                } else {
                    alert(response.message)
                }
            });

            $(".province").change(function () {
                var province_id = parseInt($(this).val());
                var text = $(".province option:selected").text();
                $('.province_name').val(text)
                console.log(province_id)

                var settings = {
                    "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/district",
                    "method": "GET",
                    "timeout": 0,
                    "headers": {
                        "token": "{{ config('constants.ghn_api_token') }}",
                        "Content-Type": "application/json"
                    },
                    "data": {
                        "province_id": province_id
                    },
                    "dataType": "json"
                };
                $.ajax(settings).done(function (response) {
                    // console.log(response)
                    if (response.code === 200) {
                        let html = `<option value="">Chọn </option>`;
                        $('.ward').html(html)
                        for (const element of response.data) {
                            html += ` <option value="${element.DistrictID}">${element.DistrictName}</option>`
                        }
                        $('.district').html(html)


                    } else {
                        alert(response.message)
                    }
                });
            });
            $(".province1").change(function () {
                var province_id = parseInt($(this).val());
                var text = $(".province1 option:selected").text();
                //alert(text)
                $('.province_name1').val(text)

                var settings = {
                    "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/district",
                    "method": "GET",
                    "timeout": 0,
                    "headers": {
                        "token": "{{ config('constants.ghn_api_token') }}",
                        "Content-Type": "application/json"
                    },
                    "data": {
                        "province_id": province_id
                    },
                    "dataType": "json"
                };
                $.ajax(settings).done(function (response) {
                    // console.log(response)
                    if (response.code === 200) {
                        let html = `<option value="">Chọn </option>`;
                        for (const element of response.data) {
                            html += ` <option value="${element.DistrictID}">${element.DistrictName}</option>`
                        }
                        $('.district1').html(html)


                    } else {
                        alert(response.message)
                    }
                });
            });


            $(".district").change(function () {
                var district_id = parseInt($(this).val());
                var text = $(".district option:selected").text();

                $('.district_name').val(text)
                var settings = {
                    "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/ward",
                    "method": "GET",
                    "timeout": 0,
                    "headers": {
                        "token": "{{ config('constants.ghn_api_token') }}",
                        "Content-Type": "application/json"
                    },
                    "data": {
                        "district_id": district_id
                    },
                    "dataType": "json"

                };

                $.ajax(settings).done(function (response) {
                    if (response.code === 200) {
                        let html = `<option value="">Chọn </option>`;

                        for (const element of response.data) {
                            html += ` <option value="${element.WardCode}">${element.WardName}</option>`
                        }
                        $('.ward').html(html)
                    } else {
                        alert(response.message)
                    }
                });
            });
            $(".district1").change(function () {
                var district_id = parseInt($(this).val());
                var text = $(".district1 option:selected").text();
                var district_text = $(this).find('option:selected').text();

                $('.district_name1').val(district_text)
                var settings = {
                    "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/ward",
                    "method": "GET",
                    "timeout": 0,
                    "headers": {
                        "token": "{{ config('constants.ghn_api_token') }}",
                        "Content-Type": "application/json"
                    },
                    "data": {
                        "district_id": district_id
                    },
                    "dataType": "json"

                };

                $.ajax(settings).done(function (response) {
                    if (response.code === 200) {
                        let html = `<option value="">Chọn </option>`;
                        for (const element of response.data) {
                            html += ` <option value="${element.WardCode}">${element.WardName}</option>`
                        }
                        $('.ward1').html(html)
                    } else {
                        alert(response.message)
                    }
                });
            });

            $(".ward").change(function () {
                var text = $(".ward option:selected").text();
                $('.ward_name').val(text)
            });


            $(".ward1").change(function () {
                var text = $(".ward1 option:selected").text();
                var ward1_text = $(this).find('option:selected').text();
                $('.ward_name1').val(ward1_text)
            });


            //
            $(".edit-address").click(function () {
                $('#exampleModalAddress').modal('show');
            });
            $(".btn-close").click(function () {
                $('#exampleModalAddress').modal('hide');
            });
        });

        function formatNumber(input) {
            let value = input.value.replace(/\D/g, '');

            // Định dạng số theo định dạng ngàn nghìn
            let formattedValue = new Intl.NumberFormat().format(value);

            // Gán giá trị đã định dạng vào trường nhập
            input.value = formattedValue;
        }

        function clearPlaceholder(input) {
            // Xóa placeholder khi trường nhập được tập trung
            input.placeholder = '';
        }

        function restorePlaceholder(input) {
            // Khôi phục placeholder nếu trường nhập trống
            if (input.value === '') {
                input.placeholder = '';
            }
        }
    </script>
@endsection

