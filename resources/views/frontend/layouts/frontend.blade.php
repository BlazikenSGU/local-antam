<!doctype html>
<html class="no-js" lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}"/>

        @php
            $_title = $META_TITLE;
            $_description = $META_DESCRIPTIONS;
            $_keywords = $META_KEYWORDS;

            if(isset($seo_title)&&!empty($seo_title)){
            $_title = $seo_title;
        }elseif (isset($title)&&!empty($title)){
        $_title = $title;
        }

        if(isset($seo_description)&&!empty($seo_description)){
        $_description = $seo_description;
        }elseif (isset($description)&&!empty($description)){
        $_description = $description;
        }

        if(isset($seo_keywords)&&!empty($seo_keywords)){
        $_keywords = $seo_keywords;
        }

        $_image_fb_url = url('/storage/uploads').'/'.$LOGO;

        if(isset($image_fb_url)&&!empty($image_fb_url)){
        $_image_fb_url = $image_fb_url;
        }

        @endphp

        <title>{{$_title}}</title>

        <link rel="canonical" href="{{url()->current()}}"/>

        <meta name="distribution" content="Global">
        <meta name="description" content="{{$_description}}">
        <meta name="keywords" content="{{$_keywords}}">
        <meta name="author" content="{{$META_AUTHOR}}">
        <meta property="fb:app_id" content="970891727087844" />
        <meta property="og:locale" content="vi_VN">
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{$_title}}">
        <meta property="og:description" content="{{$_description}}">
        <meta property="og:url" content="{{url()->current()}}">
        <meta property="og:site_name" content="{{$COMPANY_NAME}}">
        <meta property="og:image" content="{{$_image_fb_url}}">
        <meta property="og:image:alt" content="{{$_description}}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{$_title}}">
        <meta name="twitter:description" content="{{$_description}}">
        <meta name="twitter:image" content="{{$_image_fb_url}}">

        <link rel="alternate" type="application/rss+xml" title="{{$META_TITLE}}" href="{{url('feed')}}"/>

        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{url('/storage/uploads').'/'.$FAVICON}}">

        <!-- CSS ============================================ -->
        <link rel="stylesheet"
              href="{{ asset('/storage/frontend')}}/css/bootstrap.min.css?v={{config('constants.assets_version')}}">

        <link rel="stylesheet"
              href="{{ asset('/storage/frontend')}}/css/bootstrap-select.min.css?v={{config('constants.assets_version')}}">
        <link rel="stylesheet" href="{{ asset('/storage/frontend')}}/fonts/stylesheet.css">
        <link rel="stylesheet" href="{{ asset('/storage/frontend')}}/css/vendor/vendor.min.css">
        <link rel="stylesheet" href="{{ asset('/storage/frontend')}}/css/plugins/plugins.min.css">
        <link rel="stylesheet"
              href="{{ asset('/storage/frontend')}}/css/contact.css?v={{config('constants.assets_version')}}">
        <link rel="stylesheet"
              href="{{ asset('/storage/frontend')}}/css/style.min.css?v={{config('constants.assets_version')}}">
        <link rel="stylesheet"
              href="{{ asset('/storage/frontend')}}/css/custom.css?v={{config('constants.assets_version')}}">

        <link rel="stylesheet" href="{{ asset('/storage/frontend/dropzone-master')}}/dist/dropzone.css">
        <link rel="stylesheet" href="//st.app1h.com/themes/05/assets/font-awesome-4.7.0/css/font-awesome.min.css">
        @yield('style')
        <style>
            .main-categori-wrap > a i {
                font-size: 25px;
                margin-right: 7px;
                position: relative;
                top: 0px;
                line-height: 0;
            }

            p img {
                width: 100%;
            }

            @media only screen and (min-width: 1366px) and (max-width: 1600px) {
                .category-menu-dropdown.ct-menu-res-height-2 {
                    height: 280px;
                    overflow-y: auto;
                }
            }

            .category-menu nav > ul > li .category-menu-dropdown {
                width: 1000px;
            }

            .category-menu nav > ul > li .category-menu-dropdown .single-category-menu {
                display: inline-block;
                width: 25%;
                float: left;
            }

            @media only screen and (max-width: 767px) {
                .footer-app a img {
                    width: 150px;
                }

                .quick-alo-show {
                    display: block;
                }

                .policy-page {
                    padding-top: 165px;
                }
            }
        </style>
        <script>

            var BASE_URL = "{{config('app.url')}}";
        </script>
    </head>
    <body>
        <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please
            <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div id="fb-root"></div>
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous"
                src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v12.0&appId=970891727087844&autoLogAppEvents=1"
                nonce="hX4JaFXU"></script>

        <div class="inbody section">
            @include('frontend.parts.header-2')

            <main>
                @yield('content')

               {{-- <div class="zalo-chat-widget" data-oaid="3669292124589125580"
                     data-welcome-message="Rất vui khi được hỗ trợ bạn!" data-autopopup="0" data-width=""
                     data-height="">

                </div>

                <script src="https://sp.zalo.me/plugins/sdk.js"></script>--}}

            </main>

        </div>

        <div class="lib-ember-hotline-001" style="width: 230px;">
            <p style="color: #ffffff">
                <i class="fa fa-phone"></i><span>Hotline :</span>
                <a href="tel:{{$HOTLINE}}" title="{{$HOTLINE}}"><b>{{$HOTLINE}}</b></a>
            </p>
          <!---  <p style="color: #ffffff">

                <i class="fa fa-phone"></i><span>Hotline 2:</span>
                <a href="tel:{{$HOTLINE2}}" title="{{$HOTLINE2}}"><b>{{$HOTLINE2}}</b></a>
            </p>
		!--->

        </div>
        @include('frontend.parts.footer')

        <script src="{{ asset('/storage/frontend')}}/js/vendor/modernizr-3.6.0.min.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/vendor/jquery-3.5.1.min.js"></script>
        {{--        <script src="{{ asset('/storage/frontend')}}/js/vendor/jquery-migrate-3.3.0.min.js"></script>--}}
        <script src="{{ asset('/storage/frontend')}}/js/vendor/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/plugins/jquery-ui.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/plugins/slick.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/plugins/jquery.syotimer.min.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/plugins/jquery.nice-select.min.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/plugins/wow.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/plugins/jquery-ui-touch-punch.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/plugins/magnific-popup.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/plugins/sticky-sidebar.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/plugins/easyzoom.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/plugins/scrollup.js"></script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"
                type="text/javascript"></script>
        <script src="{{ asset('/storage/frontend')}}/js/main.js?v={{config('constants.assets_version')}}"></script>
        <script src="{{ asset('/storage/frontend')}}/js/custom.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/bootstrap-select.min.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/bootstrap.min.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/popper.min.js"></script>
        <script src="{{ asset('/storage/frontend')}}/js/jquery.validate.js"></script>
        <script type="text/javascript" src="{{ asset('/storage/frontend/dropzone-master/dist/dropzone.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script>
            window.onscroll = function () {
                myFunction()
            };
            //
            var header = document.getElementById("myHeader");
            // var mobile_header = document.getElementById("mobileHeader");
            var sticky = header.offsetTop;

            function myFunction() {
                if (window.pageYOffset > sticky) {
                    header.classList.add("sticky");
                    // mobile_header.classList.add("sticky");
                } else {
                    header.classList.remove("sticky");
                    // mobile_header.classList.remove("sticky");
                }
            }

            function googleTranslateElementInit() {
                new google.translate.TranslateElement({
                    pageLanguage: 'vn',
                    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                    autoDisplay: false
                }, 'google_translate_element');
            }

            function translateLanguage(lang) {
                googleTranslateElementInit();
                var $frame = $('.goog-te-menu-frame:first');
                if (!$frame.size()) {
                    alert("Error: Could not find Google translate frame.");
                    return false;
                }
                $frame.contents().find('.goog-te-menu2-item span.text:contains(' + lang + ')').get(0).click();
                return false;
            }

            $(function () {
                $('.selectpicker').selectpicker();
            });

        </script>
        @yield('script')
    </body>
</html>
