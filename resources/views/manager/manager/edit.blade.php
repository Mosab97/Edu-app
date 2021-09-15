{{--Dev Mosab Irwished
    eng.mosabirwished@gmail.com
    WhatsApp =+970592879186
    WhatsApp Link https://api.whatsapp.com/send/?phone=970592879186&text&app_absent=0
    --}}

@extends('manager.layout.container')
@section('style')
    <style>
        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            cursor: pointer;
        }

        .upload-btn-wrapper:hover {
            cursor: pointer;
        }

        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }
    </style>
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.manager.index') }}">{{ t('Managers Accounts') }}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($manager) ? t('Edit Account') : t('Add Account') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-10 offset-1">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($manager) ? t('Edit Account') : t('Add Account') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{ isset($manager) ? route('manager.manager.update', $manager->id): route('manager.manager.store') }}"
                      method="post">
                    {{ csrf_field() }}
                    @if(isset($manager))
                        <input type="hidden" name="_method" value="patch">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Manager Name') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="name" type="text"
                                               value="{{ isset($manager->name) ? $manager->name : old('name') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Email') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="email" type="email"
                                               value="{{ isset($manager->email) ? $manager->email : old('email') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Password') }} </label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="password" type="password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Country') }} </label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select name="country_id" id="country" class="form-control">
                                            <option value="">{{t('Select Country')}}</option>
                                            @foreach($countries as $index=>$country)
                                                <option
                                                    value="{{$country->id}}" {{isset($manager) && $manager->country_id == $country->id? 'selected':''}}>{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Roles') }} </label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select  name="roles[]" class="form-control">
                                            @foreach($roles as $role)
                                                <option
                                                    {{ isset($manager)&& in_array($role->id, $userRole) ? 'selected':'' }} value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
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
                                            class="btn btn-brand">{{ isset($manager) ? t('update'):t('Save') }}</button>&nbsp;
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
    <script type="text/javascript">

    </script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#form_information') !!}
@endsection
