@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.location.district.edit') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <form class="form-horizontal" action="" method="post">

                        {{ csrf_field() }}
                        <div class="row">

                            <div class="col-md-4" style="margin: auto">

                                @include('backend.partials.msg')
                                @include('backend.partials.errors')

                                <div class="form-group">
                                    <label class="form-control-label">Tỉnh/Thành phố
                                        <span class="text-danger">*</span></label>
                                    <select class="form-control  select_province select2" name="province_id" required="required">
                                        <option value="">Chọn</option>
                                        @foreach($provinces as $province)
                                            <option value="{{$province->id}}"
                                                    {!! old('province_id',$district->province_id)==$province->id?'selected="selected"':'' !!}>{{$province->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label">Tên <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control col-md-3" required="required"
                                                name="type">
                                            <option value="Quận" {!! old('type',$district->type) =='Quận' ? 'selected="selected"' : '' !!}>
                                                Quận
                                            </option>
                                            <option value="Huyện" {!! old('type',$district->type)== 'Huyện' ? 'selected="selected"' : '' !!}>
                                                Huyện
                                            </option>
                                        </select>
                                        <input type="text"
                                               class="form-control col-md-9"
                                               name="name_origin" required="required"
                                               value="{{old('name_origin',$district->name_origin)}}">
                                    </div>
                                </div>

                                <div class="form-group text-center">
                                    <button class="btn btn-info" type="submit">Cập nhật</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection