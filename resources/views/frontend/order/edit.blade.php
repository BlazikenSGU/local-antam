@extends('frontend.layouts.main')

@push('title')
    Cập nhật đơn hàng
@endpush

@section('content')
    <style>
        .product-name {
            display: none;
        }

        .l-o {
            color: #F26522;
        }

        .l-b {
            color: blue;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        .suggestion-item:hover {
            background-color: red;
            color: white;
            cursor: pointer;
        }

        .sticky-sidebar {
            position: sticky;
            top: 100px;
            height: fit-content;
        }

        .page-titles {
            text-align: center;
            border-radius: 8px;
            position: sticky;
            top: 5px;
            background: white;
            z-index: 1000;
            padding: 15px 10px 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .total-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        span.select2-selection__arrow {
            display: none;
        }

        @media(max-width: 992px) and (min-width:768px) {
            .mobile-form {
                flex-direction: column !important;
            }
        }


        @media (max-width: 768px) and (min-width: 375px) {

            .mobile-form,
            .mobile-bennhan,
            .mobile-product,
            .mobile-weight,
            .mobile-note {
                flex-direction: column !important;
            }

            .mobile-product .col-md-4,
            .mobile-product .col-md-2,
            .mobile-product .col-md-1 {
                margin: .5rem 0 !important;
            }
        }

        @media (max-width: 650px) {


            .container {
                padding: 0;
                margin: 0;
                max-width: 100% !important;
            }

            .page-titles {
                padding: 0px;
            }

            .row {
                flex-direction: column;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .section-form {
                padding: 0 !important;
            }

            .page-form {
                padding: 0;
            }

            .section-bennhan {
                padding: 5px !important;
            }


            .section-weight,
            .section-note,
            .section-product {
                padding: 0 5px !important;
            }

            .mobile-bennhan,
            .mobile-product,
            .mobile-weight,
            .mobile-note {
                flex-direction: column;
            }

            .form-result {
                margin: 1rem 0;
            }

            .form-check-label,
            .section-klqd,
            .section-kltc {
                font-size: 10px;
            }
        }

        @media (max-width: 350px) {
            .card-body {
                padding: .5rem !important;
            }

            .mobile-form {
                display: flex;
                flex-direction: column !important;
            }

            .section-product {
                margin: .5rem 0 !important;
            }
        }

        span.select2 {
            width: 100% !important;
        }

        .section-layout,
        .layout-2 {
            padding: 0;
        }
    </style>
    <div class="container-xxl section-layout">
        <section class="mt-4">

            <div class="row ">
                <div class="col-md-12 col-12 layout-2">

                    <div class="col-md-12 col-11 page-titles" style="margin: 0 auto;">

                        <h2>Cập nhật đơn hàng [ <span class="l-o"> {{ $order->order_code }} </span> _
                            @if ($order->statusName)
                                {{ format_order_status($order->statusName) }}
                            @endif]
                        </h2>

                    </div>

                    <div class="col-md-12 mt-2 col-12 page-form">
                        <div class=" card-outline-info">
                            <div>

                                <form class="form-horizontal" action="{{ route('user.order.update', $order->id) }}"
                                    method="POST" id="myForm">
                                    @csrf
                                    <div class="col-md-12 col-12 col-lg-12 d-flex flex-row mobile-form mt-2">

                                        <div class="col-md-12 col-12 col-lg-8 p-2 section-form">
                                            <div class="card">
                                                <div class="card-body">

                                                    <div class="col-md-12" id="sender-section">

                                                        <h2 style="color: #F26522">
                                                            Bên Gửi
                                                        </h2>
                                                        <div class="col-md-12">

                                                            <div class="col-md-12 p-3">
                                                                <label for="">Kho hàng</label>
                                                                <div class="d-none" id="manual-form">
                                                                    <div class="col-md-12 d-flex mt-2">
                                                                        <div class="col-md-4 p-2">
                                                                            <input type="text" class="form-control"
                                                                                name="input_name" placeholder="tên"
                                                                                value="">
                                                                        </div>
                                                                        <div class="col-md-4 p-2">
                                                                            <input type="number" class="form-control"
                                                                                name="input_phone" placeholder="sdt"
                                                                                value="">
                                                                        </div>
                                                                        <div class="col-md-4 p-2">
                                                                            <input type="text" class="form-control"
                                                                                name="input_street_name"
                                                                                placeholder="địa chỉ" value="">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12 d-flex mt-2">
                                                                        <div class="col-md-4 p-2">
                                                                            <select name="province_id_1" id="province_id_1"
                                                                                class="form-control province_1">
                                                                                <option value=""></option>
                                                                            </select>

                                                                            <input type="hidden"
                                                                                class="form-control form-control-line province_name_1"
                                                                                value=" @if (!empty($data)) {{ $data->province_name_1 }} @endif"
                                                                                name="province_name_1">
                                                                        </div>

                                                                        <div class="col-md-4 p-2">
                                                                            <select name="district_id_1" id="district_id_1"
                                                                                class="form-control district_1">
                                                                                <option
                                                                                    value="{{ isset($data) ? $data->district_id_1 : '' }}">
                                                                                    {{ isset($data) ? $data->district_name - 1 : '' }}
                                                                                </option>
                                                                                <input type="hidden"
                                                                                    class="form-control form-control-line district_name_1"
                                                                                    value="{{ isset($data) ? $data->district_name_1 : '' }}"
                                                                                    name="district_name_1">
                                                                            </select>

                                                                        </div>

                                                                        <div class="col-md-4 p-2">
                                                                            <select name="ward_id_1" id="ward_id_1"
                                                                                class="form-control ward_1">
                                                                                <option value=""></option>
                                                                            </select>
                                                                            <input type="hidden"
                                                                                class="form-control form-control-line ward_name_1"
                                                                                value="{{ isset($data) ? $data->ward_name_1 : '' }}"
                                                                                name="ward_name_1">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <select id="warehouse-select" class="form-control mt-2"
                                                                    disabled>
                                                                    <option value=""></option>
                                                                    @foreach ($address as $item)
                                                                        <option value="{{ $item->id }}"
                                                                            data-street="{{ $item->street_name }}"
                                                                            data-name="{{ $item->name }}"
                                                                            data-phone="{{ $item->phone ?? '' }}"
                                                                            data-ward="{{ $item->ward_name }}"
                                                                            data-district="{{ $item->district_name }}"
                                                                            data-province="{{ $item->province_name }}"
                                                                            data-require_note="{{ $item->required_note }}"
                                                                            data-payment_type="{{ $item->payment_type }}"
                                                                            data-district_id="{{ $item->district_id }}"
                                                                            data-ward_code="{{ $item->ward_id }}"
                                                                            data-note="{{ $item->note }}"
                                                                            data-transport_unit="{{ $item->transport_unit }}"
                                                                            {{ $item->name == $order->fullname ? 'selected' : '' }}>
                                                                            {{ $item->name }} - {{ $item->phone ?? '' }}
                                                                            -
                                                                            {{ $item->street_name }} -
                                                                            {{ $item->ward_name }} -
                                                                            {{ $item->district_name }} -
                                                                            {{ $item->province_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                                <input type="hidden" name="from_name" id="from_name"
                                                                    value="">
                                                                <input type="hidden" name="from_phone" id="from_phone"
                                                                    value="">
                                                                <input type="hidden" name="from_address" id="street_name"
                                                                    value="">
                                                                <input type="hidden" name="from_ward_name"
                                                                    id="ward_name_store" value="">
                                                                <input type="hidden" name="from_district_name"
                                                                    id="district_name_store" value="">
                                                                <input type="hidden" name="from_province_name"
                                                                    id="province_name_store" value="">
                                                                <input type="hidden" id="require_note"
                                                                    name="require_note_hidden">
                                                                <input type="hidden" id="payment_type"
                                                                    name="payment_type_hidden">
                                                                <input type="hidden" id="note" name="note_hidden">
                                                                <input type="hidden" id="transport_unit"
                                                                    name="transport_unit_hidden">
                                                                <input type="hidden" name="from_district_id"
                                                                    id="from_district_id">
                                                                <input type="hidden" name="from_ward_code"
                                                                    id="from_ward_code">
                                                            </div>

                                                            <div class="col-md-12 p-3">
                                                                <label for="">Địa chỉ hoàn trả hàng</label>
                                                                <select name="" id="address_return"
                                                                    class="form-control" disabled>
                                                                    <option value="" selected></option>
                                                                    @foreach ($address as $item)
                                                                        <option value="{{ $item->id }}"
                                                                            data-street="{{ $item->street_name }}"
                                                                            data-name="{{ $item->name }}"
                                                                            data-phone="{{ $item->phone ?? '' }}"
                                                                            data-ward="{{ $item->ward_name }}"
                                                                            data-district="{{ $item->district_name }}"
                                                                            data-province="{{ $item->province_name }}"
                                                                            {{ $item->street_name == $order->return_address && $item->phone == $order->return_phone ? 'selected' : '' }}>
                                                                            {{ $item->name }} -
                                                                            {{ $item->phone ?? '' }} -
                                                                            {{ $item->street_name }} -
                                                                            {{ $item->ward_name }}
                                                                            -
                                                                            {{ $item->district_name }} -
                                                                            {{ $item->province_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="return_phone"
                                                                    id="return_phone">
                                                                <input type="hidden" name="return_address"
                                                                    id="return_address">

                                                            </div>

                                                        </div>

                                                        <div class="col-md-12 mt-4" id="receiver-section">
                                                            <h2 class="font-weight-bold" style="color: #F26522">
                                                                Bên Nhận </h2>

                                                            <div class="col-md-12 col-12 d-flex mobile-bennhan">
                                                                <div class="col-md-6 p-3 section-bennhan">

                                                                    <div class="mb-3">
                                                                        <label for="" class="form-label">Số điện
                                                                            thoại</label>
                                                                        <input type="text" class="form-control"
                                                                            id="" name="to_phone"
                                                                            value="{{ $order->to_phone }}">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="" class="form-label">Họ
                                                                            tên</label>
                                                                        <input type="text" class="form-control"
                                                                            id="" name="to_name"
                                                                            value="{{ $order->to_name }}">
                                                                    </div>

                                                                </div>
                                                                <div class="col-md-6 p-3 section-bennhan">

                                                                    <div class="mb-3">
                                                                        <label for="" class="form-label">Địa
                                                                            chỉ</label>
                                                                        <input type="text" class="form-control"
                                                                            id="" name="to_address"
                                                                            value="{{ $order->to_address }}">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for=""
                                                                            class="form-label l-label">Tỉnh
                                                                            thành</label>
                                                                        <select name="province_id" id="province_id"
                                                                            class="form-control province select2">
                                                                            <option value="">Chọn tỉnh/thành</option>
                                                                        </select>
                                                                        <input type="hidden"
                                                                            class="form-control form-control-line province_name"
                                                                            value="{{ $order->province_name }}"
                                                                            name="province_name">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for=""
                                                                            class="form-label l-label">Quận
                                                                            huyện</label>
                                                                        <select name="district_id" id="district_id"
                                                                            class="form-control district select2">
                                                                            <option value="{{ $order->district_id }}">
                                                                                {{ $order->district_name }}</option>
                                                                        </select>
                                                                        <input type="hidden"
                                                                            class="form-control form-control-line district_name"
                                                                            value="{{ $order->district_name }}"
                                                                            name="district_name">
                                                                        <input type="hidden" name="to_district_id"
                                                                            id="to_district_id">

                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for=""
                                                                            class="form-label l-label">Xã
                                                                            phường</label>
                                                                        <select name="ward_id" id="ward_id"
                                                                            class="form-control ward select2">
                                                                            <option value="{{ $order->ward_id }}">
                                                                                {{ $order->ward_name }}</option>
                                                                        </select>
                                                                        <input type="hidden"
                                                                            class="form-control form-control-line ward_name"
                                                                            value="{{ $order->ward_name }}"
                                                                            name="ward_name">
                                                                        <input type="hidden" name="to_ward_code"
                                                                            id="to_ward_code">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-12" id="product-section">
                                                        <h2 class="font-weight-bold" style="color: #F26522">
                                                            Thông tin hàng hóa</h2>

                                                        <div class="col-md-12 mt-2">
                                                            <h4 style="color: #F26522">Sản phẩm</h4>

                                                            @php
                                                                $statusActive = ['picking', 'ready_to_pick'];
                                                                $isActive = in_array($order->statusName, $statusActive);
                                                            @endphp

                                                            <div id="product_container">
                                                                @if (isset($items) && count($items) > 0)
                                                                    @foreach ($items as $item)
                                                                        <div
                                                                            class="col-md-12 col-12 d-flex product-row mt-2 mobile-product">
                                                                            <div
                                                                                class="col-md-4 position-relative section-product">

                                                                                <select
                                                                                    class="form-control product-name-select "
                                                                                    name="name[]" {{ $isActive == true ? '' : 'readonly' }}>
                                                                                    @foreach ($product as $prod)
                                                                                        <option
                                                                                            value="{{ $prod->name }}"
                                                                                            data-sku="{{ $prod->product_code }}"
                                                                                            data-weight="{{ $prod->amount }}"
                                                                                            {{ $item['name'] == $prod->name ? 'selected' : '' }}>
                                                                                            {{ $prod->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="col-md-2 mx-2 section-product">
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="SKU" name="code[]"
                                                                                    value="{{ $item['code'] ?? '' }}"
                                                                                    readonly>
                                                                            </div>

                                                                            <div class="col-md-2 mx-2 section-product">
                                                                                <input type="number" class="form-control"
                                                                                    name="weight_item[]" id="weight_item"
                                                                                    placeholder="(gam)"
                                                                                    value="{{ $item['weight'] ?? '' }}" {{ $isActive == true ? '' : 'readonly' }}>
                                                                            </div>

                                                                            <div class="col-md-1 mx-2 section-product">
                                                                                <input type="number" name="quantity[]"
                                                                                    id="quantity" class="form-control"
                                                                                    placeholder="SL"
                                                                                    value="{{ $item['quantity'] ?? 1 }}" {{ $isActive == true ? '' : 'readonly' }}>
                                                                            </div>



                                                                            <div class="col-md-3">
                                                                                <a href="#"
                                                                                    class="btn btn-sm btn-warning add-row {{ $isActive == true ? '' : 'd-none' }}"
                                                                                    id="button_add">Thêm</a>
                                                                                <a href="#"
                                                                                    class="btn btn-sm btn-danger remove-row"
                                                                                    id="button_remove"><i
                                                                                        class='bx bx-trash'></i></a>
                                                                            </div>

                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <div class="col-md-12 d-flex product-row mt-2">

                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 my-4">
                                                            <h4 style="color: #F26522">Thông tin gói hàng</h4>
                                                        </div>

                                                        <div class="col-md-12 col-12 d-flex mobile-weight">

                                                            <div class="col-md-3 p-2 section-weight">

                                                                <div class="mb-3">
                                                                    <label for="" class="form-label">Tổng khối
                                                                        lượng
                                                                        (gram)</label>
                                                                    <input type="number" class="form-control"
                                                                        id="id_tongkhoiluong" name="weight"
                                                                        value="{{ $order->weight ?: 200 }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 p-2 section-weight">

                                                                <div class="mb-3">
                                                                    <label for="" class="form-label">Dài
                                                                        (cm)</label>
                                                                    <input type="number" class="form-control"
                                                                        id="length" name="length"
                                                                        value="{{ $order->length ?? 10 }}"
                                                                        oninput="calculateVolume()">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 p-2 section-weight">

                                                                <div class="mb-3">
                                                                    <label for="" class="form-label">Rộng
                                                                        (cm)</label>
                                                                    <input type="number" class="form-control"
                                                                        id="width" name="width"
                                                                        value="{{ $order->width ?? 10 }}"
                                                                        oninput="calculateVolume()">
                                                                </div>

                                                            </div>
                                                            <div class="col-md-3 p-2 section-weight">

                                                                <div class="mb-3">
                                                                    <label for="" class="form-label">Cao
                                                                        (cm)</label>
                                                                    <input type="number" class="form-control"
                                                                        id="height" name="height"
                                                                        value="{{ $order->height ?? 10 }}"
                                                                        oninput="calculateVolume()">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">

                                                            <strong class="section-klqd">Khối lượng quy đổi: <span
                                                                    class="khoiluongquydoi"></span></strong>
                                                            <input type="hidden" id="id_khoiluongquydoi">
                                                            <br>
                                                            <strong class="section-kltc" style="color: red">Khối lượng
                                                                tính
                                                                cước: <span class="khoiluongCuoc"></span>
                                                            </strong>
                                                            <input type="hidden" name="weight_order"
                                                                id="id_khoiluongcuoc">
                                                            <input type="hidden" value="{{ $order->shop_id }}"
                                                                name="shopId">
                                                        </div>
                                                    </div>


                                                    <div class="col-md-12 mt-4" id="note-section">
                                                        <h4 style="color: #F26522"> Lưu ý - Ghi chú</h4>

                                                        <div class="col-md-12 col-12 d-flex mobile-note">
                                                            <div class="col-md-6 p-3 section-note">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Thu hộ </label>

                                                                    <div class="cod-input-container">
                                                                        <input type="text" name="cod_amount"
                                                                            class="form-control"
                                                                            oninput="formatCurrency(this); resetCheckbox()"
                                                                            id="cod_amount"
                                                                            onkeydown="exitInput(event, this)"
                                                                            onblur="handleCodAmountBlur()"
                                                                            value="{{ number_format(floatval(str_replace(',', '', $order->cod_amount)), 0, ',', '.') }}">

                                                                    </div>
                                                                    <input type="hidden" name="cod_amount_default"
                                                                        id="cod_amount_default"
                                                                        value="{{ number_format(floatval(str_replace(',', '', $order->cod_amount)), 0, ',', '.') }}">

                                                                    <div class="form-check mt-2">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            value="" id="button_sumshiptocod">
                                                                        <label class="form-check-label" style="color:red"
                                                                            for="flexCheckDefault">
                                                                            Cộng tổng phí vào COD
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label">Giá trị đơn hàng</label>

                                                                    <input type="text" class="form-control"
                                                                        oninput="formatCurrency(this)"
                                                                        name="insurance_value" id="insurance_value"
                                                                        onkeydown="exitInput(event, this)"
                                                                        value="{{ number_format(floatval(str_replace(',', '', $order->insurance_value)), 0, ',', '.') }}">
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label">Thu tiền khi giao hàng thất
                                                                        bại</label>

                                                                    <input type="text" class="form-control"
                                                                        oninput="formatCurrency(this)"
                                                                        name="cod_failed_amount"
                                                                        onkeydown="exitInput(event, this)"
                                                                        value="{{ number_format($order->cod_failed_amount, 0, ',', '.') }}">
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label">Mã Đơn hàng <sup>Mã shop tự
                                                                            tạo
                                                                            (không
                                                                            bắt buộc)</sup></label>
                                                                    <input type="text" class="form-control"
                                                                        onkeydown="exitInput(event, this)"
                                                                        name="order_code_custom"
                                                                        value="{{ $order->order_code_custom }}" readonly>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-6 p-3 section-note">

                                                                <div class="mb-3">
                                                                    <label class="form-label">Lưu ý giao hàng</label>
                                                                    <div class="col-md-12">
                                                                        <select name="required_note"
                                                                            id="required_note_select"
                                                                            class="form-control">
                                                                            {{-- <option value="">-- Chọn lưu ý giao hàng --
                                                                    </option> --}}

                                                                            <option value="KHONGCHOXEMHANG"
                                                                                {{ $order->required_note === 'KHONGCHOXEMHANG' ? 'selected' : '' }}>
                                                                                Không cho xem hàng
                                                                            </option>
                                                                            <option value="CHOXEMHANGKHONGTHU"
                                                                                {{ $order->required_note === 'CHOXEMHANGKHONGTHU' ? 'selected' : '' }}>
                                                                                Cho xem hàng - không thử
                                                                            </option>
                                                                            <option value="CHOTHUHANG"
                                                                                {{ $order->required_note === 'CHOTHUHANG' ? 'selected' : '' }}>
                                                                                Cho thử hàng
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tuỳ chọn thanh toán</label>
                                                                    <div class="col-md-12">
                                                                        <select id="payment_method_select"
                                                                            name="payment_method" class="form-control"
                                                                            style="pointer-events: none;" readonly
                                                                            @php
$listactive=['picking', 'ready_to_pick' ]; @endphp
                                                                            {{ !in_array($order->statusName, $listactive) ? 'disabled' : '' }}>
                                                                            {{-- <option value="">...</option> --}}
                                                                            <option value="2"
                                                                                {{ $order->payment_method == '2' ? 'selected' : '' }}>
                                                                                Bên nhận trả phí
                                                                            </option>
                                                                            <option value="1"
                                                                                {{ $order->payment_method == '1' ? 'selected' : '' }}>
                                                                                Bên gửi trả phí
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="" class="form-label">Đơn vị vận
                                                                        chuyển</label>
                                                                    <select name="" id="transport_unit_select"
                                                                        class="form-control" disabled>
                                                                        <option value="1"
                                                                            {{ $order->transport_unit == '1' ? 'selected' : '' }}>
                                                                            Giao hàng nhanh</option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label">Ghi chú</label>
                                                                    <textarea id="note_select" rows="4" type="number" class="form-control" name="note">{{ $order->note }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-12 col-12 p-2 col-lg-4 section-form form-result">
                                            <div class="sticky-sidebar">
                                                <div class="card total-card">
                                                    <div class="card-body">

                                                        <div class="row">
                                                            <h4 style="color: #F26522"> Tổng cộng</h4>

                                                            <div class="col-md-12">

                                                                <table class="table table-bordered">

                                                                    <tbody>
                                                                        <tr>
                                                                            <th scope="row">Vận chuyển:</th>
                                                                            <td id="service-fee">
                                                                                {{ number_format((int) $order->main_service) }}
                                                                                đ</td>
                                                                        </tr>
                                                                        {{-- @if ($order->payment_method == 1)

                                                                        @elseif ($order->payment_method == 2)
                                                                            <tr>
                                                                                <th scope="row">Vận chuyển:</th>
                                                                                <td id="service-fee">
                                                                                    {{ number_format((int) $order->main_service, 0, ',', '.') }}
                                                                                    đ</td>

                                                                            </tr>
                                                                        @endif --}}
                                                                        <tr>
                                                                            <th scope="row">Khai giá:</th>
                                                                            <td id="insurance-fee">
                                                                                {{ number_format((int) $order->insurance_fee, 0, ',', '.') }}
                                                                                đ</td>
                                                                            <input type="hidden" name="insurance_fee"
                                                                                value="{{ $order->insurance_fee }}">
                                                                        </tr>

                                                                        <tr>
                                                                            <th scope="row" style="color:red">Phí chênh
                                                                                lệch:</th>
                                                                            <td id="fee_shopId" style="color:red">
                                                                                {{-- @if ($order->payment_method == 2)
                                                                                    {{ number_format((int) $order->fee_shopId, 0, ',', '.') }}
                                                                                    đ
                                                                                @elseif ($order->payment_method == 1)
                                                                                    0 đ
                                                                                @endif --}}
                                                                                {{ number_format((int) $order->fee_shopId, 0, ',', '.') }}
                                                                                đ
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Phí giao lại</th>
                                                                            <td id="ship-again-fee">
                                                                                {{ number_format((int) $order->R2S, 0, ',', '.') }}
                                                                                đ</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <th>Phí hoàn hàng</th>
                                                                            <td id="return-fee">
                                                                                {{ number_format((int) $order->Return, 0, ',', '.') }}
                                                                                đ</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <th>Phí giao 1 phần</th>
                                                                            <td id="fee_gh1p">
                                                                                {{ number_format((int) $order->phi_gh1p, 0, ',', '.') }}
                                                                                đ</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th scope="row">Tổng phí:</th>
                                                                            <td id="total-fee">
                                                                                {{ number_format(
                                                                                    (int) $order->main_service +
                                                                                        (int) $order->insurance_fee +
                                                                                        (int) $order->R2S +
                                                                                        (int) $order->Return +
                                                                                        (int) $order->phi_gh1p +
                                                                                        (int) $order->fee_shopId,
                                                                                    0,
                                                                                    ',',
                                                                                    '.',
                                                                                ) }}

                                                                                đ
                                                                            </td>

                                                                        </tr>

                                                                    </tbody>
                                                                </table>

                                                                <div class="text-center mt-4">
                                                                    <button type="submit" class="btn btn-primary ">Cập
                                                                        nhật
                                                                        đơn
                                                                        hàng</button>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </form>



                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endsection



        @section('script')
            <script>
                function checkProductFields() {
                    let isValid = true;
                    $('.product-row').each(function() {
                        let weight = $(this).find('input[name="weight_item[]"]').val();
                        let quantity = $(this).find('input[name="quantity[]"]').val();

                        if (!weight || parseFloat(weight) <= 0 || !quantity || parseInt(quantity) <= 0) {
                            isValid = false;
                            return false;
                        }
                    });

                    if (isValid) {
                        $('button[type="submit"]').removeClass('disabled-btn').prop('disabled', false);
                    } else {
                        $('button[type="submit"]').addClass('disabled-btn').prop('disabled', true);
                    }
                }

                $(document).on('click', '.add-row', function() {
                    setTimeout(checkProductFields, 100); // Đợi DOM cập nhật xong
                });

                $(document).on('input change', 'input[name="weight_item[]"], input[name="quantity[]"]', function() {
                    checkProductFields();
                });

                $(document).ready(function() {
                    checkProductFields();
                });
            </script>

            <script>
                $('.select2').select2({
                    theme: 'bootstrap4'
                });
                // ham xet cod_amount theo payment_method, chia ra 2 input neu payment_method = 2, 1 input khi payment_method = 1
                $(document).ready(function() {
                    let isEditing = false;
                    // Hàm cập nhật hiển thị input dựa trên payment_method
                    function updateCodDisplay() {

                        if (isEditing) return;
                        const paymentMethod = $('#payment_method_select').val();
                        const codContainer = $('.cod-input-container');
                        const codValue = {{ $order->cod_amount ?? 0 }};
                        const feeShopId = {{ $order->fee_shopId ?? 0 }};

                        codContainer.empty(); // Xóa nội dung hiện tại

                        if (paymentMethod == '2') {
                            // Hiển thị 2 input khi payment_method = 2
                            codContainer.html(`
                <div class="col-md-12 d-flex flex-row">
                    <div class="col-md-6 p-2">
                        <input type="text"
                            name="cod_amount"
                            class="form-control"
                            oninput="formatCurrency(this)"
                            id="cod_amount"
                            onkeydown="exitInput(event, this)"
                            value="${new Intl.NumberFormat('vi-VN').format(codValue - feeShopId)}">
                    </div>
                    <div class="col-md-6 p-2">
                        <input type="text"
                            name="fee_shopId_show"
                            class="form-control"
                            readonly
                            value="+ ${new Intl.NumberFormat('vi-VN').format(feeShopId)}">
                    </div>
                </div>
            `);
                        } else if (paymentMethod == '1') {
                            // Hiển thị 1 input khi payment_method = 1
                            codContainer.html(`
                <input type="text"
                    name="cod_amount"
                    class="form-control"
                    oninput="formatCurrency(this); resetCheckbox()"
                    id="cod_amount"
                    onblur="handleCodAmountBlur()"
                    onkeydown="exitInput(event, this)"
                    value="${new Intl.NumberFormat('vi-VN').format(codValue)}">
            `);
                        }
                        attachInputEvents();
                    }

                    function attachInputEvents() {
                        // Khi focus vào input
                        $(document).on('focus', '#cod_amount', function() {
                            isEditing = true;
                            // Xóa định dạng số để dễ edit
                            $(this).val($(this).val().replace(/[,.]/g, ''));
                        });

                        // Khi blur khỏi input
                        $(document).on('blur', '#cod_amount', function() {
                            isEditing = false;
                            formatCurrency(this);
                        });

                        // Khi nhấn Enter
                        $(document).on('keydown', '#cod_amount', function(e) {
                            if (e.key === 'Enter') {
                                isEditing = false;
                                $(this).blur();
                            }
                        });
                    }

                    // Gọi hàm khi trang load
                    updateCodDisplay();
                    attachInputEvents();

                    // Gọi hàm khi thay đổi payment method
                    $('#payment_method_select').on('change', function() {
                        if (!isEditing) {
                            updateCodDisplay();
                        }
                    });

                });
            </script>

            <script>
                $('#button_sumshiptocod').on('change', function() {
                    if ($(this).is(':checked')) {
                        let totalFee = $('#total-fee').text() || 0;
                        let totalNumber = totalFee.replace(/\./g, '').replace(/[^\d]/g, '');
                        totalNumber = parseFloat(totalNumber);
                        let currentCod = ($('#cod_amount').val().replace(/\./g, '')) || 0;
                        currentCod = parseFloat(currentCod);

                        let newCod = currentCod + totalNumber;
                        let formattedNewCod = newCod.toLocaleString('vi-VN');

                        $('#cod_amount').val(formattedNewCod);
                    } else {
                        let totalFee = $('#total-fee').text() || 0;
                        let totalNumber = totalFee.replace(/\./g, '').replace(/[^\d]/g, '');
                        totalNumber = parseFloat(totalNumber);
                        let currentCod = ($('#cod_amount').val().replace(/\./g, '')) || 0;
                        currentCod = parseFloat(currentCod);

                        let newCod = currentCod - totalNumber;
                        let formattedNewCod = newCod.toLocaleString('vi-VN');

                        $('#cod_amount').val(formattedNewCod);
                    }
                });

                function resetCheckbox() {
                    document.getElementById('button_sumshiptocod').checked = false;
                }

                function blockbuttonSumshiptocod() {
                    const checkbox = document.getElementById('button_sumshiptocod');
                    checkbox.disabled = true; // Khóa checkbox ngay khi click
                    setTimeout(() => {
                        checkbox.disabled = false; // Sau 1s mở khóa lại
                    }, 1500);
                }

                function handleCodAmountBlur() {
                    const checkbox = document.getElementById('button_sumshiptocod');
                    checkbox.disabled = true; // Khóa checkbox ngay khi blur

                    setTimeout(() => {
                        checkbox.disabled = false; // Sau 1s mở khóa lại
                    }, 1000);
                }


                $(document).ready(function() {
                    // Lưu trữ giá trị ban đầu của tất cả các trường
                    const initialValues = {};
                    const formInputs = $('#myForm').find('input, select, textarea').not('[type="hidden"]');

                    formInputs.each(function() {
                        initialValues[$(this).attr('name')] = $(this).val();
                    });

                    // Thêm style cho nút submit khi disable
                    const submitBtn = $('button[type="submit"]');
                    submitBtn.addClass('disabled-btn');
                    submitBtn.prop('disabled', true);

                    // Thêm CSS inline hoặc trong style tag
                    $('head').append(`
                            <style>
                                .disabled-btn {
                                    background-color: #cccccc !important;
                                    border-color: #cccccc !important;
                                    cursor: not-allowed;
                                    opacity: 0.6;
                                }
                            </style>
                            `);
                    let changeTimeout = null;

                    function checkFormChangesWithDelay() {
                        clearTimeout(changeTimeout);
                        submitBtn.addClass('disabled-btn').prop('disabled', true);

                        changeTimeout = setTimeout(function() {
                            let hasChanges = false;
                            formInputs.each(function() {
                                const $input = $(this);
                                const name = $input.attr('name');
                                const currentValue = $input.val();

                                if (initialValues[name] !== currentValue) {
                                    hasChanges = true;
                                    return false; // break loop
                                }
                            });

                            if (hasChanges) {
                                submitBtn.removeClass('disabled-btn').prop('disabled', false);
                            } else {
                                submitBtn.addClass('disabled-btn').prop('disabled', true);
                            }
                        }, 1000); // 1 giây
                    }

                    // Theo dõi sự thay đổi của form
                    formInputs.on('change input', function() {
                        checkFormChangesWithDelay();
                    });
                });

                let codDefaul = $('#cod_amount_default').val();

                $(document).on('input change click',
                    '#warehouse-select, #button_remove, #button_add, #product-name-select, input[name="weight_item[]"], input[name="quantity[]"], input[name="length"], input[name="width"], input[name="height"]',
                    function() {
                        resetCheckbox();
                        $('#cod_amount').val(codDefaul);

                    }
                );

                // Thêm đoạn script này sau khi trang đã load
                $(document).ready(function() {
                    // Lấy giá trị insurance_value ban đầu và tính phí
                    let initialInsuranceValue = $('#insurance_value').val();

                    if (initialInsuranceValue) {
                        // Đảm bảo giá trị được format đúng
                        let cleanValue = initialInsuranceValue.replace(/[,.]/g, '');
                        let insuranceFee = (cleanValue * 0.005);

                        // Tính phí nếu đủ điều kiện
                        if ($('#from_district_id').val() &&
                            $('#from_ward_code').val() &&
                            $('#to_district_id').val() &&
                            $('#to_ward_code').val()) {
                            calculateFee();
                        }
                    }

                    // Cập nhật sự kiện khi thay đổi giá trị insurance_value
                    $('#insurance_value').on('change input blur', function() {
                        let value = $(this).val().replace(/[,.]/g, '');

                        // Chỉ tính phí nếu có đủ thông tin
                        if ($('#from_district_id').val() &&
                            $('#from_ward_code').val() &&
                            $('#to_district_id').val() &&
                            $('#to_ward_code').val()) {
                            calculateFee();
                        }
                    });
                });

                $(document).on('input change', '#weight_item, #insurance_value, #quantity, #length, #width, #height',
                    function() {
                        blockbuttonSumshiptocod();
                    });

                $(document).on('click', '#button_add, #button_remove, #remove_row, #product-name-select',
                    function() {
                        blockbuttonSumshiptocod();
                    });


                $('#cod_amount').on('blur', function() {
                    let value = $(this).val();

                    let codDefaul = $('#insurance_value').val();

                    // Đảm bảo giá trị được cập nhật vào insurance_value
                    setTimeout(() => {
                        if ($('input[name="insurance_value"]').val() &&
                            $('input[name="from_district_id"]').val() &&
                            $('input[name="from_ward_code"]').val() &&
                            $('input[name="to_district_id"]').val() &&
                            $('input[name="to_ward_code"]').val()) {
                            calculateFee();
                        }
                    }, 100);

                    $('#warehouse-select, #product-name-select,#insurance_value, input[name="weight_item[]"], input[name="quantity[]"], input[name="length"], input[name="width"], input[name="height"]')
                        .on('change', function() {
                            resetCheckbox();
                            $('#cod_amount').val(codDefaul);
                        });

                });

                $('input[name="weight_order"]').on('change', function() {
                    calculateFee();
                });



                document.addEventListener('DOMContentLoaded', function() {
                    // Kiểm tra giá trị payment_method khi load trang
                    const paymentMethod = $('#payment_method_select').val();
                    const feeWarningContainer = $('#fee_warning_container');


                    if (paymentMethod == '2') {
                        feeWarningContainer.show();

                    } else {
                        feeWarningContainer.hide();

                    }


                    // Giữ nguyên event listener cho việc thay đổi
                    $('#payment_method_select').on('change', function() {
                        const paymentMethod = $(this).val();
                        const feeWarningContainer = $('#fee_warning_container');

                        if (paymentMethod == '2') {
                            feeWarningContainer.show();
                        } else if (paymentMethod == '1') {
                            feeWarningContainer.hide();
                        }
                        calculateFee();
                    });
                });

                $('input[name="insurance_value"]').on('change input blur', function() {
                    let value = $(this).val().replace(/[,.]/g, '');

                    // Chỉ tính phí nếu có đủ thông tin
                    if ($('#from_district_id').val() &&
                        $('#from_ward_code').val() &&
                        $('#to_district_id').val() &&
                        $('#to_ward_code').val()) {
                        calculateFee();
                    }
                });

                function calculateFee() {
                    // Lấy và chuyển đổi giá trị
                    const values = {
                        from_district_id: $('#from_district_id').val() || '', // Giữ nguyên dùng ID
                        from_ward_code: $('#from_ward_code').val() || '', // Giữ nguyên dùng ID
                        to_district_id: $('#to_district_id').val() || '', // Giữ nguyên dùng ID
                        to_ward_code: $('#to_ward_code').val() || '', // Giữ nguyên dùng ID
                        insurance_value: $('#insurance_value').val().replace(/[,.]/g, '') || '0'
                    };

                    $.ajax({
                        url: '/user/calculate-fee',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            from_district_id: $('#from_district_id').val(),
                            from_ward_code: $('#from_ward_code').val(),
                            to_district_id: $('#to_district_id').val(),
                            to_ward_code: $('#ward_id').val(),
                            height: $('input[name="height"]').val() || 10,
                            length: $('input[name="length"]').val() || 10,
                            width: $('input[name="width"]').val() || 10,
                            quantity: $('#quantity').val() || 1,
                            weight_order: $('input[name="weight_order"]').val() || 200,
                            shopId: $('input[name="shopId"]').val(),
                            pickup_area_hidden: $('input[name="shopId"]').val(),
                            insurance_value: Number(values.insurance_value)
                        },
                        success: function(response) {
                            if (response.success) {

                                const paymentMethod = $('#payment_method_select').val();
                                const feeWarningContainer = $('#fee_warning_container');

                                if (paymentMethod == '1') {
                                    feeWarningContainer.hide();
                                    $('#service-fee').text(formatMoney(response.service_fee) +
                                        ' đ');
                                    $('#insurance-fee').text(formatMoney(response.insurance_fee) + ' đ');
                                    $('#fee_shopId').text(formatMoney(response.fee_warning) + ' đ');

                                    $('#total-fee').text(formatMoney(response.service_fee + response.insurance_fee +
                                            response.fee_warning) +
                                        ' đ');
                                }
                            }
                        },
                        error: function(xhr) {
                            console.log('Lỗi tính phí:', xhr);
                        }
                    });
                }

                $(document).ready(function() {
                    $('input[name="to_district_id"], input[name="to_ward_code"], input[name="insurance_value"], #ward_id')
                        .on(
                            'change', calculateFee);
                });

                function formatMoney(amount) {
                    return new Intl.NumberFormat('vi-VN').format(amount);
                }

                // Gọi tính phí khi thay đổi giá trị insurance_value
                // $('input[name="insurance_value"]').on('change', calculateFee);
            </script>

            <script>
                // Thêm sự kiện cho select địa chỉ trả hàng
                $('#address_return').on('change', function() {
                    let selectedOption = $(this).find('option:selected');

                    // Lấy giá trị từ data attributes của option được chọn
                    const returnPhone = selectedOption.data('phone') || '';
                    const returnAddress = selectedOption.data('street') || '';

                    // Cập nhật giá trị vào các input hidden
                    $('#return_phone').val(returnPhone);
                    $('#return_address').val(returnAddress);


                });

                // Tự động set giá trị khi trang load nếu có option được chọn sẵn
                $(document).ready(function() {
                    let selectedOption = $('#address_return option:selected');
                    if (selectedOption.length > 0) {
                        $('#return_phone').val(selectedOption.data('phone') || '');
                        $('#return_address').val(selectedOption.data('street') || '');
                    }
                });
            </script>


            {{-- lay gia tri tinh thanh quan huyen phuong xa tu DB va so sanh tren api tinh quan xa, neu co thi select --}}
            <script>
                $(document).ready(function() {
                    // Khai báo biến global để dễ debug
                    const savedProvinceId = '{{ $order->to_province }}';
                    const savedDistrictId = '{{ $order->to_district }}';
                    const savedWardId = '{{ $order->to_ward }}';
                    const savedProvinceName = '{{ $order->to_province_name }}';
                    const savedDistrictName = '{{ $order->to_district_name }}';
                    const savedWardName = '{{ $order->to_ward_name }}';


                    function loadProvinces() {
                        return $.ajax({
                            url: "https://online-gateway.ghn.vn/shiip/public-api/master-data/province",
                            method: "GET",
                            headers: {
                                "token": "{{ config('constants.ghn_api_token') }}",
                                "Content-Type": "application/json"
                            }
                        }).then(function(response) {
                            if (response.code === 200) {
                                let html = '<option value="">Chọn tỉnh/thành</option>';
                                let selectedProvinceId = null;

                                response.data.forEach(province => {
                                    const isSelected = province.ProvinceID == savedProvinceId ||
                                        province.ProvinceName.toLowerCase() === savedProvinceName
                                        .toLowerCase();
                                    if (isSelected) {
                                        selectedProvinceId = province.ProvinceID;
                                    }
                                    html +=
                                        `<option value="${province.ProvinceID}" ${isSelected ? 'selected' : ''}>${province.ProvinceName}</option>`;
                                });


                                $('#province_id').html(html);

                                if (selectedProvinceId) {
                                    $('#province_id').val(selectedProvinceId).trigger('change'); // Sửa ở đây

                                    $('.province_name').val(savedProvinceName);
                                    $('#province_id').select2({
                                        theme: 'bootstrap4'
                                    });

                                    return selectedProvinceId;
                                }
                                return selectedProvinceId;
                            }
                            return null;
                        });
                    }

                    function loadDistricts(provinceId) {
                        if (!provinceId) return Promise.reject('No province ID');

                        return $.ajax({
                            url: "https://online-gateway.ghn.vn/shiip/public-api/master-data/district",
                            method: "GET",
                            headers: {
                                "token": "{{ config('constants.ghn_api_token') }}",
                                "Content-Type": "application/json"
                            },
                            data: {
                                "province_id": parseInt(provinceId)
                            }
                        }).then(function(response) {
                            if (response.code === 200) {
                                let html = '<option value="">Chọn quận/huyện</option>';
                                let selectedDistrictId = null;

                                response.data.forEach(district => {
                                    const isSelected = String(district.DistrictID) === String(
                                            savedDistrictId) ||
                                        district.DistrictName.toLowerCase() === savedDistrictName
                                        .toLowerCase();
                                    if (isSelected) {
                                        selectedDistrictId = district.DistrictID;
                                    }
                                    html +=
                                        `<option value="${district.DistrictID}" ${isSelected ? 'selected' : ''}>${district.DistrictName}</option>`;
                                });

                                $('#district_id').html(html);

                                if (selectedDistrictId) {
                                    $('#district_id').val(selectedDistrictId).trigger('change');
                                    $('#district_id').select2({
                                        theme: 'bootstrap4'
                                    });
                                    $('.district_name').val(savedDistrictName);
                                    $('#to_district_id').val(selectedDistrictId);
                                    return selectedDistrictId;
                                }
                                return savedDistrictId;
                            }
                            return null;
                        });
                    }

                    function loadWards(districtId) {
                        if (!districtId) return Promise.reject('No district ID');

                        return $.ajax({
                            url: "https://online-gateway.ghn.vn/shiip/public-api/master-data/ward",
                            method: "GET",
                            headers: {
                                "token": "{{ config('constants.ghn_api_token') }}",
                                "Content-Type": "application/json"
                            },
                            data: {
                                "district_id": districtId
                            }
                        }).then(function(response) {
                            if (response.code === 200) {
                                let html = '<option value="">Chọn phường/xã</option>';
                                let selectedWardCode = null;

                                response.data.forEach(ward => {
                                    const isSelected = ward.WardCode == savedWardId ||
                                        ward.WardName.toLowerCase() === savedWardName.toLowerCase();
                                    if (isSelected) {
                                        selectedWardCode = ward.WardCode;
                                    }
                                    html +=
                                        `<option value="${ward.WardCode}" ${isSelected ? 'selected' : ''}>${ward.WardName}</option>`;
                                });

                                $('#ward_id').html(html);

                                if (selectedWardCode) {
                                    $('#ward_id').val(selectedWardCode).trigger('change');
                                    $('#ward_id').select2({
                                        theme: 'bootstrap4'
                                    });
                                    $('.ward_name').val(savedWardName);
                                    $('#to_ward_code').val(selectedWardCode);
                                }
                            }
                        });
                    }

                    // Khởi tạo chuỗi promise
                    loadProvinces()
                        .then(provinceId => {
                            if (provinceId) return loadDistricts(provinceId);
                            return null;
                        })
                        .then(districtId => {
                            if (districtId) return loadWards(districtId);
                        })
                        .catch(error => console.error('Error in loading address chain:', error));

                    // Xử lý sự kiện thay đổi
                    $('#province_id').off('change').on('change', function() {
                        const provinceId = $(this).val();
                        const provinceName = $(this).find('option:selected').text();

                        $('.province_name').val(provinceName);

                        // Reset quận/huyện và phường/xã
                        $('#district_id').html('<option value="">Chọn quận/huyện</option>');
                        $('#ward_id').html('<option value="">Chọn phường/xã</option>');
                        $('.district_name').val('');
                        $('.ward_name').val('');

                        if (provinceId) {
                            loadDistricts(provinceId);
                        }
                    });

                    $('#district_id').off('change').on('change', function() {
                        const districtId = $(this).val();
                        const districtName = $(this).find('option:selected').text();
                        // console.log("District changed:", districtId, districtName);

                        // Lưu giá trị đã chọn
                        const selectedOption = $(this).find('option:selected');

                        $('.district_name').val(districtName);
                        $('#to_district_id').val(districtId);

                        // Reset phường/xã
                        $('#ward_id').html('<option value="">Chọn phường/xã</option>');
                        $('.ward_name').val('');

                        if (districtId) {
                            loadWards(districtId).then(() => {
                                // Đảm bảo giá trị được giữ nguyên sau khi load xong
                                if (districtId && districtName) {
                                    $('.district_name').val(districtName);
                                    $('#to_district_id').val(districtId);
                                    $(this).val(districtId);
                                }
                            });
                        }
                    });



                    $('#ward_id').off('change').on('change', function() {
                        const oldwardCode = $('#to_ward_code').val();

                        const wardCode = $(this).val();
                        const wardName = $(this).find('option:selected').text();

                        if (oldwardCode && oldwardCode != wardCode) {
                            calculateFee();
                        }

                        // Lưu giá trị đã chọn
                        $('.ward_name').val(wardName);
                        $('#to_ward_code').val(wardCode);

                    });
                });
            </script>

            <script>
                function exitInput(event, element) {
                    if (event.key === "Enter") {
                        element.blur(); // Mất focus khi nhấn Enter
                        event.preventDefault(); // Ngăn form submit nếu có
                    }
                }
            </script>

            <script>
                // Khi chọn warehouse
                $("#warehouse-select").change(function() {
                    var selectedOption = $(this).find('option:selected');

                    const values = {
                        name: selectedOption.data('name'),
                        phone: selectedOption.data('phone'),
                        street: selectedOption.data('street'),
                        ward: selectedOption.data('ward'),
                        district: selectedOption.data('district'),
                        province: selectedOption.data('province'),
                        district_id: selectedOption.data('district_id'),
                        ward_code: selectedOption.data('ward_code'),

                    };

                    $('#from_name').val(values.name);
                    $('#from_phone').val(values.phone);
                    $('#street_name').val(values.street);
                    $('#ward_name_store').val(values.ward);
                    $('#district_name_store').val(values.district);
                    $('#province_name_store').val(values.province);

                    $('#from_district_id').val(values.district_id);
                    $('#from_ward_code').val(values.ward_code);


                    // Trigger calculateFee nếu đủ điều kiện
                    if (values.district_id && values.ward_code) {
                        calculateFee();
                    }

                    // Set giá trị và trigger change event
                    $('#from_district_id').val(values.district_id).trigger('change');
                    $('#from_ward_code').val(values.ward_code).trigger('change');
                });

                // Thêm đoạn này để tự động set giá trị khi trang load
                $(document).ready(function() {
                    var selectedOption = $('#warehouse-select option:selected');
                    if (selectedOption.length > 0) {
                        $('#from_name').val(selectedOption.data('name'));
                        $('#from_phone').val(selectedOption.data('phone'));
                        $('#street_name').val(selectedOption.data('street'));
                        $('#ward_name_store').val(selectedOption.data('ward'));
                        $('#district_name_store').val(selectedOption.data('district'));
                        $('#province_name_store').val(selectedOption.data('province'));

                        $('#from_district_id').val(selectedOption.data('district_id'));
                        $('#from_ward_code').val(selectedOption.data('ward_code'));

                    }
                });
            </script>

            {{-- chọn sản phẩm sau đó auto gán sku và weight --}}
            {{-- tính tổng khối lượng từ sản phẩm nhân số lượng --}}
            {{-- tinh gia tri khoi luong quy doi --}}
            <script>
                $(document).ready(function() {
                    // Xử lý khi select sản phẩm thay đổi
                    $(document).on('change', '.product-name-select', function() {
                        // Lấy row hiện tại
                        let currentRow = $(this).closest('.product-row');
                        let selectedOption = $(this).find('option:selected');

                        // Lấy giá trị SKU và weight từ data attributes
                        let sku = selectedOption.data('sku');
                        let weight = selectedOption.data('weight');

                        // Điền giá trị và trigger change event
                        let weightInput = currentRow.find('input[name="weight_item[]"]');
                        let codeInput = currentRow.find('input[name="code[]"]');


                        codeInput.val(sku).trigger('change');
                        weightInput.val(weight).trigger('change');
                        // Gọi ngay hàm tính tổng

                        if (sku) {
                            codeInput.val(sku);
                            codeInput.attr('value', sku); // Thêm dòng này
                        } else {
                            codeInput.val('');
                            codeInput.attr('value', '');
                        }
                        updateTotalWeight();
                    });
                });

                function updateTotalWeight() {

                    if ($('.product-row').length === 0 ||
                        $('.product-row').find('input[name="weight_item[]"]').filter(function() {
                            return $(this).val() !== '';
                        }).length === 0) {
                        let orderWeight = {{ $order->weight ?? 200 }};
                        $('#id_tongkhoiluong').val(orderWeight);

                        calculateVolume();

                        if (orderWeight) {
                            calculateFee();
                        }

                        return;
                    }

                    let totalWeight = 0;

                    $('.product-row').each(function() {
                        let weight = parseFloat($(this).find('input[name="weight_item[]"]').val()) || 0;
                        let quantity = parseInt($(this).find('input[name="quantity[]"]').val()) || 1;
                        totalWeight += weight * quantity;
                    });

                    $('#id_tongkhoiluong').val(totalWeight);

                    calculateVolume();

                }

                function calculateVolume() {
                    let length = parseInt(document.querySelector('input[name="length"]').value) || 10;
                    let width = parseInt(document.querySelector('input[name="width"]').value) || 10;
                    let height = parseInt(document.querySelector('input[name="height"]').value) || 10;

                    let convertedWeight = Math.ceil((length * width * height) / 5);
                    document.querySelector('.khoiluongquydoi').textContent = (convertedWeight.toLocaleString('vi-VN')) +
                        ' g';
                    document.getElementById('id_khoiluongquydoi').value = convertedWeight.toFixed(0);

                    let totalWeight = parseInt(document.getElementById('id_tongkhoiluong').value) ||
                        0;
                    let chargeableWeight = Math.max(totalWeight, convertedWeight);
                    document.querySelector('.khoiluongCuoc').textContent = chargeableWeight.toLocaleString('vi-VN') +
                        ' g';
                    //   document.getElementById('id_khoiluongcuoc').value = chargeableWeight;
                    document.querySelector('input[name="weight_order"]').value = chargeableWeight;

                    // Kiểm tra điều kiện trước khi tính phí
                    if (
                        $('#from_district_id').val() &&
                        $('#from_ward_code').val() &&
                        $('#to_district_id').val() &&
                        $('#to_ward_code').val()
                    ) {
                        calculateFee();
                    }

                }

                //khi 3 giá tri kích thước thay đổi thì capnhat lại
                // document.querySelectorAll(
                //         'input[name="length"], input[name="width"], input[name="height"]')
                //     .forEach(input => {
                //         input.addEventListener("input", calculateVolume);
                //     });

                //update khi thay doi can nang
                document.getElementById("product_container").addEventListener("input", function(event) {
                    if (event.target.matches("input[name='weight_item[]'], input[name='quantity[]']")) {
                        updateTotalWeight();
                    }
                });

                //update lai khi xoa san pham
                document.getElementById("product_container").addEventListener("click", function(event) {
                    if (event.target.closest(".remove-row")) {
                        event.preventDefault();
                        let row = event.target.closest(".product-row");
                        if (row) {
                            row.remove();
                            updateTotalWeight();
                            checkProductFields();
                        }
                    }
                });

                updateTotalWeight();
                calculateVolume();
            </script>
            {{--

        {{-- them row trong san pham trong don hang --}}
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var container = document.getElementById("product_container");

                    function updateRemoveButtons() {
                        var rows = container.querySelectorAll(".product-row");
                        container.querySelectorAll(".remove-row").forEach(button => {
                            button.style.display = rows.length > 1 ? "inline-block" : "none";
                        });
                    }

                    container.addEventListener("click", function(event) {
                        // Thêm dòng mới
                        if (event.target.closest(".add-row")) {
                            event.preventDefault();

                            var firstRow = container.querySelector(".product-row");
                            if (!firstRow) return;

                            var newRow = firstRow.cloneNode(true);

                            // Xóa dữ liệu trong input
                            newRow.querySelectorAll("input").forEach(input => {
                                if (input.name === "code[]") {
                                    // Giữ nguyên readonly cho input SKU
                                    input.value = "";
                                    input.readOnly = true;
                                } else if (input.name === "quantity[]") {
                                    // Đặt giá trị mặc định 1 cho quantity
                                    input.value = "1";
                                } else {
                                    // Các input khác reset về rỗng
                                    input.value = "";
                                }
                            });

                            newRow.querySelector("select").value = "";

                            container.appendChild(newRow);
                            updateRemoveButtons();
                        }

                        // Xóa dòng
                        if (event.target.closest(".remove-row")) {
                            event.preventDefault();

                            var row = event.target.closest(".product-row");
                            row.remove();
                            updateRemoveButtons();
                        }
                    });
                    updateRemoveButtons();
                });
            </script>

            {{-- thong tin cua hang, se chia ra va truyen vao du lieu dua sang api , them nut switch giua select va input manual --}}
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    let manualForm = document.getElementById("manual-form");
                    let warehouseSelect = document.getElementById("warehouse-select");
                    let manualButton = document.querySelector(".btn-primary");

                    var selectedOption = $('#warehouse-select option:selected');
                    if (selectedOption.length > 0) {
                        $('#from_name').val(selectedOption.data('name'));
                        $('#from_phone').val(selectedOption.data('phone'));
                        $('#street_name').val(selectedOption.data('street'));
                        $('#ward_name_store').val(selectedOption.data('ward'));
                        $('#district_name_store').val(selectedOption.data('district'));
                        $('#province_name_store').val(selectedOption.data('province'));
                        $('#from_district_id').val(selectedOption.data('district_id'));
                        $('#from_ward_code').val(selectedOption.data('ward_code'));
                    }


                    manualButton.addEventListener("click", function() {
                        // manualForm.classList.toggle("d-none");
                        warehouseSelect.classList.toggle("d-none");
                    });

                    function updateHiddenFields() {
                        let fromName = document.getElementById("from_name");
                        let fromPhone = document.getElementById("from_phone");
                        let fromAddress = document.getElementById("street_name");
                        let fromWard = document.getElementById("ward_name_store");
                        let fromDistrict = document.getElementById("district_name_store");
                        let fromProvince = document.getElementById("province_name_store");
                        let requireNote = document.getElementById("require_note");
                        let paymentType = document.getElementById("payment_type");
                        let note = document.getElementById("note");
                        let transportUnit = document.getElementById("transport_unit");

                        if (manualForm.classList.contains("d-none")) {
                            // Lấy giá trị từ select
                            let selectedOption = warehouseSelect.options[warehouseSelect.selectedIndex];
                            fromName.value = selectedOption.getAttribute("data-name") || "";
                            fromPhone.value = selectedOption.getAttribute("data-phone") || "";
                            fromAddress.value = selectedOption.getAttribute("data-street") || "";
                            fromWard.value = selectedOption.getAttribute("data-ward") || "";
                            fromDistrict.value = selectedOption.getAttribute("data-district") || "";
                            fromProvince.value = selectedOption.getAttribute("data-province") || "";
                            requireNote.value = selectedOption.getAttribute("data-require_note") || "";
                            paymentType.value = selectedOption.getAttribute("data-payment_type") || "";
                            note.value = selectedOption.getAttribute("data-note") || "";
                            transportUnit.value = selectedOption.getAttribute("data-transport_unit") || "";
                        } else {
                            // Lấy giá trị từ form nhập tay
                            fromName.value = document.querySelector("input[name='input_name']").value;
                            fromPhone.value = document.querySelector("input[name='input_phone']").value;
                            fromAddress.value = document.querySelector("input[name='input_street_name']").value;
                            fromWard.value = document.querySelector("input[name='ward_name_1']").value;
                            fromDistrict.value = document.querySelector("input[name='district_name_1']").value;
                            fromProvince.value = document.querySelector("input[name='province_name_1']").value;
                        }
                    }

                    // Cập nhật khi chọn select
                    warehouseSelect.addEventListener("change", function() {
                        updateHiddenFields();

                        // Thêm phần cập nhật require_note khi thay đổi address
                        let selectedOption = $(this).find('option:selected');
                        let paymentMethodValue = selectedOption.data('payment_type');
                        let transportUnitValue = selectedOption.data('transport_unit');
                        let noteValue = selectedOption.data('note');

                        // Cập nhật select require_note
                        let selectElement3 = document.getElementById("transport_unit_select");
                        if (selectElement && requireNoteValue) {
                            selectElement.value = requireNoteValue;
                        }

                        if (selectElement2 && paymentMethodValue) {
                            selectElement2.value = paymentMethodValue;
                        }

                        if (selectElement3 && transportUnitValue) {
                            selectElement3.value = transportUnitValue;
                        }

                        if (selectElement4 && noteValue) {
                            selectElement4.value = noteValue;
                        }
                    });

                    // Cập nhật khi rời khỏi input nhập tay
                    document.querySelectorAll("#manual-form input").forEach(input => {
                        input.addEventListener("blur", updateHiddenFields);
                    });


                    //gan gia tri require note
                    let selectElement3 = document.getElementById("transport_unit_select");
                    let selectElement4 = document.getElementById("note_select");


                    if (selectElement3) {
                        let optionToSelect = selectElement3.querySelector(
                            `option[value="${selectedOption.data('transport_unit')}"]`);
                        if (optionToSelect) {
                            optionToSelect.selected = true;
                        }
                    }

                    if (selectElement4) {
                        let optionToSelect = selectElement4.querySelector(
                            `option[value="${selectedOption.data('note')}"]`);
                    }
                });
            </script>

            {{-- laythong tin api tinh thanh quan huyen xa --}}
            {{-- <script>
            $(document).ready(function() {

                    var settings = {
                        "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/province",
                        "method": "GET",
                        "timeout": 0,
                        "headers": {
                            "token": "{{ config('constants.ghn_api_token') }}",
                            "Content-Type": "application/json"
                        },
                    };

                    $.ajax(settings).done(function(response) {

                        if (response.code === 200) {

                            let html = `<option value="">Chọn tỉnh/thành</option>`;
                            response.data.forEach(element => {
                                html +=
                                    `<option value="${element.ProvinceID}">${element.ProvinceName}</option>`;
                            });
                            $("#province_id").html(html);
                        } else {
                            alert(response.message)
                        }
                    });

                    //province
                    $(".province ").change(function() {
                        var province_id = parseInt($(this).val());
                        var text = $(".province option:selected").text();
                        $('.province_name').val(text)

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

                        $.ajax(settings).done(function(response) {

                            if (response.code === 200) {
                                let html = ``;
                                for (const element of response.data) {
                                    html +=
                                        ` <option value="${element.DistrictID}">${element.DistrictName}</option>`
                                }
                                $('.district').html(html)

                            } else {
                                alert(response.message)
                            }
                        });
                    });

                    //district
                    $(".district").change(function() {
                        var district_id = parseInt($(this).val());
                        var text = $(".district option:selected").text();
                        $('.district_name').val(text)
                        $('#to_district_id').val(district_id); // Thêm dòng này


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

                        $.ajax(settings).done(function(response) {
                            if (response.code === 200) {
                                let html = `<option value="">Chọn </option>`;
                                for (const element of response.data) {
                                    html +=
                                        ` <option value="${element.WardCode}">${element.WardName}</option>`
                                }
                                $('.ward').html(html)
                            } else {
                                alert(response.message)
                            }
                        });
                    });

                    $(".ward").change(function() {
                        var text = $(".ward option:selected").text();
                        var wardCode = $(this).val();
                        $('#to_ward_code').val(wardCode);
                        $('.ward_name').val(text)
                    });
                });
        </script> --}}

            {{-- dinh sang so sang kieu du lieu tien te vnd --}}
            <script>
                function formatCurrency(input) {
                    let value = input.value.replace(/\D/g, '');
                    if (value === '') {
                        input.value = 0;
                    } else {
                        input.value = new Intl.NumberFormat('vi-VN').format(value);
                    }

                    $(input).trigger('change');
                }
            </script>

            {{-- check user theo sdt va lay ra cac gia tri --}}
            <script>
                document.querySelector('input[name="to_phone"]').addEventListener('blur', function() {
                    let phone = this.value.trim();
                    if (phone !== '') {
                        fetch(`/user/get-user-info?phone=${phone}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.querySelector('input[name="to_name"]').value = data.user.name || '';
                                    document.querySelector('input[name="to_address"]').value = data.user.address || '';
                                    document.querySelector('input[name="ward_name"]').value = data.user
                                        .ward_name || '';
                                    document.querySelector('input[name="district_name"]').value =
                                        data.user.district_name || '';

                                    let provinceSelect = document.querySelector('select[name="province_id"]');
                                    let districtSelect = document.querySelector('select[name="district_id"]');
                                    let wardSelect = document.querySelector('select[name="ward_id"]');

                                    provinceSelect.value = data.user.province_id;
                                    document.querySelector('input[name="province_name"]').value = data.user
                                        .province_name || '';

                                    if (data.user.province_id) {
                                        fetch('/user/api/get-districts', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',

                                                },
                                                body: JSON.stringify({
                                                    province_id: parseInt(provinceSelect.value)
                                                })
                                            })
                                            .then(response => response.json())
                                            .then(districtData => {
                                                districtSelect.innerHTML =
                                                    '<option value="">Chọn Quận/Huyện</option>';
                                                let foundDistrict = false;

                                                districtData.data.forEach(district => {
                                                    let selected = (district.DistrictID == data.user
                                                        .district_id) ? 'selected' : '';
                                                    if (selected) foundDistrict = true;
                                                    districtSelect.innerHTML +=
                                                        `<option value="${district.DistrictID}" ${selected}>${district.DistrictName}</option>`;
                                                });

                                                if (foundDistrict && data.user.district_id) {
                                                    fetch('/user/api/get-wards', {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/json',

                                                            },
                                                            body: JSON.stringify({
                                                                district_id: parseInt(data.user
                                                                    .district_id)
                                                            })
                                                        })
                                                        .then(response => response.json())
                                                        .then(wardData => {
                                                            wardSelect.innerHTML =
                                                                '<option value="">Chọn Xã/Phường</option>';
                                                            let foundWard = false;

                                                            wardData.data.forEach(ward => {
                                                                let selected = (ward.WardCode == data
                                                                    .user.ward_id) ? 'selected' : '';
                                                                if (selected) foundWard = true;
                                                                wardSelect.innerHTML +=
                                                                    `<option value="${ward.WardCode}" ${selected}>${ward.WardName}</option>`;
                                                            });
                                                            setTimeout(() => {
                                                                validateReceiverSection();
                                                                $('#receiver-section input, #receiver-section select')
                                                                    .trigger('change');
                                                            }, 100);
                                                        })
                                                        .catch(error => console.error(
                                                            'Lỗi lấy danh sách xã/phường:', error));
                                                }

                                            }).catch(error => console.error('Lỗi lấy danh sách quận/huyện:', error));
                                    }

                                    if (data.user.district_id) {
                                        $('#to_district_id').val(data.user.district_id);
                                    }
                                    if (data.user.ward_id) {
                                        $('#to_ward_code').val(data.user.ward_id);
                                    }

                                    if ($('input[name="insurance_value"]').val()) {
                                        calculateFee();
                                    }
                                }
                            }).catch(error => console.error("Lỗi API:", error));
                    }
                });
            </script>

            {{-- ngan chan ko cho submit lung tung --}}
            <script>
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        let target = event.target;
                        if (target.tagName === 'INPUT' && target.type !== 'submit') {
                            event.preventDefault();
                        }
                    }
                });
            </script>

    </div>

    </section>
    </div>
@endsection
