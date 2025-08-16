@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.warehouses.index') }}

        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <form class="form-horizontal form-bordered"
                          action="{{ $isEditable ? Route('backend.warehouses.update',['id'=>$warehouse->id]) : Route('backend.warehouses.store') }}"
                          method="post">
                        @csrf
                        <div class="x">
                            <div class="form-group row">
                                <label class="control-label text-left col-md-2" for="name">
                                    Tên Bàn<span style="color: red"> (*)</span>
                                </label>
                                <div class="col-md-10">
                                    <input type="text"
                                           name="name"
                                           value="{{old('name',!empty($warehouse) ? $warehouse->name : '')}}"
                                           class="form-control"
                                           id="name"
                                           placeholder="Vui lòng nhập tên bàn ">
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback" style="display:block">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if(count($branch) > 0)
                                <div class="form-group row">
                                    <label class="control-label text-left col-md-2" for="branch_id">
                                        Chi nhánh<span style="color: red"> (*)</span>
                                    </label>
                                    <div class="col-md-10">
                                        <select class="form-control" name="branch_id" id="branch_id">
                                            @foreach ($branch as $item)
                                                <option value="{{ $item->id }} "
                                                @if(!empty($warehouse) && $warehouse->branch_id == $item->id) selected @endif
                                                >
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            @endif


                        </div>

                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <div class="form-group">
                                    @if($isEditable)
                                        <button class="btn btn-info" type="submit">Cập nhật</button>
                                    @else
                                        <button class="btn btn-info" type="submit">Tạo mới</button>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link href="{{ asset('/storage/backend')}}/assets/plugins/codemirror/lib/codemirror.css" rel="stylesheet">
    <link href="{{ asset('/storage/backend')}}/assets/plugins/codemirror/theme/monokai.css" rel="stylesheet">
@stop

@section('script')
    <script type="text/javascript">

        $(document).ready(function () {

        });

        function sendFileToServer(formData, d) {

            $.ajax({
                url: "",
                type: "POST",
                contentType: false,
                processData: false,
                cache: false,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    if (data.e) {
                        alert(data.r);
                    } else {
                        d.parent().find('img').attr('src', data.r[0].url);
                        d.parent().find('input.upload_img_value').val(data.r[0].path);
                        d.parent().parent().find('.upload_img_select').val('');
                    }
                }
            });

        }
    </script>
@stop
