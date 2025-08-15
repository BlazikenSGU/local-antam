@extends('frontend.layouts.frontend')

@section('content')
    @include('frontend.parts.breadcrumbs')

    <div class="contact-area section-padding">
        <div class="container">
            <div class="mb-8">
                <div>
                    {!! html_entity_decode($HTML_INTRODUCE) !!}
                </div>
            </div>
        </div>
    </div>
@stop
