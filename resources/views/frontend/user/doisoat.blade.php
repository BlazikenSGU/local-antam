@extends('frontend.layouts.main')
@push('title')
    Quản lý đối soát
@endpush

@section('content')
    <style>
        /* CSS cho bảng đối soát chính */
        /* .table-bordered th,
                                .table-bordered td {
                                    width: 200px !important;
                                    min-width: 200px !important;
                                    max-width: 200px !important;
                                    white-space: nowrap;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                } */

        .table-bordered .width-250 {
            width: 250px !important;
            min-width: 250px !important;
            max-width: 250px !important;
        }

        /* CSS cho bảng đối soát phụ */
        /* #table_id1 th,
                                #table_id1 td {
                                    width: 200px !important;
                                    min-width: 200px !important;
                                    max-width: 200px !important;
                                    white-space: nowrap;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                } */

        /* Đảm bảo bảng có thể cuộn ngang */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Đảm bảo bảng không bị co lại */
        /* .table-bordered,
                                #table_id1 {
                                    width: auto !important;
                                    table-layout: fixed;
                                } */

        /* Đảm bảo wrapper của DataTable không bị vỡ */
        #table_id_wrapper {
            width: 100%;
            overflow: auto;
        }

        /* CSS cho phân trang */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            margin: 20px 0;
            padding: 0;
            list-style: none;
        }

        .pagination li {
            margin: 0 2px;
        }

        .pagination li a,
        .pagination li span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 35px;
            height: 35px;
            padding: 0 10px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            color: #333;
            text-decoration: none;
            background: #fff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .pagination li.active span {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.2);
        }

        .pagination li a:hover {
            background: #f8f9fa;
            border-color: #007bff;
            color: #007bff;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .pagination li.disabled span {
            color: #999;
            background: #f5f5f5;
            border-color: #e0e0e0;
            cursor: not-allowed;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            border-radius: 4px;
        }

        .pagination .page-item.active .page-link {
            z-index: 3;
        }

        .wgp-pagination {
            margin: 30px 0;
            padding: 0 15px;
        }

        @media (max-width: 650px) {
            .t-b {
                font-size: 14px;
                color: #1b4e87;
                font-weight: bold;
            }

            .t-o {
                font-size: 14px;
                color: #f26522;
                font-weight: bold;
            }

            .table_web {
                display: none;
            }

            .table_mobile {
                display: block !important;
            }
        }
    </style>

    <div class="container-fluid mt-4">

        <div class="row page-titles mb-2">
            <H3>QUẢN LÝ ĐỐI SOÁT</H3>
        </div>

        @if (Auth::guard('backend')->check() && Auth::guard('backend')->user()->id == 168)
            <div class="row page-titles">
                <div class="col-md-12">
                    <div class="card card-outline-info">
                        <div class="card-body">
                            <div class="">
                                <span href="{{-- route('backend.doi_soat.export') --}}" class="btn btn-info modalExport"><i
                                        class="mdi mdi-file-excel"></i> Export Excel</span>
                                <a href="{{ route('backend.doi_soat.showFormImport') }}" class="btn btn-secondary"> <i
                                        class="mdi mdi-file-excel"></i> Import Excel</a>
                                <a href="{{ route('backend.doi_soat.run') }}" class="btn btn-warning"> <i
                                        class="mdi mdi-file-excel"></i> Chạy đối soát</a>
                            </div>

                            <div class="table-responsive mt-3">

                                <table class="table table-bordered" style=" overflow-x: auto;">
                                    <thead>
                                        <tr class="table-primary">
                                            <th scope="col" style="width: 100px">ID đối soát</th>
                                            <th class="width-250" scope="col">Đối soát</th>
                                            <th scope="col">Mã đơn hàng</th>
                                            <th scope="col">Mã giao 1 phần</th>
                                            <th scope="col">Mã đơn hàng riêng</th>
                                            <th scope="col">Bảng giá</th>
                                            <th scope="col">ID User </th>
                                            <th scope="col">Ngày tạo đơn</th>
                                            <th scope="col">Ngày giao/hoàn thành công</th>
                                            <th scope="col">Tình trạng thu tiền GTB</th>
                                            <th scope="col">Trạng thái</th>
                                            <th scope="col">COD</th>
                                            <th scope="col">Tiền GTB - Thu tiền</th>
                                            <th scope="col">Phí giao hàng</th>
                                            <th scope="col">Phí giao lại</th>
                                            <th scope="col">Phí khai giá</th>
                                            <th scope="col">Phí hoàn hàng</th>
                                            <th scope="col">Phí giao 1 phần</th>
                                            <th scope="col">Tổng phí</th>
                                            <th scope="col">Tổng đối soát</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @foreach ($doisoat as $item)
                                            <tr>
                                                <td>{{ $item->id ?? '' }}</td>
                                                <td>{{ $item->doisoat ?? '' }}</td>
                                                <td style="width: 200px">{{ $item->OrderCode ?? '' }}</td>
                                                <td>{{ $item->PartialReturnCode ?? '' }}</td>
                                                <td>{{ $item->order_code_custom ?? '' }}</td>
                                                <td>
                                                    {{ \App\Models\Branch::where('shopId', $item->ShopID)->first()->name_show ?? '' }}
                                                </td>

                                                <td>{{ \App\Models\CoreUsers::find($item->IDUser)->phone ?? '' }}</td>
                                                <td>{{ $item->created_at ?? '' }}</td>
                                                <td>{{ $item->ngaygiaohoanthanhcong ?? '' }}</td>
                                                <td>
                                                    @if (!empty($item->tinhtrangthutienGTB))
                                                        {{ $item->tinhtrangthutienGTB == 1 ? 'Thành Công' : 'Thất bại' }}
                                                    @endif
                                                </td>
                                                <td>{{ \App\Models\StatusName::where('key', $item->statusName)->first()->name ?? '' }}
                                                </td>
                                                <td>{{ number_format($item->CODAmount ?? 0, 0, ',', '.') }}</td>
                                                <td>{{ (int) str_replace(['.', ','], '', $item->cod_failed_amount) ?? '' }}
                                                </td>
                                                <td>{{ number_format($item->MainService ?? 0, 0, ',', '.') }}</td>
                                                <td>{{ $item->R2S ?? '' }}</td>
                                                <td>{{ $item->Insurance ?? '' }}</td>
                                                <td>{{ $item->Return ?? '' }}</td>
                                                <td>{{ $item->phigiao1lan ?? '' }}</td>
                                                <td>{{ number_format($item->tongphi ?? 0, 0, ',', '.') }}</td>
                                                <td>{{ number_format($item->tongdoisoat ?? 0, 0, ',', '.') }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                {{ $doisoat->links('pagination::bootstrap-4') }}
                            </div>


                        </div>
                    </div>
                </div>

            </div>
        @else
            <div class="row page-titles desktop">
                <div class="col-md-12 table_web">
                    <div class="card card-outline-info">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="table_id1" class="table mt-5">
                                    <thead class="bg-secondary text-light">
                                        <tr>
                                            <th scope="col">Mã phiên chuyển tiền </th>
                                            <th scope="col">Thời gian chuyển tiền</th>
                                            <th scope="col">Tổng tiền COD</th>
                                            <th scope="col">GTB - thu tiền</th>
                                            <th scope="col">Thực nhận</th>
                                            <th scope="col">Số ĐH tương ứng</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($data1 as $a1)
                                            <tr>
                                                <td style="width: 300px !important">
                                                    <a href="{{ route('user.doisoat.view', $a1->id) }}">
                                                        {{ $a1->maphienchuyentien }}
                                                    </a>
                                                </td>

                                                <td>{{ $a1->thoigianchuyentien }}</td>
                                                <td>
                                                    <?php
                                                    $doisoat = \App\Models\DoiSoat::where('IdDoiSoatUser', $a1->id)->get();
                                                    $ArrraysOderCode = [];
                                                    foreach ($doisoat as $key => $value) {
                                                        $ArrraysOderCode[] = $value['OrderCode'];
                                                    }
                                                    
                                                    //$ArrraysOderCode = $doisoat->puck('OrderCode');
                                                    //var_dump($ArrraysOderCode);
                                                    $sumPayment_fee = \App\Models\Orders::whereIn('order_code', $ArrraysOderCode)->sum('payment_fee');
                                                    //echo $sumPayment_fee;
                                                    ?>
                                                    {{ \App\Utils\Common::FormatNumberVND($a1->tongtienCOD) }}


                                                    {{-- {{ $a1->tongtienCOD }} --}}
                                                </td>
                                                <td>
                                                    {{ \App\Utils\Common::FormatNumberVND($a1->GTBThutien) }}
                                                    {{-- {{ $a1->GTBThutien }} --}}
                                                </td>
                                                <td>
                                                    {{ \App\Utils\Common::FormatNumberVND($a1->thucnhan) }}
                                                    {{-- {{ $a1->thucnhan }} --}}
                                                </td>
                                                <td>

                                                    {{ $a1->soHDtuongung }}
                                                </td>
                                                {{-- <td>8</td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="col-md-12 mt-2 table_mobile d-none">
                    @foreach ($data1 as $a1)
                        <div class="card card-outline-info mb-2">

                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <a href="{{ route('user.doisoat.view', $a1->id) }}"><span class="t-o"
                                            style="font-size: 14px"> Mã phiên:
                                            {{ $a1->maphienchuyentien }}</span></a>
                                    <span class="t-b">Thời gian chuyển: {{ $a1->thoigianchuyentien }}</span>
                                    <span class="t-b">Tổng tiền cod:
                                        <?php
                                        $doisoat = \App\Models\DoiSoat::where('IdDoiSoatUser', $a1->id)->get();
                                        $ArrraysOderCode = [];
                                        foreach ($doisoat as $key => $value) {
                                            $ArrraysOderCode[] = $value['OrderCode'];
                                        }
                                        $sumPayment_fee = \App\Models\Orders::whereIn('order_code', $ArrraysOderCode)->sum('payment_fee');
                                        ?>
                                        {{ number_format($a1->tongtienCOD, 0, ',', '.') }} đ
                                    </span>
                                    <span class="t-b">GTB: {{ number_format($a1->GTBThutien, 0, ',', '.') }}
                                        đ</span>
                                    <span class="t-b">Thực nhận: {{ number_format($a1->thucnhan, 0, ',', '.') }}
                                        đ</span>
                                    <span class="t-b">Số đơn tương ứng: {{ $a1->soHDtuongung }}</span>
                                </div>
                            </div>

                        </div>
                    @endforeach

                </div>
            </div>
        @endif

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tải File</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="{{ route('backend.doi_soat.export') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-group-sm">
                                        <input type="text" name="date_start" value="" id="working_date_form"
                                            readonly="" class="form-control form-control-sm date_select"
                                            placeholder="Từ ngày" style="cursor: pointer;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-sm">
                                        <input type="text" name="date_end" value="" id="working_date_to"
                                            readonly="" class="form-control form-control-sm date_select"
                                            placeholder="Đến ngày" style="cursor: pointer;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    Tùy chọn
                                    <input type="checkbox" id="Status" name="status1" value="all">
                                    <label for="Status"> Tất cả</label><br>
                                </div>
                                <div class="col-md-4">

                                    <input type="checkbox" id="cod1" name="cod[]" value="1">
                                    <label for="cod1"> Chưa chuyển COD</label><br>
                                </div>
                                <div class="col-md-4">

                                    <input type="checkbox" id="cod2" name="cod[]" value="2">
                                    <label for="cod2"> Đã chuyển COD</label><br>
                                </div>
                                <div class="col-md-12">
                                    Trạng thái
                                </div>
                                @foreach ($statusNames as $k => $iStatus)
                                    <div class="col-md-4">
                                        <input type="checkbox" id="Status{{ $k }}" name="status[]"
                                            value="{{ $iStatus->key }}">
                                        <label for="Status{{ $k }}"> {{ $iStatus->name }}</label><br>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Tải</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .table-responsive th,
        .table-responsive td {
            text-align: center;
            font-size: 14px !important;
            font-weight: 400;
            padding: 5px 15px !important;

        }
    </style>
@endsection

@section('script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#working_date_to").datepicker({
                dateFormat: "yy-mm-dd", // Định dạng ngày
                changeMonth: true, // Hiển thị chọn tháng
                changeYear: true, // Hiển thị chọn năm
                showButtonPanel: true, // Hiển thị nút điều khiển
            });
            $("#working_date_form").datepicker({
                dateFormat: "yy-mm-dd", // Định dạng ngày
                changeMonth: true, // Hiển thị chọn tháng
                changeYear: true, // Hiển thị chọn năm
                showButtonPanel: true, // Hiển thị nút điều khiển
            });
        });
        $(".modalExport").click(function() {
            $('#exampleModal').modal('show');

        });
        $(".btnloadMoreDoiSoat").click(function() {

            var page = parseInt($('.btnloadMoreDoiSoat').attr('data-page')) + 1;
            let data = {
                page: page,
                _token: '{{ csrf_token() }}',
            };
            console.log(data)
            $.ajax({
                type: "GET",
                url: '',
                dataType: 'json',
                data: data,
                success: function(response) {
                    console.log(response)

                    $('#data-ajax-loadmore').append(response.data.html);
                    $('.btnloadMoreDoiSoat').attr('data-page', page);
                }
            });
        });
    </script>
@endsection
