@extends('layouts.container')
@section('style')
@endsection

@php
    $breadcrumb = true;
    $title = w('Contact Us');
@endphp
@section('content')
    <section aria-label="section">
        <div class="container">
            <div class="row">

                <div class="col-lg-8 mb-sm-30">
                    <form  id="form_information" name="contactForm" id="contact_form" class="form-border" method="post"
                          action="{{route('contact_us')}}">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <label for="name">{{w('Name')}}</label>
                                <input type="text" name="name" id="name" class="form-control"
                                       placeholder="{{w('Your Name')}}"/>
                            </div>
                            <div class="col-6">
                                <label for="email">{{w('Email')}}</label>
                                <input type="email" name="email" id="email" class="form-control"
                                       placeholder="{{w('Your Email')}}"/>
                            </div>
                            <div class="col-6">
                                <label for="phone">{{w('Phone')}}</label>
                                <input type="text" name="mobile" id="phone" class="form-control"
                                       placeholder="{{w('Your Phone')}}"/>
                            </div>
                            <div class="col-6">
                                <label for="target">{{w('Target')}}</label>
                                <select name="target" id="target" class="form-control">
                                    @foreach(\App\Models\ContactUs::target as $index => $item)
                                        <option value="{{$item}}">{{w($index)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="how_did_you_hear_about_ingaz">{{w('How Did You Hear About Ingaz')}}</label>
                                <select name="how_did_you_hear_about_ingaz" id="how_did_you_hear_about_ingaz"
                                        class="form-control">
                                    <option value="">{{w('Select Choice')}}</option>
                                    @foreach(\App\Models\ContactUs::how_did_you_hear_about_ingaz as $index => $item)
                                        <option value="{{$item}}">{{w($index)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="message">{{w('Message')}}</label>
                                <textarea name="message" id="message" class="form-control"
                                          placeholder="{{w('Your Message')}}"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">{{w('Send')}}</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-4">
                    <div class="padding40 box-rounded mb30" data-bgcolor="#f0f4fd">
                        <address class="s1">
                            <span><i class="id-color fa fa-map-marker fa-lg"></i>{{optional(setting('address'))[lang()]}}</span>
                            <span><i class="id-color fa fa-phone fa-lg"></i>{{setting('mobile')}}</span>
                            <span><i class="id-color fa fa-envelope-o fa-lg"></i><a
                                    href="mailto:{{setting('email')}}">{{setting('email')}}</a></span>
                            <span><i class="id-color fa fa-file-pdf-o fa-lg"></i><a
                                    href="{{asset(setting('brochure'))}}">{{w('Download Brochure')}}</a></span>
                        </address>
                    </div>
                </div>

            </div>
        </div>

    </section>
@endsection



@section('script')
    {!! $cont_validator->selector('#form_information') !!}
@endsection


