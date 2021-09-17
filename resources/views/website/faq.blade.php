@extends('layouts.container')
@section('style')
@endsection

@php
    $breadcrumb = true;
    $title = w('Privacy Policy');
@endphp



@section('content')
    <section aria-label="section" data-bgcolor="#ffffff">
        <div class="container">
            <div class="row align-items-center">
                {{optional(setting('privacy_policy'))[lang()]}}
            </div>
        </div>
    </section>
@endsection


@section('script')
@endsection


