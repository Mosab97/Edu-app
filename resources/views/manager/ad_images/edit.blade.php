@extends('manager.layout.container')

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.ad_images.index') }}">{{t('Ad Image')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($slider) ? t('Edit Ad Image') : t('Add Ad Image') }}
        </li>
    @endpush


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($slider) ? t('Edit Ad Image') : t('Add Ad Image') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.ad_images.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($slider))
                        <input type="hidden" name="slider_id" value="{{$slider->id}}">
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
                                             @if(!isset($slider) || is_null($slider->getOriginal('image'))) style="display:none"
                                             @endif src="{{ isset($slider) && !is_null($slider->getOriginal('image'))  ? url($slider->image):'' }}"
                                             width="150" alt="No file chosen"/>
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
                                            class="btn btn-danger">{{ isset($slider) ? t('Update'):t('Create') }}</button>&nbsp;
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
