@extends('manager.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/summernote/dist/summernote.rtl.css') }}" rel="stylesheet"/>
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route(\App\Models\Blog::manager_route . 'index') }}">{{t('Blogs')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($blog) ? t('Edit Blog') : t('Add Blog') }}
        </li>
    @endpush

    @php
        $details = isset($blog) ? $blog->getTranslations()['details'] : null;
        $short_details = isset($blog) ? $blog->getTranslations()['short_details'] : null;
        $title_blog = isset($blog) ? $blog->getTranslations()['title'] : null;
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($blog) ? t('Edit Blog') : t('Add Blog') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route(\App\Models\Blog::manager_route . 'store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($blog))
                        <input type="hidden" name="blog_id" value="{{$blog->id}}">
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
                                                     @if(!isset($blog) || is_null($blog->getOriginal('image'))) style="display:none"
                                                     @endif src="{{ isset($blog) && !is_null($blog->getOriginal('image'))  ? url($blog->image):'' }}"
                                                     width="150" alt="No file chosen"/>
                                            </div>
                                        </div>

                                        <div class="row">
                                            @foreach(config('translatable.locales') as $local)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ t('Title') }} <small>({{ $local }}
                                                                )</small></label>
                                                        <input name="title[{{$local}}]" type="text"
                                                               class="form-control" placeholder=""
                                                               value="{{  optional($title_blog)[$local]}}"
                                                        >
                                                    </div>
                                                </div>
                                            @endforeach
                                            @foreach(config('translatable.locales') as $local)
                                                <div class="col-lg-6">
                                                    <label>{{ t('Short Details') }} <small>({{ $local }}
                                                            )</small></label>
                                                    <textarea class="form-control " name="short_details[{{$local}}]"
                                                    >{{optional($short_details)[$local]}}</textarea>
                                                </div>
                                            @endforeach
                                            @foreach(config('translatable.locales') as $local)
                                                <div class="col-lg-6">
                                                    <label>{{ t('Details') }} <small>({{ $local }})</small></label>
                                                    <textarea class="form-control summernote" name="details[{{$local}}]"
                                                    >{{optional($details)[$local]}}</textarea>
                                                </div>
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
                                            class="btn btn-danger">{{ isset($blog) ? t('Update'):t('Create') }}</button>&nbsp;
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
    <script src="{{ asset('assets/vendors/general/summernote/dist/summernote.min.js') }}"
            type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $('.summernote').summernote({
                height: '300px',
            });
        });
    </script>



    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#form_information') !!}




@endsection
