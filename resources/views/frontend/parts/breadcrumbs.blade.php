<!-- breadcrumb area start -->
<div class="breadcrumb-area bg-gray-4">
    <div class="container">
        <div class="breadcrumb-content text-center">
            <div class="breadcrumb-area" style="padding: 0px">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="breadcrumb-wrap">
                                <nav aria-label="breadcrumb">
                                    <ul class="breadcrumb d-inline-block text-center">
                                        <li class="breadcrumb-item d-inline-block"><a href="{{url('/')}}">Trang chủ</a></li>
                                        @foreach($breadcrumbs as $v)
                                            <li class="breadcrumb-item d-inline-block"><a
                                                    href="{{$v['link']}}">{{$v['name']}}</a></li>
                                        @endforeach
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb area end -->
