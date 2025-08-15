@extends('backend.layouts.main2222')

@section('content')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{ $subtitle }}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.notification.index') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    @if (auth()->guard('backend')->user()->can('notification.add'))
                        <div class="row">
                            <div class="col-md-2 pull-right">
                                <a href="{{ Route('backend.notification.add') }}"
                                    class="btn waves-effect waves-light btn-block btn-info">
                                    <i class="fa fa-plus"></i>&nbsp;Push thông báo
                                </a>
                            </div>
                        </div>
                    @endif

                    <br>

                    <div class="table-responsive">
                        <table class="table color-table muted-table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    {{-- <th>Tiêu đề</th> --}}
                                    <th>Kênh</th>
                                    <th>Nội dung</th>
                                    
                                    <th>Ngày gửi</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifications as $notification)
                                    <tr>
                                        <td>{{ ++$start }}</td>
                                        {{-- <td>{{ $notification->title }}</td> --}}
                                        <td>{{ $chanels[$notification->chanel] }}</td>
                                        <td style="font-size: 10px;">{{ $notification->content }}</td>
                                       
                                        </td>
                                        <td>{{ $notification->created_at }}</td>
                                        <td>

                                            <a href=" {{ route('backend.notification.push', [$notification->id]) }} "
                                                class="btn waves-effect waves-light btn-info btn-sm" data-toggle="tooltip"
                                                data-placement="top" title="Đẩy lại thông báo">
                                                <i class="fa fa-pencil-square-o"></i> </a>

                                            <a href="{{ route('backend.notification.delete', [$notification->id]) }}  "
                                                id="delete" class="btn waves-effect waves-light btn-danger btn-sm"
                                                data-bb="confirm" data-toggle="tooltip" data-placement="top" title="Xóa">
                                                <i class="fa fa-trash-o"></i> </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Chưa có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
