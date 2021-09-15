@php
    $logo = optional(getSettings('logo'))->value;
    $logo_min = optional(getSettings('logo_min'))->value;

    $name = optional(getSettings('name'))->value;
    $name = isset($name)?unserialize($name) : ['ar' => '', 'en' => ''];


    $address = optional(getSettings('address'))->value;
    $address = isset($address)?unserialize($address) : ['ar' => '', 'en' => ''];

    $email = optional(getSettings('email'))->value;
    $mobile = optional(getSettings('mobile'))->value;
    $whatsApp = optional(getSettings('whatsApp'))->value;
    $linkedin = optional(getSettings('linkedin'))->value;
    $facebook = optional(getSettings('facebook'))->value;
    $twitter = optional(getSettings('twitter'))->value;
    $instagram = optional(getSettings('instagram'))->value;
    $youtube = optional(getSettings('youtube'))->value;
    $ios_url = optional(getSettings('ios_url'))->value;
    $android_url = optional(getSettings('android_url'))->value;
    $about_us = optional(getSettings('about_us'))->value;
    $services = optional(getSettings('services'))->value;
    $conditions = optional(getSettings('conditions'))->value;

//orders
    $tax = optional(getSettings('tax'))->value;
    $commission = optional(getSettings('commission'))->value;
    $merchants_range = optional(getSettings('merchants_range'))->value;
    $commission_delivery = optional(getSettings('commission_delivery'))->value;
    $commission_cancel_delivery = optional(getSettings('commission_cancel_delivery'))->value;
    $kilo_cost = optional(getSettings('kilo_cost'))->value;


    //$stop_app_for_current_country = optional(getSettings('stop_app_for_current_country'))->value;
    $stop_app_all_countries = optional(getSettings('stop_app_all_countries'))->value;
@endphp

@extends('manager.layout.container')
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
                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class=""
                                                   style="background: #ccc">{{ setting('token_form_dashboard') }}</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <a href="{{route('manager.generate_admin_app_api_new_token')}}"
                                                       class="btn btn-danger">{{t('Generate New Token')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Facebook') }}</label>
                                            <input type="text"
                                                   value="{{ isset($facebook) ? $facebook:old('facebook') }}"
                                                   name="facebook" class="form-control"
                                                   placeholder="{{ t('Facebook') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Twitter') }}</label>
                                            <input type="text" value="{{ isset($twitter) ? $twitter:old('twitter') }}"
                                                   name="twitter" class="form-control" placeholder="{{ t('Twitter') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Instagram') }}</label>
                                            <input type="text"
                                                   value="{{ isset($instagram) ? $instagram:old('instagram') }}"
                                                   name="instagram" class="form-control"
                                                   placeholder="{{ t('Instagram') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Linkedin') }}</label>
                                            <input type="text"
                                                   value="{{ isset($linkedin) ? $linkedin:old('linkedin') }}"
                                                   name="linkedin" class="form-control"
                                                   placeholder="{{ t('Linkedin') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">{{ t('About Us') }}
                                                    <small>({{ $local }})</small></label>
                                                <textarea class="form-control" name="about_us[{{$local}}]" id="about_us"
                                                          cols="30"
                                                          rows="10">{{isset($about_us) && is_array($about_us) && array_key_exists($local,$about_us)? $about_us[$local]:'' }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">{{ t('conditions') }}
                                                    <small>({{ $local }})</small></label>
                                                <textarea class="form-control" name="conditions[{{$local}}]"
                                                          id="conditions" cols="30"
                                                          rows="10">{{  isset($conditions) && is_array($conditions) && array_key_exists($local,$conditions)? $conditions[$local]:''  }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach


                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label
                                            class="col-12 col-form-label font-weight-bold">{{t('Stop Mobile App For All Countries')}}</label>
                                        <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" value="1"
                                                   {{ isset($stop_app_all_countries) && $stop_app_all_countries == true ? 'checked' :'' }} name="stop_app_all_countries">
                                            <span></span>
                                            </label>
                                        </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            class="col-12 col-form-label font-weight-bold">{{t('Stop Mobile App For Current Country')}}</label>
                                        <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" value="1"
                                                   {{   getCurrentCountry()->app_is_stopped == true ? 'checked' :'' }} name="stop_app_for_current_country">
                                            <span></span>
                                            </label>
                                        </span>
                                        </div>
                                    </div>
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

@endsection
