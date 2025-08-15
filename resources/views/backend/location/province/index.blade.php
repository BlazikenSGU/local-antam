@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.location.province.index') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    @if(auth()->guard('backend')->user()->can('province.add'))
                        <div class="row">
                            <div class="col-md-2 pull-right">
                                <a href="{{Route('backend.location.province.add')}}"
                                   class="btn waves-effect waves-light btn-block btn-info">
                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Thêm mới
                                </a>
                            </div>
                        </div>
                    @endif

                    <form action="" method="get" id="form-filter">
                        <div class="form-body">
                            <div class="row p-t-20">

                                <div class="col-md-12">
                                    @include('backend.partials.msg')
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Tên</label>
                                        <input type="text"
                                               name="name"
                                               value="{{$filter['name']}}"
                                               id="name"
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Số record</label>
                                        <select class="form-control"
                                                name="limit">
                                            @foreach($_limits as $st)
                                                <option value="{{$st}}"
                                                        {!! $filter['limit']==$st?'selected="selected"':'' !!}>{{number_format($st)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="control-label">&nbsp;</label>
                                    <div class="btn-group" role="group" style="display: inherit">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i>Tìm
                                        </button>

                                        <a title="Clear search"
                                           href="{{Route('backend.location.province.index')}}"
                                           class="btn btn-danger">
                                            <i class="fa fa-close"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table color-table muted-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            {{--<th>ID</th>--}}
                                            <th>Tên</th>
                                            <th>Ngày tạo</th>
                                            <th>Ngày cập nhật</th>
                                            <th class="text-right">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($provinces as $key => $province)
                                            <tr>
                                                <td>{{++$start}}</td>
                                                {{--<td>{{$province->id}}</td>--}}
                                                <td>{{$province->name}}</td>
                                                <td>{{$province->created_at}}</td>
                                                <td>{{$province->updated_at}}</td>

                                                <td class="text-right">
                                                    @if(auth()->guard('backend')->user()->can('province.edit'))
                                                        <a href="{{Route('backend.location.province.edit',[$province->id]). '?_ref=' .$current_url }}"
                                                           class="btn waves-effect waves-light btn-info btn-sm">
                                                            <i class="fa fa-pencil-square-o"></i> Sửa</a>
                                                    @endif

                                                    @if(auth()->guard('backend')->user()->can('province.del'))
                                                        <a href="{{Route('backend.location.province.del',[$province->id]) . '?_ref=' .$current_url }}"
                                                           class="btn waves-effect waves-light btn-danger btn-sm" data-bb="confirm">
                                                            <i class="fa fa-trash-o"></i> Xóa</a>
                                                    @endif

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">-</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center">
                                {{ $provinces->links() }}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection