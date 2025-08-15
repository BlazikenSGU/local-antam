@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.discount.index') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">
                    @if(auth()->guard('backend')->user()->can('posts.add'))
                        <div class="row">
                            <div class="col-md-2 pull-right">
                                <a href="{{Route('backend.discount.add')}}"
                                   class="btn waves-effect waves-light btn-block btn-info">
                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Thêm mới
                                </a>
                            </div>
                        </div>
                    @endif
                    <br>
                    @include('backend.partials.msg')
                    @include('backend.partials.errors')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table color-table muted-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tiêu đề</th>
                                            <th>Code</th>
                                            <th>Loại</th>
                                            <th>Giá trị</th>
                                            <th>Số lần sử dụng</th>
                                            <th>Thời hạn</th>
                                            <th>Ngày tạo</th>
                                            <th>Ngày cập nhật</th>
                                            <th class="text-right">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($list_data as $key => $item)
                                            <tr>
                                                <td>{{++$start}}</td>
                                                <td>
                                                    {{$item->title}}
                                                </td>
                                                <td>
                                                    {{$item->code}}
                                                </td>
                                                <td>
                                                    {{$item->type==1?'Giảm số tiền':'Giảm %'}}
                                                </td>
                                                <td>
                                                    {{$item->value}}
                                                </td>
                                                <td>
                                                    {{$item->used_count}} / {{$item->limit}}
                                                </td>
                                                <td>
                                                    {{$item->start_date}} <br> {{$item->end_date}}
                                                </td>
                                                <td>
                                                    {{$item->created_at}}
                                                </td>
                                                <td>
                                                    {{$item->updated_at}}
                                                </td>

                                                <td class="text-right">
                                                    @if(auth()->guard('backend')->user()->can('posts.edit'))
                                                        <a href="{{Route('backend.discount.edit',[$item->id]). '?_ref=' .$current_url }}"
                                                           class="btn waves-effect waves-light btn-info btn-sm">
                                                            <i class="fa fa-pencil-square-o"></i> Sửa</a>
                                                    @endif

                                                    @if(auth()->guard('backend')->user()->can('posts.del'))
                                                        <a href="{{Route('backend.discount.del',[$item->id]) . '?_ref=' .$current_url }}"
                                                           class="btn waves-effect waves-light btn-danger btn-sm"
                                                           data-bb="confirm">
                                                            <i class="fa fa-trash-o"></i> Xóa</a>
                                                    @endif

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">-</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{--pagination--}}
                            <div class="text-center">
                                {{ $list_data->links() }}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
