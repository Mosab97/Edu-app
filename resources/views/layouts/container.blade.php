<!DOCTYPE html>
<html lang="{{lang()}}">
<head>
    <meta charset="utf-8"/>
    <title>Injaz-إنجاز</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="Elaxo App and Software Template" name="description"/>
    <meta content="" name="keywords"/>
    <meta content="" name="author"/>

<!--[if lt IE 9]>
    <script src="{{asset_site('js/html5shiv.js')}}"></script>
    <![endif]-->

    <!-- CSS Files
================================================== -->
    <link id="bootstrap" href="{{asset_site('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link id="bootstrap-grid" href="{{asset_site('css/bootstrap-grid.min.css')}}" rel="stylesheet" type="text/css"/>
    <link id="bootstrap-reboot" href="{{asset_site('css/bootstrap-reboot.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset_site('css/animate.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset_site('css/owl.carousel.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset_site('css/owl.theme.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset_site('css/owl.transitions.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset_site('css/magnific-popup.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset_site('css/jquery.countdown.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset_site('css/style.css')}}" rel="stylesheet" type="text/css"/>

    <!-- color scheme -->
    <link id="colors" href="{{asset_site('css/colors/scheme-01.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset_site('css/coloring.css')}}" rel="stylesheet" type="text/css"/>


    <link href="{{ asset('assets/vendors/general/toastr/build/toastr.'.direction('.').'css') }}" rel="stylesheet"
          type="text/css"/>

</head>

<body>

<div id="wrapper">
{{--    <div id="topbar" class="text-white bg-color">--}}
{{--        <div class="container">--}}
{{--            <div class="topbar-left sm-hide">--}}
{{--                        <span class="topbar-widget tb-social">--}}
{{--                            <a href="{{setting('facebook')}}"><i class="fa fa-facebook"></i></a>--}}
{{--                            <a href="#"><i class="fa fa-twitter"></i></a>--}}
{{--                            <a href="#"><i class="fa fa-instagram"></i></a>--}}
{{--                        </span>--}}
{{--            </div>--}}

{{--            <div class="topbar-right">--}}
{{--                <span class="topbar-widget sm-hide"><a href="download.html">Latest Version Available!</a></span>--}}
{{--                <span class="topbar-widget"><a href="pricing.html">Today's Deal: Get 50% Discount!</a></span>--}}
{{--            </div>--}}
{{--            <div class="clearfix"></div>--}}
{{--        </div>--}}
{{--    </div>--}}

<!-- header begin -->
    <header class="transparent scroll-light">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="de-flex sm-pt10">
                        <div class="de-flex-col">
                            <!-- logo begin -->
                            <div id="logo">
                                <a href="{{route('home')}}">
                                    <img alt="" class="logo" src="{{asset_public(setting('logo_light'))}} "
                                         style="width: 70px;height: 21px"/>
                                    <img alt="" class="logo-2" src="{{asset_public(setting('logo'))}}"
                                         style="width: 70px;height: 21px"/>
                                </a>
                            </div>
                            <!-- logo close -->
                        </div>
                        <div class="de-flex-col header-col-mid">
                            <!-- mainmenu begin -->
                            <ul id="mainmenu" style="display: inline-block; font-size:15px;" >
                                <li><a href="{{route('home')}}">{{w('Home')}}<span></span></a></li>
                                <li><a href="{{route('about_us')}}">{{w('About Us')}}<span></span></a></li>
                                <li>
                                    <a href="javascript:;">{{w('Services')}}<span></span></a>
                                    <ul>
{{--                                        @foreach($services as $index=>$service)--}}
{{--                                            <li>--}}
{{--                                                <a href="{{route('view_service_details',$service->id)}}">{{$service->name}}</a>--}}
{{--                                            </li>--}}
{{--                                        @endforeach--}}
                                    </ul>
                                </li>
                                <li><a href="{{route('view_special_service_form')}}">{{w('Request Service')}}
                                        <span></span></a></li>
                                <li><a href="{{route('view_contactUs')}}">{{w('Contact Us')}}<span></span></a></li>
                                <li><a href="{{route('blogs')}}">{{w('Blog')}}<span></span></a></li>
                                <li>
                                    @if(lang() == 'ar')
                                        <a href="{{route('switch-language', 'en')}}">{{ w('English')  }}
                                            <span></span></a>
                                    @else
                                        <a href="{{route('switch-language', 'ar')}}">{{ w('Arabic')  }}<span></span></a>
                                    @endif
                                </li>
                            </ul>
                        </div>



                        <div class="de-flex-col">
                        @if(auth()->check())
                        <ul id="mainmenu" style="display: inline-block; font-size:15px;">
                            <li>
                                <a href="javascript:;"><b>{{w('Account')}}</b><span></span></a>
                                <ul>
                                    <li>
                                        <form id="logout-form" action="{{ url("/logout") }}" method="POST"
                                            style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                        <a class="btnC_info" style="border-radius:0px" href="javascript:;" onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();"><i
                                                class="fa fa-arrow-down"></i> {{w('Logout')}}</a>
                                    </li>
                                    <li>
                                        <a class="btnC_info" style="border-radius:0px" href="{{route('profile')}}"><i
                                                class="fa fa-user"></i> {{w('profile')}}</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        @else
                         <a class="btnC_info" href="{{route('login')}}"><i
                                        class="fa fa-arrow-down"></i><b> {{w('Login')}}</b></a>
                        @endif
                            <span id="menu-btn"></span>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header close -->
    <!-- content begin -->
    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        @if(isset($breadcrumb) && $breadcrumb== true)
            {{--            @php--}}
            {{--                $showcase = Setting('showcase_background');--}}
            {{--                    $bre = isset($showcase)?asset_public(Setting('showcase_background')):asset_site('images/background/1.jpg');--}}
            {{--            @endphp--}}
            <section id="subheader" class="text-light"
                     data-bgimage="url('{{asset_public(optional(Setting('showcase_background'))[lang()])}}') bottom"
            >

                <div class="center-y relative text-center">
                    <div class="container">
                        <div class="row">

                            <div class="col-md-12 text-center">
                                <h1>{{$title}}</h1>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @yield('content')
    </div>
    <!-- content close -->

    <a href="#" id="back-to-top"></a>

    <!-- footer begin -->
    <footer class="footer-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="widget">
                        <a href="{{route('home')}}"><img style="width: 70px;height: 21px" alt="logo" class="logo"
                                                         src="{{asset_public(setting('logo'))}}"></a>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="widget">
                        <h5>Company</h5>
                        <ul>
                            <li><a class="a-underline" href="{{route('privacy_policy')}}">{{w('Privacy Policy')}}
                                    <span></span></a></li>
                            <li><a class="a-underline" href="{{route('all_faq')}}">{{w('FAQ')}}<span></span></a></li>
                            <li><a class="a-underline" href="{{route('conditions')}}">{{w('Conditions')}}
                                    <span></span></a></li>
                            <li><a class="a-underline" href="{{asset(setting('brochure'))}}">{{w('Download Brochure')}}
                                    <span></span></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="widget">
                        <h5>Product</h5>
                        <ul>
{{--                            @foreach($services as $index=>$item)--}}
{{--                                <li><a class="a-underline"--}}
{{--                                       href="{{route('view_service_details',$item->id)}}">{{$item->name}}--}}
{{--                                        <span></span></a>--}}
{{--                                </li>--}}
{{--                            @endforeach--}}
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="widget">
                        <h5>Resources</h5>
                        <ul>
                            <li><a class="a-underline" href="https://injaz.tawk.help/">{{w('Help Center')}}<span></span></a>
                            </li>
                            <li><a class="a-underline" href="{{route('blogs')}}">{{w('Blog')}}<span></span></a></li>
                            <li><a class="a-underline" href="{{route('contact_us')}}">{{w('Contact Us')}}
                                    <span></span></a></li>
                            <li><a class="a-underline" target="_blank"
                                   href="{{setting('join_us_url')}}">{{w('Join Us')}}
                                    <span></span></a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

        <div class="subfooter">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="de-flex">
                            <div class="de-flex-col">
                                © Copyright 2021 - Injaz Developers Team
                            </div>

                            <div class="de-flex-col">
                                <div class="social-icons">
                                    <a href="{{setting('facebook')}}" target="_blank"><i
                                            class="fa fa-facebook fa-lg"></i></a>
                                    <a href="{{setting('twitter')}}" target="_blank"><i class="fa fa-twitter fa-lg"></i></a>
                                    <a href="{{setting('linkedin')}}" target="_blank"><i
                                            class="fa fa-linkedin fa-lg"></i></a>
                                    <a href="{{setting('whatsApp')}}" target="_blank"><i
                                            class="fa fa-whatsapp  fa-lg"></i></a>
                                    {{--                                    <a href="#"><i class="fa fa-rss fa-lg"></i></a>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </footer>
    <!-- footer close -->

    <div id="preloader">
        <div class="spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
</div>


<!-- Javascript Files
================================================== -->
<script src="{{asset_site('js/jquery.min.js')}}"></script>
<script src="{{asset_site('js/bootstrap.min.js')}}"></script>
<script src="{{asset_site('js/wow.min.js')}}"></script>
<script src="{{asset_site('js/jquery.isotope.min.js')}}"></script>
<script src="{{asset_site('js/easing.js')}}"></script>
<script src="{{asset_site('js/owl.carousel.js')}}"></script>
<script src="{{asset_site('js/validation.js')}}"></script>
<script src="{{asset_site('js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset_site('js/enquire.min.js')}}"></script>
<script src="{{asset_site('js/jquery.stellar.min.js')}}"></script>
<script src="{{asset_site('js/jquery.plugin.js')}}"></script>
<script src="{{asset_site('js/typed.js')}}"></script>
<script src="{{asset_site('js/jquery.countTo.js')}}"></script>
<script src="{{asset_site('js/jquery.countdown.js')}}"></script>
<script src="{{asset_site('js/typed.js')}}"></script>
<script src="{{ asset("assets/vendors/general/toastr/build/toastr-home.min.js") }}" type="text/javascript"></script>

<script type="text/javascript">
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
    @endif
</script>

<script>
    var isRTL = "{{lang() == 'ar'?'on':'off'}}";
    var baseURL = "{{asset_site('/')}}/";
</script>
<script src="{{asset_site('js/designesia.js')}}"></script>

<script>
    $(function () {
        // jquery typed plugin
        $(".typed").typed({
            stringsElement: $('.typed-strings'),
            typeSpeed: 100,
            backDelay: 1500,
            loop: true,
            contentType: 'html', // or text
            // defaults to false for infinite loop
            loopCount: false,
            callback: function () {
                null;
            },
            resetCallback: function () {
                newTyped();
            }
        });
    });
</script>

<script src="{{ asset('assets/vendors/general/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"
        type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

@yield('script')
<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function () {
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/60b7e50a6699c7280daa6234/1f775dcrj';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
<!--End of Tawk.to Script-->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-V4WLKWZ68H"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());
    gtag('config', 'G-V4WLKWZ68H');
</script>
</body>
</html>
