@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.location.province.edit') }}
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
                                    <label class="form-control-label">Tên <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control col-md-4" required="required"
                                                name="type">
                                            <option value="Tỉnh" {!! old('type',$province->type) =='Tỉnh' ? 'selected="selected"' : '' !!}>
                                                Tỉnh
                                            </option>
                                            <option value="Thành phố" {!! old('type',$province->type)== 'Thành phố' ? 'selected="selected"' : '' !!}>
                                                Thành phố
                                            </option>
                                        </select>
                                        <input type="text"
                                               class="form-control col-md-8"
                                               name="name_origin" required="required"
                                               value="{{old('name_origin',$province->name_origin)}}">
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