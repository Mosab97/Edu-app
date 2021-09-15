@extends('manager.layout.container')

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.category.index') }}">{{t('Categories')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($category) ? t('Edit Category') : t('Add Category') }}
        </li>
    @endpush

    @php
        $name = isset($category) ? $category->getTranslations()['name'] : null;
    @endphp

    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($category) ? t('Edit Category') : t('Add Category') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.category.store') }}"
                      method="post">
                    {{ csrf_field() }}
                    @if(isset($category))
                        <input type="hidden" name="category_id" value="{{$category->id}}">
                    @endif

                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Image') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-brand">{{ t('upload file') }}</button>
                                            <input name="image" class="imgInp" id="imgInp" type="file" />
                                        </div>
                                        <img id="blah" @if(!isset($category) || is_null($category->getOriginal('image'))) style="display:none" @endif src="{{ isset($category) && !is_null($category->getOriginal('image'))  ? url($category->image):'' }}" width="150" alt="No file chosen" />
                                    </div>
                                </div>

                                @foreach(config('translatable.locales') as $local)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Name') }}
                                            <small>({{ $local }})</small></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="name[{{$local}}]" type="text"
                                                   value="{{ isset($name) ? $name[$local] : old("name[$local]") }}">
                                        </div>
                                    </div>
                                @endforeach
{{--                                <div class="form-group row">--}}
{{--                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Ordered') }}</label>--}}
{{--                                    <div class="col-lg-9 col-xl-6">--}}
{{--                                        <input class="form-control" name="ordered" type="number"--}}
{{--                                               value="{{ isset($category->ordered) ? $category->ordered : 1 }}">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group row">--}}
{{--                                    <label class="col-3 col-form-label font-weight-bold">{{t('Draft')}}</label>--}}
{{--                                    <div class="col-3">--}}

{{--                                        <span class="kt-switch">--}}
{{--                                            <label>--}}
{{--                                            <input type="checkbox" value="1"--}}
{{--                                                   {{ isset($category) && $category->draft == 1 ? 'checked' :'' }} name="draft">--}}
{{--                                            <span></span>--}}
{{--                                            </label>--}}
{{--                                        </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"
                                            class="btn btn-danger">{{ isset($category) ? t('Update'):t('Create') }}</button>&nbsp;
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
