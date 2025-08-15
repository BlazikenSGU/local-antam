@extends('backend.layouts.main2222')

@section('title', 'Danh sách shopId')

@section('content')
    <style>
        .row.page-titles {
            margin: 2rem 1rem;
        }

        .color_a {
            color: white;
            text-decoration: none;
        }
    </style>


    <div class="row page-titles">

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">
                    <div class="col-md-5 align-self-center mb-3">
                        <h3 class="text-themecolor">ShopId</h3>
                    </div>

                    <div class="row">
                        <div class="col-md-2 pull-right">
                            <a href="{{ Route('backend.brands.create') }}"
                                class="btn waves-effect waves-light btn-block btn-info">
                                <i class="fa fa-plus"></i>&nbsp;&nbsp;Thêm mới
                            </a>
                        </div>
                    </div>
                    <br>
                    @include('backend.partials.msg')
                    @include('backend.partials.errors')

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table color-table muted-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên shopid</th>
                                            <th>ShopId</th>
                                            <th>Type</th>
                                            <th>token</th>
                                            <th>From weight(kg)</th>
                                            <th>To weight(kg)</th>
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
                                                    <span class="badge {{ $item->type == 2 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $item->type == 2 ? 'Kênh bán hàng' : 'Tạo đơn thủ công' }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->token ?: '' }}</td>
                                                <td>
                                                    {{ $item->from_weight }}
                                                </td>
                                                <td>
                                                    {{ $item->to_weight }}
                                                </td>
                                                <td>
                                                    {{ $item->name_show }}
                                                </td>
                                                <td>

                                                    <span
                                                        class="badge {{ $item->use_create_order == 1 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $item->use_create_order == 1 ? 'Có' : 'Không' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $item->service_type_id ?: '' }}
                                                </td>

                                                <td class="text-right">

                                                    <a href="{{ route('backend.brands.edit', [$item->id]) }}"
                                                        class="color_a">
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


                            <div class="text-center">
                                {{ $data->links() }}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- @include('backend.branch.modal') --}}
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
