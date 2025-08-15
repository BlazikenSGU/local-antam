@extends('backend.layouts.main2222')

@section('title', 'Quản lý tài khoản')

@section('content')
    <style>
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

        .color_a {
            color: white;
            text-decoration: none;
        }
    </style>

    <div class="container-fluid mt-4">

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <div class="row page-titles mb-3">
                        <div class="col-md-5 align-self-center">
                            <h3>Danh sách tài khoản</h3>
                        </div>
                    </div>

                    {{-- <form action="" method="get" id="form-filter">
                        <div class="form-body">
                            <div class="row p-t-20">

                                <div class="col-md-12">
                                    @include('backend.partials.msg')
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold" for="phone">SĐT</label>
                                        <input type="phone" name="phone" value="{{ $filter['phone'] }}" id="phone"
                                            class="form-control" placeholder="SĐT">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label font-weight-bold">Trạng thái</label>
                                        <select class="form-control custom-select" name="status">
                                            <option value="" {!! empty($filter['status']) ? 'selected="selected"' : '' !!}>
                                                Tất cả
                                            </option>
                                            <option value="{{ \App\Models\CoreUsers::STATUS_REGISTERED }}"
                                                {!! $filter['status'] == \App\Models\CoreUsers::STATUS_REGISTERED ? 'selected="selected"' : '' !!}>
                                                Đang hoạt động
                                            </option>
                                            <option value="{{ \App\Models\CoreUsers::STATUS_NEWACCOUNT }}"
                                                {!! $filter['status'] == \App\Models\CoreUsers::STATUS_NEWACCOUNT ? 'selected="selected"' : '' !!}>
                                                Tài khoản mới
                                            </option>
                                            <option value="{{ \App\Models\CoreUsers::STATUS_BANNED }}"
                                                {!! $filter['status'] == \App\Models\CoreUsers::STATUS_BANNED ? 'selected="selected"' : '' !!}>
                                                Đã bị cấm
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">&nbsp;</label>
                                    <div class="btn-group" role="group" style="display: inherit">
                                        <button type="submit" class="btn"
                                            style="background-color: #00467F; color: #fff">
                                            <i class="fa fa-search"></i>Tìm
                                        </button>

                                        <a title="Clear search" href="{{ Route('backend.staff.index') }}" class="btn"
                                            style="background-color: #F26522; color: #fff">
                                            <i class="fa fa-close"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form> --}}

                    <div class="table-responsive">
                        <table class="table color-table muted-table font-weight-bold">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>SĐT</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đăng ký</th>
                                    <th>Lần cuối online</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $key => $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->fullname ?? '' }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>
                                            @if ($user->status == \App\Models\CoreUsers::STATUS_BANNED)
                                                <span class="badge bg-danger">Đã bị cấm</span>
                                            @elseif($user->status == \App\Models\CoreUsers::STATUS_NEWACCOUNT)
                                                <span class="badge bg-primary">Tài khoản mới</span>
                                            @else
                                                <span class="badge bg-success">Đang hoạt động</span>
                                            @endif

                                        </td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>{{ $user->last_login ?: '' }}</td>
                                        <td class="text-right">

                                            <a href="{{ route('backend.staff.edit', $user->id) }}" class="color_a">
                                                <button class="btn btn-sm btn-primary"><i
                                                        class="fa-solid fa-pen-to-square"></i></button>
                                            </a>
                                            @php
                                                $check = \App\Models\Orders::where('user_id', $user->id)->first();

                                            @endphp
                                            <a data-bb="confirm" onclick="return confirm('Xóa nhân viên này?')"
                                                class=" btn color_a btn-sm 
                                                {{ $check == null ? '' : 'disabled' }}
                                                {{ $check == null ? 'btn-danger' : 'btn-secondary' }} 
                                                {{ $user->id == 168 ? 'd-none' : '' }}"
                                                href="{{ route('backend.staff.delete', $user->id) }}"><i
                                                    class="fa-solid fa-trash "></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">-</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                        {{ $users->links('pagination::bootstrap-4') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
