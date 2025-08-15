@extends('frontend.layouts.main')

@push('title')
    Xem Đơn hàng
@endpush

@section('content')
    <div class="container">

        <section class="mt-4">

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
                    top: 20px;
                    height: fit-content;
                }

                /* Thêm style cho card để đảm bảo hiển thị đẹp */
                .total-card {
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }
            </style>

            <div class="row">

                <div class="row page-titles">
                    <h2>Xem đơn hàng</h2>
                </div>

                <div class="col-md-12 mt-2">
                    <div class=" card-outline-info">
                        <div>



                                <div class="col-md-12 d-flex">

                                    <div class="col-md-9 p-2">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class="col-md-12" id="sender-section">

                                                    <h2 style="color: #F26522">
                                                        Bên Gửi
                                                    </h2>


                                                    <div class="col-md-12">

                                                        <div class="col-md-12 p-3">
                                                            <label for="">Kho hàng</label>

                                                            <input type="text" class="form-control mt-2"
                                                                value="{{ $order->fullname }} - {{ $order->phone }} - {{ $order->address }} - {{ $order->ward_name }} - {{ $order->district_name }} - {{ $order->province_name }}"
                                                                readonly>

                                                        </div>

                                                        <div class="col-md-12 p-3">
                                                            <label for="">Địa chỉ hoàn trả hàng</label>
                                                            <select name="return_address_id" id="address_return"
                                                                class="form-control">
                                                                <option value="" selected></option>

                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-4" id="receiver-section">
                                                    <h2 class="font-weight-bold" style="color: #F26522">
                                                        Bên Nhận </h2>

                                                    <div class="col-md-12 d-flex">
                                                        <div class="col-md-6 p-3">

                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Số điện
                                                                    thoại</label>
                                                                <input type="text" class="form-control" id=""
                                                                    name="to_phone" value="{{ $order->to_phone }}" readonly>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Họ
                                                                    tên</label>
                                                                <input type="text" class="form-control" id=""
                                                                    name="to_name" value="{{ $order->to_name }}" readonly>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-6 p-3">

                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Địa
                                                                    chỉ</label>
                                                                <input type="text" class="form-control" id=""
                                                                    name="to_address" value="{{ $order->to_address }}"
                                                                    readonly>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="" class="form-label l-label">Tỉnh
                                                                    thành</label>
                                                                {{-- <select name="province_id" id="province_id"
                                                                    class="form-control province">
                                                                    <option value=""></option>
                                                                </select> --}}
                                                                <input type="text"
                                                                    class="form-control form-control-line province_name"
                                                                    value=" {{ $order->province_name }} "
                                                                    name="province_name">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="" class="form-label l-label">Quận
                                                                    huyện</label>
                                                                {{-- <select name="district_id" id="district_id"
                                                                    class="form-control district">
                                                                    <option
                                                                        value="{{ isset($data) ? $data->district_id : '' }}">
                                                                        {{ isset($data) ? $data->district_name : '' }}
                                                                    </option>
                                                                </select> --}}
                                                                <input type="text"
                                                                    class="form-control form-control-line district_name"
                                                                    value="{{ $order->district_name }}"
                                                                    name="district_name">
                                                                {{-- <input type="hidden" name="to_district_id"
                                                                    id="to_district_id"> --}}

                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="" class="form-label l-label">Xã
                                                                    phường</label>
                                                                {{-- <select name="ward_id" id="ward_id"
                                                                    class="form-control ward">
                                                                    <option
                                                                        value="{{ isset($data) ? $data->ward_id : '' }}">
                                                                        {{ isset($data) ? $data->ward_name : '' }}
                                                                    </option>
                                                                </select> --}}
                                                                <input type="text"
                                                                    class="form-control form-control-line ward_name"
                                                                    value="{{ $order->ward_name }}" name="ward_name">
                                                                {{-- <input type="hidden" name="to_ward_code"
                                                                    id="to_ward_code"> --}}

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

                                                            <div class="col-md-12 d-flex product-row mt-2">
                                                                <div class="col-md-4 position-relative">

                                                                    <select class="form-control product-name-select"
                                                                        name="name[]">
                                                                        <option value=""> Chọn </option>
                                                                        @foreach ($product as $item)
                                                                            <option value="{{ $item->name }}"
                                                                                data-sku="{{ $item->product_code }}"
                                                                                data-weight="{{ $item->amount }}">
                                                                                {{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-2 mx-2">
                                                                    <input type="text" class="form-control"
                                                                        placeholder="SKU" name="code[]" value=""
                                                                        readonly>
                                                                </div>

                                                                <div class="col-md-2 mx-2">
                                                                    <input type="number" class="form-control"
                                                                        name="weight_item[]" placeholder="(gam)"
                                                                        value="{{ old('weight_item', '') }}">
                                                                </div>

                                                                <div class="col-md-1 mx-2">
                                                                    <input type="number" name="quantity[]"
                                                                        class="form-control" placeholder="SL"
                                                                        value="{{ old('quantity', 1) }}">
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <a href="#"
                                                                        class="btn btn-sm btn-warning add-row">Thêm</a>
                                                                    <a href="#"
                                                                        class="btn btn-sm btn-danger remove-row"><i
                                                                            class='bx bx-trash'></i></a>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 my-4">
                                                        <h4 style="color: #F26522">Thông tin gói hàng</h4>
                                                    </div>

                                                    <div class="col-md-12 d-flex">


                                                        <div class="col-md-3 p-2">

                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Tổng khối
                                                                    lượng
                                                                    (gram)</label>
                                                                <input type="number" class="form-control"
                                                                    id="id_tongkhoiluong" min="100" name=""
                                                                    value="" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 p-2">

                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Dài
                                                                    (cm)</label>
                                                                <input type="number" class="form-control" id=""
                                                                    name="length" value="{{ old('length', 10) }}"
                                                                    oninput="calculateVolume()">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 p-2">

                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Rộng
                                                                    (cm)</label>
                                                                <input type="number" class="form-control" id=""
                                                                    name="width" value="{{ old('width', 10) }}"
                                                                    oninput="calculateVolume()">
                                                            </div>

                                                        </div>
                                                        <div class="col-md-3 p-2">

                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Cao
                                                                    (cm)</label>
                                                                <input type="number" class="form-control" id=""
                                                                    name="height" value="{{ old('height', 10) }}"
                                                                    oninput="calculateVolume()">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">

                                                        <strong>Khối lượng quy đổi: <span
                                                                class="khoiluongquydoi"></span></strong>
                                                        <input type="hidden" id="id_khoiluongquydoi">
                                                        <br>
                                                        <strong style="color: red">Khối lượng tính cước: <span
                                                                class="khoiluongCuoc"></span>
                                                        </strong>
                                                        <input type="hidden" name="weight_order" id="id_khoiluongcuoc">
                                                    </div>
                                                </div>


                                                <div class="col-md-12 mt-4" id="note-section">
                                                    <h4 style="color: #F26522"> Lưu ý - Ghi chú</h4>


                                                    <div class="col-md-12 d-flex">
                                                        <div class="col-md-6 p-3">
                                                            <div class="mb-3">
                                                                <label class="form-label">Thu hộ</label>
                                                                <input type="text" name="cod_amount"
                                                                    class="form-control" oninput="formatCurrency(this)"
                                                                    id="cod_amount" onkeydown="exitInput(event, this)"
                                                                    value="{{ number_format(floatval(str_replace(',', '', $order->cod_amount)), 0, ',', '.') }}"
                                                                    readonly>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Giá trị đơn hàng</label>

                                                                <input type="text" class="form-control"
                                                                    oninput="formatCurrency(this)" name="insurance_value"
                                                                    id="insurance_value"
                                                                    onkeydown="exitInput(event, this)"
                                                                    value="{{ number_format(floatval(str_replace(',', '', $order->insurance_value)), 0, ',', '.') }}"
                                                                    readonly>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Thu tiền khi giao hàng thất
                                                                    bại</label>

                                                                <input type="text" class="form-control"
                                                                    oninput="formatCurrency(this)"
                                                                    name="cod_failed_amount"
                                                                    onkeydown="exitInput(event, this)"
                                                                    value="{{ number_format($order->cod_failed_amount, 0, ',', '.') }}"
                                                                    readonly>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Mã Đơn hàng <sup>Mã shop tự
                                                                        tạo
                                                                        (không
                                                                        bắt buộc)</sup></label>
                                                                <input type="text" class="form-control" value=""
                                                                    onkeydown="exitInput(event, this)"
                                                                    name="order_code_custom"
                                                                    value="{{ $order->order_code_custom }}" readonly>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-6 p-3">

                                                            <div class="mb-3">
                                                                <label class="form-label">Lưu ý giao hàng</label>
                                                                <div class="col-md-12">
                                                                    <select name="required_note" id="required_note_select"
                                                                        class="form-control">
                                                                        <option value=""></option>
                                                                        <option value="KHONGCHOXEMHANG"
                                                                            {{ $order->required_note == 'KHONGCHOXEMHANG' ? 'selected' : '' }}>
                                                                            Không cho xem
                                                                            hàng
                                                                        </option>
                                                                        <option value="CHOXEMHANGKHONGTHU"
                                                                            {{ $order->required_note == 'CHOXEMHANGKHONGTHU' ? 'selected' : '' }}>
                                                                            Cho xem hàng
                                                                            -
                                                                            không
                                                                            thử
                                                                        </option>
                                                                        <option value="CHOTHUHANG"
                                                                            {{ $order->required_note == 'CHOTHUHANG' ? 'selected' : '' }}>
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
                                                                        <option value="" selected></option>
                                                                        <option value="2"
                                                                            {{ $order->payment_method == '2' ? 'selected' : '' }}>
                                                                            Người nhận trả phí
                                                                        </option>
                                                                        <option value="1"
                                                                            {{ $order->payment_method == '1' ? 'selected' : '' }}>
                                                                            Người bán trả phí
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="" class="form-label">Đơn vị vận
                                                                    chuyển</label>
                                                                <select name="" id="transport_unit_select"
                                                                    class="form-control">
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

                                    <div class="col-md-3 p-2">
                                        <div class="sticky-sidebar">
                                            <div class="card total-card">
                                                <div class="card-body">

                                                    <div class="row">
                                                        <h4 style="color: #F26522"> Tổng cộng</h4>

                                                        <div class="col-md-12">

                                                            <table class="table table-bordered">

                                                                @php
                                                                    $fee_cost = $fee->cost ?? 0;
                                                                @endphp
                                                                <tbody>
                                                                    <tr>
                                                                        <th scope="row">Vận chuyển:</th>
                                                                        <td id="service-fee">
                                                                            {{ number_format((int) $order->main_service + ((int) $fee_cost), 0, ',', '.') }}
                                                                            đ</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row">Khai giá:</th>
                                                                        <td id="insurance-fee">
                                                                            {{ number_format((int) $order->insurance_fee, 0, ',', '.') }}
                                                                            đ</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row">Tổng phí:</th>
                                                                        <td id="total-fee">
                                                                            {{ number_format((int) $order->main_service + (int) $order->insurance_fee + (int) $fee_cost, 0, ',', '.') }}
                                                                            đ</td>

                                                                    </tr>

                                                                </tbody>
                                                            </table>

                                                            <div class="text-center mt-4">
                                                                <a href="{{ route('user.order.edit', $order->id) }}"
                                                                    class="btn btn-primary ">Sửa đơn hàng</a>

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
                </div>

            </div>
        @endsection


        @section('script')


            <script>
                $('#cod_amount').on('blur', function() {
                    let value = $(this).val();
                    $('#insurance_value').val(value);

                    // Đảm bảo giá trị được cập nhật vào insurance_value
                    setTimeout(() => {
                        if ($('#insurance_value').val() &&
                            $('#from_district_id').val() &&
                            $('#from_ward_code').val() &&
                            $('#to_district_id').val() &&
                            $('#to_ward_code').val()) {
                            calculateFee();
                        }
                    }, 100);
                });

                $('input[name="weight_order"]').on('change', function() {
                    calculateFee();
                });

                function calculateFee() {
                    if (!$('input[name="insurance_value"]').val() ||
                        !$('#from_district_id').val() ||
                        !$('#from_ward_code').val() ||
                        !$('#to_district_id').val() ||
                        !$('#to_ward_code').val()) {
                        showError('Vui lòng điền đầy đủ thông tin để tính phí');
                        return;
                    }

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
                            weight_order: $('input[name="weight_order"]').val() || 200,
                            insurance_value: Number($('input[name="insurance_value"]').val().replace(/[,.]/g, '')) || 0
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#service-fee').text(formatMoney(response.service_fee) + ' đ');
                                $('#insurance-fee').text(formatMoney(response.insurance_fee) + ' đ');
                                $('#total-fee').text(formatMoney(response.service_fee + response.insurance_fee) + ' đ');
                            }
                        },
                        error: function(xhr) {
                            console.log('Lỗi tính phí:', xhr);
                        }
                    });
                }

                $('#to_district_id, #to_ward_code, input[name="insurance_value"]').on('change', calculateFee);

                function formatMoney(amount) {
                    return new Intl.NumberFormat('vi-VN').format(amount);
                }

                // Gọi tính phí khi thay đổi giá trị insurance_value
                $('input[name="insurance_value"]').on('change', calculateFee);
            </script>

            <script>
                function exitInput(event, element) {
                    if (event.key === "Enter") {
                        element.blur(); // Mất focus khi nhấn Enter
                        event.preventDefault(); // Ngăn form submit nếu có
                    }
                }
            </script>

            {{-- tu dong lay gia tri store return tra ve --}}
            <script>
                $(document).ready(function() {
                    $("#address_return").change(function() {
                        var selectedOption = $(this).find("option:selected");

                        var phone = selectedOption.data("phone") || "";
                        var street = selectedOption.data("street") || "";

                        $("#return_phone").val(phone);
                        $("#return_address").val(street);

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
                            codeInput.attr('value', sku); // Thêm dòng này
                        } else {
                            codeInput.val('');
                            codeInput.attr('value', '');
                        }
                        updateTotalWeight();
                    });
                });

                // Đảm bảo hàm updateTotalWeight được gọi cho mọi thay đổi
                $(document).on('change input', 'input[name="weight_item[]"], input[name="quantity[]"]', function() {
                    updateTotalWeight();
                });

                function updateTotalWeight() {
                    let totalWeight = 0;

                    $('.product-row').each(function() {
                        let weight = parseFloat($(this).find('input[name="weight_item[]"]').val()) || 0;
                        let quantity = parseInt($(this).find('input[name="quantity[]"]').val()) || 1;
                        totalWeight += weight * quantity;
                    });

                    $('#id_tongkhoiluong').val(totalWeight);
                    calculateVolume();
                    if (totalWeight) {
                        calculateFee();
                    }

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
                document.querySelectorAll(
                        'input[name="length"], input[name="width"], input[name="height"]')
                    .forEach(input => {
                        input.addEventListener("input", calculateVolume);
                    });

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
                calculateVolume();
            </script>
            {{--
            <script>
                $(document).ready(function() {

                    // Giữ nguyên event handler hiện tại cho việc thay đổi địa chỉ hoàn trả
                    $('#address_return').change(function() {
                        let selected = $(this).find('option:selected');
                        if (selected.val()) {
                            $('#return_phone').val(selected.data('phone'));
                            $('#return_address').val(selected.data('street'));
                            $('#return_ward_code').val(selected.data('ward'));
                            $('#return_district_id').val(selected.data('district'));
                        } else {
                            // Clear các trường khi không chọn địa chỉ
                            $('#return_phone').val('');
                            $('#return_address').val('');
                            $('#return_ward_code').val('');
                            $('#return_district_id').val('');
                        }
                    });
                });
            </script> --}}



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

            {{-- nhan enter cho phép xuóng dòng --}}

            {{-- gia tri cod auto nhap vao gia tri don hang --}}
            <script>
                document.getElementById('cod_amount').addEventListener('blur', function() {
                    document.getElementById('insurance_value').value = this.value;
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
                        $('#require_note').val(selectedOption.data('require_note'));
                        $('#payment_type').val(selectedOption.data('payment_type'));
                        $('#note').val(selectedOption.data('note'));
                        $('#transport_unit').val(selectedOption.data('transport_unit'));
                        $('#from_district_id').val(selectedOption.data('district_id'));
                        $('#from_ward_code').val(selectedOption.data('ward_code'));
                    }


                    manualButton.addEventListener("click", function() {
                        manualForm.classList.toggle("d-none");
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
                        let requireNoteValue = selectedOption.data('require_note');
                        let paymentMethodValue = selectedOption.data('payment_type');
                        let transportUnitValue = selectedOption.data('transport_unit');
                        let noteValue = selectedOption.data('note');
                        // Cập nhật select require_note
                        let selectElement = document.getElementById("required_note_select");
                        let selectElement2 = document.getElementById("payment_method_select");
                        let selectElement3 = document.getElementById("transport_unit_select");
                        let selectElement4 = document.getElementById("note_select");
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
                    let selectElement = document.getElementById("required_note_select");
                    let selectElement2 = document.getElementById("payment_method_select");
                    let selectElement3 = document.getElementById("transport_unit_select");
                    let selectElement4 = document.getElementById("note_select");
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
                        let optionToSelect = selectElement4.querySelector(
                            `option[value="${selectedOption.data('note')}"]`);
                    }
                });
            </script>

            {{-- laythong tin api tinh thanh quan huyen xa --}}
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
                                                                // Kích hoạt sự kiện change để các listener khác có thể phản ứng
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
