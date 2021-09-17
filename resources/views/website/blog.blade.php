@extends('layouts.container')
@section('style')
@endsection

@php
    $breadcrumb = true;
    $title = $blog->title;
@endphp



@section('content')

    <!-- section begin -->
    <section aria-label="section">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="blog-read">

                        <img alt="" src="{{$blog->image}}" class="img-fullwidth">

                        <div class="post-text">
                            <p>{{$blog->title}}</p>
                            <p>{!! $blog->details !!}</p>
                            <span class="post-date">{{$blog->created_at}}</span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection


@section('script')
@endsection


