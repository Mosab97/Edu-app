@php
    $logo = Setting('logo');
    $logo = isset($logo)?$logo : dashboard_logo();
    $logo_min =  $logo;
      $name = optional(Setting('name'))[lang()];

@endphp
    <!DOCTYPE html>
<html lang="{{app()->getlocale()}}" dir="{{direction()}}">

<!-- begin::Head -->
<head>
    <meta charset="utf-8"/>
    <title>{{ isset($title) ? $title. " | ":''  }}{{ t('Dashboard') }}</title>
    <meta name="description" content="{{ t('Dashboard') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--begin::Fonts -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
            },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!--end::Fonts -->


    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="{{ asset('assets/css/demo6/style.bundle.'.direction('.').'css') }}" rel="stylesheet" type="text/css"/>
    <!--end::Global Theme Styles -->
    <link href="{{ asset('assets/css/demo6/pages/general/invoices/invoice-1.'.direction('.').'css') }}" rel="stylesheet"
          type="text/css"/>

    <!--begin:: Global Mandatory Vendors -->
    <link href="{{ asset('assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.'.direction('.').'css') }}"
          rel="stylesheet" type="text/css"/>

    <!--end:: Global Mandatory Vendors -->
@yield('b_style')

<!--begin:: Global Optional Vendors -->
    <link href="{{ asset('assets/vendors/general/tether/dist/css/tether.'.direction('.').'css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/vendors/general/animate.css/animate.'.direction('.').'css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/vendors/general/toastr/build/toastr.'.direction('.').'css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/vendors/general/sweetalert2/dist/sweetalert2.'.direction('.').'css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/custom/vendors/line-awesome/css/line-awesome.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset("assets/vendors/custom/vendors/flaticon/flaticon.css") }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset("assets/vendors/custom/vendors/flaticon2/flaticon.css") }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.'.direction('.').'css') }}"
          rel="stylesheet" type="text/css"/>

    <!--end:: Global Optional Vendors -->

    <!--begin::Global Theme Styles(used by all pages) -->
    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
@yield('style')
<!--end::Layout Skins -->
    @if(isset($logo_min))
        <link rel="shortcut icon" href="{{ asset($logo_min) }}"/>
    @endif
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body
    class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-aside--minimize kt-page--loading">

<!-- begin:: Page -->

<!-- begin:: Header Mobile -->
<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
    <div class="kt-header-mobile__logo">
        <a href="{{ url("/manager/home") }}">
            {{--            <img alt="Logo" src="{{ asset("assets/media/logos/logo.svg") }}" style="width: 6%"/>--}}
            <h3>App Logo</h3>
        </a>
    </div>
    <div class="kt-header-mobile__toolbar">
        <div class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left"
             id="kt_aside_mobile_toggler">
            <span></span>
        </div>
        <div class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></div>
        <div class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i
                class="flaticon-more"></i></div>
    </div>
</div>

<!-- end:: Header Mobile -->
<div class="kt-grid kt-grid--hor kt-grid--root">
    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

        <!-- begin:: Aside -->
        <button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
        <div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop"
             id="kt_aside">

            <!-- begin:: Aside Menu -->
            <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
                <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1"
                     data-ktmenu-dropdown-timeout="500">
                    <ul class="kt-menu__nav ">
                        @can('Dashboard')
                            <li class="kt-menu__item  @if(Request::is('manager/home*') ) kt-menu__item--active @endif"
                                aria-haspopup="true">
                                <a href="{{ route('manager.home') }}" class="kt-menu__link ">
                                    <i class="kt-menu__link-icon flaticon2-protection"></i>
                                    <span class="kt-menu__link-text">{{ t('Dashboard') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('map')
                            <li class="kt-menu__item  @if(Request::is('manager/map*') ) kt-menu__item--active @endif"
                                aria-haspopup="true">
                                <a href="{{route('manager.map.index')}}" class="kt-menu__link ">
                                    <i class="kt-menu__link-icon flaticon2-map"></i>
                                    <span class="kt-menu__link-text">{{ t('Map') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('financial_system')
                            <li class="kt-menu__item  @if(Request::is('manager/financial_system*') ) kt-menu__item--active @endif"
                                aria-haspopup="true">
                                <a href="{{route('manager.financial_system.index')}}" class="kt-menu__link ">
                                    <i class="kt-menu__link-icon fa fa-dollar-sign"></i>
                                    <span class="kt-menu__link-text">{{ t('Financial system') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('products')
                            <li class="kt-menu__item  @if(Request::is('manager/product*') ) kt-menu__item--active @endif"
                                aria-haspopup="true">
                                <a href="{{route('manager.product.index')}}" class="kt-menu__link ">
                                    <i class="kt-menu__link-icon flaticon2-cube-1"></i>
                                    <span class="kt-menu__link-text">{{ t('Products') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('Product Offers')
                            <li class="kt-menu__item  @if(Request::is('manager/offer*') ) kt-menu__item--active @endif"
                                aria-haspopup="true">
                                <a href="{{route('manager.offer.index')}}" class="kt-menu__link ">
                                    <i class="kt-menu__link-icon flaticon2-cube-1"></i>
                                    <span class="kt-menu__link-text">{{ t('Offers') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('Orders')
                            <li class="kt-menu__item  @if(Request::is('manager/order*') ) kt-menu__item--active @endif"
                                aria-haspopup="true">
                                <a href="{{route('manager.order.index')}}" class="kt-menu__link ">
                                    <i class="kt-menu__link-icon flaticon2-time"></i>
                                    <span class="kt-menu__link-text">{{ t('Orders') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('Categories')
                            <li class="kt-menu__item  @if(Request::is('manager/category*') ) kt-menu__item--active @endif"
                                aria-haspopup="true">
                                <a href="{{route('manager.category.index')}}" class="kt-menu__link ">
                                    <i class="kt-menu__link-icon flaticon2-cube-1"></i>
                                    <span class="kt-menu__link-text">{{ t('Sections') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('Users')
                            <li class="kt-menu__item kt-menu__item--submenu   @if(Request::is('manager/user*') ) kt-menu__item--active @endif
                            @if(Request::is('manager/merchant*') || Request::is('manager/distributor*') ) kt-menu__item--active @endif"
                                aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                                <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                    <span class="kt-menu__link-icon">
                                        <i class="kt-menu__link-icon flaticon2-user-1"></i>
                                    </span>
                                    <span class="kt-menu__link-text">{{t('Users')}}</span>
                                    <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="kt-menu__submenu " kt-hidden-height="200"
                                     style="display: none; overflow: hidden;"><span class="kt-menu__arrow"></span>
                                    <ul class="kt-menu__subnav">
                                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                        <span class="kt-menu__link">
                                            <span class="kt-menu__link-text">{{t('Users')}}</span>
                                        </span>
                                        </li>
{{--                                        @can('Customers')--}}
                                            <li class="kt-menu__item " aria-haspopup="true">
                                                <a
                                                    href="{{route('manager.user.index')}}" class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">{{ t('Customers') }}</span></a>
                                            </li>
{{--                                        @endcan--}}
{{--                                        @can('Merchant')--}}
                                            <li class="kt-menu__item " aria-haspopup="true">
                                                <a
                                                    href="{{route('manager.merchant.index')}}" class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">{{ t('Merchants') }}</span></a>
                                            </li>
{{--                                        @endcan--}}
{{--                                        @can('Distributor')--}}
                                            <li class="kt-menu__item " aria-haspopup="true">
                                                <a
                                                    href="{{route('manager.distributor.index')}}"
                                                    class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">{{ t('Distributor') }}</span></a>
                                            </li>
{{--                                        @endcan--}}
                                    </ul>
                                </div>
                            </li>
                        @endcan
                        @can('Macca pay')
                            <li class="kt-menu__item  @if(Request::is('manager/gift*') ) kt-menu__item--active @endif"
                                aria-haspopup="true">
                                <a href="{{route('manager.gift.index')}}" class="kt-menu__link ">
                                    <i class="kt-menu__link-icon flaticon2-cube-1"></i>
                                    <span class="kt-menu__link-text">{{ t('Macca pay') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('Contact Us')
                            <li class="kt-menu__item  @if(Request::is('manager/contact*') ) kt-menu__item--active @endif"
                                aria-haspopup="true">
                                <a href="{{route('manager.contact_us.index')}}" class="kt-menu__link ">
                                    <i class="kt-menu__link-icon flaticon2-email"></i>
                                    <span class="kt-menu__link-text">{{ t('Support Center') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </div>

            <!-- end:: Aside Menu -->
        </div>

        <!-- end:: Aside -->
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

            <!-- begin:: Header -->
            <div id="kt_header" class="kt-header kt-grid kt-grid--ver  kt-header--fixed ">

                <!-- begin:: Aside -->
                <div class="kt-header__brand kt-grid__item  " id="kt_header_brand">
                    <div class="kt-header__brand-logo">
                        <a href="{{ url("/manager/home") }}">
                            @if(isset($logo_min))
                                <img alt="Logo" src="{{ asset($logo_min) }}" style="width: 90px"/>
                            @else
                                <h4 class="text-center">Macca</h4>
                            @endif
                        </a>
                    </div>
                </div>

                <!-- end:: Aside -->

                <!-- begin:: Title -->
                <div class="dd" style="margin-right: 20px;padding: 20px 0;">
                    <h3 class="kt-header_title kt-grid_item">{{ t('Management Control Panel') }}</h3>
                    @if(isset(cached()->name))
                        <p>{{ cached()->name }}</p>
                    @endif
                </div>

                <!-- end:: Title -->

                <!-- begin: Header Menu -->
                <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i
                        class="la la-close"></i></button>
                <div class="kt-header-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_header_menu_wrapper">
                    <div id="kt_header_menu"
                         class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
                        <ul class="kt-menu__nav ">
                            <li class="kt-menu__item"
                                aria-haspopup="true">
                                <a href="/" class="kt-menu__link ">
                                    <i class="kt-menu__link-icon flaticon2-protection"></i>
                                    <span class="kt-menu__link-text">{{ t('Preview') }}</span>
                                </a>
                            </li>
                            @can('Notification')
                                <li class="kt-menu__item"
                                    aria-haspopup="true">
                                    <a data-toggle="modal" data-target="#sendNotification" class="kt-menu__link ">
                                        <i class="kt-menu__link-icon flaticon2-notification"></i>
                                        <span class="kt-menu__link-text">{{ t('Send Notification') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('General Settings')
{{--                            @canany(['General Settings', 'Managers', 'Roles', 'Ad Images','Countries','Currencies','Slider'])--}}
                                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @if(Request::is('manager/settings*') || Request::is('manager/city*') || Request::is('manager/slider*')|| Request::is('manager/ad_images*') || Request::is('manager/testimonial*')) kt-menu__item--active @endif @if(Request::is('manager/roles*') ) kt-menu__item--active @endif @if(Request::is('manager/page*') ) kt-menu__item--active @endif"
                                    data-ktmenu-submenu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <span class="kt-menu__link-text">{{ t('Settings') }}</span>
                                        <i class="kt-menu__hor-arrow la la-angle-down"></i>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                    <div class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                                        <ul class="kt-menu__subnav">
{{--                                            @can('General Settings')--}}
                                                <li class="kt-menu__item" aria-haspopup="true">
                                                    <a href="{{route('manager.settings.general')}}"
                                                       class="kt-menu__link">
                                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                                            <span></span>
                                                        </i>
                                                        <span
                                                            class="kt-menu__link-text">{{ t('General Settings') }}</span>
                                                    </a>
                                                </li>
{{--                                            @endcan--}}
{{--                                            @can('Countries')--}}
                                                <li class="kt-menu__item" aria-haspopup="true">
                                                    <a href="{{route('manager.country.index')}}" class="kt-menu__link">
                                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="kt-menu__link-text">{{ t('Countries') }}</span>
                                                    </a>
                                                </li>
{{--                                            @endcan--}}
{{--                                            @can('Currencies')--}}
                                                <li class="kt-menu__item" aria-haspopup="true">
                                                    <a href="{{route('manager.currency.index')}}" class="kt-menu__link">
                                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="kt-menu__link-text">{{ t('Currencies') }}</span>
                                                    </a>
                                                </li>
{{--                                            @endcan--}}
{{--                                            @can('Slider')--}}
                                                <li class="kt-menu__item" aria-haspopup="true">
                                                    <a href="{{route('manager.slider.index')}}" class="kt-menu__link">
                                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="kt-menu__link-text">{{ t('Sliders') }}</span>
                                                    </a>
                                                </li>
{{--                                            @endcan--}}
{{--                                            @can('Ad Images')--}}
                                                <li class="kt-menu__item" aria-haspopup="true">
                                                    <a href="{{route('manager.ad_images.index')}}"
                                                       class="kt-menu__link">
                                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="kt-menu__link-text">{{ t('Ad Images') }}</span>
                                                    </a>
                                                </li>
{{--                                            @endcan--}}
{{--                                            @can('Post')--}}
                                                <li class="kt-menu__item" aria-haspopup="true">
                                                    <a href="{{route('manager.post.index')}}" class="kt-menu__link">
                                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="kt-menu__link-text">{{ t('Posts') }}</span>
                                                    </a>
                                                </li>
{{--                                            @endcan--}}
{{--                                            @can('Managers')--}}
                                                <li class="kt-menu__item" aria-haspopup="true">
                                                    <a href="{{route('manager.manager.index')}}" class="kt-menu__link">
                                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="kt-menu__link-text">{{ t('Managers') }}</span>
                                                    </a>
                                                </li>
{{--                                            @endcan--}}
{{--                                            @can('Roles')--}}
                                                <li class="kt-menu__item" aria-haspopup="true">
                                                    <a href="{{route('manager.manager_roles.index')}}"
                                                       class="kt-menu__link">
                                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                                            <span></span>
                                                        </i>
                                                        <span
                                                            class="kt-menu__link-text">{{ t('Managers Roles') }}</span>
                                                    </a>
                                                </li>
{{--                                            @endcan--}}
                                        </ul>
                                    </div>
                                </li>
                            @endcan
{{--                            @endcanany--}}
                            @canany(['Countries','Currencies'])
                                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @if(Request::is('manager*')) kt-menu__item--active @endif"
                                    data-ktmenu-submenu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                                        <span
                                            class="kt-menu__link-text">{{ optional(getCurrentCountry())->name }}</span>
                                        <i class="kt-menu__hor-arrow la la-angle-down"></i>
                                        <i class="kt-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                    @if(user()->id == 1||user()->id == 2 )
                                        @can('Countries')
                                            <div
                                                class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left">
                                                <ul class="kt-menu__subnav">
                                                    @foreach(\App\Models\Country::get() as $key=>$item)
                                                        <li class="kt-menu__item {{$item->id == optional(getCurrentCountry())->id? 'kt-menu__item--active':''}}"
                                                            aria-haspopup="true">
                                                            <a href="{{route('manager.settings.changeCountry',['id'=>$item->id,'segments'=>sizeof(request()->segments())])}}"
                                                               class="kt-menu__link">
                                                                <i class="kt-menu__link-bullet kt-menu__link-bullet--dot">
                                                                    <span></span>
                                                                </i>
                                                                <span
                                                                    class="kt-menu__link-text">{{ $item->name }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach


                                                </ul>
                                            </div>

                                        @endcan
                                    @endif
                                </li>
                            @endcanany
                        </ul>
                    </div>
                </div>
                <!-- end: Header Menu -->

                <!-- begin:: Header Topbar -->
                <div class="kt-header__topbar">


                    <!--begin: Language bar -->
                    <div class="kt-header__topbar-item kt-header__topbar-item--langs">
                        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
									<span class="kt-header__topbar-icon kt-header__topbar-icon--brand">
                                        @if(isRtl())
                                            <img class="" src="{{ asset("assets/media/flags/008-saudi-arabia.svg") }}"
                                                 alt=""/>
                                        @else
                                            <img class="" src="{{ asset("assets/media/flags/020-flag.svg") }}" alt=""/>
                                        @endif
									</span>
                        </div>
                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim">
                            <ul class="kt-nav kt-margin-t-10 kt-margin-b-10">
                                <li class="kt-nav__item kt-nav__item--active">
                                    <a href="{{ route('manager.switch-language', 'en') }}" class="kt-nav__link">
                                        <span class="kt-nav__link-icon"><img
                                                src="{{ asset("assets/media/flags/020-flag.svg") }}" alt=""/></span>
                                        <span class="kt-nav__link-text">English</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="{{ route('manager.switch-language', 'ar') }}" class="kt-nav__link">
                                        <span class="kt-nav__link-icon"><img
                                                src="{{ asset("assets/media/flags/008-saudi-arabia.svg") }}"
                                                alt=""/></span>
                                        <span class="kt-nav__link-text">العربية</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--end: Language bar -->
                    <!--begin: Notifications -->
                    @php
                        $notifications = \App\Models\Notification::query()->where(function ($query){
                                $query->where('notifiable_id', auth()->user()->id)->orWhere('notifiable_id', 0);
                            })->where('notifiable_type', \App\Models\Manager::class)
                                ->latest()->whereNull('read_at')->latest()->get();
                    @endphp
                    <div class="kt-header__topbar-item dropdown">
                        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
                            <span class="kt-header__topbar-icon kt-header__topbar-icon--success">
                                <i class="flaticon2-bell-alarm-symbol"></i>
                                    <span class="kt-badge kt-badge--danger"
                                          id="notification_count"  {{isset($notifications) && count($notifications) > 0 ? '':'style="display: none"'}}>{{ count($notifications) }}</span>
                            </span>

                        </div>
                        <div
                            class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">
                            <form>

                                <!--begin: Head -->
                                <div class="kt-head kt-head--skin-dark kt-head--fit-x kt-head--fit-b"
                                     style="background: linear-gradient(to right,#db1515,#ec5252)">
                                    <h3 class="kt-head__title">
                                        {{ t('Notifications') }}
                                    </h3>
                                    <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-success kt-notification-item-padding-x"
                                        role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active show" data-toggle="tab"
                                               href="#topbar_notifications_notifications" role="tab"
                                               aria-selected="true"></a>
                                        </li>
                                    </ul>
                                </div>

                                <!--end: Head -->
                                <div class="tab-content">
                                    <div class="tab-pane active show" id="topbar_notifications_notifications"
                                         role="tabpanel">
                                        <div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll"
                                             id="notification_list"
                                             data-scroll="true" data-height="300" data-mobile-height="200">
                                            @isset($notifications)
                                                @foreach($notifications as $notification)
                                                    <a href="{{ url('/manager/notification/'.$notification->id) }}"
                                                       class="kt-notification__item">
                                                        <div class="kt-notification__item-icon">
                                                            <i class="flaticon2-notification kt-font-success"></i>
                                                        </div>
                                                        <div class="kt-notification__item-details">
                                                            <div class="kt-notification__item-title">
                                                                {{ $notification->title }}
                                                            </div>
                                                            <div class="kt-notification__item-time">
                                                                {{ $notification->created_at->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            @endisset
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!--end: Notifications -->
                    <!--begin: User bar -->
                    <div class="kt-header__topbar-item kt-header__topbar-item--user">
                        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
                            <span class="kt-hidden kt-header__topbar-welcome"></span>
                            <span class="kt-hidden kt-header__topbar-username">{{ Auth::user()->name }}</span>
                            <span class="kt-header__topbar-icon kt-hidden-"><i
                                    class="flaticon2-user-outline-symbol"></i></span>
                        </div>
                        <div
                            class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">

                            <!--begin: Head -->
                            <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x"
                                 style="background: linear-gradient(to right,#db1515,#ec5252)">
                                <div class="kt-user-card__avatar">

                                    {{--                                    <span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">{{ substr(Auth::user()->name,0,1) }}</span>--}}
                                </div>
                                <div class="kt-user-card__name">
                                    {{ Auth::user()->name }}
                                </div>

                            </div>
                            <!--end: Head -->
                            <!--begin: Navigation -->
                            <div class="kt-notification">
                                <a href="{{route('manager.profile.show')}}" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-calendar-3 kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title kt-font-bold">
                                            {{ t('profile') }}
                                        </div>
                                        <div class="kt-notification__item-time">
                                            {{ t('profile Settings') }}
                                        </div>
                                    </div>
                                </a>
                                <div class="kt-notification__custom kt-space-between">
                                    <form id="logout-form" action="{{ url("/manager/logout") }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                    <a href="{{ url("/manager/logout") }}" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();"
                                       class="btn btn-label btn-label-brand btn-sm btn-bold">{{ t('Logout') }}</a>
                                </div>
                            </div>

                            <!--end: Navigation -->
                        </div>
                    </div>

                    <!--end: User bar -->
                </div>

                <!-- end:: Header Topbar -->
            </div>

            <!-- end:: Header -->
            @stack('search')
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

                <!-- begin:: Content -->

                <!-- begin:: Content -->
                <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
                    @if(!Request::is('manager/home'))
                        <div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/manager/home') }}">{{ t('Home') }}</a>
                                </li>
                                @stack('breadcrumb')
                            </ul>
                        </div>
                    @endif
                <!--Begin::Dashboard 6-->
                    {{--
                    @if(Session::has('message'))
                        <div class="alert alert-{{ Session::get('m-class') }} role="alert">
                            <div class="alert-text">{{ Session::get('message') }}</div>
                            <div class="alert-close">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="la la-close"></i></span>
                                </button>
                            </div>
                        </div>
                    @endif

                    --}}
                    @if (count($errors) > 0)
                        <div class="alert alert-warning">
                            <ul style="width: 100%;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield("content")
                </div>

                <!-- end:: Content -->

                <!-- end:: Content -->
            </div>

            <!-- begin:: Footer -->
            <div class="kt-footer kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
                <div class="kt-footer__copyright">
                    {{ date('Y') }}&nbsp;&copy;&nbsp;<a href="http://soe.com.sa" target="_blank" class="kt-link"> </a>
                </div>
                <div class="kt-footer__menu">
                </div>
            </div>

            <!-- end:: Footer -->
        </div>
    </div>
</div>

<!-- end:: Page -->

<div class="modal fade" id="sendNotification" tabindex="-1" role="dialog" aria-labelledby="sendNotification"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ t('Create New Notification') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form method="post" action="{{ route('manager.notification.store') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">{{ t('Recipients') }}:</label>
                        <select name="recipients" class="form-control" id="notification_type">
                            <option value="{{ALL_USERS}}">{{ t('All Users') }}</option>
                            <option value="{{CUSTOMERS}}">{{ t('Clients') }}</option>
                            <option value="{{MERCHANTS}}">{{ t('Merchants') }}</option>
                            <option value="{{DISTRIBUTORS}}">{{ t('Distributors') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">{{ t('Title') }}:</label>
                        <textarea name="title" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">{{ t('Content') }}:</label>
                        <textarea name="content" class="form-control" required></textarea>
                    </div>
                    <div class="form-group" style="display: none" id="user_id">
                        <label for="recipient-name" class="form-control-label">{{ t('User ID') }}:</label>
                        <input type="number" class="form-control" name="user_id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ t('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ t('Send') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- begin::Scrolltop -->
<div id="kt_scrolltop" class="kt-scrolltop">
    <i class="fa fa-arrow-up"></i>
</div>

<!-- end::Scrolltop -->


<!-- begin::Global Config(global config for global JS sciprts) -->

<!-- end::Global Config -->
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#22b9ff",
                "light": "#ffffff",
                "dark": "#282a3c",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#000000"]
            }
        }
    };
</script>
<!--begin:: Global Mandatory Vendors -->
<script src="{{ asset("assets/vendors/general/jquery/dist/jquery.js") }}" type="text/javascript"></script>
<script src="{{ asset("assets/vendors/general/popper.js/dist/umd/popper.js") }}" type="text/javascript"></script>
<script src="{{ asset("assets/vendors/general/bootstrap/dist/js/bootstrap.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("assets/vendors/general/js-cookie/src/js.cookie.js") }}" type="text/javascript"></script>
<script src="{{ asset("assets/vendors/general/moment/min/moment.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("assets/vendors/general/sticky-js/dist/sticky.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js") }}"
        type="text/javascript"></script>


<!--end:: Global Mandatory Vendors -->
@yield('b_script')
<!--begin:: Global Optional Vendors -->
<script src="{{ asset("assets/vendors/general/toastr/build/toastr.min.js") }}" type="text/javascript"></script>

<!--end:: Global Optional Vendors -->

<!--begin::Global Theme Bundle(used by all pages) -->
<script src="{{ asset("assets/js/demo6/scripts.bundle.js") }}" type="text/javascript"></script>
{{--<script src="https://js.pusher.com/7.0/pusher.min.js"></script>--}}


<!--end::Global Theme Bundle -->

<!--begin::Page Vendors(used by this page) -->

<!--end::Page Vendors -->

<!--begin::Page Scripts(used by this page) -->

<!--end::Page Scripts -->
@yield('script')
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

    function notifyMe(notify) {
        // Let's check if the browser supports notifications
        if (!("Notification" in window)) {
            alert("This browser does not support desktop notification");
        }

        // Let's check if the user is okay to get some notification
        else if (Notification.permission === "granted") {
            // If it's okay let's create a notification
            var notification = new Notification(notify.title, {
                body: notify.body, // content for the alert
                icon: "https://antaderk.com/web/img/logo/logo.png" // optional image url
            });
        }

            // Otherwise, we need to ask the user for permission
            // Note, Chrome does not implement the permission static property
        // So we have to check for NOT 'denied' instead of 'default'
        else if (Notification.permission !== 'denied') {
            Notification.requestPermission(function (permission) {

                // Whatever the user answers, we make sure we store the information
                if (!('permission' in Notification)) {
                    Notification.permission = permission;
                }

                // If the user is okay, let's create a notification
                if (permission === "granted") {
                    var notification = new Notification(notify.title, {
                        body: notify.body, // content for the alert
                        icon: "https://antaderk.com/web/img/logo/logo.png" // optional image url
                    });
                }
            });
        } else {
            alert(`Permission is ${Notification.permission}`);
            toastr.success(notify.title);
        }
        $('#notification_list').prepend(" <a href=\"/manager/notification/" + notify.id + "\"\n" +
            "                                                   class=\"kt-notification__item\">\n" +
            "                                                    <div class=\"kt-notification__item-icon\">\n" +
            "                                                        <i class=\"flaticon2-notification kt-font-success\"></i>\n" +
            "                                                    </div>\n" +
            "                                                    <div class=\"kt-notification__item-details\">\n" +
            "                                                        <div class=\"kt-notification__item-title\">\n" +
            "                                                            " + notify.title + "\n" +
            "                                                        </div>\n" +
            "                                                        <div class=\"kt-notification__item-time\">\n" +
            "                                                            " + "{{t('now')}}" + "\n" +
            "                                                        </div>\n" +
            "                                                    </div>\n" +
            "                                                </a>");
        if (notify.unread_notifications !== undefined) {
            $('#notification_count').show().text(notify.unread_notifications);
        }

        // At last, if the user already denied any notification, and you
        // want to be respectful there is no need to bother him any more.
    }

    //
    // var pusher = new Pusher('8addaf29914d8862443f', {
    //     cluster: 'mt1'
    // });

    // call_back = function (message) {
    //     notifyMe(message)
    //     console.log(message);
    // };
    //Also remember to change channel and event name if your's are different.
    // var channel = pusher.subscribe('managers');
    // channel.bind('manager-notification', call_back);

    {{--var user_channel = pusher.subscribe('user_{{auth()->user()->id}}');--}}
    {{--user_channel.bind('manager-notification', call_back);--}}


</script>

</body>

<!-- end::Body -->
</html>
