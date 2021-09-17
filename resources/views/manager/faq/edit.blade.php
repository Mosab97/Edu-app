@extends('manager.layout.container')

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.faq.index') }}">{{t('Faq')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($faq) ? t('Edit Faq') : t('Add Faq') }}
        </li>
    @endpush

    @php

        $key = isset($faq) ? $faq->getTranslations()['key'] : null;
        $value = isset($faq) ? $faq->getTranslations()['value'] : null;
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($faq) ? t('Edit Faq') : t('Add Faq') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.faq.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($faq))
                        <input type="hidden" name="faq_id" value="{{$faq->id}}">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                @foreach(config('translatable.locales') as $local)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Key') }}
                                            <small>({{ $local }})</small></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="key[{{$local}}]" type="text"
                                                   value="{{  optional($key)[$local]}}">
                                        </div>
                                    </div>
                                @endforeach
                                    @foreach(config('translatable.locales') as $local)
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label">{{ t('details') }}
                                                <small>({{ $local }})</small></label>
                                            <div class="col-lg-9 col-xl-6">
                                                <textarea name="value[{{$local}}]" id="details" cols="30" rows="10"
                                                          class="form-control">{{optional($value)[$local]}}</textarea>
                                            </div>
                                        </div>
                                    @endforeach

                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"
                                            class="btn btn-danger">{{ isset($faq) ? t('Update'):t('Create') }}</button>&nbsp;
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
