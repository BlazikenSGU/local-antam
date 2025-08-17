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
                                    <div class="table-responsive ajax-result">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Mã đơn hàng</th>
                                                    <th>Họ tên</th>
                                                    <th>Số ĐT</th>
                                                    <th>Ngày đặt</th>
                                                    <th>Số tiền</th>
                                                    <th>Tình trạng</th>
                                                    <th class="text-right" style="min-width: 86px">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($data_list as $key => $item)
                                                    <tr>
                                                        <td>{{++$start}}</td>
                                                        <td>{{$item->order_code}}</td>
                                                        <td>{{$item->fullname}}</td>
                                                        <td>{{$item->phone}}</td>
                                                        <td>{{date('d/m/Y',strtotime($item->created_at))}}</td>
                                                        <td>{{number_format($item->total_price)}} đ</td>

                                                        <td>{{\App\Models\Orders::$status[$item->status]}}</td>

                                                        <td class="text-right">
                                                            <a href="'?phone=' . $item->phone . '&order_code=' . $item->order_code }}"
                                                               target="_blank"
                                                               class="btn waves-effect waves-light btn-info btn-sm">
                                                                Chi tiết </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" style="text-align: center">Chưa có dữ liệu</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
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
