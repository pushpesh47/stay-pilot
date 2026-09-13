@extends('frontend.layouts.app')

@section('content')
    <div class="breadcrumb__area dark-green breadcrumb-space overflow-hidden position-relative z-1" data-background="{{url('assets/frontend/imgs/breadcrumb/breadcrumb.png')}}">
        <div class="breadcrumb__shapes">
            <img class="upDown" src="{{url('assets/frontend/imgs/breadcrumb/shape.png')}}" alt="img not found">
        </div>
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-12">
                    <div class="breadcrumb__content">
                        <div class="breadcrumb__title-wrapper mb-15 mb-sm-10 mb-xs-5">
                            <h1 class="breadcrumb__title color-white wow fadeInLeft animated" data-wow-delay=".2s">Contact Us</h1>
                        </div>
                        <div class="breadcrumb__menu wow fadeInLeft animated" data-wow-delay=".4s">
                            <nav>
                                <ul>
                                    <li><span><a href="{{url('/')}}">Home</a></span></li>
                                    <li class="active"><span>Contact Us</span></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SI info Area -->
    <div class="si__info__area section-space">
        <div class="container">
            <div class="row si__info__border">
                <div class="col-lg-4">
                    <div class="si__info__box text-center">
                        <div class="si__info__icon">
                            <a href="#"><i class="fa-sharp fa-solid fa-location-dot"></i></a>
                        </div>
                        <div class="si__info__text">
                            <span>Location</span>
                            <p>2972 Westheimer Rd. Santa Ana, <br> Illinois 85486</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="si__info__box text-center">
                        <div class="si__info__icon">
                            <a href="#"><i class="fa-solid fa-paper-plane"></i></a>
                        </div>
                        <div class="si__info__text">
                            <span>E-mail</span>
                            <p>tim.jennings@example.com<br>debra.holt@example.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="si__info__box border__none text-center">
                        <div class="si__info__icon">
                            <a href="#"><i class="fa-sharp fa-solid fa-phone"></i></a>
                        </div>
                        <div class="si__info__text">
                            <span>Contact</span>
                            <p>019457896332, 017485962546<br> 016457896333</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3560.8818662650456!2d80.9978678!3d26.811889899999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x399be35334348e8b%3A0x714f4cdc0e4aa41!2sZuzustay%20Inc!5e0!3m2!1sen!2sin!4v1782374483264!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
    </div>

    <!-- SI Contact Area -->
    <div class="si__contact__area section-space">
        <div class="container custom-width-2">
            <div class="row si__contact__bg">
                <div class="col-lg-12">
                    <div class="si__contact__text text-center">
                        <h1>Write here below?</h1>
                        <p>For your car we will do everything  advice, repairs and they can<br> maintenance. We are the some preferred choice </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="si__contact__info text-center">
                            <input type="text" placeholder="Your Name..." required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="si__contact__info text-center">
                            <input type="text" placeholder="Your Email" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="si__contact__info text-center">
                            <input type="text" placeholder="Your phone" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="si__contact__info text-center">
                            <div class="room-seclect">
                                <form>
                                    <select id="nice" class="nice-2">
                                        <option>Choose Option</option>
                                        <option>City Room</option>
                                        <option>City Room</option>
                                        <option>City Room</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-34">
                        <div class="si__contact__info">
                            <textarea placeholder="Your Message" name="text" id="text"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="si__contact__info text-center">
                        <a class="rr-btn-2 btn" href="room.html"> Submit Now <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.6875 7.71875L8.6875 12.7188C8.5 12.9062 8.25 13 8 13C7.71875 13 7.46875 12.9062 7.28125 12.7188C6.875 12.3438 6.875 11.6875 7.28125 11.3125L10.5625 8H1C0.4375 8 0 7.5625 0 7C0 6.46875 0.4375 6 1 6H10.5625L7.28125 2.71875C6.875 2.34375 6.875 1.6875 7.28125 1.3125C7.65625 0.90625 8.3125 0.90625 8.6875 1.3125L13.6875 6.3125C14.0938 6.6875 14.0938 7.34375 13.6875 7.71875Z" fill="white"/>
                            </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection