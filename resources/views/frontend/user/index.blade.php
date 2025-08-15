@extends('frontend.layouts.main')

@push('title')
    Trang chủ
@endpush

@section('content')
    <style>
        .card-body {
            min-height: 380px;
        }

        @media (max-width: 650px) {

            .col-xl-6.tab_1,
            .col-xl-6.tab_2 {
                width: 100% !important;
                font-size: 10px;
            }

            span,
            button {
                font-size: 10px !important;
            }

            .col-xl-6.tab_1 {
                padding-right: 0rem !important;
            }

            .card-body {
                min-height: fit-content;
            }
        }
    </style>

    <div class="row desktop">

        <div class="col-12 col-xl-12 d-flex flex-wrap">

            <div class="col-xl-6 col-6 mt-2 tab_1" style="padding-right: 1rem;">

                <div class="">

                    <span class="text-danger font-weight-bold font-italic"> Cập nhật lúc {{ date('H:i:s d-m-Y') }}</span>
                    <div class="card card-outline-info mt-2">
                        <div class="card-body ">


                            @include('backend.partials.msg')
                            @include('backend.partials.errors')
                            <p class="font-weight-bold" style="color: #00467f">Báo cáo vận hành ngày hôm nay <span
                                    class="font-weight-bold" style="color: #F26522">- Live</span></p>
                            <div class="row">

                                <div class=" col-md-12 "
                                    style="background-color: #00467F; color: #fff; border-radius: 20px; padding: 10px 10px 0px 10px;">
                                    <div class="row">
                                        <div class="col-6">
                                            <a href="{{ url('user/orders?status=15') }}" style="color: #fff">
                                                <p class="m-0">Đang xử lý</p>
                                                <p class="font-weight-bold"><span style="font-size: 20px">
                                                        {{ $codlive['dangXuly'] }}
                                                    </span> đơn hàng <i class="fa-solid fa-arrow-right"></i></p>
                                            </a>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <a href="{{ url('user/orders?status=10') }}" style="color: #fff">

                                                <p class="m-0">Giao hàng thành công</p>
                                                <p class="font-weight-bold"><span style="font-size: 20px">
                                                        {{ $codlive['hoantatchuachuyencod'] }}
                                                    </span> đơn hàng <i class="fa-solid fa-arrow-right"></i></p>
                                            </a>
                                        </div>

                                    </div>
                                    <div class="row">


                                        <div class="col-6">
                                            <a href="{{ url('user/orders?status=12') }}" style="color: #fff">
                                                <p class="m-0">Hoàn hàng thành công</p>
                                                <p class="font-weight-bold"><span style="font-size: 20px">
                                                        {{ $codlive['hoanhangthanhcong'] }}
                                                    </span> đơn hàng <i class="fa-solid fa-arrow-right"></i></p>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="row" style="padding: 5px 5px 0px 5px; ">
                                        <div class="col-12"
                                            style="background-color: #F26522;border-radius: 0px 0px 10px 10px">
                                            <a href="{{ url('user/orders?status=5') }}" style="color: #fff">
                                                <p class="m-0 font-weight-bold">Chờ xác nhận giao lại
                                                </p>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <p class="font-weight-bold"><span style="font-size: 20px">
                                                            {{ $codlive['giaolai'] }}
                                                        </span> đơn hàng </p>
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </div>
                                            </a>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-6 mt-2 tab_2">
                <div class="">

                    <span class="text-danger font-weight-bold font-italic"> Cập nhật lúc
                        {{ date('H:i:s d-m-Y') }}</span>
                    <div class="card card-outline-info mt-2">
                        <div class="card-body ">


                            @include('backend.partials.msg')
                            @include('backend.partials.errors')
                            <p class="font-weight-bold" style="color: #00467f">Dòng tiền <span class="font-weight-bold"
                                    style="color: #F26522">- Live</span>
                            </p>
                            <div class="row" style=" ">

                                <div class="col-md-12 p-2"
                                    style="background-color: #00467F; color: #fff; border-radius: 20px; height: fit-content;">
                                    <div class="">
                                        <p class="font-weight-bold">Số dư hiện tại (GHN sắp chuyển cho khách)</p>
                                    </div>
                                    <div class="ml-3 mr-3">
                                        <a href="{{ url('/user/orders?status=6') }}" style="color: #fff">
                                            <div class="d-flex align-items-center justify-content-between mb-2"
                                                style="font-size: 14px">
                                                <span>Tiền thu hộ (COD) <i class="fa-solid fa-arrow-right"></i></span>
                                                <span
                                                    class="font-weight-bold">{{ number_format((int) str_replace(['.', ','], '', $codlive['sum_cod']), 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </a>
                                        <a href="{{ url('/user/orders?status=6') }}" style="color: #fff">
                                            <div class="d-flex align-items-center justify-content-between mb-2"
                                                style="font-size: 14px">
                                                <span class="mt-2">Giao thất bại thu tiền <i
                                                        class="fa-solid fa-arrow-right"></i></span>
                                                <span
                                                    class="font-weight-bold">{{ number_format((int) str_replace(['.', ','], '', $codlive['sum_cod_failed']), 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </a>
                                        <a href="{{ url('/user/orders?status=6') }}" style="color: #fff">
                                            <div class="d-flex a lign-items-center justify-content-between mb-2"
                                                style="font-size: 14px">
                                                <span class="mt-2">Phí dịch vụ tạm thu <i
                                                        class="fa-solid fa-arrow-right"></i></span>
                                                <span class="font-weight-bold">


                                                    @if ($codlive['main_service'] != 0)
                                                        -
                                                        {{ number_format((int) str_replace(['.', ','], '', $codlive['main_service']), 0, ',', '.') }}
                                                    @else
                                                        0
                                                    @endif

                                                </span>
                                            </div>
                                        </a>
                                        <a href="{{ route('user.doisoat') }}" style="color: #fff">
                                            <div class="d-flex align-items-center justify-content-between "
                                                style="font-size: 14px">
                                                <span class="mt-2">Nợ tồn </span>

                                                <span class="font-weight-bold">
                                                    @if ($codlive['no_ton'] != 0)
                                                        {{ number_format((int) str_replace(['.', ','], '', $codlive['no_ton']), 0, ',', '.') }}
                                                    @else
                                                        0
                                                    @endif

                                                </span>
                                            </div>
                                        </a>
                                        <hr style="background-color: #fff">
                                        <a href="{{ url('/user/orders?status=6') }}" style="color: #fff">
                                            <div class="d-flex align-items-center justify-content-between mb-2"
                                                style="font-size: 14px">
                                                <span>Tổng số dư hoàn tất </span>
                                                <span class="font-weight-bold">
                                                    {{ number_format((int) str_replace(['.', ','], '', $codlive['sum_total']), 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </a>
                                        <div class="text-center mt-2">
                                            <a class="" href="{{ url('/user/orders?status=3') }}"
                                                style="color: #fff">
                                                <div class="d-flex align-items-center justify-content-between mb-2"
                                                    style="font-size: 14px">
                                                    <span>COD lưu kho / đang xử lý </span>
                                                    <span class="font-weight-bold">
                                                        {{ number_format((int) str_replace(['.', ','], '', $codlive['luuKho']), 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </a>
                                            {{-- <button class="btn font-weight-bold"
                                                style="background-color: #fff; color: #F26522">*Tổng số dư hiện tai = Tiền
                                                thu
                                                hộ - Phí dịch vụ tạm thu - Nợ tồn</button> --}}
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
