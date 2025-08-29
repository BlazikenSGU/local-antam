@extends('backend.layouts.admin')

@section('title', 'Select picker test')

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


                    <br>
                    <div class="col-md-2 pull-right">
                        <select id="userSelect" class="selectpicker" data-live-search="true" title="Chọn người dùng">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" data-tokens="{{ $user->id }} {{ $user->fullname }}">
                                    {{ $user->fullname }} (UID: {{ $user->id }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table color-table muted-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Mã</th>
                                            <th>UID</th>
                                            <th>fullname</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderTableBody">
                                        @forelse($orders as $key => $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>
                                                    {{ $item->order_code }}
                                                </td>
                                                <td>
                                                    {{ $item->user_id }}
                                                </td>
                                                <td>{{ $item->fullname ?: '' }}</td>


                                                <td class="text-right">

                                                    <a href="{{ route('backend.brands.edit', [$item->id]) }}"
                                                        class="color_a">
                                                        <button class="btn btn-sm btn-primary"> <i
                                                                class="fa-solid fa-pen-to-square"></i></button>
                                                    </a>

                                                    <a href="#" class="btn color_a btn-danger btn-sm btnShowModal"
                                                        data-id="{{ $item->id }}">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
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
                                {{ $orders->links() }}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script>
        $(function() {
            $('.selectpicker').selectpicker();

            $('#userSelect').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
                var userId = $(this).val();

                if (userId) {
                    $.ajax({
                        url: '{{ route('backend.ajax.orders_by_user') }}',
                        type: 'GET',
                        data: {
                            user_id: userId
                        },
                        success: function(response) {
                            let html = '';

                            if (response.data.length) {
                                response.data.forEach(function(order) {
                                    html += `<tr>
                                                <td>${order.id}</td>
                                                <td>${order.order_code}</td>
                                                <td>${order.user_id}</td>
                                                <td>${order.fullname || ''}</td>
                                                <td class="text-right">
                                                    <a href="{{ url('backend/brands/edit') }}/${order.id}" class="color_a">
                                                        <button class="btn btn-sm btn-primary"> <i class="fa-solid fa-pen-to-square"></i></button>
                                                    </a>
                                                    <a href="#" class="btn color_a btn-danger btn-sm btnShowModal" data-id="${order.id}">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>    
                                            </td>
                                            </tr>`;
                                });
                            } else {
                                html = `<tr><td colspan="10">(null)</td></tr>`;
                            }

                            $('#orderTableBody').html(html);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching orders:', error);
                        }
                    });
                } else {
                    $('#orderTableBody').empty();
                }
            });

        })
    </script>

@stop
