@extends('frontend.layouts.frontend')

@section('content')
    @include('frontend.parts.breadcrumbs')

    <div class="blog-area pt-20 pb-20">
        <div class="container">
            <div class="row flex-row-reverse">
                <div class="col-lg-9">
                    <div class="row">
                        @forelse($items as $item)

                            <div class="col-lg-6 col-md-6 col-12 col-sm-6">
                                <div class="blog-wrap mb-40">
                                    <div class="blog-img mb-20">
                                        <a href="{{$item->post_link()}}" title="{{$item->name}}">
                                            <img src="{{$item->thumbnail ? $item->thumbnail->file_src : ''}}" alt="{{$item->name}}">
                                        </a>
                                    </div>
                                    <div class="blog-content">
                                        <div class="blog-meta">
                                            <ul>
                                                <li><a href="#">{{$item->category->name}}</a></li>
                                                <li>{{$item->created_at}}</li>
                                            </ul>
                                        </div>
                                        <h1><a href="{{$item->post_link()}}" title="{{$item->name}}">{{$item->name}}</a></h1>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>Nội dung đang được cập nhật.</p>
                        @endforelse
                    </div>
                    @include('frontend.parts.pagination', ['paginator' => $items])

                </div>
                @include('frontend.news.sidebar')
            </div>
        </div>
    </div>
@endsection
