@extends('manager.layout.container')

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.country.index') }}">{{t('Countries')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($country) ? t('Edit Country') : t('Add Country') }}
        </li>
    @endpush

    @php
        $name = isset($country) ? $country->getTranslations()['name'] : null;
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($country) ? t('Edit Country') : t('Add Country') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.country.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($country))
                        <input type="hidden" name="country_id" value="{{$country->id}}">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                @foreach(config('translatable.locales') as $local)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Name') }}
                                            <small>({{ $local }})</small></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="name[{{$local}}]" type="text"
                                                   value="{{  isset($name) && is_array($name) && array_key_exists($local,$name)? $name[$local]: old("name[$local]")}}"
                                            >
                                        </div>
                                    </div>
                                @endforeach

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Currency') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="currency_id">
                                            <option value="" selected
                                                    disabled>{{t('Select Currency')}}</option>
                                            @foreach($currencies as $currency)
                                                <option
                                                    value="{{$currency->id}}"
                                                    {{isset($country) && $country->currency_id == $currency->id ? 'selected':''}}>{{$currency->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Kilo Cost') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control" name="kilo_cost"
                                               value="{{isset($country)?$country->kilo_cost : old('kilo_cost')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Max Distance') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control" name="max_distance"
                                               value="{{isset($country)?$country->max_distance : old('max_distance')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Email') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="email" class="form-control" name="email"
                                               value="{{isset($country)?$country->email : old('email')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('mobile') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="text" class="form-control" name="mobile"
                                               value="{{isset($country)?$country->mobile : old('mobile')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('address') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="text" class="form-control" name="address"
                                               value="{{isset($country)?$country->address : old('address')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Zip') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control" name="zip"
                                               value="{{isset($country)?$country->getAttributes()['zip'] : old('zip')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('vat') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control" name="vat"
                                               value="{{isset($country)?$country->vat : old('vat')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Default Speed') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control" name="default_speed"
                                               value="{{isset($country)?$country->default_speed : old('default_speed')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        class="col-xl-3 col-lg-3 col-form-label">{{ t('New Registered Users Discount Percent') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control"
                                               name="new_registered_users_discount_percent"
                                               value="{{isset($country)?$country->new_registered_users_discount_percent : old('new_registered_users_discount_percent')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        class="col-xl-3 col-lg-3 col-form-label">{{ t('New Registered Users Discount Time') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control"
                                               name="new_registered_users_discount_time"
                                               value="{{isset($country)?$country->new_registered_users_discount_time : old('new_registered_users_discount_time')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Customer Points') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control"
                                               name="customer_points"
                                               value="{{isset($country)?$country->customer_points : old('customer_points')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Merchant Points') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control"
                                               name="merchant_points"
                                               value="{{isset($country)?$country->merchant_points : old('merchant_points')}}">
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
                                            class="btn btn-danger">{{ isset($country) ? t('Update'):t('Create') }}</button>&nbsp;
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
    {!! $validator->selector('#form_information') !!}
@endsection
