@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.policy.index') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">
                    @if(auth()->guard('backend')->user()->can('policy.add'))
                        <div class="row">
                            <div class="col-md-2 pull-right">
                                <a href="{{Route('backend.policy.add')}}"
                                   class="btn waves-effect waves-light btn-block btn-info">
                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Thêm mới
                                </a>
                            </div>
                        </div>
                    @endif
                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table color-table muted-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="min-width: 100px">Hình</th>
                                            <th>Tên bài viết</th>
                                            <th style="min-width: 200px">Danh mục</th>
                                            <th style="min-width: 110px">Trạng thái</th>
                                            <th class="text-right" style="min-width: 150px">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($list_data as $key => $item)
                                            <tr>
                                                <td>{{++$start}}</td>
                                                <td>
                                                    @if($item->thumbnail)
                                                        <img src="{{$item->thumbnail->file_src}}" height="40"
                                                             class="image-border image-popup-no-margins" href="{{$item->thumbnail->file_src}}"/>
                                                    @endif
                                                </td>
                                                <td>{{$item->name}}<br>
                                                <small>Tạo lúc: {{$item->created_at}} - Cập nhật lúc: {{$item->updated_at}}</small></td>
                                                <td>{{isset($item->category->name)?$item->category->name:''}}</td>
                                                <td>
                                                    @foreach($status as $st)
                                                        @if($st['id']==$item->status)
                                                            {{$st['name']}}
                                                            @break
                                                        @endif
                                                    @endforeach
                                                </td>

                                                <td class="text-right">
                                                    @if(auth()->guard('backend')->user()->can('policy.edit'))
                                                        <a href="{{Route('backend.policy.edit',[$item->id]). '?_ref=' .$current_url }}"
                                                           class="btn waves-effect waves-light btn-info btn-sm">
                                                            <i class="fa fa-pencil-square-o"></i> Sửa</a>
                                                    @endif

                                                    @if(auth()->guard('backend')->user()->can('policy.del'))
                                                        <a href="{{Route('backend.policy.del',[$item->id]) . '?_ref=' .$current_url }}"
                                                           class="btn waves-effect waves-light btn-danger btn-sm" data-bb="confirm">
                                                            <i class="fa fa-trash-o"></i> Xóa</a>
                                                    @endif

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">-</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
