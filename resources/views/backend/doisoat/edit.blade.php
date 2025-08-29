@extends('backend.layouts.admin')

@section('page_title', 'Sửa đơn đối soát')
@section('title', 'Sửa đơn đối soát')

@section('content')
    <style>
        /* CSS cho bảng đối soát chính */
        .table-bordered th,
        .table-bordered td {
            width: 200px !important;
            min-width: 200px !important;
            max-width: 200px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table-bordered .width-250 {
            width: 250px !important;
            min-width: 250px !important;
            max-width: 250px !important;
        }

        /* CSS cho bảng đối soát phụ */
        #table_id1 th,
        #table_id1 td {
            width: 200px !important;
            min-width: 200px !important;
            max-width: 200px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Đảm bảo bảng có thể cuộn ngang */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Đảm bảo bảng không bị co lại */
        .table-bordered,
        #table_id1 {
            width: auto !important;
            table-layout: fixed;
        }

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

        /* Đảm bảo container phân trang có khoảng cách phù hợp */
        .wgp-pagination {
            margin: 30px 0;
            padding: 0 15px;
        }

        /* Ẩn nút tăng/giảm trên Chrome, Safari, Edge */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Ẩn nút tăng/giảm trên Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .page-titles {
            width: 100%;
        }

        .title_h3 {
            background-color: white;
            margin: 0 .5rem;
            padding: .5rem;
            border-radius: 5px;
        }

        .form-label {
            font-weight: bold;
            color: #084298;
        }

        #layoutSidenav_content {
            justify-content: flex-start !important;
        }

        .card {
            display: flex;
            flex-direction: row;
        }
    </style>

    <div class="container-fluid mt-4">

        <div class="col-md-12">
            <div class="card">
                <form class="col-md-12" action="{{ route('backend.doi_soat.update', $doisoat->id) }}" method="POST">
                    @csrf
                    <div class="col-md-6 p-2">
                        <div class="col-auto mb-3">
                            <label for="exampleInputEmail1" class="form-label">ID</label>
                            <input type="id" class="form-control" id="exampleInputEmail1" value="{{ $doisoat->id }}"
                                name="id" readonly>
                        </div>

                        <div class=" mb-3">
                            <label for="exampleInputEmail1" class="form-label">Mã đơn hàng</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" name="ordercode"
                                value="{{ $doisoat->OrderCode }}" readonly>
                        </div>

                        <div class=" mb-3">
                            <label for="exampleInputEmail1" class="form-label">Trạng thái COD đối soát</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" name="ordercode"
                                value="{{ $doisoat->doisoat ?: '' }}" readonly>
                        </div>

                        <div class=" mb-3">
                            <label for="exampleInputEmail1" class="form-label">COD</label>
                            <input type="number" class="form-control" id="exampleInputEmail1" name="cod"
                                value="{{ $doisoat->CODAmount }}">
                        </div>

                        <div class=" mb-3">
                            <label for="exampleInputEmail1" class="form-label">Tình trạng thu tiền GTB</label>
                            <select class="form-control" name="tinhtrangthutienGTB" id="tinhtrangthutienGTB"
                                {{ $doisoat->tinhtrangthutienGTB == 1 ? 'disabled' : '' }}>
                                <option value="">--</option>
                                <option value="1" {{ $doisoat->tinhtrangthutienGTB == 1 ? 'selected' : '' }}>TRUE
                                </option>
                            </select>
                        </div>

                        <div class=" mb-3">
                            <label for="exampleInputEmail1" class="form-label">Tiền GTB - Thu tiền</label>
                            <input type="number" class="form-control" id="exampleInputEmail1" name="cod_failed_amount"
                                value="{{ $doisoat->cod_failed_amount }}">
                        </div>

                        <div class=" mb-3">
                            <label for="exampleInputEmail1" class="form-label">Phí giao hàng</label>
                            <input type="number" class="form-control" id="exampleInputEmail1" name="mainservice"
                                value="{{ $doisoat->MainService }}">
                        </div>

                        <div class=" mb-3">
                            <label for="exampleInputEmail1" class="form-label">Tổng phí</label>
                            <input type="id" class="form-control" id="exampleInputEmail1" name="tongphi"
                                value="{{ $doisoat->tongphi }}">
                        </div>

                        <div class=" mb-3">
                            <label for="exampleInputEmail1" class="form-label">Tổng đối soát</label>
                            <input type="number" class="form-control" id="exampleInputEmail1" name="tongdoisoat"
                                value="{{ $doisoat->tongdoisoat }}">
                        </div>

                        <button class="btn btn-primary btn_submit" type="submit">Submit</button>

                    </div>

                    <div class="col-md-6 p-2">
                    </div>
                </form>
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
