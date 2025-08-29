@extends('backend.layouts.admin')

@section('title', 'Danh sách shopId')
@section('page_title', 'Danh sách ShopId')

@section('content')
    <style>
        .row.page-titles {
            margin: 2rem 1rem;
        }

        .color_a {
            color: white;
            text-decoration: none;
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

        .table thead tr th {
            padding: 0.5rem;
        }

        .table-responsive {
            max-height: 70vh;
        }
    </style>


    <div class="container-fluid mt-4">

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ Route('backend.brands.create') }}" class="btn rounded-pill btn-outline-primary"
                                data-bs-toggle="tooltip" title="Thêm mới">
                                <i class="fa-solid fa-calendar-plus"></i>
                            </a>
                            <a class="btn rounded-pill btn-outline-primary" href="{{ route('backend.brands.index') }}"
                                data-bs-toggle="tooltip" title="Reset data"><i class="fa-solid fa-rotate"></i></a>
                        </div>

                        <div class="d-flex align-items-center">
                            <form action="{{ route('backend.orders.search') }}" method="GET">

                                <div class="input-group ">
                                    <input type="text" class="form-control" placeholder="Mã đơn, sđt, tên" id="keyword"
                                        name="keyword" value="{{ request('keyword') ? request('keyword') : '' }}">
                                    <button class="btn btn-primary" type="submit" id="button-addon2"><i
                                            class="fa-solid fa-magnifying-glass"></i></button>
                                </div>
                            </form>
                        </div>

                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered" style=" overflow-x: auto;">
                            <thead>
                                <tr class="table-primary">
                                    <th>ID</th>
                                    <th>Tên shopid</th>
                                    <th>ShopId</th>
                                    <th>Type</th>
                                    <th>token</th>
                                    <th>weight(kg)</th>
                                    <th>name_show</th>
                                    <th>Dùng tạo đơn</th>
                                    <th>ServiceTypeID</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $key => $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>
                                            {{ $item->name }}
                                        </td>
                                        <td>
                                            {{ $item->shopId }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $item->type == 2 ? 'bg-success rounded-pill' : 'bg-danger rounded-pill' }}">
                                                {{ $item->type == 2 ? 'Kênh bán hàng' : 'Tạo đơn thủ công' }}
                                            </span>
                                        </td>
                                        <td style="max-width: 150px">{{ $item->token ?: '' }}</td>
                                        <td>
                                            {{ $item->from_weight }} - {{ $item->to_weight }}
                                        </td>

                                        <td>
                                            {{ $item->name_show }}
                                        </td>
                                        <td>

                                            <span
                                                class="badge {{ $item->use_create_order == 1 ? 'bg-success rounded-pill' : 'bg-danger rounded-pill' }}">
                                                {{ $item->use_create_order == 1 ? 'Có' : 'Không' }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $item->service_type_id ?: '' }}
                                        </td>

                                        <td class="text-right">

                                            <a href="{{ route('backend.brands.edit', [$item->id]) }}" class="color_a">
                                                <button class="btn btn-sm btn-primary"> <i
                                                        class="fa-solid fa-pen-to-square"></i></button>
                                            </a>

                                            <a href="#" class="btn color_a btn-danger btn-sm btnShowModal"
                                                data-id="{{ $item->id }}">
                                                <i class="fa-solid fa-trash"></i></a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">(null)</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                        {{ $data->links('pagination::bootstrap-4') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Thông báo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có muốn xóa không?
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="delete_id" value="0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-info btnDelete">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

            $(document).on('click', 'a.btnShowModal', function(e) {
                let delete_id = $(this).data('id');
                $('#delete_id').val(delete_id);
                e.preventDefault();
                deleteModal.show();
            });

            $(document).on('click', 'button.btnDelete', function(e) {
                let delete_id = $('#delete_id').val();
                let data = {
                    _token: '{{ csrf_token() }}',
                    id: delete_id
                }
                $.ajax({
                    type: 'POST',
                    url: '{{ Route('backend.brands.delete') }}',
                    dataType: 'json',
                    data: data,
                    success: function(json) {
                        window.location.reload();
                    }
                })
            });
        });
    </script>
@stop
