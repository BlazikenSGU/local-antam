@extends('frontend.layouts.main')

@push('title')
    Tạo Đơn hàng
@endpush

@section('content')
    <style>
        #warehouse-select {
            text-wrap: auto;
        }

        #address_return {
            text-wrap: auto;
        }

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
            top: 20px;
            height: fit-content;
        }

        select.form-control {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Thêm style cho card để đảm bảo hiển thị đẹp */
        .total-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .section-bengui {
            overflow-x: hidden;
        }

        @media(max-width: 992px) and (min-width:768px) {
            .mobile-form {
                flex-direction: column;
            }
        }

        @media (max-width: 768px) and (min-width: 375px) {

            .mobile-form,
            .mobile-manual,
            .mobile-bennhan,
            .mobile-product,
            .mobile-weight,
            .mobile-note {
                flex-direction: column;
            }

            .mobile-product .col-md-4,
            .mobile-product .col-md-2,
            .mobile-product .col-md-1 {
                margin: .5rem 0 !important;
            }

        }

        span.select2-selection__arrow {
            display: none;
        }

        span.select2 {
            margin-top: 10px;
            width: 100% !important;
        }

        option {
            width: 100%;
        }

        .section-layout {
            padding: 0 !important;
        }

        /* giao dien mobile */
        @media (max-width: 650px) {

            .layout_mobile {
                flex-direction: column;
                align-items: center;
            }

            .col-md-3.p-2.section-mobile {
                margin-top: 10px;
            }

            .section-mobile {
                margin-top: 1rem;
                padding: 0 !important;
            }

            .container {
                padding: 0 !important;
            }

            .row.page-titles h2 {
                text-align: center;
                font-weight: bold;
                color: #1b4e87;
            }

            .sanpham-mobile {
                padding: 5px !important;
            }

            .section-goihang {
                padding: 5px !important;
            }

            .section-bennhan {
                padding: 5px !important;
            }

            .form-check-label,
            .section-klqd,
            .section-kltc {
                font-size: 10px;
            }

            .section-note {
                padding: 5px !important;
            }

            .section-layout {
                padding: 0 !important;
            }

            .page-titles h2 {
                margin-bottom: 0;
            }
        }

        @media (max-width: 375px) {
            .mobile-form {
                flex-direction: column;
            }

            .card-body {
                padding: .5rem !important;
            }

            .mobile-bennhan {
                flex-direction: column;
            }

            .mobile-product {
                flex-direction: column;
            }

            .sanpham-mobile {
                margin: 0% !important;
                padding: 5px !important;
            }

            .mobile-weight {
                flex-direction: column;
            }

            .mobile-note {
                flex-direction: column;
            }

            .section-bengui {
                padding: .5rem 0 !important;
            }


        }
    </style>

    <div class="container-xxl section-layout">

        <section class="mt-4">

            <div class="row layout_mobile">

                <div class="row page-titles">
                    <h2>Tạo đơn hàng</h2>
                </div>

                <div class="col-md-12 mt-2">
                    <div class=" card-outline-info">
                        <div>

                            <form class="form-horizontal" action="{{ route('user.order.store') }}" method="post"
                                id="myForm">
                                @csrf
                                <div class="col-md-12 col-lg-12 col-12 d-flex mobile-form">

                                    <div class="col-md-12 col-lg-8 p-2 col-12 section-mobile">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class="col-md-12" id="sender-section">

                                                    <h2 style="color: #F26522">
                                                        Bên Gửi
                                                    </h2>

                                                    <div class="col-md-12">

                                                        <div class="col-md-12 p-3 section-bengui">
                                                            <label for="">Kho hàng
                                                                {{-- <a style="color:white"
                                                                    class="btn btn-sm btn-primary">Thêm
                                                                    mới
                                                                    thủ công</a>  --}}
                                                            </label>

                                                            {{-- <div class="d-none" id="manual-form">
                                                                <div class="col-md-12 d-flex mt-2 mobile-manual">
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
                                                                            name="input_street_name" placeholder="địa chỉ"
                                                                            value="">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12 d-flex mt-2 mobile-manual">
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
                                                            </div> --}}

                                                            <select id="warehouse-select" class="form-control">
                                                                @foreach ($address as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        data-id = "{{ $item->id }}"
                                                                        data-street = "{{ $item->street_name }}"
                                                                        data-name="{{ $item->name }}"
                                                                        data-phone="{{ $item->phone ?? '' }}"
                                                                        data-ward="{{ $item->ward_name }}"
                                                                        data-district="{{ $item->district_name }}"
                                                                        data-province="{{ $item->province_name }}"
                                                                        data-require_note = "{{ $item->required_note }}"
                                                                        data-payment_type  = "{{ $item->payment_type }}"
                                                                        data-district_id="{{ $item->district_id }}"
                                                                        data-ward_code="{{ $item->ward_id }}"
                                                                        data-note = "{{ $item->note }}"
                                                                        data-transport_unit = "{{ $item->transport_unit }}"
                                                                        data-money_fail = "{{ $item->money_fail }}"
                                                                        data-pickup_area = "{{ $item->pickup_area }}"
                                                                        {{ $item->is_default == 1 ? 'selected' : '' }}>
                                                                        {{ $item->name }} - {{ $item->phone ?? '' }} -
                                                                        {{ $item->street_name ?? '' }} -
                                                                        {{ $item->ward_name }} -
                                                                        {{ $item->district_name }} -
                                                                        {{ $item->province_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            <input type="hidden" name="from_id" id="from_id"
                                                                value="">
                                                            <input type="hidden" name="from_name" id="from_name"
                                                                value="">
                                                            <input type="hidden" name="from_phone" id="from_phone"
                                                                value="">
                                                            <input type="hidden" name="from_address" id="street_name"
                                                                value="">
                                                            <input type="hidden" name="from_ward_name" id="ward_name_store"
                                                                value="">
                                                            <input type="hidden" name="from_district_name"
                                                                id="district_name_store" value="">
                                                            <input type="hidden" name="from_province_name"
                                                                id="province_name_store" value="">
                                                            <input type="hidden" id="require_note"
                                                                name="require_note_hidden">
                                                            <input type="hidden" id="payment_type"
                                                                name="payment_type_hidden">
                                                            <input type="hidden" id="note" name="note_hidden">
                                                            <input type="hidden" id="money_fail" name="money_fail_hidden">
                                                            <input type="hidden" id="pickup_area"
                                                                name="pickup_area_hidden">
                                                            <input type="hidden" id="transport_unit"
                                                                name="transport_unit_hidden">
                                                            <input type="hidden" name="from_district_id"
                                                                id="from_district_id">
                                                            <input type="hidden" name="from_ward_code" id="from_ward_code">
                                                        </div>

                                                        <div class="col-md-12 p-3 section-bengui">
                                                            <label for="">Địa chỉ hoàn trả hàng</label>
                                                            <select name="return_address_id" id="address_return"
                                                                class="form-control">

                                                                @foreach ($address as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        data-street = "{{ $item->street_name }}"
                                                                        data-name="{{ $item->name }}"
                                                                        data-phone="{{ $item->phone ?? '' }}"
                                                                        data-ward="{{ $item->ward_name }}"
                                                                        data-district="{{ $item->district_name }}"
                                                                        data-province="{{ $item->province_name }}"
                                                                        data-district_id="{{ $item->district_id }}"
                                                                        data-ward_code="{{ $item->ward_id }}"
                                                                        {{ $item->is_default == 1 ? 'selected' : '' }}>
                                                                        {{ $item->name }} -
                                                                        {{ $item->phone ?? '' }} -
                                                                        {{ $item->street_name ?? '' }} -
                                                                        {{ $item->ward_name }} -
                                                                        {{ $item->district_name }} -
                                                                        {{ $item->province_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            <input type="hidden" name="return_phone" id="return_phone">
                                                            <input type="hidden" name="return_name" id="return_name">
                                                            <input type="hidden" name="return_address"
                                                                id="return_address">
                                                            <input type="hidden" name="return_district_id"
                                                                id="return_district_id">
                                                            <input type="hidden" name="return_ward_code"
                                                                id="return_ward_code">

                                                        </div>
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
                                                                <input type="text" class="form-control" id=""
                                                                    name="to_phone" value="{{ old('to_phone') }}">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Họ
                                                                    tên</label>
                                                                <input type="text" class="form-control" id=""
                                                                    name="to_name" value="{{ old('to_name') }}">
                                                            </div>

                                                        </div>
                                                        <div class="col-md-6 p-3 section-bennhan">

                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Địa
                                                                    chỉ</label>
                                                                <input type="text" class="form-control" id=""
                                                                    name="to_address" value="{{ old('to_address') }}">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="" class="form-label l-label">Tỉnh
                                                                    thành</label>
                                                                <select name="province_id" id="province_id"
                                                                    class="form-control province select2">
                                                                    <option value=""></option>
                                                                </select>
                                                                <input type="hidden"
                                                                    class="form-control form-control-line province_name"
                                                                    value=" @if (!empty($data)) {{ $data->province_name }} @endif"
                                                                    name="province_name">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="" class="form-label l-label">Quận
                                                                    huyện</label>
                                                                <select name="district_id" id="district_id"
                                                                    class="form-control district select2">
                                                                    <option
                                                                        value="{{ isset($data) ? $data->district_id : '' }}">
                                                                        {{ isset($data) ? $data->district_name : '' }}
                                                                    </option>
                                                                </select>
                                                                <input type="hidden"
                                                                    class="form-control form-control-line district_name"
                                                                    value="{{ isset($data) ? $data->district_name : '' }}"
                                                                    name="district_name">
                                                                <input type="hidden" name="to_district_id"
                                                                    id="to_district_id">

                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="" class="form-label l-label">Xã
                                                                    phường</label>
                                                                <select name="ward_id" id="ward_id"
                                                                    class="form-control ward select2">
                                                                    <option
                                                                        value="{{ isset($data) ? $data->ward_id : '' }}">
                                                                        {{ isset($data) ? $data->ward_name : '' }}
                                                                    </option>
                                                                </select>
                                                                <input type="hidden"
                                                                    class="form-control form-control-line ward_name"
                                                                    value="{{ isset($data) ? $data->ward_name : '' }}"
                                                                    name="ward_name">
                                                                <input type="hidden" name="to_ward_code"
                                                                    id="to_ward_code">

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-12" id="product-section">
                                                    <h2 class="font-weight-bold" style="color: #F26522">
                                                        Thông tin hàng hóa</h2>


                                                    <div class="col-md-12 mt-2">
                                                        <h4 style="color: #F26522">Sản phẩm</h4>

                                                        <div id="product_container">

                                                            <div
                                                                class="col-md-12 col-12 d-flex product-row mt-2 mobile-product">
                                                                <div class="col-md-4 position-relative sanpham-mobile">

                                                                    <select class="form-control product-name-select"
                                                                        id="product-name-select" name="name[]">
                                                                        <option value=""> Chọn </option>
                                                                        @foreach ($product as $item)
                                                                            <option value="{{ $item->name }}"
                                                                                data-sku="{{ $item->product_code }}"
                                                                                data-weight="{{ $item->amount }}">
                                                                                {{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-2 mx-2 sanpham-mobile">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="SKU" name="code[]" value=""
                                                                        readonly>
                                                                </div>

                                                                <div class="col-md-2 mx-2 sanpham-mobile">
                                                                    <input type="number" class="form-control"
                                                                        name="weight_item[]" id="weight_item"
                                                                        placeholder="(gam)" value="">
                                                                </div>

                                                                <div class="col-md-1 mx-2 sanpham-mobile">
                                                                    <input type="number" name="quantity[]"
                                                                        id="quantity" class="form-control"
                                                                        placeholder="SL" value="1">
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <a href="#"
                                                                        class="btn btn-sm btn-warning add-row"
                                                                        id="add_row">Thêm</a>
                                                                    <a href="#"
                                                                        class="btn btn-sm btn-danger remove-row"
                                                                        id="remove_row"><i class='bx bx-trash'></i></a>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 my-4">
                                                        <h4 style="color: #F26522">Thông tin gói hàng</h4>
                                                    </div>

                                                    <div class="col-md-12 col-12 d-flex mobile-weight">

                                                        <div class="col-md-3 p-2 section-goihang">

                                                            <div class="mb-3">
                                                                <label for="" class="form-label"
                                                                    style="font-size: 10px;">Tổng khối
                                                                    lượng hàng
                                                                    (gam)</label>
                                                                <input type="number" class="form-control"
                                                                    id="id_tongkhoiluong" min="100"
                                                                    name="total_weight" value="{{ old('total_weight') }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 p-2 section-goihang">

                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Dài
                                                                    (cm)</label>
                                                                <input type="number" class="form-control" id="length"
                                                                    name="length" value="{{ old('length', 10) }}"
                                                                    oninput="calculateVolume()">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 p-2 section-goihang">

                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Rộng
                                                                    (cm)</label>
                                                                <input type="number" class="form-control" id="width"
                                                                    name="width" value="{{ old('width', 10) }}"
                                                                    oninput="calculateVolume()">
                                                            </div>

                                                        </div>
                                                        <div class="col-md-3 p-2 section-goihang">

                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Cao
                                                                    (cm)</label>
                                                                <input type="number" class="form-control" id="height"
                                                                    name="height" value="{{ old('height', 10) }}"
                                                                    oninput="calculateVolume()">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">

                                                        <strong class="section-klqd" style="font-size: 14px;">Khối lượng
                                                            quy đổi (kích thước): <span
                                                                class="khoiluongquydoi"></span></strong>
                                                        <input type="hidden" id="id_khoiluongquydoi">
                                                        <br>
                                                        <strong class="section-kltc"
                                                            style="font-size: 14px;color: red">Khối lượng tính
                                                            cước: <span class="khoiluongCuoc"></span>
                                                        </strong>
                                                        <input type="hidden" name="weight_order" id="id_khoiluongcuoc">
                                                    </div>
                                                </div>


                                                <div class="col-md-12 mt-4" id="note-section">
                                                    <h4 style="color: #F26522"> Lưu ý - Ghi chú</h4>


                                                    <div class="col-md-12 col-12 d-flex mobile-note">
                                                        <div class="col-md-6 p-3 section-note">
                                                            <div class="mb-3">
                                                                <label class="form-label">Thu hộ </label>
                                                                <input type="text" name="cod_amount"
                                                                    class="form-control"
                                                                    oninput="formatCurrency(this); resetCheckbox()"
                                                                    onblur="handleCodAmountBlur()" id="cod_amount"
                                                                    onkeydown="exitInput(event, this)"
                                                                    value="{{ old('cod_amount', 0) }}">

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
                                                                    oninput="formatCurrency(this)" name="insurance_value"
                                                                    id="insurance_value"
                                                                    onkeydown="exitInput(event, this)"
                                                                    value="{{ old('insurance_value', 0) }}">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Thu tiền khi giao hàng thất
                                                                    bại</label>

                                                                <input type="text" class="form-control"
                                                                    id="cod_failed_amount" oninput="formatCurrency(this)"
                                                                    name="cod_failed_amount"
                                                                    onkeydown="exitInput(event, this)"
                                                                    value="{{ old('cod_failed_amount', 0) }}">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Mã Đơn hàng <sup>Mã shop tự
                                                                        tạo
                                                                        (không
                                                                        bắt buộc)</sup></label>
                                                                <input type="text" class="form-control"
                                                                    value=" {{ old('order_code_custom') }}"
                                                                    onkeydown="exitInput(event, this)"
                                                                    name="order_code_custom">
                                                            </div>

                                                        </div>
                                                        <div class="col-md-6 p-3 section-note">

                                                            <div class="mb-3">
                                                                <label class="form-label">Lưu ý giao hàng</label>
                                                                <div class="col-md-12 ">
                                                                    <select name="required_note" id="required_note_select"
                                                                        class="form-control">
                                                                        <option value=""></option>
                                                                        <option value="KHONGCHOXEMHANG">
                                                                            Không cho xem
                                                                            hàng
                                                                        </option>
                                                                        <option value="CHOXEMHANGKHONGTHU">
                                                                            Cho xem hàng
                                                                            -
                                                                            không
                                                                            thử
                                                                        </option>
                                                                        <option value="CHOTHUHANG">
                                                                            Cho thử hàng
                                                                        </option>
                                                                    </select>
                                                                </div>

                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Tuỳ chọn thanh toán</label>
                                                                <div class="col-md-12">
                                                                    <select id="payment_method_select"
                                                                        name="payment_method" class="form-control">
                                                                        {{-- <option value="">-chọn-</option> --}}
                                                                        <option value="1">
                                                                            Bên gửi trả phí
                                                                        </option>
                                                                        {{-- <option value="2">
                                                                            Bên nhận trả phí
                                                                        </option> --}}
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Đơn vị vận
                                                                    chuyển</label>
                                                                <select name="" id="transport_unit_select"
                                                                    class="form-control">
                                                                    {{-- <option value="" selected></option> --}}
                                                                    <option value="1">
                                                                        Giao hàng nhanh</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Ghi chú</label>
                                                                <textarea id="note_select" rows="4" type="number" class="form-control" name="note"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-12 col-lg-4 p-2 col-12 section-mobile">
                                        <div class="sticky-sidebar">
                                            <div class="card total-card">
                                                <div class="card-body">

                                                    <div class="row">
                                                        <h4 style="color: #F26522"> Tổng cộng</h4>

                                                        <div class="col-md-12 table-responsive">

                                                            <table class="table table-bordered">

                                                                <tbody>
                                                                    <tr>
                                                                        <th scope="row">Vận chuyển:</th>
                                                                        <td id="service-fee">0 đ</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row">Khai giá:</th>
                                                                        <td id="insurance-fee">0 đ</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row" style="color:red">Phí chênh
                                                                            lệch:</th>
                                                                        <td id="fee_shopId" style="color:red">0 đ</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row">Tổng phí:</th>
                                                                        <td id="total-fee">0 đ</td>

                                                                    </tr>

                                                                </tbody>
                                                            </table>


                                                        </div>
                                                    </div>
                                                    <div class="text-center mt-2">
                                                        <button type="submit" class="btn btn-primary ">Tạo đơn
                                                            hàng</button>
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
        @endsection


        @section('script')
            <script>
                //ngăn chặn double click submit form
                let isSubmitting = false;

                $(document).ready(function() {
                    $('#myForm').on('submit', function(e) {
                        if (isSubmitting) {
                            e.preventDefault();
                            return false;
                        }

                        isSubmitting = true;
                        $('button[type="submit"]').prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');
                    });
                });
            </script>


            <script>
                $(function() {
                    // Chặn nhập chữ, chỉ cho nhập số
                    $('input[name="to_phone"]').on('keypress', function(e) {
                        // Chỉ cho phép phím số (0-9), phím điều hướng, phím xóa
                        if (e.which < 48 || e.which > 57) {
                            if (e.which !== 8 && e.which !== 0) { // 8: backspace, 0: các phím điều hướng
                                e.preventDefault();
                            }
                        }
                    });

                    // Format khi blur, delay 0.5s
                    $('input[name="to_phone"]').on('blur paste', function() {
                        let input = $(this);
                        setTimeout(function() {
                            let v = input.val().replace(/\s+/g, '').replace(/\D/g, '');
                            if (v.startsWith('84')) v = '0' + v.slice(2);
                            if (v.startsWith('+84')) v = '0' + v.slice(3);
                            input.val(v);
                        }, 500);
                    });
                });
            </script>

            <script>
                $('.select2').select2({
                    theme: 'bootstrap4'
                });
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

                let codDefaul = $('#insurance_value').val();;

                $(document).on('input change',
                    'input[name="weight_item[]"], input[name="quantity[]"], #insurance_value, #length, #width, #height',
                    function() {
                        blockbuttonSumshiptocod();
                    });

                $(document).on('click', '.add-row, .remove-row, .product-name-select',
                    function() {
                        blockbuttonSumshiptocod();
                    });

                $('#cod_amount').on('blur', function() {
                    let value = $(this).val();
                    $('#insurance_value').val(value);

                    let codDefaul = $('#insurance_value').val();;

                    setTimeout(() => {
                        if ($('#insurance_value').val() &&
                            $('#from_district_id').val() &&
                            $('#from_ward_code').val() &&
                            $('#to_district_id').val() &&
                            $('#to_ward_code').val()) {
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

                function calculateFee() {
                    if (
                        !$('input[name="insurance_value"]').val() ||
                        !$('#from_district_id').val() ||
                        !$('#from_ward_code').val() ||
                        !$('#to_district_id').val() ||
                        !$('#to_ward_code').val()) {
                        showError('Vui lòng điền đầy đủ thông tin để tính phí');
                        return;
                    }

                    const weightOrder = $('input[name="weight_order"]').val() || 200;

                    $.ajax({
                        url: '/user/calculate-fee',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            from_district_id: $('#from_district_id').val(),
                            from_ward_code: $('#from_ward_code').val(),
                            to_district_id: $('#to_district_id').val(),
                            to_ward_code: $('#to_ward_code').val(),
                            height: $('input[name="height"]').val() || 10,
                            length: $('input[name="length"]').val() || 10,
                            width: $('input[name="width"]').val() || 10,
                            quantity: $('#quantity').val(),
                            pickup_area_hidden: $('#pickup_area').val() || '',
                            weight_order: weightOrder,
                            insurance_value: Number($('input[name="insurance_value"]').val().replace(/[,.]/g,
                                '')) || 0
                        },
                        success: function(response) {
                            if (response.success) {

                                const paymentMethod = $('#payment_method_select').val();
                                const feeWarningContainer = $('#fee_warning_container');
                                const buttonSumshiptocod = $('#button_sumshiptocod');

                                buttonSumshiptocod.prop('disabled', true);

                                setTimeout(function() {
                                    buttonSumshiptocod.prop('disabled', false);
                                }, 1500); // 1000ms = 1 giây

                                if (paymentMethod == 1) {
                                    feeWarningContainer.hide();
                                    $('#service-fee').text(formatMoney(response.service_fee) +
                                        ' đ');
                                    $('#insurance-fee').text(formatMoney(response.insurance_fee) + ' đ');
                                    $('#fee_shopId').text(formatMoney(response.fee_warning) + ' đ');
                                    $('#total-fee').text(formatMoney(response.service_fee + response
                                            .insurance_fee +
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
                    $('input[name="to_district_id"], input[name="to_ward_code"], input[name="insurance_value"]').on(
                        'change', calculateFee);
                });

                function formatMoney(amount) {
                    return new Intl.NumberFormat('vi-VN').format(amount);
                }
            </script>

            {{-- Mất focus khi nhấn Enter, Ngăn form submit nếu có --}}
            <script>
                function exitInput(event, element) {
                    if (event.key === "Enter") {
                        element.blur();
                        event.preventDefault();
                    }
                }
            </script>

            {{-- tu dong lay gia tri store return tra ve --}}
            <script>
                $(document).ready(function() {
                    $("#address_return").change(function() {
                        var selectedOption = $(this).find("option:selected");

                        var name = selectedOption.data("name") || "";
                        var phone = selectedOption.data("phone") || "";
                        var street = selectedOption.data("street") || "";
                        var district_id = selectedOption.data("district_id") || "";
                        var ward_code = selectedOption.data("ward_code") || "";

                        $("#return_name").val(name);
                        $("#return_phone").val(phone);
                        $("#return_address").val(street);
                        $("#return_district_id").val(district_id);
                        $("#return_ward_code").val(ward_code);

                    });
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
                            codeInput.attr('value', sku);
                        } else {
                            codeInput.val('');
                            codeInput.attr('value', '');
                        }
                        updateTotalWeight();
                    });
                });

                // Đảm bảo hàm updateTotalWeight được gọi cho mọi thay đổi
                let weightInputTimer;

                // $(document).on('blur', 'input[name="weight_item[]"], input[name="quantity[]"]', function() {
                //         updateTotalWeight();
                // });

                function updateTotalWeight() {

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

                    let convertedWeight = (length * width * height) / 5;
                    document.querySelector('.khoiluongquydoi').textContent = convertedWeight.toLocaleString('vi-VN') +
                        ' g';
                    document.getElementById('id_khoiluongquydoi').value = convertedWeight.toFixed(0);

                    let totalWeight = parseInt(document.getElementById('id_tongkhoiluong').value) ||
                        0;
                    let chargeableWeight = Math.max(totalWeight, convertedWeight) || 200;
                    document.querySelector('.khoiluongCuoc').textContent = chargeableWeight.toLocaleString('vi-VN') +
                        ' g';
                    document.querySelector('input[name="weight_order"]').value = chargeableWeight || 200;

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

                //khi 3 giá tri kích thước thay đổi thì cap nhat lại
                // $(document).on('input', 'input[name="length"], input[name="width"], input[name="height"]',
                //     function() {
                //         calculateVolume();
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
                        }
                    }
                });

                updateTotalWeight();
                // calculateVolume();
            </script>

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
                        $('#from_id').val(selectedOption.data('id'));
                        $('#from_name').val(selectedOption.data('name'));
                        $('#from_phone').val(selectedOption.data('phone'));
                        $('#return_name').val(selectedOption.data('name'));
                        $('#return_phone').val(selectedOption.data('phone'));
                        $('#street_name').val(selectedOption.data('street'));
                        $('#return_address').val(selectedOption.data('street'));
                        $('#return_district_id').val(selectedOption.data('district_id'));
                        $('#return_ward_code').val(selectedOption.data('ward_code'));
                        $('#ward_name_store').val(selectedOption.data('ward'));
                        $('#district_name_store').val(selectedOption.data('district'));
                        $('#province_name_store').val(selectedOption.data('province'));
                        $('#require_note').val(selectedOption.data('require_note'));
                        $('#payment_type').val(selectedOption.data('payment_type'));
                        $('#note_select').val(selectedOption.data('note'));
                        $('#pickup_area').val(selectedOption.data('pickup_area'));
                        $('#transport_unit').val(selectedOption.data('transport_unit'));
                        $('#from_district_id').val(selectedOption.data('district_id'));
                        $('#from_ward_code').val(selectedOption.data('ward_code'));
                        $('#money_fail').val(selectedOption.data('money_fail'));
                    }

                    manualButton.addEventListener("click", function() {
                        manualForm.classList.toggle("d-none");
                        warehouseSelect.classList.toggle("d-none");
                    });

                    //gan gia tri data cua hang vao input hidden
                    function updateHiddenFields() {
                        let fromId = document.getElementById("from_id");
                        let fromName = document.getElementById("from_name");
                        let fromPhone = document.getElementById("from_phone");
                        let fromAddress = document.getElementById("street_name");
                        let fromWardId = document.getElementById("from_ward_code");
                        let fromWard = document.getElementById("ward_name_store");
                        let fromDistrictId = document.getElementById("from_district_id");
                        let fromDistrict = document.getElementById("district_name_store");
                        let fromProvince = document.getElementById("province_name_store");
                        let returnPhone = document.getElementById("return_phone");
                        let returnName = document.getElementById("return_name");
                        let returnAddress = document.getElementById("return_address");
                        let returnDistrictId = document.getElementById("return_district_id");
                        let returnWardcode = document.getElementById("return_ward_code");
                        let requireNote = document.getElementById("require_note");
                        let paymentType = document.getElementById("payment_type");
                        let note = document.getElementById("note");
                        let pickupArea = document.getElementById("pickup_area");
                        let money_fail = document.getElementById("money_fail");
                        let transportUnit = document.getElementById("transport_unit");

                        if (manualForm.classList.contains("d-none")) {
                            // Lấy giá trị từ select
                            let selectedOption = warehouseSelect.options[warehouseSelect.selectedIndex];
                            fromId.value = selectedOption.getAttribute("data-id") || "";
                            fromName.value = selectedOption.getAttribute("data-name") || "";
                            fromPhone.value = selectedOption.getAttribute("data-phone") || "";
                            fromAddress.value = selectedOption.getAttribute("data-street") || "";
                            fromWard.value = selectedOption.getAttribute("data-ward") || "";
                            fromDistrict.value = selectedOption.getAttribute("data-district") || "";
                            fromProvince.value = selectedOption.getAttribute("data-province") || "";
                            fromWardId.value = selectedOption.getAttribute("data-ward_code" || "");
                            fromDistrictId.value = selectedOption.getAttribute("data-district_id" || "");
                            returnPhone.value = selectedOption.getAttribute("data-phone") || "";
                            returnName.value = selectedOption.getAttribute("data-name") || "";
                            returnAddress.value = selectedOption.getAttribute("data-street") || "";
                            returnDistrictId.value = selectedOption.getAttribute("data-district_id" || "");
                            returnWardcode.value = selectedOption.getAttribute("data-ward_code" || "");
                            requireNote.value = selectedOption.getAttribute("data-require_note") || "";
                            paymentType.value = selectedOption.getAttribute("data-payment_type") || "";
                            note.value = selectedOption.getAttribute("data-note") || "";
                            money_fail.value = selectedOption.getAttribute("data-money_fail") || "";
                            pickupArea.value = selectedOption.getAttribute("data-pickup_area") || "";
                            transportUnit.value = selectedOption.getAttribute("data-transport_unit") || "";
                        }
                    }

                    // Cập nhật khi select cua hang khac
                    warehouseSelect.addEventListener("change", function() {

                        updateHiddenFields();

                        // Thêm phần cập nhật require_note khi thay đổi address cua hang
                        let selectedOption = $(this).find('option:selected');

                        let requireNoteValue = selectedOption.data('require_note');
                        let paymentMethodValue = selectedOption.data('payment_type');
                        let transportUnitValue = selectedOption.data('transport_unit');
                        let noteValue = selectedOption.data('note');
                        let moneyfailValue = selectedOption.data('money_fail');
                        let pickupAreaValue = selectedOption.data('pickup_area');

                        // khi select thay doi thi cap nhat
                        let selectElement = document.getElementById("required_note_select");
                        let selectElement2 = document.getElementById("payment_method_select");
                        let selectElement3 = document.getElementById("transport_unit_select");
                        let selectElement4 = document.getElementById("note_select");
                        let selectElement5 = document.getElementById("cod_failed_amount");
                        let selectElement6 = document.getElementById("pickup_area");

                        if (selectElement && requireNoteValue) {
                            selectElement.value = requireNoteValue;
                        }

                        if (selectElement3 && transportUnitValue) {
                            selectElement3.value = transportUnitValue;
                        }

                        if (selectElement4 && noteValue) {
                            selectElement4.value = noteValue;
                        }

                        if (selectElement5 && moneyfailValue) {
                            selectElement5.value = moneyfailValue;
                        }

                        if (selectElement6 && pickupAreaValue) {
                            selectElement6.value = pickupAreaValue;
                        }

                        //khi select warehouse thi se auto gan vao address return warehouse
                        let selectedValue = this.value; // lấy value bên warehouse
                        document.getElementById('address_return').value = selectedValue;

                    });

                    // Cập nhật khi rời khỏi input nhập tay
                    document.querySelectorAll("#manual-form input").forEach(input => {
                        input.addEventListener("blur", updateHiddenFields);
                    });


                    //gan gia tri require note
                    let selectElement = document.getElementById("required_note_select");
                    let selectElement2 = document.getElementById("payment_method_select");
                    let selectElement3 = document.getElementById("transport_unit_select");
                    let selectElement4 = document.getElementById("note_select");
                    let selectElement5 = document.getElementById("cod_failed_amount");
                    let selectElement6 = document.getElementById("pickup_area");

                    if (selectElement) {
                        let optionToSelect = selectElement.querySelector(
                            `option[value="${selectedOption.data('require_note')}"]`);
                        if (optionToSelect) {
                            optionToSelect.selected = true;
                        }
                    }

                    if (selectElement2) {
                        let optionToSelect = selectElement2.querySelector(
                            `option[value="${selectedOption.data('payment_type')}"]`);
                        if (optionToSelect) {
                            optionToSelect.selected = true;
                        }
                    }

                    if (selectElement3) {
                        let optionToSelect = selectElement3.querySelector(
                            `option[value="${selectedOption.data('transport_unit')}"]`);
                        if (optionToSelect) {
                            optionToSelect.selected = true;
                        }
                    }

                    if (selectElement4) {
                        selectElement4.value = selectedOption.data('note') || '';
                    }

                    if (selectElement5.value == 0) {
                        selectElement5.value = selectedOption.data('money_fail');
                    }

                    if (selectElement6.value == 0) {
                        selectElement5.value = selectedOption.data('pickup_area');
                    }
                });
            </script>


            {{-- lay thong tin api tinh thanh quan huyen xa --}}
            <script>
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
                        $('#to_district_id').val(district_id);

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

                                // Reset ward khi thay đổi district
                                // $('#to_ward_code').val('');
                                // calculateFee();
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

                        // Tính phí khi thay đổi ward
                        calculateFee();
                    });
                });
            </script>

            {{-- laythong tin api tinh thanh quan huyen xa clone 1 --}}
            <script>
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
                            $("#province_id_1").html(html);
                        } else {
                            alert(response.message)
                        }
                    });

                    //province
                    $(".province_1 ").change(function() {
                        var province_id_1 = parseInt($(this).val());
                        var text = $(".province_1 option:selected").text();
                        $('.province_name_1').val(text)

                        var settings = {
                            "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/district",
                            "method": "GET",
                            "timeout": 0,
                            "headers": {
                                "token": "{{ config('constants.ghn_api_token') }}",
                                "Content-Type": "application/json"
                            },
                            "data": {
                                "province_id": province_id_1
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
                                $('.district_1').html(html)

                            } else {
                                alert(response.message)
                            }
                        });
                    });

                    //district
                    $(".district_1").change(function() {
                        var district_id_1 = parseInt($(this).val());
                        var text = $(".district_1 option:selected").text();
                        $('.district_name_1').val(text)

                        var settings = {
                            "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/ward",
                            "method": "GET",
                            "timeout": 0,
                            "headers": {
                                "token": "{{ config('constants.ghn_api_token') }}",
                                "Content-Type": "application/json"
                            },
                            "data": {
                                "district_id": district_id_1
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
                                $('.ward_1').html(html)
                            } else {
                                alert(response.message)
                            }
                        });
                    });

                    $(".ward_1").change(function() {
                        var text = $(".ward_1 option:selected").text();
                        $('.ward_name_1').val(text)
                    });
                });
            </script>

            {{-- dinh sang so sang kieu du lieu tien te vnd --}}
            <script>
                function formatCurrency(input) {
                    let value = input.value.replace(/\D/g, ''); // Chỉ giữ lại số
                    if (value === '') {
                        input.value = 0; // Khi xóa hết, input về 0
                    } else {
                        input.value = new Intl.NumberFormat('vi-VN').format(value);
                    }
                }
            </script>

            {{-- check user theo sdt va lay ra cac gia tri  --}}
            <script>
                document.querySelector('input[name="to_phone"]').addEventListener('blur', function() {
                    let phone = this.value.trim();
                    if (phone !== '') {
                        fetch(`/user/get-user-info?phone=${phone}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Set các giá trị cơ bản
                                    document.querySelector('input[name="to_name"]').value = data.user.name || '';
                                    document.querySelector('input[name="to_address"]').value = data.user.address || '';
                                    document.querySelector('input[name="ward_name"]').value = data.user.ward_name || '';
                                    document.querySelector('input[name="district_name"]').value = data.user
                                        .district_name || '';

                                    let provinceSelect = document.querySelector('select[name="province_id"]');
                                    let districtSelect = document.querySelector('select[name="district_id"]');
                                    let wardSelect = document.querySelector('select[name="ward_id"]');

                                    // Set province và trigger change
                                    if (data.user.province_id) {
                                        provinceSelect.value = data.user.province_id;
                                        $(provinceSelect).trigger('change');

                                        // Đợi district load xong
                                        setTimeout(() => {
                                            if (data.user.district_id) {
                                                districtSelect.value = data.user.district_id;
                                                $(districtSelect).trigger('change');

                                                // Đợi ward load xong
                                                setTimeout(() => {
                                                    if (data.user.ward_id) {
                                                        wardSelect.value = data.user.ward_id;
                                                        $(wardSelect).trigger('change');
                                                    }
                                                }, 500);
                                            }
                                        }, 500);
                                    }

                                    // Set các giá trị hidden
                                    if (data.user.district_id) {
                                        $('#to_district_id').val(data.user.district_id);
                                    }
                                    if (data.user.ward_id) {
                                        $('#to_ward_code').val(data.user.ward_id);
                                    }

                                    // Tính phí sau khi đã set xong tất cả
                                    setTimeout(() => {
                                        calculateFee();
                                    }, 1000);
                                }
                            })
                            .catch(error => console.error("Lỗi API:", error));
                    }
                });
            </script>


            {{-- ngan chan ko cho submit lung tung  --}}
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

            {{-- phan chia vung div hien thi va khoa section --}}
            <script>
                $(document).ready(function() {
                    // Khởi tạo - mặc định khóa các section
                    function initializeSections() {
                        // Khóa section sản phẩm
                        $('#product-section input:not([name="code[]"]), #product-section select, #product-section button, #product-section a')
                            .prop('disabled', true);
                        // Khóa section ghi chú
                        $('#note-section input, #note-section select, #note-section textarea').prop('disabled', true);
                        // Khóa nút submit
                        $('button[type="submit"]').prop('disabled', true);
                    }

                    // Gọi hàm khởi tạo khi trang load
                    initializeSections();

                    // Theo dõi input số điện thoại bên nhận
                    $('input[name="to_phone"]').on('change input', function() {
                        let phone = $(this).val().trim();
                        if (phone !== '') {
                            // Mở section sản phẩm
                            $('#product-section input:not([name="code[]"]):not([readonly]), #product-section select, #product-section button, #product-section a')
                                .prop('disabled', false);
                        } else {
                            // Khóa section sản phẩm nếu xóa số điện thoại
                            $('#product-section input:not([name="code[]"]), #product-section select, #product-section button, #product-section a')
                                .prop('disabled', true);
                            // Khóa section ghi chú
                            $('#note-section input, #note-section select, #note-section textarea').prop('disabled',
                                true);
                            // Khóa nút submit
                            $('button[type="submit"]').prop('disabled', true);
                        }
                    });

                    // Theo dõi khi chọn sản phẩm
                    $(document).on('change', '.product-name-select', function() {
                        let productName = $(this).val();
                        if (productName) {
                            // Mở section ghi chú và nút tạo đơn
                            $('#note-section input, #note-section select, #note-section textarea').prop('disabled',
                                false);
                            $('button[type="submit"]').prop('disabled', false);
                        }
                    });
                });
            </script>

    </div>
    </section>
    </div>
@endsection
