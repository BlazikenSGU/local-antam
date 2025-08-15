$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


var Variation = {
    initialised: false,
    processing: false,
    selected_variation_value_ids: [],
    init: function () {

        if (!this.initialised) {
            this.initialised = true;
        } else {
            return;
        }
        Variation.select_variation();
    },
    select_variation: function () {
        $('.select_variation').on('change', function () {
            var name = $(this).attr('name');
            var radioValue = [];

            $.each($("input.select_variation:checked"), function (i, v) {
                radioValue.push($(v).val());
            })
            Variation.selected_variation_value_ids = radioValue;
            Variation.get_product_variation();
        });
    },
    // product_detail_gallery: function(){
    //
    // },
    get_product_variation: function () {
        var product_id = $('input[name="product_id"]').val();
        $.ajax({
            url: BASE_URL + '/ajax/product-variation',
            type: "GET",
            cache: false,
            data: {product_id: product_id, variation_value_ids: Variation.selected_variation_value_ids},
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.status) {

                    if (data.variation.id != undefined) {
                        $('input[name="product_variation_id"]').val(data.variation.id);

                        jQuery('.inventory_status_parent').removeClass('d-none');

                        if (data.variation.price != null) {
                            jQuery('.new-price').html(data.variation.price_text);
                        }
                    }

                    if (data.galleries_html != '') {
                        $('.product-details-tab').html(data.galleries_html);
                        $('.pro-dec-big-img-slider').slick({
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            arrows: false,
                            draggable: false,
                            fade: false,
                            asNavFor: '.product-dec-slider-small , .product-dec-slider-small-2',
                        });

                        /*---------------------------------------
                            Product details small image slider
                        -----------------------------------------*/
                        $('.product-dec-slider-small').slick({
                            slidesToShow: 4,
                            slidesToScroll: 1,
                            asNavFor: '.pro-dec-big-img-slider',
                            dots: false,
                            focusOnSelect: true,
                            fade: false,
                            prevArrow: '<span class="pro-dec-prev"><i class="icon-arrow-left"></i></span>',
                            nextArrow: '<span class="pro-dec-next"><i class="icon-arrow-right"></i></span>',
                            responsive: [{
                                breakpoint: 991,
                                settings: {
                                    slidesToShow: 3,
                                }
                            },
                                {
                                    breakpoint: 767,
                                    settings: {
                                        slidesToShow: 4,
                                    }
                                },
                                {
                                    breakpoint: 575,
                                    settings: {
                                        slidesToShow: 2,
                                    }
                                }
                            ]
                        });
                    }else{

                    }

                    if (!data.can_buy) {
                        $('.add_to_cart').attr('disabled', 'disabled');
                        jQuery('.inventory_status').removeClass('text-success').addClass('text-danger').html(data.inventory_text);
                    } else {
                        $('.add_to_cart').removeAttr('disabled');
                        jQuery('.inventory_status').addClass('text-success').removeClass('text-danger').html(data.inventory_text);
                    }
                }
            }
        });
    }
}

Variation.init();

$('.add_to_cart').click(function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var quantity = $('#quantity').val();
    var product_variation_id = $('.product_variation_id').val();
    var act = $(this).data('act');
    $.ajax({
        url: BASE_URL + '/ajax/cart/add',
        type: 'post',
        data: {product_id: id, quantity: quantity, product_variation_id: product_variation_id},
        dataType: 'json',
        success: function (result) {
            console.log(result);
            if (result.status) {
                // count = parseInt(el) + 1;
                // console.log(el);
                $('.count-cart').text(result.data.total_format);
                if (act == undefined) {
                    Swal.fire({
                        title: 'Đã thêm sản phẩm vào giỏ hàng',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#5cb85c',
                        cancelButtonColor: 'rgb(5 159 198)',
                        confirmButtonText: '<span style="color: #fff">Đến giỏ hàng</span>',
                        cancelButtonText: '<span style="color: #fff">Tiếp tục mua hàng</span>',

                    }).then((result) => {
                        if (result.value) {
                            window.location.href = BASE_URL + '/gio-hang.html';
                        }
                    })
                } else {
                    window.location.href = BASE_URL + '/dat-hang.html';
                }
            } else {
                Swal.fire({
                    title: result.message,
                    icon: 'error',
                    confirmButtonColor: 'red',
                    confirmButtonText: 'Đóng',
                })
            }
        }
    });
});

$('.cart-remove').click(function (e) {
    e.preventDefault();

    var item_id = $(this).data('item_id');
    $.ajax({
        url: BASE_URL + '/ajax/cart/delete',
        type: 'post',
        data: {item_id: item_id},
        dataType: 'json',
        success: function (result) {
            if (result.status) {
                window.location.reload();
            } else {
                Swal.fire({
                    title: result.message,
                    icon: 'danger',
                    confirmButtonColor: 'red',
                    confirmButtonText: 'Đóng',
                })
            }
        }
    });
});

$('#subscribe-form').submit(function (e) {
    var form = $(this);
    var url = form.attr('action');
    $.ajax({
        type: "POST",
        url: '/ajax/subscribe-email.html',
        data: form.serialize(),
        dataType: 'json',
        success: function (data) {
            alert(data.message);
            if (data.status)
                $('#subscribe-form .email').val('');
        }
    });
    e.preventDefault();
});

/* Xử lý quantity */
$('body').on('focus', '.quantity-number', function () {
    $(this).attr('data-temp', $(this).val());
})
$('body').on('click', '.xi-step-up', function () {
    var e = $(this).siblings('.quantity-number');
    e.attr('data-temp', e.val());
    e.val(parseInt(e.val()) + 1);
    e.trigger('change');
})
$('body').on('click', '.xi-step-down', function () {
    var e = $(this).siblings('.quantity-number');
    e.attr('data-temp', e.val());
    if (parseInt(e.val()) - 1 > 0) {
        e.val(parseInt(e.val()) - 1);
    }
    e.trigger('change');
})


var siteNav = $("#site-nav--mobile");
var siteOverlay = $("#site-overlay");

$("#site-menu-handle").on("click focusin", function () {
    siteNav.hasClass("active") || (siteNav.addClass("active"), siteNav.removeClass("show-filters").removeClass("show-cart").removeClass("show-search"), siteOverlay.addClass("active"), a(".main-body").addClass("sidebar-move"))
});

$(".site-close-handle, #site-overlay").on("click", function () {
    siteNav.hasClass("active") && (siteNav.removeClass("active"), siteOverlay.removeClass("active"), a(".main-body").removeClass("sidebar-move"))
});

$(document).on("click", "span.icon-subnav", function () {
    if ($(this).parent().hasClass('active')) {
        $(this).parent().removeClass('active');
        $(this).siblings('ul').slideUp();
    } else {
        if ($(this).parent().hasClass("level0") || $(this).parent().hasClass("level1")) {
            $(this).parent().siblings().find("ul").slideUp();
            $(this).parent().siblings().removeClass("active");
        }
        $(this).parent().addClass('active');
        $(this).siblings('ul').slideDown();
    }
});

$(document).ready(function () {
    if ($(window).width() < 768)
        $('#side_bar_category').trigger('click');
})
