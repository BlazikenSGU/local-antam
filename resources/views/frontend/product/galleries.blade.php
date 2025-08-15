@if(count($galleries))
{{--    <div class="">--}}
        <div class="pro-dec-big-img-slider">
            @foreach($galleries as $v)
                <div class="easyzoom-style">
                    <div class="easyzoom easyzoom--overlay">
                        <a href="{{url('storage/uploads/' . $v['file_path'])}}">
                            <img src="{{url('storage/uploads/' . $v['file_path'])}}" alt="">
                        </a>
                    </div>
                    <a class="easyzoom-pop-up img-popup"
                       href="{{url('storage/uploads/' . $v['file_path'])}}"><i
                            class="icon-size-fullscreen"></i></a>
                </div>
            @endforeach
        </div>
        <div class="product-dec-slider-small product-dec-small-style1">
            @foreach($galleries as $v)

                <div class="product-dec-small active">
                    <img src="{{url('storage/uploads/' . $v['file_path'])}}" alt="">
                </div>
            @endforeach

        </div>
{{--    </div>--}}
@endif
