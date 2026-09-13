
<div id="scroll-percentage"><span id="scroll-percentage-value"></span></div>
<!-- Footer area start -->
<footer>
    <section class="footer__area-common dark overflow-hidden position-relative z-1">
        <div class="container">
            <div class="footer2__top d-flex justify-content-between pt-80 pb-30">
                <div class="footer__logo wow fadeInLeft animated" data-wow-delay=".2s">
                    <a href="index.html">
                        <img src="{{url(asset('storage/' .setting_value('company_large_logo')))}}" alt="logo not found">
                    </a>
                </div>
                <div class="footer2__right d-flex wow fadeInLeft animated" data-wow-delay=".3s">
                    <div class="footer2__right-social d-flex">
                        <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://instagram.com/"><i class="fa-brands fa-instagram"></i></a>
                        <a href="https://www.pinterest.com/"><i class="fa-brands fa-pinterest-p"></i></a>
                        <a href="https://vimeo.com/"><i class="fa-brands fa-vimeo-v"></i></a>
                    </div>
                </div>
            </div>
            <div class="row mb-minus-40 footer-wrap">
                <div class="col-lg-4">
                    <div class="footer__widget footer__widget-item-1">
                        <div class="footer__content footer__content-2">
                            <p>Sign up to Privates Weekly Newsletter to Get <br> The Latest Updates.</p>
                        </div>
                        <div class="footer__widget-from mt-25">
                            <input class="book__form-control book__form-control-2" type="email" name="email" placeholder="Your email...">
                            <a class="rr-btn-2 btn" href="#">Subscribe <i class="fa-solid fa-plus"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="footer__widget footer__widget-item-2">
                    <div class="footer__widget-title footer__widget-title-2">
                        <h4>Quick Links</h4>
                    </div>
                    <div class="footer__link footer__link-2">
                        <ul>
                            <li><a href="{{url('blogs')}}">Blog</a></li>
                            <li><a href="{{url('contact-us')}}">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="footer__widget footer__widget-item-3">
                    <div class="footer__widget-title footer__widget-title-2">
                        <h4>Services</h4>
                    </div>
                    <div class="footer__link footer__link-2">
                        <ul>
                                <li><a href="{{url('rooms')}}">Rooms</a></li>
                                <li><a href="{{url('contact-us')}}">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="footer__widget footer__widget-item-4">
                        <div class="footer__widget-title footer__widget-title-2">
                            <h4>Contact</h4>
                        </div>

                        <div class="footer__subscribe footer__subscribe-2 subscribe-2 d-flex mt-15">
                            <ul>
                                <li>
                                    <a href="https://www.google.com/maps">
                                        <h5 class="footer__subscribe-2-heading">Address</h5>
                                        <span class="mail mail-2">6391 Elgin St. Celina <br> Delaware 10299</span>
                                    </a>
                                </li>
                                <li class="gap-10">
                                    <h5 class="footer__subscribe-2-heading">Phone</h5>
                                    <a class="number" href="tel:406555-0120">(406) 555-0120</a>
                                </li>
                                <li>
                                    <a href="mailto:rrdevs@gmail.com">
                                        <h5 class="footer__subscribe-2-heading">Email</h5>
                                        <span class="text text-2">deanna.curtis@example.com</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer__bottom-wrapper dark">
            <div class="container">
                <div class="footer__bottom">
                    <div class="footer__copyright footer__copyright-2">
                        <p class="copy">© <a href="{{url('/')}}">{{ ucfirst(setting_value('company_name')) }}</a> | {{ setting_value('company_foundation_year') }} - {{ date('Y') }} | All Rights Reserved</p>
                    </div>

                    <div class="footer__copyright-menu footer__copyright-menu-2">
                        <ul>
                            <li><a href="{{url('/')}}">Terms & Condition</a></li>
                            <li><a href="{{url('/')}}">Privacy Policy</a></li>
                            <li><a href="{{url('contact-us')}}">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</footer>
<!-- Footer area end -->