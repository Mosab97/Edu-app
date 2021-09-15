{{--Dev Mosab Irwished
    eng.mosabirwished@gmail.com
    WhatsApp =+970592879186
    WhatsApp Link https://api.whatsapp.com/send/?phone=970592879186&text&app_absent=0
    --}}

@php
    $logo = Setting('logo');
    $logo = isset($logo)?$logo : dashboard_logo();
    $logo_min =  $logo;
      $name = optional(Setting('name'))[lang()];

@endphp

    <!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{'مكا كافيه MaccaCafe'}}</title>
    <meta property="og:type" content=""/>
    <meta property="og:title" content=""/>
    <meta property="og:description" content=" "/>
    <meta property="og:image" content=""/>
    <meta property="og:image:width" content=""/>
    <meta property="og:image:height" content=""/>
    <meta property="og:url" content=""/>
    <meta property="og:site_name" content=" "/>
    <meta property="og:ttl" content=""/>
    <meta name="twitter:card" content=""/>
    <meta name="twitter:domain" content=""/>
    <meta name="twitter:site" content=""/>
    <meta name="twitter:creator" content=""/>
    <meta name="twitter:image:src" content=""/>
    <meta name="twitter:description" content=""/>
    <meta name="twitter:title" content=" "/>
    <meta name="twitter:url" content=""/>
    <meta name="description" content="  "/>
    <meta name="keywords" content=""/>
    <meta name="author" content=""/>
    <meta name="copyright" content=" "/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(lang() == 'ar')
        <link rel="stylesheet" href="{{asset_site('assest/css/bootstrap-rtl.css')}}">
    @else
        <link rel="stylesheet" href="{{asset_site('assest/css/bootstrap.min.css')}}">
    @endif
    <link rel="stylesheet" href="{{asset_site('assest/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset_site('assest/css/fontawesome.min.css')}}">
    {{--    <link rel="stylesheet" href="{{asset_site('assest/css/style'.(sizeof(request()->segments()) == 0? '-home':'' ).'.css')}}">--}}

    <link rel="stylesheet" href="{{asset_site('assest/css/style.css')}}">
    @if(lang() == 'ar')
        <link rel="stylesheet" href="{{asset_site('assest/css/style-rtl.css')}}">@endif
    <link rel="stylesheet" href="{{asset_site('assest/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset_site('assest/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset_site('assest/css/animate.css')}}">
    <style>
        .nav li:hover a {
            color: #fff !important;
        }

        #hover_nav li:hover a {
            color: #000 !important;
        }

        .main-header .menu-container .main-menu .menu_item:hover .menu__submenu {
            opacity: 1;
            visibility: visible;
            top: 140%;
            background: #ca2028;
            min-width: 120px;
        }
        .Drop {
            box-shadow: -2px 2px 3px rgb(0,0,0,0.2);
            border: 2px solid #fff !important;
        }

        .owlNavs .owl-nav {
            margin: 0px 10px;
            border-radius: 50px;
            outline: 0;
            width: 50px;
            text-align: center;
            line-height: 50px;
            color: #B5151B;
            height: 50px;
            background: #ffffff !important;
            filter: drop-shadow(0px 3px 12px rgba(0, 0, 0, 0.16));
            box-shadow: -2px 2px 3px rgb(0,0,0,0.2);
        }

        .testimonials {
            float: left;
            max-width: 450px;
        }

        .owlNavs {
            position: relative;
            z-index: 5;
            display: flex;
            align-items: center;
            margin-top: 40% !important;
            margin-right: 85% !important;
        }

        .menu_item {
            /* overflow: hidden; */
            padding: 0px 10px;
            border-radius: 50px;
            -webkit-transition: all 0.2s ease-in-out;
            -moz-transition: all 0.2s ease-in-out;
            -o-transition: all 0.2s ease-in-out;
            -ms-transition: all 0.2s ease-in-out;
            transition: all 0.2s ease-in-out;
            border: 2px solid white;
            background-color: #fff;
            box-shadow: -2px 2px 3px rgb(0,0,0,0.2);
        }

        .box-shadow {
            box-shadow: none !important;
        }
    </style>
    @yield('style')

    @if(isset($logo_min))
        <link rel="shortcut icon" href="{{ asset($logo_min) }}"/>
    @endif

</head>

<body>

{{--<div class="loader-page"><span></span><span></span></div>--}}
<div class="mobile-menu-overlay"></div>
<!-- begin:: Header -->
<header class="main-header container {{sizeof(request()->segments()) == 0? 'mainPageHead':'' }}">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <div class="logo">
                <a href="{{route('home')}}"><img src="{{asset_site('assest/images/logo.png')}}" alt=""/></a>
            </div>
            <div class="menu--mobile mx-lg-auto">
                <div class="menu-container d-lg-none">
                    <div class="btn-close-header-mobile justify-content-end">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
                <div class="menu-container w-100 d-lg-flex align-items-center justify-content-between">
                    <ul  class="main-menu ml-auto list-main-menu d-lg-flex justify-content-center nav">
                        <li class="menu_item mr-4 menu_bxHid">
                            <a class="menu_link menu_bx box-shadow" href="{{route('home')}}">{{w('Home')}}</a>
                        </li>
                        <li class="menu_item mr-4 menu_bxHid">
                            <a class="menu_link menu_bx box-shadow" href="{{route('products')}}">{{w('Products')}}</a>
                        </li>
                        <li class="menu_item mr-4 menu_bxHid">
                            <a class="menu_link menu_bx box-shadow" href="{{route('offers')}}">{{w('Offers')}}</a>
                        </li>
                        <li class="menu_item mr-4 menu_bxHid">
                            <a class="menu_link menu_bx box-shadow" href="{{route('gallery')}}">{{w('Gallery')}}</a>
                        </li>
                    </ul>


                    <div class="menu-container d-lg-flex align-items-center ml-auto">
                        <form class="search mr-4" action="{{route('search')}}" method="post">
                            @csrf
                            <input class="search__toggle" id="toggleSearch" type="checkbox" hidden=""/>
                            <div class="search__field">
                                <input class="search__input" type="text" name="search" value="{{request()->search}}"
                                       placeholder="ابحث هنا"/>
                                <label class="search__label" for="toggleSearch">
                                    <div class="search__button">
                                        <div class="search__icon search__button--toggle"><i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                    <div class="search__button search__button--submit"><i class="fas fa-times"></i>
                                    </div>
                                </label>
                            </div>
                        </form>
                        <ul class="main-menu d-flex align-items-center mr-lg-4 nav">
                            <li class="menu_item Drop">
                                <a class="menu_link">{{optional(getCurrentCountry())->name}}<i
                                        class="fas fa-chevron-down fa-xs ml-2"></i></a>
                                <ul class="menu__submenu box_shadow" id="hover_nav">
                                    @foreach($countries as $index=>$country)
                                        <li class="item-submenu">
                                            <a class="link-submenu"
                                               href="{{route('change-country',$country->id)}}"> {{optional($country)->name}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                        <ul class="main-menu d-flex align-items-center">
                            <li class="menu_item Drop py-1">
                                <a class="menu_link"><img
                                        @php
                                            $image = lang() == 'ar'?'saudi-arabia.png':'american.png';
                                        @endphp
                                        src="{{asset_site('assest/images/'.$image)}}"
                                        style="width: 32px;height: 32px"
                                        alt=""/><i
                                        class="fas fa-chevron-down fa-xs ml-2"></i></a>
                                <ul class="menu__submenu box_shadow">
                                    @if(lang() == 'ar')
                                        <li class="item-submenu">
                                            <a class="link-submenu" href="{{route('switch-language', 'en')}}">
                                                <img
                                                    style="width: 32px;height: 32px"
                                                    src="{{asset_site('assest/images/american.png')}}"
                                                    alt=""/></a>
                                        </li>
                                    @else
                                        <li class="item-submenu">
                                            <a class="link-submenu" href="{{route('switch-language', 'ar')}}"><img
                                                    src="{{asset_site('assest/images/saudi-arabia.png')}}" alt=""/></a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="header-mobile__toolbar ml-3 d-lg-none fa-lg">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </div>
</header>

@yield('content')

<footer>
    <div class="container">
        <div class="lastFooter d-flex align-items-center justify-content-between flex-wrap">

            <div class="privace mb-3 width-100">
                <ul class="list-unstyled d-flex align-items-center justify-content-between  ">
                    <li class="mr-3"><a href="{{route('privacy_policy')}}">{{w('Privacy & policy')}}</a></li>
                    <li class="mr-3"><a href="{{route('about_us')}}">{{w('About Us')}}</a></li>
                </ul>

            </div>
            <div
                class="copy text-center mb-3 d-flex align-items-center justify-content-between flex-wrap width-100 ">
                <p>{{'Copyrights' . date('Y')}}</p>

                <a class="hidePs" href="javascript:;">
                    @if(lang() == 'ar')
                        مدعوم من قبل مؤسسة وليد الطاهر/ م.بهاء
                    @else
                        Powered by Waleed Al Taher Est/ Eng.Bahaa
                    @endif
                </a>
            </div>
            <div class="social mb-3 width-100 d-flex align-items-center justify-content-between ">
                <ul class="list-unstyled d-flex align-items-center width-100 justify-content-center">
                    <li class="mr-3 d-n"><a href="javascript:;">  @if(lang() == 'ar')
                                مدعوم من قبل مؤسسة وليد الطاهر/ م.بهاء
                            @else
                                Powered by Waleed Al Taher Est/ Eng.Bahaa
                            @endif</a></li>
                    <li class="mr-3"><a href="{{setting('facebook')}}"><i class="fab fa-facebook-f"></i></a></li>
                    <li class="mr-3"><a href="{{setting('instagram')}}"><i class="fab fa-instagram"></i></a></li>
                    <li class="mr-3"><a href="{{setting('twitter')}}"><i class="fab fa-twitter"></i></a></li>
                    <li class="mr-3"><a href="{{setting('linkedin')}}"><i style="color: black"
                                                                          class="fab fa-linkedin-in"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<!-- end:: section -->
<script src="{{asset_site('assest/js/jQuery.js')}}"></script>
<script src="{{asset_site('assest/js/bootstrap.bundle.js')}}"></script>
<script src="{{asset_site('assest/js/owl.carousel.min.js')}}"></script>
@if(lang() =='ar')
    <script src="{{asset_site('assest/js/owlRtl.js')}}"></script>
@endif
<script src="{{asset_site('assest/js/owl-Function.js')}}"></script>
<script src="{{asset_site('assest/js/function.js')}}"></script>
<script src="{{asset_site('assest/js/wow.min.js')}}"></script>

@yield('js')

</body>

</html>
