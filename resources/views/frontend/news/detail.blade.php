@extends('frontend.layouts.frontend')

@section('content')
    @include('frontend.parts.breadcrumbs')

    <!-- blog main wrapper start -->
    <div class="blog-area pt-20 pb-20">
        <div class="blog-main-wrapper section-padding">
            <div class="container">
                <div class="row">
                    @include('frontend.news.sidebar')

                    <div class="col-lg-9">
                        <div class="blog-details-wrapper">
                            <div class="blog-details-top">
                                <div class="blog-details-img">
                                    <img alt="" src="{{$post->thumbnail?$post->thumbnail->file_src:''}}">
                                </div>
                                <div class="blog-details-content">
                                    <div class="blog-meta-2">
                                        <ul>
                                            <li>{{$post->category->name}}</li>
                                            <li>{{$post->created_at}}</li>
                                        </ul>
                                    </div>
                                    <h1>{{$post->name}}</h1>
                                    <p>{{$post->excerpt}}</p>
                                </div>
                            </div>
                            {!! html_entity_decode($post->detail) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- blog main wrapper end -->
@endsection
