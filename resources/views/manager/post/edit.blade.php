@extends('manager.layout.container')

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.post.index') }}">{{t('Posts')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($post) ? t('Edit Post') : t('Add Post') }}
        </li>
    @endpush


    @php
        $post =  isset($post)?$post:null;
            $title_field = isset($post) ? $post->getTranslations()['title'] : null;
            $details = isset($post) ? $post->getTranslations()['details'] : null;
    @endphp
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($post) ? t('Edit Post') : t('Add Post') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.post.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($post))
                        <input type="hidden" name="post_id" value="{{$post->id}}">
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
                                             @if(!isset($post) || is_null($post->getOriginal('image'))) style="display:none"
                                             @endif src="{{ isset($post) && !is_null($post->getOriginal('image'))  ? url($post->image):'' }}"
                                             width="150" alt="No file chosen"/>
                                    </div>
                                </div>
                                @foreach(config('translatable.locales') as $local)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Title') }}
                                            <small>({{ $local }})</small></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="title[{{$local}}]" type="text"
                                                   value="{{  isset($post)?optional($title_field)[$local]: old("title[$local]")}}"
                                            >
                                        </div>
                                    </div>
                                @endforeach
                                @foreach(config('translatable.locales') as $local)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Details') }}
                                            <small>({{ $local }})</small></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <textarea name="details[{{$local}}]" cols="30" rows="10"
                                                      class="form-control">{{  isset($post)?optional($details)[$local]: old("details[$local]")}}</textarea>
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
                                            class="btn btn-danger">{{ isset($post) ? t('Update'):t('Create') }}</button>&nbsp;
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
