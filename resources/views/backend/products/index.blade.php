@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.products.index') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">
                    @include('backend.products.formFilter')

                    <div class="ajax-result">
                        @include('backend.products.ajaxList')
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('style_top')
    <style>
        .select2 {
            width: 100% !important;
            height: 36px !important;
        }

        .checkbox-basic {
            position: initial !important;
            left: initial !important;
            opacity: 1 !important;
        }

        a.sort.active {
            color: red;
        }

        .sort_btn {
            margin-top: 10px;
        }
    </style>
@stop

@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script>

        $(document).on('click', 'ul.pagination li a', function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            //window.history.pushState(window.location.href, "", url);
            load_product(url);

        });

        $(document).on('click', 'a.sort', function (event) {
            event.preventDefault();
            var sort = $(this).data('sort');
            $('#form-filter .input_sort').val(sort);
            var data = $('#form-filter').serialize();
            var url = $('#form-filter').attr('action');
            load_product(url, data)
        });

        function load_product(url, params) {
          //  alert(url);
            var params = params !== undefined ? params : null;
            $.ajax({
                url: url,
                type: 'get',
                data: params,
                success: function (data) {
                    if (data.e == 0) {
                        $('div.ajax-result').html(data.r);
                        $('html, body').animate({scrollTop: 0}, 0);
                    }

                }
            });
        }

        $('#form-filter').on('change', 'select', function () {
            var data = $('#form-filter').serialize();
            var url = $('#form-filter').attr('action');
            load_product(url, data)
        });

        var timeoutID = null;
        $('#form-filter').on('keyup', 'input', function () {
            var data = $('#form-filter').serialize();
            var url = $('#form-filter').attr('action');
            clearTimeout(timeoutID);

            timeoutID = setTimeout(function () {
                load_product(url, data)
            }, 500);
        });

        $(document).on('click', '#un_approved_btn', function (event) {
            var x = getChecked();
            if (!x)
                return;
            var r = confirm("Ẩn các sản đã chọn?");
            if (r == true) {
                $.ajax({
                    url: '{{route('backend.products.ajax.un_approved')}}',
                    type: 'post',
                    data: {product_ids: val, _token: '{{csrf_token()}}'},
                    success: function (data) {
                        alert('Ẩn sản phẩm thành công!');
                        window.location.reload();
                    }
                });
            }
        });

        $(document).on('click', '#delete_btn', function (event) {

            var x = getChecked();
            if (!x)
                return;
            var r = confirm("Xóa các sản phẩm đã chọn?");
            if (r == true) {
                $.ajax({
                    url: '{{route('backend.products.ajax.delete')}}',
                    type: 'post',
                    data: {product_ids: val, _token: '{{csrf_token()}}'},
                    success: function (data) {
                        alert('Đã xóa thành công!');
                        window.location.reload();
                    }
                });
            }
        });

        $(document).on('click', '#approved_btn', function (event) {
            var x = getChecked();
            if (!x)
                return;
            var r = confirm("Hiển thị các sản phẩm đã chọn?");
            if (r == true) {
                $.ajax({
                    url: '{{route('backend.products.ajax.approved')}}',
                    type: 'post',
                    data: {product_ids: val, _token: '{{csrf_token()}}'},
                    success: function (data) {
                        alert('Hiển thị sản phẩm thành công!');
                        window.location.reload();
                    }
                });
            }
        });

    </script>
@stop
