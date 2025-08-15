@extends('frontend.layouts.frontend')

@section('content')
    {{--    @include('frontend.parts.breadcrumbs')--}}
    <div class="container">
        <div class="pt-20 pb-20 policy-page">
            {!! html_entity_decode($SHIPPING_POLICY) !!}
        </div>
    </div>
@endsection
