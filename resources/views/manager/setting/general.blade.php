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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                class="col-xl-3 col-lg-3 col-form-label">{{ t('Logo Light') }}</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-brand">{{ t('upload file') }}</button>
                                                    <input name="logo_light" class="imgInp" id="imgInp_min"
                                                           type="file"/>
                                                </div>
                                                <img id="blah_min"
                                                     style="display:{{!isset($logo_light)?'none':'block'}}"
                                                     src="{{ isset($logo_light)  ? url($logo_light):'' }}" width="150"
                                                     alt="No file chosen"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    class="col-xl-3 col-lg-3 col-form-label">{{ t('Showcase Background') . "($local)" }}</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="upload-btn-wrapper">
                                                        <button class="btn btn-brand">{{ t('upload file') }}</button>
                                                        <input name="showcase_background[{{$local}}]" class="imgInp"
                                                               id="imgInp"
                                                               type="file"/>
                                                    </div>
                                                    <img id="blah"
                                                         src="{{ isset($showcase_background)  ? url(optional($showcase_background)[$local]):'' }}"
                                                         width="150"
                                                         alt="No file chosen"/>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row">

                                    {{--                                    <div class="col-md-6">--}}
                                    {{--                                        <div class="form-group">--}}
                                    {{--                                            <label--}}
                                    {{--                                                class="col-xl-3 col-lg-3 col-form-label">{{ t('Showcase Background')  }}</label>--}}
                                    {{--                                            <div class="col-lg-9 col-xl-6">--}}
                                    {{--                                                <div class="upload-btn-wrapper">--}}
                                    {{--                                                    <button class="btn btn-brand">{{ t('upload file') }}</button>--}}
                                    {{--                                                    <input name="showcase_background" class="imgInp"--}}
                                    {{--                                                           id="imgInp"--}}
                                    {{--                                                           type="file"/>--}}
                                    {{--                                                </div>--}}
                                    {{--                                                <img id="blah"--}}
                                    {{--                                                     src="{{ url($showcase_background) }}"--}}
                                    {{--                                                     width="150"--}}
                                    {{--                                                     alt="No file chosen"/>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                class="col-xl-3 col-lg-3 col-form-label">{{ t('Showcase Background Front')  }}</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-brand">{{ t('upload file') }}</button>
                                                    <input name="showcase_background_front" class="imgInp"
                                                           id="imgInp"
                                                           type="file"/>
                                                </div>
                                                <img id="blah"
                                                     src="{{ url($showcase_background_front) }}"
                                                     width="150"
                                                     alt="No file chosen"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ t('Brochure File')  }}</label>
                                            <input type="file" name="brochure" id="brochure" class="form-control">
                                            <a href="{{asset($brochure)}}" target="_blank">{{w('Browse')}}</a>

                                            {{--                                            <div class="col-lg-9 col-xl-6">--}}
                                            {{--                                                <div class="upload-btn-wrapper">--}}
                                            {{--                                                    <button class="btn btn-brand">{{ t('upload file') }}</button>--}}
                                            {{--                                                    <input name="brochure" class="imgInp"--}}
                                            {{--                                                           id="brochure"--}}
                                            {{--                                                           type="file"/>--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                for="about_us_image"
                                                class="col-xl-3 col-lg-3 col-form-label">{{ t('About Us Image') }}</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-brand">{{ t('upload file') }}</button>
                                                    <input name="about_us_image" class="imgInp" id="about_us_image"
                                                           type="file"/>
                                                </div>
                                                <img id="about_us_image"
                                                     style="display:{{!isset($about_us)?'none':'block'}}"
                                                     src="{{ isset($about_us_image)  ? url($about_us_image):'' }}"
                                                     width="150"
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
                                <div class="row">
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">{{ t('Address') }}
                                                    <small>({{ $local }})</small></label>
                                                <input name="address[{{$local}}]" type="text"
                                                       value="{{  isset($address) && is_array($address) && array_key_exists($local,$address)? $address[$local]:''}}"
                                                       class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="commission">{{ t('Commission') }}</label>
                                            <input type="number" step="0.01"
                                                   value="{{ isset($commission) ? $commission:old('commission') }}"
                                                   name="commission" class="form-control"
                                                   placeholder="{{ t('Commission') }}">
                                        </div>


                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                class="col-12 col-form-label font-weight-bold" style="text-align: right">{{t('Commission Active')}}</label>
                                            <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" value="1"
                                                   {{ isset($commission_active) && $commission_active == true ? 'checked' :'' }}
                                                   name="commission_active">
                                            <span></span>
                                            </label>
                                        </span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1 ">{{ t('Email') }}</label>
                                            <input type="text" value="{{ isset($email) ? $email:old('email') }}"
                                                   name="email" class="form-control" placeholder="{{ t('Email') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Mobile') }}</label>
                                            <input type="text" dir="ltr"
                                                   value="{{ isset($mobile) ? $mobile:old('mobile') }}" name="mobile"
                                                   class="form-control" placeholder="{{ t('Mobile') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('whatsApp') }}</label>
                                            <input type="text"
                                                   value="{{ isset($whatsApp) ? $whatsApp:old('whatsApp') }}"
                                                   name="whatsApp" class="form-control"
                                                   placeholder="{{ t('whatsApp') }}">
                                        </div>
                                    </div>
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
                                            <label for="exampleInputPassword1">{{ t('Linkedin') }}</label>
                                            <input type="text"
                                                   value="{{ isset($linkedin) ? $linkedin:old('linkedin') }}"
                                                   name="linkedin" class="form-control"
                                                   placeholder="{{ t('Linkedin') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Youtube') }}</label>
                                            <input type="text" value="{{ isset($youtube) ? $youtube:old('youtube') }}"
                                                   name="youtube" class="form-control" placeholder="{{ t('Youtube') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="currency">{{ t('Currency') }}</label>
                                            <select name="currency" id="currency" class="form-control">
                                                <option value="">{{t('Select Currency')}}</option>
                                                <option
                                                    value="{{\App\Models\Setting::currencies['SAR']}}" {{isset($currency) && $currency == \App\Models\Setting::currencies['SAR']? 'selected':''}}>{{t('SAR')}}</option>
                                                <option
                                                    value="{{\App\Models\Setting::currencies['USD']}}" {{isset($currency) && $currency == \App\Models\Setting::currencies['USD']? 'selected':''}}>{{t('USD')}}</option>
                                                <option
                                                    value="{{\App\Models\Setting::currencies['AED']}}" {{isset($currency) && $currency == \App\Models\Setting::currencies['AED']? 'selected':''}}>{{t('AED')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ t('Join Us Url') }}</label>
                                            <input type="url"
                                                   value="{{ isset($join_us_url) ? $join_us_url:old('join_us_url') }}"
                                                   name="join_us_url" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="calendly_url">{{ t('Calendly Url') }}</label>
                                            <input type="url"
                                                   value="{{ isset($calendly_url) ? $calendly_url:old('calendly_url') }}"
                                                   name="calendly_url" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="showcase_title{{$local}}">{{ t('About Us Title') }}
                                                    <small>({{ $local }})</small></label>

                                                <input name="about_us_title[{{$local}}]" type="text"
                                                       value="{{ optional($about_us_title)[$local]}}"
                                                       class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    @endforeach

                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-lg-6">
                                            <label>{{ t('About Us') }} <small>({{ $local }})</small></label>
                                            <textarea class="form-control summernote"
                                                      name="about_us_details[{{$local}}]"
                                            >{{optional($about_us_details)[$local]}}</textarea>
                                        </div>
                                    @endforeach


                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-lg-6">
                                            <label>{{ t('Special Service Details') }} <small>({{ $local }}
                                                    )</small></label>
                                            <textarea class="form-control summernote"
                                                      name="special_service_details[{{$local}}]"
                                            >{{optional($special_service_details)[$local]}}</textarea>
                                        </div>
                                    @endforeach
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-lg-6">
                                            <label>{{ t('Services') }} <small>({{ $local }})</small></label>
                                            <textarea class="form-control summernote" name="services[{{$local}}]"
                                            >{{optional($services)[$local]}}</textarea>
                                        </div>
                                    @endforeach
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-lg-6">
                                            <label>{{ t('Conditions') }} <small>({{ $local }})</small></label>
                                            <textarea class="form-control summernote" name="conditions[{{$local}}]"
                                            >{{optional($conditions)[$local]}}</textarea>
                                        </div>
                                    @endforeach
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-lg-6">
                                            <label>{{ t('Privacy Policy') }} <small>({{ $local }})</small></label>
                                            <textarea class="form-control summernote" name="privacy_policy[{{$local}}]"
                                            >{{optional($privacy_policy)[$local]}}</textarea>
                                        </div>
                                    @endforeach


                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="showcase_title{{$local}}">{{ t('Showcase Title') }}
                                                    <small>({{ $local }})</small></label>

                                                <input name="showcase_title[{{$local}}]" type="text"
                                                       value="{{ optional($showcase_title)[$local]}}"
                                                       class="form-control" placeholder="">
                                            </div>
                                        </div>
                                    @endforeach
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="showcase_details{{$local}}">{{ t('Showcase Details') }}
                                                    <small>({{ $local }})</small></label>
                                                <textarea class="form-control" name="showcase_details[{{$local}}]"
                                                          id="showcase_details{{$local}}" cols="30"
                                                          rows="10">{{  optional($showcase_details)[$local]  }}</textarea>
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

