@extends('frontend.layouts.frontend')

@section('content')
    @include('frontend.parts.breadcrumbs')

    <div class="contact-area pt-20 pb-20">
        <div class="container">
            <div class="row mb-10">
                <div class="col-md-5 col-xl-4">
                    <div class="border-bottom border-color-1 mb-5">
                        <h3 class="section-title mb-0 pb-2 font-size-25">{!! html_entity_decode($COMPANY_NAME) !!}</h3>
                    </div>
                    <div class="mr-xl-6">
                        <div class="footer-widget mb-40 ">
                            <div class="contact-info-2">
                                <div class="single-contact-info-2">
                                    <div class="contact-info-2-icon">
                                        <i class="icon-call-end"></i>
                                    </div>
                                    <div class="contact-info-2-content">
                                        <p>Hotline 24/7: {{$HOTLINE}}</p>
                                    </div>
                                </div>
                                <div class="single-contact-info-2">
                                    <div class="contact-info-2-icon">
                                        <i class="icon-cursor icons"></i>
                                    </div>
                                    <div class="contact-info-2-content">
                                        <p>{{$ADDRESS}}</p>
                                    </div>
                                </div>
                                <div class="single-contact-info-2">
                                    <div class="contact-info-2-icon">
                                        <i class="icon-envelope-open "></i>
                                    </div>
                                    <div class="contact-info-2-content">
                                        <p>{{$EMAIL}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-xl-8">
                    <div class="mb-8">
                        <div class="gmaps" id="googleMap"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('script')
    <script>
        var ADDRESS = [{"address":"203 Đường Tam Bình, Phường Tam Phú, Quận Thủ Đức, TP.HCM","lat_long":"10.859336, 106.740541"}];
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBSxX2Un4CjYcWVyA3FOjBNcrVC1hlervk"></script>
    <script src="{{ asset('/storage/frontend')}}/js/map.js"></script>
@endsection
