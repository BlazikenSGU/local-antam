@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.location.district.index') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

{{--                    @if(auth()->guard('backend')->user()->can('district.add'))--}}
                        <div class="row">
                            <div class="col-md-2 pull-right">
                                <a href="{{Route('backend.location.district.add')}}"
                                   class="btn waves-effect waves-light btn-block btn-info">
                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Thêm mới
                                </a>
                            </div>
                        </div>
{{--                    @endif--}}

                    <form action="" method="get" id="form-filter">
                        <div class="form-body">
                            <div class="row p-t-20">

                                <div class="col-md-12">
                                    @include('backend.partials.msg')
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label" for="province_id">Tỉnh/TP</label>
                                        <select class="form-control select_province select2 form-control-sm"
                                                style="width: 100%; height:36px;" name="province_id">
                                            <option value="">Tất cả</option>
                                            @foreach($provinces as $province)
                                                <option value="{{$province->id}}"
                                                        {!! request('province_id')==$province->id?'selected="selected"':'' !!}
                                                >{{$province->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                           href="{{Route('backend.location.district.index')}}"
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
                                            <th>Tỉnh/TP</th>
                                            <th>Ngày tạo</th>
                                            <th>Ngày cập nhật</th>
                                            <th class="text-right">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($districts as $key => $district)
                                            <tr>
                                                <td>{{++$start}}</td>
                                                {{--<td>{{$district->id}}</td>--}}
                                                <td>{{$district->name}}</td>
                                                <td>{{$district->province->name}}</td>
                                                <td>{{$district->created_at}}</td>
                                                <td>{{$district->updated_at}}</td>

                                                <td class="text-right">
{{--                                                    @if(auth()->guard('backend')->user()->can('district.edit'))--}}
                                                        <a href="{{Route('backend.location.district.edit',[$district->id]). '?_ref=' .$current_url }}"
                                                           class="btn waves-effect waves-light btn-info btn-sm">
                                                            <i class="fa fa-pencil-square-o"></i> Sửa</a>
{{--                                                    @endif--}}

{{--                                                    @if(auth()->guard('backend')->user()->can('district.del'))--}}
                                                        <a href="{{Route('backend.location.district.del',[$district->id]) . '?_ref=' .$current_url }}"
                                                           class="btn waves-effect waves-light btn-danger btn-sm" data-bb="confirm">
                                                            <i class="fa fa-trash-o"></i> Xóa</a>
{{--                                                    @endif--}}

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

                            <div class="text-center">
                                {{ $districts->links() }}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('style_top')
    <link href="{{ asset('/storage/backend/assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('script')
    <script src="{{ asset('/storage/backend/assets/plugins/select2/dist/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script>
        $('.select2').select2();
    </script>
@stop
