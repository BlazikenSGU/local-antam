<div class="col-lg-3">
    <div class="sidebar-wrapper sidebar-wrapper-mrg-right">
        <div class="sidebar-widget pb-20">
            <div class="sidebar-search p-0 m-0">
            </div>
        </div>

        <div class="sidebar-widget">
            <h4 class="sidebar-widget-title">Danh mục </h4>
            <div class="shop-catigory">
                <ul class="shop-categories list-cat">
                    <li>
                        <a href="" title="Tất cả sản phẩm">
                            Tất cả sản phẩm
                        </a>
                    </li>
                    <li>
                        <i>Thực phẩm</i>
                        <ul style="padding: 10px">
                            {!! \App\Utils\Category::sidebar_menu_category($category_tree_1, $all_category_1, $category_id)!!}
                        </ul>
                    </li>
                    <li>
                        <i>Sản phẩm ăn vặt</i>
                        <ul style="padding: 10px">
                            {!! \App\Utils\Category::sidebar_menu_category($category_tree_2, $all_category_2, $category_id)!!}
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{--    <div class="sidebar-widget shop-sidebar-border mb-40 pt-40">--}}
    {{--        <h4 class="sidebar-widget-title">Giá</h4>--}}
    {{--        <div class="price-filter">--}}
    {{--            <span>Range:  300000VND - 8000000VND </span>--}}
    {{--            <div id="slider-range"></div>--}}
    {{--            <div class="price-slider-amount">--}}
    {{--                <div class="label-input">--}}
    {{--                    <input type="text" id="amount" name="price" placeholder="Add Your Price"/>--}}
    {{--                </div>--}}
    {{--                <button type="button">Filter</button>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
</div>
