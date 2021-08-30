@extends('layouts.container')
@section('style')
@endsection

@php
    $breadcrumb = true;
    $title = w('About Us');
@endphp



@section('content')
    <section aria-label="section" data-bgcolor="#ffffff">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-5 ">
                    <img src="{{asset(setting('about_us_image'))}}" class="img-fluid" alt="">
                </div>
                <div class="col-md-6 offset-md-1">
                    <h3>
                        {{optional(setting('about_us_title'))[lang()]}}
                    </h3>
                    <div>
                        {!! optional(setting('about_us_details'))[lang()] !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="section-fun-facts" class="pt60 pb60 text-light bg-color">
        <div class="container">
            <div class="row">
                @php
                    $delay = [0,0.25,0.5,0.75,1];
                @endphp
                @foreach($statistics as $index=>$statistic)
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="{{$delay[$index]}}s">
                        <div class="de_count">
                            <h3><span class="timer" data-to="{{$statistic->value}}"
                                      data-speed="3000">{{$statistic->value}}</span>+</h3>
                            <h5>{{$statistic->key}}</h5>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection


@section('script')
@endsection


