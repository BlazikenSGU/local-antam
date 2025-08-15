@extends('frontend.layouts.frontend')

@section('content')
    @include('frontend.parts.breadcrumbs')

    <div class="contact-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center" style="min-height: 300px">
                    @if(isset($token))
                        <p class="mt-8">Vui lòng click vào nút phía dưới để kích hoạt tài khoản.</p>
                        <a class="btn btn-danger col-md-4 col-xs-12" href="{{route('frontend.user.activate',$token)}}?confirm=1">Kích
                            hoạt</a>

                    @else
                        <div class="alert alert-{{$class}} col-md-4 col-xs-12 mt-8" role="alert" style="margin: 0 auto;">
                            {{$msg}}
                        </div>

                        <p class="btn btn-black mt-3"><a style="color: #fff" href="{{url('/')}}">Quay lại trang chủ</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
