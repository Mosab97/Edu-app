@php
    $logo = Setting('logo');
    $logo_min = Setting('logo_min');
    $logo_light = Setting('logo_light');
    $about_us_image = Setting('about_us_image');
    $name = Setting('name');
    $address = Setting('address');

    $email = Setting('email');
    $commission = Setting('commission');
    $commission_active = Setting('commission_active');
    $mobile = Setting('mobile');
    $whatsApp = Setting('whatsApp');
    $facebook = Setting('facebook');
    $twitter = Setting('twitter');
    $linkedin = Setting('linkedin');
    $currency = Setting('currency');
    $calendly_url = Setting('calendly_url');
    $join_us_url = Setting('join_us_url');
    $youtube = Setting('youtube');
    $about_us_details = Setting('about_us_details');
    $about_us_title = Setting('about_us_title');
    $special_service_details = Setting('special_service_details');
    $services = Setting('services');
    $conditions = Setting('conditions');
    $conditions = Setting('conditions');
    $showcase_title = Setting('showcase_title');
    $showcase_details = Setting('showcase_details');
    $showcase_background = Setting('showcase_background');
    $showcase_background_front = Setting('showcase_background_front');
    $brochure = Setting('brochure');
    //dd(url($showcase_background_front),$showcase_background,$showcase_background_front);
    $privacy_policy = Setting('privacy_policy');
//dd($showcase_background,$showcase_details,$showcase_title)
@endphp
@extends('manager.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/summernote/dist/summernote.rtl.css') }}" rel="stylesheet"/>
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{ t('General Settings') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            <i class="flaticon-responsive"></i> {{ t('General Settings') }}</h3>
                    </div>
                </div>
                <form class="kt-form kt-form--label-right" enctype="multipart/form-data"
                      action="{{ route('manager.settings.updateSettings') }}" method="post">
                    {{ csrf_field() }}
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Logo') }}</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-brand">{{ t('upload file') }}</button>
                                                    <input name="logo" class="imgInp" id="imgInp" type="file"/>
                                                </div>
                                                <img id="blah" style="display:{{!isset($logo)? 'none' :'block'}}"
                                                     src="{{ isset($logo)  ? url($logo):'' }}" width="150"
                                                     alt="No file chosen"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">{{ t('Name') }} <small>({{ $local }}
                                                        )</small></label>

                                                <input name="name[{{$local}}]" type="text"
                                                       value="{{  isset($name) && is_array($name) && array_key_exists($local,$name)? $name[$local]:''}}"
                                                       class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn btn-brand">{{ t('Save') }}</button>&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/vendors/general/summernote/dist/summernote.min.js') }}"
            type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $('.summernote').summernote({
                height: '300px',
            });
        });
    </script>
@endsection

