@extends('layouts.container')
@php
    $breadcrumb = true;
    $title = w('User Profile');
@endphp
@section('content')
    <section aria-label="section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form  id='form_information' class="form-border" method="post"
                          action='{{route('post_profile')}}'>
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-set">
                                    <label>{{w('Name')}}</label>
                                    <input type='text' name='name' id='name' class="form-control"
                                           value="{{isset($user)? $user->name:old('name')}}"
                                    >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-set">
                                    <label>{{w('Email')}}</label>
                                    <input type='email' name='email' id='email' class="form-control"
                                           value="{{isset($user)? $user->email:old('email')}}"
                                    >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-set">
                                    <label>{{w('Phone')}}</label>
                                    <input type='text' name='phone' id='phone' class="form-control"
                                           value="{{isset($user)? $user->phone:old('phone')}}"
                                    >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-set">
                                    <label>{{w('Country')}}</label>
                                    <input type='text' name='country' id='country' class="form-control"
                                           value="{{isset($user)? $user->country:old('country')}}"
                                    >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-set">
                                    <label>{{w('City')}}</label>
                                    <input type='text' name='city' id='city' class="form-control"
                                           value="{{isset($user)? $user->city:old('city')}}"
                                    >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-set">
                                    <label>{{w('Client Type')}}</label>
                                    <select name="client_type" id="client_type" class="form-control">
                                        <option
                                            value="{{\App\Models\User::client_type['CLIENT']}}"{{isset($user)&& $user->client_type == \App\Models\User::client_type['CLIENT']? 'selected':''}}>{{t('Client')}}</option>
                                        <option
                                            value="{{\App\Models\User::client_type['COMPANY']}}" {{isset($user)&& $user->client_type == \App\Models\User::client_type['COMPANY']? 'selected':''}}>{{t('Company')}}</option>
                                    </select>
                                </div>
                            </div>
                            {{--                            <div class="col-md-6">--}}
                            {{--                                <div class="field-set">--}}
                            {{--                                    <label for="url">{{w('Url')}}</label>--}}
                            {{--                                    <input type='url' name='url' id='url' class="form-control">--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            <div class="col-md-6">
                                <div class="field-set">
                                    <label for="username">{{w('Username')}}</label>
                                    <input type='text' name='username' id='username' class="form-control"
                                           value="{{isset($user)? $user->username:old('username')}}"
                                    >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-set">
                                    <label for="password">{{w('Password')}}</label>
                                    <input type='password' name='password' id='password' class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div id='submit' class="pull-left">
                                    <input type='submit' value='{{w('Save')}}'
                                           class="btn btn-custom color-2">
                                </div>


                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

@endsection




@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"
            type="text/javascript"></script>

    {!! $validator->selector('#form_information') !!}
@endsection


