@extends('layouts.container')
@section('style')
@endsection

@php
    $breadcrumb = true;
    $title = w('Service Details');
@endphp



@section('content')
    <section aria-label="section" data-bgcolor="#ffffff">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-5 ">
                    <img src="{{asset($service->image)}}" class="img-fluid" alt="">
                </div>
                <div class="col-md-6 offset-md-1">
                    <h3>
                        {{$service->name}}
                    </h3>
                    <div>
                        {{$service->details}}
                        <br>
{{--                        {{w('Price:') . $service->price . ' ' . (\App\Models\Setting::getCurrency(setting('currency'))[lang()])}}--}}
                        <br>
                        <a href="{{route('view_service_form',$service->id)}}" class="btn btn-success">{{w('Buy')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


@section('script')
@endsection


