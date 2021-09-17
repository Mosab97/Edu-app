{{--@foreach($errors as $error)--}}
{{--    {{dd($error)}}--}}
{{--@endforeach--}}
@extends('layouts.container')
@section('style')
@endsection

@php
    $breadcrumb = true;
    $title = w('Request a special service');
@endphp
@section('content')
    <section aria-label="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mb-sm-30">
                    <p>{!!optional(setting('special_service_details'))[lang()]!!}</p><br>
                    <form name="contactForm" id="form_information" class="form-border" method="post"
                          action="{{route('post_special_service_form')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <label for="project_title">{{w('Project Title')}}</label>
                                <input type="text" name="project_title" id="project_title" class="form-control"/>
                            </div>
                            <div class="col-6">
                                <label for="expected_budget">{{w('Expected Budget') . '($)'}}</label>
                                <input type="number" name="expected_budget" id="expected_budget" class="form-control"/>
                            </div>
                            <div class="col-6">
                                <label for="expected_delivery_time">{{w('Expected Delivery Time In Days')}}</label>
                                <input type="text" name="expected_delivery_time" id="expected_delivery_time"
                                       class="form-control"/>
                            </div>
                            <div class="col-6">
                                <label for="other_help_attachments">{{w('Other help attachments')}}</label>
                                <input type="file" name="other_help_attachments" id="other_help_attachments"
                                       class="form-control"/>
                            </div>
                            <div class="col-6">
                                <label for="service_type">{{w('Service Type')}}</label>
                                <select name="service_type" id="service_type"
                                        class="form-control">
                                    @foreach(\App\Models\SpecialService::type as $index => $item)
                                        <option value="{{$item}}">{{w($index)}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6"></div>
                            <div class="col-12">
                                <label for="project_details">{{w('Project Details')}}</label>
                                <textarea name="project_details" id="project_details" cols="30" rows="10"
                                          class="form-control"></textarea>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-success">{{w('Send')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </section>
@endsection


@section('script')
    {!! $service_validator->selector('#form_information') !!}
@endsection


