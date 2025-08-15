@extends('frontend.layouts.main')

@push('title')
    Quản lý cửa hàng
@endpush

@section('content')
    <style>
        /* @media (max-width: 768px) and (min-width: 375px) {
            th.address-column {
                min-width: 200px !important;
            }
        } */

        @media (max-width: 650px) {

            .table-bordered th,
            .table-bordered td,
            .table-bordered a,
            .table-bordered span {
                font-size: 8px;
            }

            label.switch {
                width: 27px !important;
                height: 15px !important;
            }

        }
    </style>
    <div class="">

        <div class="col-md-12 mt-4">
            <div class="card card-outline-info">
                <div class="card-body">

                    <div class="row">
                        <h2>Quản lý cửa hàng</h2>

                        <div class=" pull-right">
                            <a href="{{ route('user.mystore.addstore') }}" class="btn btn-primary">Thêm cửa hàng</a>
                        </div>

                        <div>
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Tên</th>
                                            <th scope="col" class="address-column">Địa chỉ</th>
                                            <th scope="col">Mặc định</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($address as $item)
                                            <tr>
                                                <td> <a href="{{ route('user.mystore.edit', $item->id) }}">
                                                        {{ $item->name }} </a></td>
                                                <td>
                                                    {{ $item->phone }} - {{ $item->street_name }}
                                                    - {{ $item->ward_name }} - {{ $item->district_name }}
                                                    - {{ $item->province_name }}
                                                </td>
                                                <td class="text-right">
                                                    <div class="checkbox-wrapper-64">
                                                        <label class="switch">
                                                            <input type="checkbox"
                                                                @if ($item->is_default == 1) checked @endif
                                                                class="checkbox" data-id="{{ $item->id }}">
                                                            <span class="slider"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card card-outline-info mt-4">
                <div class="card-body">
                    <h2>Quản lý sản phẩm</h2>
                    <div class="pull-right">
                        <a href="{{ route('user.mystore.addproduct') }}" class="btn btn-primary">Thêm sản phẩm</a>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Tên sản phẩm</th>
                                    <th scope="col">sku</th>
                                    <th scope="col">Khối lượng (gram)</th>
                                    <th scope="col">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product as $item)
                                    <tr>
                                        <td> <a href="{{ route('user.mystore.editproduct', $item->id) }}">
                                                {{ $item->name }}
                                            </a>
                                        </td>
                                        <td>{{ $item->product_code }}</td>
                                        <td>{{ $item->amount }}</td>
                                        <td>
                                            @if ($item->status == 1)
                                                <span class="badge text-bg-success">on</span>
                                            @else
                                                <span class="badge text-bg-danger">off</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModalTestStatusOrder" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('backend.ops-live.change.file') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="input-group mb-3">

                                <input type="file" name="file" class="form-control" placeholder="Username"
                                    aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Tải</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        .checkbox-wrapper-64 input[type="checkbox"] {
            visibility: hidden;
            display: none;
        }

        .checkbox-wrapper-64 *,
        .checkbox-wrapper-64 ::after,
        .checkbox-wrapper-64 ::before {
            box-sizing: border-box;
        }

        /* The switch - the box around the slider */
        .checkbox-wrapper-64 .switch {
            font-size: 17px;
            position: relative;
            display: inline-block;
            width: 3.5em;
            height: 2em;
        }

        /* Hide default HTML checkbox */
        .checkbox-wrapper-64 .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .checkbox-wrapper-64 .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #fff;
            border: 1px solid #adb5bd;
            transition: .4s;
            border-radius: 30px;
        }

        .checkbox-wrapper-64 .slider:before {
            position: absolute;
            content: "";
            height: 1.4em;
            width: 1.4em;
            border-radius: 20px;
            left: 0.27em;
            bottom: 0.25em;
            background-color: #adb5bd;
            transition: .4s;
        }

        .checkbox-wrapper-64 input:checked+.slider {
            background-color: #196d1e;
            border: 1px solid #196d1e;
        }

        .checkbox-wrapper-64 input:focus+.slider {
            box-shadow: 0 0 1px #007bff;
        }

        .checkbox-wrapper-64 input:checked+.slider:before {
            transform: translateX(1.4em);
            background-color: #fff;
        }
    </style>
    <link rel="stylesheet" href="/extends/jsx/products.css?v=0.0.2.1">
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.checkbox').on('change', function() {
                var addressId = $(this).data('id');

                if ($(this).is(':checked')) {
                    $('.checkbox').not(this).prop('checked', false);

                    $.ajax({
                        url: '/user/address/set-default', // Cập nhật URL phù hợp với route
                        type: 'POST', // Đảm bảo method là POST
                        data: {
                            address_id: addressId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                alert('Đã cập nhật địa chỉ mặc định');
                            }
                        },
                        error: function() {
                            $(this).prop('checked', false);
                            alert('Có lỗi xảy ra');
                        }
                    });
                } else {
                    $(this).prop('checked', true);
                    alert('Phải có ít nhất 1 địa chỉ mặc định');
                }
            });
        });
    </script>

    @if (session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Thông báo!',
                    text: "{{ session('warning') }}",
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Đã hiểu'
                });
            });
        </script>
    @endif
@endsection
