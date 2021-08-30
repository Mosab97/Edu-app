@extends('manager.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}"
          rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.user.index') }}">{{t('Clients')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($client) ? t('Edit Client') : t('Add Client') }}
        </li>
    @endpush
    @php
        $name = isset($client) ? $client->getTranslations()['name'] : null;
    @endphp

    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($client) ? t('Edit Client') : t('Add Client') }}</h3>
                    </div>
                </div>

                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.user.store') }}"
                      method="post">
                    {{ csrf_field() }}
                    @if(isset($client))
                        <input type="hidden" name="client_id" value="{{$client->id}}">
                    @endif


                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('image') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-brand">{{ t('upload file') }}</button>
                                            <input name="image" class="imgInp" id="imgInp" type="file"/>
                                        </div>
                                        <img id="blah"
                                             @if(!isset($client) || is_null($client->getOriginal('image'))) style="display:none"
                                             @endif src="{{ isset($client) && !is_null($client->getOriginal('image'))  ? url($client->image):'' }}"
                                             width="150" alt="No file chosen"/>
                                    </div>
                                </div>

                                @foreach(config('translatable.locales') as $local)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Name') }}
                                            <small>({{ $local }})</small></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="name[{{$local}}]" type="text"
                                                   value="{{ isset($name) ? optional($name)[$local] : old("name[$local]") }}">
                                        </div>
                                    </div>
                                @endforeach


                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Mobile') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" dir="ltr" placeholder="+966XXXXXXXXX" name="phone"
                                               type="text" value="{{ isset($client) ? $client->phone :old('phone') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Email') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" dir="ltr" placeholder="Enter your email"
                                               name="email"
                                               type="email" value="{{ isset($client) ? $client->email :old('email') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Password') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" placeholder="******"
                                               name="password"
                                               type="password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Country') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" dir="ltr" placeholder="Enter your Country"
                                               name="country" type="text"
                                               value="{{ isset($client) ? $client->country :old('country') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('City') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" dir="ltr" placeholder="Enter your City"
                                               name="city" type="text"
                                               value="{{ isset($client) ? $client->city :old('city') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Client Type') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select name="client_type" id="client_type" class="form-control">
                                            <option value="">{{t('Select Client Type')}}</option>
                                            <option
                                                value="{{\App\Models\User::client_type['CLIENT']}}"{{isset($user)&& $user->client_type == \App\Models\User::client_type['CLIENT']? 'selected':''}}>{{t('Client')}}</option>
                                            <option
                                                value="{{\App\Models\User::client_type['COMPANY']}}" {{isset($user)&& $user->client_type == \App\Models\User::client_type['COMPANY']? 'selected':''}}>{{t('Company')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Profile Url') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="url" name="url" id="url" class="form-control"
                                               value="{{isset($user)? $user->url : old('url')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Username') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" dir="ltr" placeholder="Enter your username"
                                               name="username"
                                               type="text"
                                               value="{{ isset($client) ? $client->username :old('username') }}">
                                    </div>
                                </div>

                                {{--                                <div class="form-group row">--}}
                                {{--                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Password') }}</label>--}}
                                {{--                                    <div class="col-lg-9 col-xl-6">--}}
                                {{--                                        <input class="form-control" name="password" type="password">--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">{{t('Active')}}</label>
                                    <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" value="1"
                                                   {{ isset($client) && $client->verified == true ? 'checked' :'' }} name="active">
                                            <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">{{t('Draft')}}</label>
                                    <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" value="1"
                                                   {{ isset($client) && $client->draft == 1 ? 'checked' :'' }} name="draft">
                                            <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"
                                            class="btn btn-danger">{{ isset($client) ? t('Update'):t('Create') }}</button>&nbsp;
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
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"
            type="text/javascript"></script>

    {!! $validator->selector('#form_information') !!}
@endsection
