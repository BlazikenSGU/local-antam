@extends('backend.layouts.main2222')

@section('content')

{{--    <div class="row page-titles">--}}
{{--        <div class="col-md-5 align-self-center">--}}
{{--            <h3 class="text-themecolor">Bảng điều khiển</h3>--}}
{{--        </div>--}}
{{--        <div class="col-md-7 align-self-center">--}}
{{--            --}}{{--<ol class="breadcrumb">--}}
{{--            --}}{{--<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>--}}
{{--            --}}{{--<li class="breadcrumb-item">pages</li>--}}
{{--            --}}{{--<li class="breadcrumb-item active">Starter kit</li>--}}
{{--            --}}{{--</ol>--}}

{{--            {{ Breadcrumbs::render('Dashboard') }}--}}

{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="container-fluid">--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-12">--}}
{{--                <div class="card-group">--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-12">--}}
{{--                                    <h2 class="m-b-0"><i class="mdi mdi-briefcase-check text-info"></i></h2>--}}
{{--                                    <h3 class="">{{$count_products}}</h3>--}}
{{--                                    <h6 class="card-subtitle">Sản phẩm</h6></div>--}}
{{--                                <div class="col-12">--}}
{{--                                    <a class="text-info" href="{{route('backend.products.index')}}">Xem thêm</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- Column -->--}}
{{--                    <!-- Column -->--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-12">--}}
{{--                                    <h2 class="m-b-0"><i class="mdi mdi-cart text-success"></i></h2>--}}
{{--                                    <h3 class="">{{$count_order}}</h3>--}}
{{--                                    <h6 class="card-subtitle">Đơn hàng</h6></div>--}}
{{--                                <div class="col-12">--}}
{{--                                    <a class="text-info" href="{{route('backend.orders.index')}}">Xem thêm</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- Column -->--}}
{{--                    <!-- Column -->--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-12">--}}
{{--                                    <h2 class="m-b-0"><i class="mdi mdi-account-multiple text-purple"></i></h2>--}}
{{--                                    <h3 class="">{{$count_users}}</h3>--}}
{{--                                    <h6 class="card-subtitle">Tài khoản</h6></div>--}}
{{--                                <div class="col-12">--}}
{{--                                    <a class="text-info" href="{{route('backend.users.index')}}">Xem thêm</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- Column -->--}}
{{--                    <!-- Column -->--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-12">--}}
{{--                                    <h2 class="m-b-0"><i class="mdi mdi-file-document text-warning"></i></h2>--}}
{{--                                    <h3 class="">{{$count_posts}}</h3>--}}
{{--                                    <h6 class="card-subtitle">Bài viết</h6></div>--}}
{{--                                <div class="col-12">--}}
{{--                                    <a class="text-info" href="{{route('backend.posts.index')}}">Xem thêm</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="row">--}}
{{--            <div class="col-lg-12">--}}
{{--                <div class="card" style="padding-bottom: 100px">--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="d-flex flex-wrap">--}}
{{--                            <div>--}}
{{--                                <h4 class="card-title">Doanh thu</h4>--}}
{{--                            </div>--}}
{{--                            <div class="ml-auto">--}}
{{--                                <ul class="list-inline">--}}
{{--                                    <li>--}}
{{--                                        <h6 class="text-muted text-success">--}}
{{--                                            <i class="fa fa-circle font-10 m-r-10 "></i>Doanh thu--}}
{{--                                        </h6>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div id="morris-area-chart2" style="height: 405px;"></div>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-lg-8 col-md-7">--}}
{{--                <div class="card card-default">--}}
{{--                    <div class="card-header">--}}
{{--                        <div class="card-actions">--}}
{{--                            <a class="" data-action="collapse"><i class="ti-minus"></i></a>--}}
{{--                            <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>--}}
{{--                            <a class="btn-close" data-action="close"><i class="ti-close"></i></a>--}}
{{--                        </div>--}}
{{--                        <h4 class="card-title m-b-0">Đơn hàng gần đây</h4>--}}
{{--                    </div>--}}
{{--                    <div class="card-body collapse show">--}}
{{--                        <div class="table-responsive">--}}
{{--                            <table class="table product-overview">--}}
{{--                                <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th>ID</th>--}}
{{--                                        <th>Tên khách hàng</th>--}}
{{--                                        <th>Tổng cộng</th>--}}
{{--                                        <th>Ngày tạo</th>--}}
{{--                                    </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                    @foreach($order_new as $item)--}}
{{--                                        <tr>--}}
{{--                                            <td>{{$item->order_code}}</td>--}}
{{--                                            <td>--}}
{{--                                                {{$item->user?$item->user->fullname:''}}--}}
{{--                                            </td>--}}
{{--                                            <td>{{number_format($item->total_price)}}đ</td>--}}
{{--                                            <td>{{$item->created_at}}</td>--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                            <p class="text-center"><a href="{{route('backend.orders.index')}}">Xem thêm</a></p>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="card card-default">--}}
{{--                    <div class="card-header">--}}
{{--                        <div class="card-actions">--}}
{{--                            <a class="" data-action="collapse"><i class="ti-minus"></i></a>--}}
{{--                            <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>--}}
{{--                            <a class="btn-close" data-action="close"><i class="ti-close"></i></a>--}}
{{--                        </div>--}}
{{--                        <h4 class="card-title m-b-0">Sản phẩm mới thêm</h4>--}}
{{--                    </div>--}}
{{--                    <div class="card-body collapse show">--}}
{{--                        <div class="table-responsive">--}}
{{--                            <table class="table product-overview">--}}
{{--                                <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th>Hình</th>--}}
{{--                                        <th>Tên sản phẩm</th>--}}
{{--                                        <th>Giá</th>--}}
{{--                                        <th>Ngày tạo</th>--}}
{{--                                    </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                    @foreach($products_new as $item)--}}
{{--                                        <tr>--}}
{{--                                            <td><img width="70px" src="{{$item->thumbnail->file_src}}"></td>--}}
{{--                                            <td>--}}
{{--                                                {{$item->title}}--}}
{{--                                            </td>--}}
{{--                                            <td><span--}}
{{--                                                    class="badge badge-warning">{{number_format($item->price)}}đ</span>--}}
{{--                                            </td>--}}
{{--                                            <td>{{$item->created_at}}</td>--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                            <p class="text-center"><a href="{{route('backend.products.index')}}">Xem thêm</a></p>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-lg-4 col-md-5">--}}
{{--                <!-- Column -->--}}
{{--                <div class="card card-default">--}}
{{--                    <div class="card-header">--}}
{{--                        <div class="card-actions">--}}
{{--                            <a class="" data-action="collapse"><i class="ti-minus"></i></a>--}}
{{--                            <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>--}}
{{--                        </div>--}}
{{--                        <h4 class="card-title m-b-0">Đơn hàng</h4>--}}
{{--                    </div>--}}
{{--                    <div class="card-body collapse show">--}}
{{--                        <div id="morris-donut-chart" class="ecomm-donute" style="height: 317px;"></div>--}}
{{--                        <ul class="list-inline m-t-20 text-center">--}}
{{--                            <li>--}}
{{--                                <h6 class="text-muted"><i class="fa fa-circle text-danger"></i> Tổng</h6>--}}
{{--                                <h4 class="m-b-0">{{$count_order}}</h4>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <h6 class="text-muted"><i class="fa fa-circle" style="color: #26dad2"></i> Hoàn thành--}}
{{--                                </h6>--}}
{{--                                <h4 class="m-b-0">{{\App\Models\Orders::where('status',\App\Models\Orders::STATUS_FINISH)->where('company_id',config('constants.company_id'))->count()}}</h4>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <h6 class="text-muted"><i class="fa fa-circle" style="color: #5c4ac7"></i> Mới đặt</h6>--}}
{{--                                <h4 class="m-b-0">{{App\Models\Orders::where('status',\App\Models\Orders::STATUS_NEW)->where('company_id',config('constants.company_id'))->count()}}</h4>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <h6 class="text-muted"><i class="fa fa-circle" style="color: #26c6da"></i> Đã xác nhận--}}
{{--                                </h6>--}}
{{--                                <h4 class="m-b-0">{{App\Models\Orders::where('status',\App\Models\Orders::STATUS_CONFIRMED)->where('company_id',config('constants.company_id'))->count()}}</h4>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <h6 class="text-muted"><i class="fa fa-circle" style="color: #c78028"></i> Đang giao--}}
{{--                                    hàng</h6>--}}
{{--                                <h4 class="m-b-0">{{App\Models\Orders::where('status',\App\Models\Orders::STATUS_DELIVERING)->where('company_id',config('constants.company_id'))->count()}}</h4>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <h6 class="text-muted"><i class="fa fa-circle" style="color: #C70F0C"></i> Đã hủy</h6>--}}
{{--                                <h4 class="m-b-0">{{App\Models\Orders::where('status',\App\Models\Orders::STATUS_CANCEL)->where('company_id',config('constants.company_id'))->count()}}</h4>--}}
{{--                            </li>--}}
{{--                        </ul>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="card">--}}
{{--                    <div class="card-body bg-inverse">--}}
{{--                        <h4 class="text-white card-title">Khách hàng mới</h4>--}}
{{--                        --}}{{--                        <h6 class="card-subtitle text-white m-0 op-5">Checkout employee list here</h6>--}}
{{--                    </div>--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="message-box contact-box">--}}
{{--                            --}}{{--                            <h2 class="add-ct-btn"><button type="button" class="btn btn-circle btn-lg btn-success waves-effect waves-dark">+</button></h2>--}}
{{--                            <div class="message-widget contact-widget">--}}
{{--                                <!-- Message -->--}}
{{--                                @foreach($users_new as $user)--}}
{{--                                    <a href="#">--}}
{{--                                        <div class="user-img"><img--}}
{{--                                                src="{{ asset('/storage/backend/assets/images/users/1.jpg') }}"--}}
{{--                                                alt="user"--}}
{{--                                                class="img-circle"> <span--}}
{{--                                                class="profile-status online pull-right"></span></div>--}}
{{--                                        <div class="mail-contnet">--}}
{{--                                            <h5>{{!empty($user->fullname)?$user->fullname:'Chưa cập nhật'}}</h5>--}}
{{--                                            <span--}}
{{--                                                class="mail-desc">{{!empty($user->email)?$user->email:$user->email}}/ {{!empty($user->phone)?$user->phone:$user->phone}}</span>--}}
{{--                                        </div>--}}
{{--                                    </a>--}}
{{--                                @endforeach--}}
{{--                                <p class="text-center"><a href="{{route('backend.users.index')}}">Xem thêm</a></p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
{{--@section('script')--}}
{{--    <script src="{{asset('storage/backend/')}}/assets/plugins/raphael/raphael-min.js"></script>--}}

{{--    <script src="{{asset('storage/backend/')}}/assets/plugins/morrisjs/morris.min.js"></script>--}}
{{--    <script>--}}
{{--        $(function () {--}}
{{--            "use strict";--}}
{{--            Morris.Area({--}}
{{--                element: 'morris-area-chart2',--}}
{{--                data: {!! json_encode($report_month) !!},--}}
{{--                xkey: 'period',--}}
{{--                ykeys: ['total_amount'],--}}
{{--                labels: ['Doanh thu'],--}}
{{--                pointSize: 0,--}}
{{--                fillOpacity: 0.4,--}}
{{--                pointStrokeColors: ['#31a8cb'],--}}
{{--                behaveLikeLine: true,--}}
{{--                parseTime: false,--}}
{{--                gridLineColor: '#49dfe0',--}}
{{--                lineWidth: 0,--}}
{{--                smooth: true,--}}
{{--                hideHover: 'auto',--}}
{{--                lineColors: ['#50c3cb'],--}}
{{--                resize: true--}}

{{--            });--}}
{{--            Morris.Donut({--}}
{{--                element: 'morris-donut-chart',--}}
{{--                data: [--}}
{{--                    {--}}
{{--                        label: "Hoàn thành",--}}
{{--                        value: "{{\App\Models\Orders::where('status',\App\Models\Orders::STATUS_FINISH)->where('company_id',config('constants.company_id'))->count()}}"--}}
{{--                    },--}}
{{--                    {--}}
{{--                        label: "Mới đặt",--}}
{{--                        value: "{{App\Models\Orders::where('status',\App\Models\Orders::STATUS_NEW)->where('company_id',config('constants.company_id'))->count()}}",--}}

{{--                    },--}}
{{--                    {--}}
{{--                        label: "Đã xác nhận",--}}
{{--                        value: "{{App\Models\Orders::where('status',\App\Models\Orders::STATUS_CONFIRMED)->where('company_id',config('constants.company_id'))->count()}}",--}}
{{--                    },--}}
{{--                    {--}}
{{--                        label: "Đang giao hàng",--}}
{{--                        value: "{{App\Models\Orders::where('status',\App\Models\Orders::STATUS_DELIVERING)->where('company_id',config('constants.company_id'))->count()}}",--}}
{{--                    },--}}
{{--                    {--}}
{{--                        label: "Đã hủy",--}}
{{--                        value: "{{App\Models\Orders::where('status',\App\Models\Orders::STATUS_CANCEL)->where('company_id',config('constants.company_id'))->count()}}",--}}
{{--                    },--}}
{{--                ],--}}
{{--                resize: true,--}}
{{--                colors: ['#26dad2', '#5c4ac7', '#26c6da', '#c78028', '#C70F0C']--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--    <script>--}}

{{--    </script>--}}
{{--@endsection--}}
