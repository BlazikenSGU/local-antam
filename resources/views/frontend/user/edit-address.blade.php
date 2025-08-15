@extends('frontend.layouts.frontend')

@section('content')
    @include('frontend.parts.breadcrumbs')

    <!-- my account wrapper start -->
    <div class="my-account-wrapper section-padding">
        <div class="container">
            <div class="section-bg-color">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- My Account Page Start -->
                        <div class="myaccount-page-wrapper pt-50 pb-80">

                            <div class="row">
                                @include('frontend.user.menu')

                                <div class="col-lg-9 col-md-8">
                                    @include('frontend.parts.msg')

                                    @include('frontend.parts.errors')

                                    <div class="tab-pane fade active show" id="account-info" role="tabpanel">
                                        <div class="myaccount-content">
                                            <h3>Sửa địa chỉ</h3>
                                            <div class="account-details-form">
                                                <form method="post" action="#">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group ">
                                                                <label for="name">* Họ tên</label>
                                                                <input type="text" class="form-control flat"
                                                                       name="name"
                                                                       value="{{old('name',$address->name)}}"
                                                                       id="name" placeholder="Họ tên">
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group  ">
                                                                <label for="phone">* Số điện thoại</label>
                                                                <input type="text" class="form-control flat"
                                                                       name="phone"
                                                                       value="{{old('phone',$address->phone)}}"
                                                                       id="phone" placeholder="Số điện thoại">

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group ">
                                                                <label for="email">Email</label>
                                                                <input type="text" class="form-control flat"
                                                                       id="email"
                                                                       name="email"
                                                                       value="{{old('email',$address->email)}}"
                                                                       placeholder="Email">

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group  ">
                                                                <label for="province_id">* Tỉnh/Thành
                                                                    phố</label>
                                                                <select
                                                                    class="form-control flat my-select select2-hidden-accessible"
                                                                    style="width: 100%;" id="province_id"
                                                                    name="province_id"
                                                                    onchange="get_district($(this))"
                                                                    tabindex="-1" aria-hidden="true">
                                                                    <option value="">Chọn</option>
                                                                    @foreach($provinces as $province)
                                                                        <option value="{{$province->id}}"
                                                                            {!! old('province_id',$address->province_id)==$province->id?'selected="selected"':'' !!}>
                                                                            {{$province->name}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group ">
                                                                <label for="district_id">* Quận/Huyện</label>
                                                                <select
                                                                    class="form-control flat my-select select_district select2-hidden-accessible"
                                                                    style="width: 100%;" id="district_id"
                                                                    name="district_id"
                                                                    onchange="get_ward($(this))"
                                                                    tabindex="-1" aria-hidden="true">
                                                                    <option value="">Chọn</option>
                                                                    @foreach($districts as $district)
                                                                        <option value="{{$district->id}}"
                                                                            {!! old('district_id', $address->district_id)==$district->id?'selected="selected"':'' !!}>
                                                                            {{$district->name}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group ">
                                                                <label for="ward_id">* Phường/Xã</label>
                                                                <select
                                                                    class="form-control flat my-select select_ward select2-hidden-accessible"
                                                                    style="width: 100%;" id="ward_id"
                                                                    name="ward_id" tabindex="-1"
                                                                    aria-hidden="true">
                                                                    <option value="">Chọn</option>
                                                                    @foreach($wards as $ward)
                                                                        <option value="{{$ward->id}}"
                                                                            {!! old('ward_id', $address->ward_id)==$ward->id?'selected="selected"':'' !!}>
                                                                            {{$ward->name}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="street_name">* Số nhà, tên đường hoặc
                                                            hẻm</label>
                                                        <textarea name="street_name" class="form-control flat"
                                                                  id="street_name"
                                                                  placeholder="Ví dụ: 52, đường Trần Hưng Đạo">{{old('street_name',$address->street_name)}}</textarea>

                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="is_default_recipient"
                                                                   {!! old('is_default_recipient', $address->is_default_recipient)==1?'checked="checked"':'' !!}
                                                                   value="1"> Địa chỉ mặc định
                                                        </label>
                                                    </div>
                                                    <div class="single-input-item">
                                                        <button type="submit" class="check-btn sqr-btn ">Lưu</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- My Account Page End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- my account wrapper end -->
@endsection

@section('script')

    <script>
        $('.update-btn-address').click(function () {
            $('#myModal').toggle();
            $('.modal-body h3').text('Cập nhật địa chỉ giao hàng')
        });

        $('.deleteAddress').click(function () {
            var address_id = $(this).data('id');
            Swal.fire({
                title: 'Bạn có chắc địa chỉ này?',
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
                        url: BASE_URL + '/ajax/address/delete',
                        type: 'post',
                        data: {address_id: address_id},
                        dataType: 'json',
                        success: function (response) {
                            if (response.code == 200) {
                                swal.fire(
                                    'Thông báo',
                                    'Đã xóa địa chỉ',
                                    'success'
                                );
                                $('.target' + address_id).parent().parent().remove();
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
        //     })
        // });

        function get_district(d) {
            var province_id = d.val();
            $('.select_district').html('<option value="">Chọn</option>');
            $('.select_ward').html('<option value="">Chọn</option>');
            $.ajax({
                type: 'GET',
                url: BASE_URL + "/api/v1.0/location/district",
                data: {province_id: province_id},
                dataType: 'json',
                success: function (data) {
                    $.each(data.data, function (index, element) {
                        $('.select_district').append('<option value=' + element.id + '>' + element.type + ' ' + element.name + '</option>');
                    });
                }
            });
        }

        function get_ward(d) {
            var district_id = d.val();
            $('.select_ward').html('<option value="">Chọn</option>');
            $.ajax({
                type: 'GET',
                url: BASE_URL + "/api/v1.0/location/ward",
                data: {district_id: district_id},
                dataType: 'json',
                success: function (data) {
                    $.each(data.data, function (index, element) {
                        $('.select_ward').append('<option value=' + element.id + '>' + element.type + ' ' + element.name + '</option>');
                    });
                }
            });
        }

        $("#form-insert").validate({
            rules: {
                name: "required",
                phone: "required",
                province_id: "required",
                district_id: "required",
                ward_id: "required",
                email: {
                    email: true,
                    required: true,
                },
            },
            messages: {
                name: "Vui lòng nhập Họ tên!",
                phone: "Vui lòng nhập số điện thoại!",
                province_id: "Vui lòng chọn Tỉnh/Thành phố!",
                district_id: "Vui lòng chọn Quận/Huyện!",
                ward_id: "Vui lòng chọn Phường/Xã!",
                email: {
                    email: "Không đúng định dạng email!",
                    required: "Vui lòng nhập email!",
                },
            }
        });
    </script>
@endsection
