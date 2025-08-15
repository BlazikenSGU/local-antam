@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.products.inventory.index') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">
                    @include('backend.products.formFilter')

                    <div class="ajax-result">
                        @include('backend.products.inventory.ajaxList')
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
    {{--    <script src="{{ asset('/storage/backend')}}/js/inventory.js"></script>--}}

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


        setTimeout(function () {
            $('.alert-success').hide();
        }, 2000);

        $('.set_btn').click(function () {
            $(this).parent().parent().find('.input_type_update').val('set');
            $(this).parent().parent().find('.change_btn').removeClass('active');
            $(this).addClass('active');

            var root_td = $(this).parent().parent().parent().parent().parent();
            calculator_inventory(root_td)
        });

        $('.change_btn').click(function () {
            $(this).parent().parent().find('.input_type_update').val('change');
            $(this).parent().parent().find('.set_btn').removeClass('active');
            $(this).addClass('active');

            var root_td = $(this).parent().parent().parent().parent().parent();
            calculator_inventory(root_td)
        });

        $('.input-update-inventory').on('input', function () {
            var root_td = $(this).parent().parent().parent().parent().parent();
            calculator_inventory(root_td)
        });

        function calculator_inventory(root_td) {
            var old_quantity = parseInt(root_td.find('.inventory-quantity').data('value'));
            var type = root_td.find('.input_type_update').val();
            var value = root_td.find('.input-update-inventory').val();

            value = parseInt(value);

            if (type == 'change') {
                var new_quantity = old_quantity + value;
            } else {
                var new_quantity = value;
            }

            if (new_quantity < 0) {
                new_quantity = 0;

                if (type == 'change') {
                    root_td.find('.input-update-inventory').val(-old_quantity);
                } else {
                    root_td.find('.input-update-inventory').val(0);
                }
            }
            console.log(new_quantity)
            root_td.find('.submit-update-inventory').removeAttr('disabled');
            root_td.find('.inventory-quantity-arrow').removeClass('hide');
            root_td.find('.inventory-quantity--modified').html(new_quantity).removeClass('hide');
            root_td.find('.input_new_inventory').val(new_quantity);
        }

        $('.submit-update-inventory').click(function () {
            if ($(this).attr('disabled') != undefined) {
                return false;
            }

            var root_td = $(this).parent().parent().parent().parent();
            $.ajax({
                type: "POST",
                url: root_td.find('.form-update-inventory').attr('action'),
                data: root_td.find('.form-update-inventory').serialize(),
                cache: false,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if (data.e == 0) {
                        Swal.fire({
                            title: "Đã lưu",
                            icon: "success"
                        }).then(function () {
                            window.location.reload();
                        });

                        // root_td.parent().find('.inventory-quantity').html(data.i);
                        // root_td.parent().find('.inventory-quantity-arrow').addClass('hide');
                        // root_td.parent().find('.inventory-quantity--modified').addClass('hide');
                        // root_td.parent().find('.submit-update-inventory').attr('disabled', 'disabled');
                        // root_td.parent().find('.input-update-inventory').val(0)
                    } else {
                        alert(data.r);
                    }
                }
            });

        })

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    </script>
@stop
