@extends('manager.layout.container')
@section('style')
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.service.index') }}">{{t('Services')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($service) ? t('Edit Service') : t('Add Service') }}
        </li>
    @endpush

    @php
        $name = isset($service) ? $service->getTranslations()['name'] : null;
        $details = isset($service) ? $service->getTranslations()['details'] : null;
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($service) ? t('Edit Service') : t('Add Service') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.service.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($service))
                        <input type="hidden" name="service_id" value="{{$service->id}}">
                    @endif


                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
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
                                                     @if(!isset($service) || is_null($service->getOriginal('image'))) style="display:none"
                                                     @endif src="{{ isset($service) && !is_null($service->getOriginal('image'))  ? url($service->image):'' }}"
                                                     width="150" alt="No file chosen"/>
                                            </div>
                                        </div>

                                        <div class="row">

                                            @foreach(config('translatable.locales') as $local)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ t('Name') }} <small>({{ $local }})</small></label>
                                                        <input name="name[{{$local}}]" type="text"
                                                               class="form-control" placeholder=""
                                                               value="{{  optional($name)[$local]}}"
                                                        >
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if(isset($service) && $service->id == \App\Models\Service::f3Types['Training service'])
                                                    <div class="col-6">
                                                        <label for="hour_price">{{t('Hour Price')}}</label>
                                                        <input type="number" name="hour_price" id="hour_price"
                                                               class="form-control" value="{{$service->hour_price}}">
                                                    </div>
                                                @endif
                                                <div class="col-6">
                                                    <label for="price">{{t('Price')}}</label>
                                                    <input type="number" name="price" id="price"
                                                           class="form-control" value="{{isset($service)?$service->price:old('price')}}">
                                                </div>
                                                @foreach(config('translatable.locales') as $local)

                                                    <div class="col-6">
                                                        <label for="deatils">{{t('Details')}} <small>({{ $local }})</small></label>
                                                        <textarea name="details[{{$local}}]" id="details" cols="30" rows="10"
                                                                  class="form-control">{{optional($details)[$local]}}</textarea>
                                                    </div>


{{--                                                    <div class="col-md-6">--}}
{{--                                                        <div class="form-group">--}}
{{--                                                            <label>{{ t('Name') }} <small>({{ $local }})</small></label>--}}
{{--                                                            <input name="name[{{$local}}]" type="text"--}}
{{--                                                                   class="form-control" placeholder=""--}}
{{--                                                                   value="{{  optional($name)[$local]}}"--}}
{{--                                                            >--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
                                                @endforeach




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
                                    <button type="submit"
                                            class="btn btn-danger">{{ isset($service) ? t('Update'):t('Create') }}</button>&nbsp;
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
    <script src="{{ asset('assets/vendors/general/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"
            type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#form_information') !!}
@endsection
