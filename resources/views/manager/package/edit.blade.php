@extends('manager.layout.container')
@section('style')
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.package.index') }}">{{t('Packages')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($package) ? t('Edit Package') : t('Add Package') }}
        </li>
    @endpush

    @php
        $name = isset($package) ? $package->getTranslations()['name'] : null;
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($package) ? t('Edit Package') : t('Add Package') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.package.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($package))
                        <input type="hidden" name="package_id" value="{{$package->id}}">
                    @endif


                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                                <div class="kt-section kt-section--first">
                                    <div class="kt-section__body">
                                        <div class="row">
                                            <label class="col-xl-3 col-lg-3 col-form-label">{{ t('image') }}</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn btn-brand">{{ t('upload file') }}</button>
                                                    <input name="image" class="imgInp" id="imgInp" type="file"/>
                                                </div>
                                                <img id="blah"
                                                     @if(!isset($package) || is_null($package->getOriginal('image'))) style="display:none"
                                                     @endif src="{{ isset($package) && !is_null($package->getOriginal('image'))  ? url($package->image):'' }}"
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
                                                               value="{{  isset($name) && is_array($name) && array_key_exists($local,$name)? $name[$local]: old("name[$local]")}}"
                                                        >
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="price">{{t('Price')}}</label>
                                                <input type="number" name="price" id="price" class="form-control"
                                                       value="{{isset($package)? $package->price : old('price')}}">
                                            </div>
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
                                            class="btn btn-danger">{{ isset($package) ? t('Update'):t('Create') }}</button>&nbsp;
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
