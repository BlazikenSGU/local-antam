@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('backend.products.attributes.values.add') }}
        </div>

        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <form class="form-horizontal" action="" method="post">

                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                @include('backend.partials.msg')
                                @include('backend.partials.errors')
                            </div>

                            <div class="col-md-8 offset-2">
                                <input type="hidden" name="variation_id" value="{{$variation_id}}">

                                <div class="form-group">
                                    <label class="form-control-label">Giá trị thuộc tính
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           name="value" required="required"
                                           value="{{old('value')}}">
                                </div>

                                <div class="form-group text-center">
                                    <a href="{{route('backend.products.attributes.values.index',$variation_id)}}" class="btn btn-info btn-flat">
                                        <i class="fa fa-reply"></i> &nbsp; Quay lại
                                    </a>

                                    <button class="btn btn-primary" type="submit">Thêm</button>
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
    <style>
        #form-add label {
            font-size: 13px;
        }

        .result {
            display: inline-block;
        }

        .thumb-nail-view {
            /*width: 32%;*/
            /*height: 100px;*/
            margin: auto;
            padding: 5px;
            float: left;
        }

        .thumb-nail-view a {
            text-align: center;
            font-size: 10px;
            cursor: pointer !important;
            display: inline-block;
            width: 100%;
            color: red;
            font-weight: bold;
        }

        .dropzone .dz-message {
            padding: 0;
        }

        .dropzone .dz-preview .dz-image {
            border-radius: 5px;
            overflow: hidden;
            width: 70px;
            height: 70px;
        }

        .dropzone .dz-preview .dz-image img {
            width: 100%;
        }

        .dropzone .dz-preview .dz-progress {
            width: 60px;
            margin-left: -30px;
        }

        .dropzone .dz-preview .dz-details .dz-size {
            display: none;
        }

        .dropzone {
            min-height: 100px;
        }
    </style>
@stop

@section('script')

@stop
