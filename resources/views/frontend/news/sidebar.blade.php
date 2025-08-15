<div class="col-lg-3">
    <div class="sidebar-wrapper sidebar-wrapper-mrg-right">
        <div class="sidebar-widget pb-20">
            <div class="sidebar-search p-0 m-0">
{{--                <form class="sidebar-search-form" action="">--}}
{{--                    <input type="text" placeholder="Tìm bài viết...">--}}
{{--                    <button>--}}
{{--                        <i class="icon-magnifier"></i>--}}
{{--                    </button>--}}
{{--                </form>--}}
            </div>
        </div>

        <div class="sidebar-widget pb-20">
            <h4 class="sidebar-widget-title">Danh mục</h4>
            <div class="shop-catigory">
                <ul class="shop-categories list-cat">
                    {!! \App\Utils\Category::sidebar_menu_category($tree_categories, $all_categories, $category_id)!!}
                </ul>
            </div>
        </div>

        <div class="sidebar-widget pb-20">
            <h4 class="sidebar-widget-title">Bài viết nổi bật</h4>
            @foreach($top_post as $item)

                <div class="recent-post">
                    <div class="single-sidebar-blog">
                        <div class="sidebar-blog-img">
                            <a href="{{$item->post_link()}}"><img src="{{$item->thumbnail?$item->thumbnail->file_src:''}}" alt=""></a>
                        </div>
                        <div class="sidebar-blog-content">
                            <h5><a href="{{$item->post_link()}}">{{$item->name}}</a></h5>
                            <span>{{$item->created_at}}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{--                        <div class="sidebar-widget shop-sidebar-border mb-40 pt-40">--}}
        {{--                            <h4 class="sidebar-widget-title">Archives </h4>--}}
        {{--                            <div class="archives-wrap">--}}
        {{--                                <select>--}}
        {{--                                    <option>Select Month</option>--}}
        {{--                                    <option> January 2020</option>--}}
        {{--                                    <option> December 2018</option>--}}
        {{--                                    <option> November 2018</option>--}}
        {{--                                </select>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
        {{--                        <div class="sidebar-widget shop-sidebar-border pt-40">--}}
        {{--                            <h4 class="sidebar-widget-title">Popular Tags</h4>--}}
        {{--                            <div class="tag-wrap sidebar-widget-tag">--}}
        {{--                                <a href="#">Clothing</a>--}}
        {{--                                <a href="#">Accessories</a>--}}
        {{--                                <a href="#">For Men</a>--}}
        {{--                                <a href="#">Women</a>--}}
        {{--                                <a href="#">Fashion</a>--}}
        {{--                            </div>--}}
        {{--                        </div>--}}
    </div>
</div>
