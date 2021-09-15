@extends('layouts.container')
@section('style')
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
          rel="stylesheet">
    <link href="{{ asset('assets/vendors/general/toastr/build/toastr.css') }}" rel="stylesheet"
          type="text/css"/>
    <style>
        .valid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 80%;
            color: #dc3545;
        }
    </style>
@endsection


@section('content')

    @if (Session::has('errors'))
        <div class="alert alert-danger">
            @foreach (Session::get('errors') as $error)
                <br/>
                {{$error}}
                <br/>
            @endforeach
        </div>
    @endif

    <!-- end:: Header -->
    <!-- begin:: section -->
    <div class="div">
        <div class="owl-carousel owl_1">
            @if(isset($sliders)&&count($sliders)> 0)

                @foreach($sliders as $index=>$item)
                    <section class="mainPage d-flex align-items-center  wow fadeInDown" data-wow-delay="0.2s"
                             style="background-image: url('{{$item->image}}')">
                        <div class="container">
                            <div class="mainPageTexts">
                                {{--                            <h4>The Best To Start Your Day with</h4>--}}
                                {{--                            <h1>COFFEE</h1>--}}
                            </div>
                            <div class="testimonials mr-auto mt-5 " style="
    margin-top: 329px !important;
">
                                <div class="singleTes">
                                    <div class="singletesti   d-flex align-items-center ">
                                        <p>&nbsp;&nbsp;&nbsp;{{$item->details}} &nbsp;&nbsp;&nbsp;</p>
                                        <div class="avatar mr-3" style="padding-left: 5px">
                                            <img class="mr-auto" src="{{$item->user_image}}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="owlNavs">
                                <div class="custom-nav-prev owl-nav"><i class="fas fa-chevron-left"></i></div>
                                <div class="custom-nav-next owl-nav"><i class="fas fa-chevron-right"></i></div>
                            </div>
                        </div>
                    </section>
                @endforeach
            @endif
        </div>

    </div>


    <section class="sliderProducts  wow fadeInUp" data-wow-delay="0.2s">
        <div class=" px-5">
            <div class="productstext">
                <h2 class="hero">{{w('Most Popular')}}</h2>
                <p>{{w('We have chosen for you our distinctive products that are most requested by our valued customers')}}</p>

            </div>

            <div class="productsSlide owl-carousel owl_2 mt-4">
                @foreach($products as $index=>$product)
                    <a href="{{route('product',$product->id)}}">
                        <div class="singleItemSlide">
                            <div class="productImd_Widgate">
                                <img src="{{optional(optional(optional($product)->images)->first())->image}}" alt=""
                                     style="
    width: 245px;
    height: 131px;
    object-fit: fill;
">
                                <div class="starsFive">
                                    <i class="fas fa-star"> </i> {{$product->rate}}
                                </div>
                            </div>
                            <div class="productDetails_widgate px-3 py-3">
                                <div class="proNamePrice d-flex align-items-center justify-content-between">
                                    <h5>{{$product->name}}</h5>
                                    <p>{{optional(optional($product)->price)->piece_cost }} {{optional(getCurrentCountry()->currency)->name}}</p>
                                </div>
                                <div class="productDetailsCaption">
                                    <p>{{$product->description}}</p>
                                </div>
                                <div class="productCatigores d-flex ">
                                    <img src="{{optional(optional($product)->category)->image}}" alt="" class="mr-3">
                                    <p>{{optional(optional($product)->category)->name}}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </section>
    <!-- end:: section -->
    <!-- begin:: section -->

    <section class="app   wow fadeInUp" data-wow-delay="0.2s">
        <div class="container">
            <div class="appText text-center  wow fadeInDown" data-wow-delay="0.4s">
                <h2 class="hero">{{w('Free delivery for Merchant within 24 km')}}</h2>
                <p>{{w('Download the application now and enjoy our services')}}</p>
                <div class="appBtns mt-3">
                    <a href="{{setting('ios_app_url')}}"><img src="{{asset_site('assest/images/btn1.png')}}" alt=""></a>
                    <a href="{{setting('android_app_url')}}"><img src="{{asset_site('assest/images/btn2.png')}}" alt=""></a>
                </div>
                <img src="{{asset_public('img/qr_code.jpeg')}}" alt="">
            </div>
        </div>
    </section>

    <!-- end:: section -->
    <!-- begin:: section -->
    <section class="Spreading">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="spImg text-center  wow fadeInLeft" data-wow-delay="0.2s">
                        <img src="{{asset_site('assest/images/Mask.png')}}" class="img-fluid " alt="">
                    </div>
                </div>
                <div class="col-lg-6 mt-lg-0 mt-5">
                    <div class="spText  wow fadeInUp" data-wow-delay="0.2s">
                        <h2>{{w('Spreading fast')}}</h2>
                        <div class="row mt-5">
                            <div class="col-sm-6 mb-5">
                                <div class="  color_1 singleServ d-flex align-items-enter">
                                    <div class="servImg mr-3">
                                        <img src="{{asset_site('assest/images/store.png')}}" alt="">
                                    </div>
                                    <div class="servtext">
                                        <h2>{{$merchants}}</h2>
                                        <p>{{w('Merchant')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-5">
                                <div class="singleServ d-flex align-items-enter color_2">
                                    <div class="servImg mr-3">
                                        <img src="{{asset_site('assest/images/shopping-bag.png')}}" alt="">
                                    </div>
                                    <div class="servtext">
                                        <h2>{{$orders}}</h2>
                                        <p>{{w('Order')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-5">
                                <div class="singleServ d-flex align-items-enter color_3">
                                    <div class="servImg mr-3">
                                        <img src="{{asset_site('assest/images/user.svg')}}" alt="">
                                    </div>
                                    <div class="servtext">
                                        <h2>{{$customers}}</h2>
                                        <p>{{w('Customers')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-5">
                                <div class="singleServ d-flex align-items-enter color_4">
                                    <div class="servImg mr-3">
                                        <img src="{{asset_site('assest/images/supply-chain.png')}}" alt="">
                                    </div>
                                    <div class="servtext">
                                        <h2>{{$products_count}}</h2>
                                        <p>{{w('Products')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end:: section -->
    <!-- begin:: section -->
    <section class="features">
        <div class="container">
            <h2 class="hero text-center">{{w('Our Features')}}</h2>
            <div class="row mt-5">
                <div class="col-md-4 col-sm-6 mb-5   wow fadeInUp" data-wow-delay="0.2s">
                    <div class="singleFeature text-center">
                        <div class="featureImg mb-4">
                            <img src="{{asset_site('assest/images/delivery-truck.png')}}" alt="">
                        </div>
                        <div class="featureText ">
                            <h3>{{w('Fast and accurate delivery')}}</h3>
                            <p>{{w('Fast and accurate delivery Details')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-5   wow fadeInUp" data-wow-delay="0.3s">
                    <div class="singleFeature text-center">
                        <div class="featureImg mb-4">
                            <img src="{{asset_site('assest/images/gift-box.png')}}" alt="">
                        </div>
                        <div class="featureText ">
                            <h3>{{w('Special gifts and offers')}}</h3>
                            <p>{{w('Special gifts and offers Details')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-5  wow fadeInUp" data-wow-delay="0.4s">
                    <div class="singleFeature text-center">
                        <div class="featureImg mb-4">
                            <img src="{{asset_site('assest/images/Exclusion.png')}}" alt="">
                        </div>
                        <div class="featureText ">
                            <h3>{{w('Macca Pay Points')}}</h3>
                            <p>{{w('Macca Pay Points Deatils')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-5   wow fadeInUp" data-wow-delay="0.5s">
                    <div class="singleFeature text-center">
                        <div class="featureImg mb-4">
                            <img src="{{asset_site('assest/images/Path.png')}}" alt="">
                        </div>
                        <div class="featureText ">
                            <h3>{{w('Safe requests')}}</h3>
                            <p>{{w('Safe requests Deatils')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-5   wow fadeInUp" data-wow-delay="0.6s">
                    <div class="singleFeature text-center">
                        <div class="featureImg mb-4">
                            <img src="{{asset_site('assest/images/Group1.png')}}" alt="">
                        </div>
                        <div class="featureText ">
                            <h3>{{w('Support center')}}</h3>
                            <p>{{w('Support center Details')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-5   wow fadeInUp" data-wow-delay="0.7s">
                    <div class="singleFeature text-center">
                        <div class="featureImg mb-4">
                            <img src="{{asset_site('assest/images/Group2.png')}}" alt="">
                        </div>
                        <div class="featureText ">
                            <h3>{{w('Free delivery service')}}</h3>
                            <p>{{w('Free delivery service Deatils')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="contact">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="contImg   wow fadeInUp" data-wow-delay="0.2s">
                        <img src="{{asset_site('assest/images/contact.png')}}" class="img-fluid" alt="">
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="contactForm   wow fadeInDown" data-wow-delay="0.2s">
                        <form id="contact_information">
                            {{--                            @csrf--}}
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="row">
                                        <div class="col-12 mb-4">
                                            <input type="text" class="form-control" placeholder="{{w('Name')}}"
                                                   name="name">
                                            <span id='name' class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-12 mb-4">
                                            <input type="text" class="form-control" placeholder="{{w('Mobile no')}}"
                                                   name="mobile">
                                            <span id='mobile' class="invalid-feedback"></span>

                                        </div>
                                        <div class="col-12 mb-4">
                                            <input type="email" class="form-control" placeholder="{{w('Email')}}"
                                                   name="email">
                                            <span id='email' class="invalid-feedback"></span>

                                        </div>
                                        <div class="col-12 mb-4">
                                            <input type="text" class="form-control" placeholder="{{w('Title')}}"
                                                   name="title">
                                            <span id='title' class="invalid-feedback"></span>

                                        </div>
                                        <div class="col-12 mb-4">
                                            <textarea name="message" id="message" cols="30" rows="8"
                                                      class="form-control"
                                                      placeholder="{{w('Message')}}"></textarea>
                                            <span id='message_span' class="invalid-feedback"></span>

                                        </div>
                                        <div class="col-8 mx-auto">
                                            <button class="btnSend btn" type="button"
                                                    onclick="contForm(this)"
                                                    id="cont_form_btn">{{w('Send')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script src="{{ asset("assets/vendors/general/toastr/build/toastr.min.js") }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    <script>
        /* counter */
        // $('.count').counterUp({
        //     delay: 20,
        //     time: 1500
        // });
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "100",
            "hideDuration": "2000",
            "timeOut": "10000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        @if(Session::has('message'))
        toastr.{{Session::get('m-class') ? Session::get('m-class'):'success'}}("{{Session::get('message')}}");
        {{--        {{dd(Session::get('message'))}}--}}
        @endif
    </script>

    <script>

        function contForm(val) {
            // console.log(234234, val)

        }

        $("input[name=name]").on("keyup", function (e) {
            $('#name').text('');
        })
        $("input[name=email]").on("keyup", function (e) {
            $('#email').text('');
        })
        $("input[name=mobile]").on("keyup", function (e) {
            $('#mobile').text('');
        })
        $("input[name=title]").on("keyup", function (e) {
            $('#title').text('');
        })
        $("textarea[name=message]").on("keyup", function (e) {
            $('#message_span').text('');
        })
        $("#cont_form_btn").click(function (event) {
            event.preventDefault();
            console.log('cont_form_btn');
            let name = $("input[name=name]").val();
            let email = $("input[name=email]").val();
            let mobile_number = $("input[name=mobile]").val();
            let title = $("input[name=title]").val();
            let message = $("#message").val();
            let _token = $('meta[name="csrf-token"]').attr('content');
            console.log(_token)
            $.ajax({
                url: '{{route('post_contact_us')}}',
                type: "POST",
                data: {
                    name: name,
                    email: email,
                    mobile: mobile_number,
                    message: message,
                    title: title,
                    _token: _token
                },
                success: function (response) {
                    console.log(response);
                    if (response) {
                        toastr.success(response.message);
                        // $('.success').text(response.success);
                        $("#contact_information")[0].reset();
                    }
                },
                error: function (xhr, textStatus, error) {
                    toastr.error('{{w('Invalid data')}}');

                    let errors = JSON.parse(xhr.responseText)['errors'];
                    let email_error = '';
                    if (errors) {
                        email_error = JSON.parse(xhr.responseText)['errors']['email'];
                        if (email_error) email_error = email_error[0]

                        name_error = JSON.parse(xhr.responseText)['errors']['name'];
                        if (name_error) name_error = name_error[0]
                        mobile_error = JSON.parse(xhr.responseText)['errors']['mobile'];
                        if (mobile_error) mobile_error = mobile_error[0]

                        title_error = JSON.parse(xhr.responseText)['errors']['title'];
                        if (title_error) title_error = title_error[0]


                        message_error = JSON.parse(xhr.responseText)['errors']['message'];
                        if (message_error) message_error = message_error[0]
                        console.log(name_error);
                        console.log(mobile_error);
                        console.log(email_error);
                        console.log(title_error);
                        console.log(message_error);
                    }
                    if (name_error) {
                        $('#name').text(name_error);
                        $('#name').removeClass('invalid-feedback').addClass('valid-feedback');
                    } else {
                        $('#name').text('');
                    }
                    if (mobile_error) {
                        $('#mobile').text(mobile_error);
                        $('#mobile').removeClass('invalid-feedback').addClass('valid-feedback');
                    } else {
                        $('#mobile').text('');
                    }
                    if (email_error) {
                        $('#email').text(email_error);
                        $('#email').removeClass('invalid-feedback').addClass('valid-feedback');
                    } else {
                        $('#email').text('');

                    }
                    if (title_error) {
                        $('#title').text(title_error);
                        $('#title').removeClass('invalid-feedback').addClass('valid-feedback');
                    } else {
                        $('#title').text('');

                    }
                    if (message_error) {
                        $('#message_span').text(message_error);
                        $('#message_span').removeClass('invalid-feedback').addClass('valid-feedback');
                    } else {
                        $('#message_span').text('');
                    }
                    // console.log(xhr.responseText);
                    // console.log(JSON.parse(xhr.responseText));
                    // console.log(JSON.parse(xhr.responseText)['errors']['email'][0]);
                    // console.log(xhr.statusText);
                    // console.log(textStatus);
                    // console.log(error);
                }
            });
        });


    </script>

    {{--    {!! $cont_validator->selector('#contact_information') !!}--}}


@endsection
