@extends('frontend.layouts.main')

@push('title')
    Xem cửa hàng
@endpush

@section('content')

    <style>
        .l-label {
            color: #f26522;
            font-weight: bold;
        }
    </style>

    <div class="container">

        <div class="page-titles my-4">
            <h2>Xem cửa hàng</h2>
        </div>
        <div class="col-md-12">
            <form class="form-horizontal" action="{{ route('user.mystore.storestore') }}" method="post"
                enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-outline-info">
                            <div class="card-body">

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Tên shop</label>
                                    <input type="text" name="name" class="form-control" id="" placeholder=""
                                        value="{{ $address->name }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Số điện thoại</label>
                                    <input type="number" name="phone" class="form-control" id="" placeholder=""
                                        value="{{ $address->phone }}" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Tỉnh thành</label>
                                    <select name="province_id" id="province_id" class="form-control province" disabled>
                                        <option value="">Chọn tỉnh/thành</option>
                                    </select>
                                    <input type="hidden" class="form-control form-control-line province_name"
                                        value="{{ $address->province_name }}" name="province_name">
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Quận huyện</label>
                                    <select name="district_id" id="district_id" class="form-control district" disabled>
                                        <option value="{{ $address->district_id }}">{{ $address->district_name }}</option>
                                    </select>
                                    <input type="hidden" class="form-control form-control-line district_name"
                                        value="{{ $address->district_name }}" name="district_name">
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Xã phường</label>
                                    <select name="ward_id" id="ward_id" class="form-control ward" disabled>
                                        <option value="{{ $address->ward_id }}">{{ $address->ward_name }}</option>
                                    </select>
                                    <input type="hidden" class="form-control form-control-line ward_name"
                                        value="{{ $address->ward_name }}" name="ward_name">
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Tên đường</label>
                                    <input type="text" name="street_name" class="form-control" id=""
                                        value="{{ $address->street_name }}" readonly placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-outline-info">
                            <div class="card-body">
                                <h2 class="font-weight-bold" style="text-transform:uppercase;">
                                    Cài đặt lên đơn
                                </h2>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Lưu ý giao hàng</label>
                                    <select name="required_note" id="required_note" class="form-control" disabled>
                                        <option value="" selected>Chọn</option>
                                        <option value="KHONGCHOXEMHANG"
                                            {{ $address->required_note == 'KHONGCHOXEMHANG' ? 'selected' : '' }}> Không cho
                                            xem hàng</option>
                                        <option value="CHOXEMHANGKHONGTHU"
                                            {{ $address->required_note == 'CHOXEMHANGKHONGTHU' ? 'selected' : '' }}> Cho
                                            xem hàng - Không cho thử</option>
                                        <option value="CHOXEMCHOTHUHANGHANGKHONGTHU"
                                            {{ $address->required_note == 'CHOXEMCHOTHUHANGHANGKHONGTHU' ? 'selected' : '' }}>
                                            Cho thử hàng</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Bên thanh toán</label>
                                    <select name="payment_type" id="payment_type" class="form-control" disabled>
                                        <option value="" selected>Chọn</option>
                                        <option value="1" {{ $address->payment_type == '1' ? 'selected' : '' }}>Bên
                                            gủi trả phí</option>
                                        <option value="2" {{ $address->payment_type == '2' ? 'selected' : '' }}> Bên
                                            nhận trả phí</option>
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Đơn vị vận chuyển</label>
                                    <select name="transport_unit" id="transport_unit" class="form-control" disabled>
                                        <option value="">Chọn</option>
                                        <option value="1" {{ $address->transport_unit == '1' ? 'selected' : '' }}>
                                            Giao Hàng Nhanh</option>
                                    </select>
                                </div>

                                {{-- <div class="mb-3">
                                    <label for="" class="form-label l-label">Loại hàng</label>
                                    <select name="product_type" id="product_type" class="form-control" disabled>
                                        <option value="">Chọn</option>

                                        @foreach ($branch as $item)

                                            <option value="{{ $item->id }}"
                                                {{ $address->product_type == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div> --}}

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Ghi chú</label>
                                    <textarea name="note" class="form-control" rows="6" style="min-height: 157px; resize: vertical;" readonly>{{ $address->note }}</textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">

                    <div class="my-4">
                        <a href="{{ route('user.mystore.edit', $address->id) }}" class="btn btn-primary">chỉnh sửa</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection


@section('style')
    <link rel="stylesheet" href="{{ asset('/storage/backend/assets/plugins/dropify/dist/css/dropify.min.css') }}">
@stop

@section('script')
    <!-- jQuery file upload -->
    <script src="{{ asset('/storage/backend/assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Basic
            $('.dropify').dropify();

            // Translated
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez',
                    replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                    remove: 'Supprimer',
                    error: 'Désolé, le fichier trop volumineux'
                }
            });

            // Used events
            var drEvent = $('#input-file-events').dropify();

            drEvent.on('dropify.beforeClear', function(event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });

            drEvent.on('dropify.afterClear', function(event, element) {
                alert('File deleted');
            });

            drEvent.on('dropify.errors', function(event, element) {
                console.log('Has Errors');
            });

            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function(e) {
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
        });
    </script>

    {{-- lay thong tin address tu api --}}
    <script>
        $(document).ready(function() {
            const savedProvinceId = '{{ $address->province_id }}';
            const savedDistrictId = '{{ $address->district_id }}';
            const savedWardId = '{{ $address->ward_id }}';

            loadProvinces();



            function loadProvinces() {
                $.ajax({
                    url: "https://online-gateway.ghn.vn/shiip/public-api/master-data/province",
                    method: "GET",
                    headers: {
                        "token": "{{ config('constants.ghn_api_token') }}",
                        "Content-Type": "application/json"
                    },
                    success: function(response) {
                        if (response.code === 200) {
                            let html = '<option value="">Chọn tỉnh/thành</option>';
                            response.data.forEach(province => {
                                const selected = province.ProvinceID == savedProvinceId ?
                                    'selected' : '';
                                html +=
                                    `<option value="${province.ProvinceID}" ${selected}>${province.ProvinceName}</option>`;
                            });
                            $('#province_id').html(html);

                            // Load quận/huyện nếu có province được chọn
                            if (savedProvinceId) {
                                loadDistricts(savedProvinceId, savedDistrictId);
                            }
                        }
                    }
                });
            }


            //district
            function loadDistricts(provinceId, selectedDistrictId) {
                $.ajax({
                    url: "https://online-gateway.ghn.vn/shiip/public-api/master-data/district",
                    method: "GET",
                    headers: {
                        "token": "{{ config('constants.ghn_api_token') }}",
                        "Content-Type": "application/json"
                    },
                    data: {
                        "province_id": provinceId
                    },
                    success: function(response) {
                        if (response.code === 200) {
                            let html = '<option value="">Chọn quận/huyện</option>';
                            response.data.forEach(district => {
                                const selected = district.DistrictID == selectedDistrictId ?
                                    'selected' : '';
                                html +=
                                    `<option value="${district.DistrictID}" ${selected}>${district.DistrictName}</option>`;
                            });
                            $('#district_id').html(html);

                            // Load phường/xã nếu có district được chọn
                            if (selectedDistrictId) {
                                loadWards(selectedDistrictId, savedWardId);
                            }
                        }
                    }
                });
            }

            function loadWards(districtId, selectedWardId) {
                $.ajax({
                    url: "https://online-gateway.ghn.vn/shiip/public-api/master-data/ward",
                    method: "GET",
                    headers: {
                        "token": "{{ config('constants.ghn_api_token') }}",
                        "Content-Type": "application/json"
                    },
                    data: {
                        "district_id": districtId
                    },
                    success: function(response) {
                        if (response.code === 200) {
                            let html = '<option value="">Chọn phường/xã</option>';
                            response.data.forEach(ward => {
                                const selected = ward.WardCode == selectedWardId ? 'selected' :
                                    '';
                                html +=
                                    `<option value="${ward.WardCode}" ${selected}>${ward.WardName}</option>`;
                            });
                            $('#ward_id').html(html);
                        }
                    }
                });
            }

            $('#province_id').change(function() {
                const provinceId = $(this).val();
                const provinceName = $(this).find('option:selected').text();
                $('.province_name').val(provinceName);

                // Reset quận/huyện và phường/xã
                $('#district_id').html('<option value="">Chọn quận/huyện</option>');
                $('#ward_id').html('<option value="">Chọn phường/xã</option>');
                $('.district_name').val('');
                $('.ward_name').val('');

                if (provinceId) {
                    loadDistricts(provinceId, '');
                }
            });

            $('#district_id').change(function() {
                const districtId = $(this).val();
                const districtName = $(this).find('option:selected').text();
                $('.district_name').val(districtName);

                // Reset phường/xã
                $('#ward_id').html('<option value="">Chọn phường/xã</option>');
                $('.ward_name').val('');

                if (districtId) {
                    loadWards(districtId, '');
                }
            });

            $('#ward_id').change(function() {
                const wardName = $(this).find('option:selected').text();
                $('.ward_name').val(wardName);
            });
        });
    </script>
@stop
