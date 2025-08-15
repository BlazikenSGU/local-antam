<div class="quick-alo-phone quick-alo-green quick-alo-show" id="quick-alo-phoneIcon" style="display: none">
    <a href="tel:{{$HOTLINE}} " class="info topopup btn">
                <div class="quick-alo-ph-circle"></div>
                <div class="quick-alo-ph-circle-fill"></div>
        <div class="quick-alo-ph-img-circle"><i class="fa fa-phone"></i></div>
        <div class="quick-alo-ph-img-circle" style="opacity: 0;left: -4px;"><i class="fa fa-phone"></i></div>
        <div class="quick-alo-ph-number">{{$HOTLINE}} </div>
    </a>
</div>

<div class="subscribe-area pt-40" style="border-top: 1px solid #dbdbdb; background: #f2f2f2">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-5">
                <div class="section-title-3">
                    <h2 style="color: #c9aa2a">{{$COMPANY_NAME}}</h2>
                    <p>{{$INTRODUCE}}</p>
                </div>
            </div>
            <div class="col-lg-7 col-md-7">
                <div class="col-md-12">
                    <div id="mc_embed_signup" class="subscribe-form-2 pt-20">
                        <form id="subscribe-form" class="validate subscribe-form-style-2" novalidate=""
                              method="post">
                            @csrf
                            <div id="mc_embed_signup_scroll" class="mc-form-2">
                                <input class="email" type="email" required="" autocomplete="off"
                                       placeholder="Nhập Email của bạn" name="email" value="">
                                <div class="mc-news-2" aria-hidden="true">
                                    <input type="text" value="" tabindex="-1"
                                           name="b_6bbb9b6f5827bd842d9640c82_05d85f18ef">
                                </div>
                                <div class="clear-2 clear-2-blue">
                                    <input id="mc-embedded-subscribe" class="button" type="submit"
                                           name="subscribe"
                                           value="Đăng ký">
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer-area">
    <div class="footer-top border-bottom-4 pb-20 pt-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-4 col-sm-6 col-12">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="footer-widget mb-20" style="padding: 10px 5px 10px 5px;">
                                <h3 class="footer-title">Văn phòng đại diện</h3>

                                <div class="contact-info-2">
                                    {{--                                    <div class="single-contact-info-2">--}}
                                    {{--                                        <div class="contact-info-2-icon">--}}
                                    {{--                                            <i class="icon-call-end"></i>--}}
                                    {{--                                        </div>--}}
                                    {{--                                        <div class="contact-info-2-content">--}}
                                    {{--                                            <p>HOTLINE 24/7: {{$HOTLINE}}</p>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    <div class="single-contact-info-2">
                                        <div class="contact-info-2-icon">
                                            <i class="icon-cursor icons"></i>
                                        </div>
                                        <div class="contact-info-2-content">
                                            <p>{{$ADDRESS}}</p>
                                        </div>
                                    </div>
                                    {{--                                    <div class="single-contact-info-2">--}}
                                    {{--                                        <div class="contact-info-2-icon">--}}
                                    {{--                                            <i class="icon-envelope-open "></i>--}}
                                    {{--                                        </div>--}}
                                    {{--                                        <div class="contact-info-2-content">--}}
                                    {{--                                            <p>{{$EMAIL}}</p>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                </div>
                                {{--                                <div class="social-style-1 social-style-1-font-inc social-style-1-mrg-2">--}}
                                {{--                                    <a href="{{$FACEBOOK}}"><i class="icon-social-facebook"></i></a>--}}
                                {{--                                    <a href="{{$INSTAGRAM}}"><i class="icon-social-instagram"></i></a>--}}
                                {{--                                    <a href="{{$YOUTUBE}}"><i class="icon-social-youtube"></i></a>--}}
                                {{--                                </div>--}}
                            </div>
<!--                            <div class="footer-widget mb-20" style="padding: 10px 5px 10px 5px;">
                                <h3 class="footer-title">Chi nhánh Tây Ninh</h3>

                                <div class="contact-info-2">
                                    <div class="single-contact-info-2">
                                        <div class="contact-info-2-icon">
                                            <i class="icon-cursor icons"></i>
                                        </div>
                                        <div class="contact-info-2-content">
                                            <p>{{$ADDRESS_2}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                            <div class="footer-widget mb-20 ">

                                <h3 class="footer-title">Kết nối với chúng tôi</h3>
                                <div class="social-style-2 social-style-2-mrg">
                                    <a href="{{$TWITTER}}"><i class="social_twitter"></i></a>
                                    <a href="{{$FACEBOOK}}"><i class="social_facebook"></i></a>
                                    <a href="{{$GOOGLE_PLUS}}"><i class="social_googleplus"></i></a>
                                    <a href="{{$INSTAGRAM}}"><i class="social_instagram"></i></a>
                                    <a href="{{$YOUTUBE}}"><i class="social_youtube"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <div class="footer-widget mb-20 ">
                        <h3 class="footer-title">Hình thức thanh toán</h3>

                        <div class="pb-20">

                            <a><img width="70px" src="{{url('storage/frontend/images/visa.svg')}}"></a>
                            <a><img width="70px" src="{{url('storage/frontend/images/mastercard.svg')}}"></a>
                            <a><img width="70px" src="{{url('storage/frontend/images/jcb.svg')}}"></a>
                            <a><img width="70px" src="{{url('storage/frontend/images/cash.svg')}}"></a>
                            <a><img width="70px" src="{{url('storage/frontend/images/internet-banking.svg')}}"></a>
                        </div>
                    </div>
<!--                    <div class="footer-widget mb-20">

                        <h3 class="footer-title" style="margin-top: 5px">Thông tin chuyển khoản</h3>

                        <p>Chủ tài khoản: {{$BANK_ACCOUNT_NAME}}</p>

                        <p>STK: {{$BANK_ACCOUNT_NUMBER}}</p>

                        <p>Ngân hàng: {{$BANK_NAME}}</p>

                        <h3 class="footer-title">Thông tin chuyển khoản</h3>

                        <p>Chủ tài khoản: {{$BANK_ACCOUNT_NAME_2}}</p>

                        <p>STK: {{$BANK_ACCOUNT_NUMBER_2}}</p>

                        <p>Ngân hàng: {{$BANK_NAME_2}}</p>
                    </div>-->

                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="footer-widget ml-70 mb-20">
                        <h3 class="footer-title">Hỗ trợ khách hàng</h3>
                        <div class="footer-info-list">
                            <ul>
                                <li><a href="{{url('/dieu-khoan-su-dung.html')}}">Điều khoản sử dụng</a></li>
                                <li><a href="{{url('/huong-dan-dat-hang-doi-tra.html')}}">Hướng dẫn Đặt hàng</a></li>
                                <li><a href="{{url('/chinh-sach-doi-tra-hang.html')}}">Chính sách đổi trả</a></li>
                                <li><a href="{{url('/chinh-sach-van-chuyen-giao-nhan.html')}}">Chính sách vận chuyển,
                                        giao nhận</a></li>
                                <li><a href="{{url('/chinh-sach-bao-mat-thong-tin-khach-hang.html')}}">Chính sách bảo
                                        mật thông tin khách hàng</a></li>
                                <li><a href="{{url('/cau-hoi-thuong-gap.html')}}">Các câu hỏi thường gặp</a></li>
                                <li><a href="{{url('/tin-tuc/tuyen-dung')}}">Tuyển dụng</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="footer-bottom pt-30 pb-30 ">
        <div class="container">
            <div class="row flex-row-reverse">
                {{--                <div class="col-lg-6 col-md-6">--}}
                {{--                    <div class="payment-img payment-img-right">--}}
                {{--                        <a href="#"><img src="{{url('storage/frontend/images/payment.png')}}" alt=""></a>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                <div class="col-lg-12">
                    <div class="copyright copyright-center">
                        <p class="text-center">© Bản quyền thuộc về <span
                                style="color: brown; font-weight: bold"> {{$COMPANY_NAME}}</span>
                            | Cung cấp bởi
                            <span style="color: #00b2ff; font-weight: bold"><a href="https://thietke24h.com/">Thietke24h.com</a></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
