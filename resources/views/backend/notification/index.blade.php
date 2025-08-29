@extends('backend.layouts.admin')

@section('page_title', 'Danh sách webhook')
@section('title', 'Danh sách webhook')

@section('content')
    <style>
        .table-responsive {
            max-height: 70vh;
        }

        .table thead tr th {
            padding: 0.5rem;
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
    </style>

    <div class="container-fluid mt-4">

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a class="btn rounded-pill btn-outline-primary" href="{{ route('backend.notification.index') }}"
                                data-bs-toggle="tooltip" title="Reset data"><i class="fa-solid fa-rotate"></i>
                            </a>
                        </div>

                        <div class="d-flex align-items-center">
                            <form action="" method="GET">

                                <div class="input-group ">
                                    <input type="text" class="form-control" placeholder="Ngày trả webhook" id="keyword"
                                        name="keyword" value="{{ request('keyword') ? request('keyword') : '' }}">
                                    <button class="btn btn-primary" type="submit" id="button-addon2"><i
                                            class="fa-solid fa-magnifying-glass"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table color-table muted-table table-striped">
                            <thead>
                                <tr class="table-primary">

                                    <th scope="col">Kênh</th>
                                    <th scope="col">Ngày gửi</th>
                                    <th scope="col">Nội dung</th>


                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifications as $notification)
                                    <tr>

                                        <td>{{ $chanels[$notification->chanel] }}</td>
                                        <td style="font-size: 10px;">{{ $notification->created_at }}</td>
                                        <td style="font-size: 10px;">{{ $notification->content }}</td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Chưa có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                        {{ $notifications->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
