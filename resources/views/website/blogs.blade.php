@extends('layouts.container')
@section('style')
@endsection

@php
    $breadcrumb = true;
    $title = w('Blogs');
@endphp



@section('content')
    <!-- section begin -->
    <section aria-label="section">
        <div class="container">
            <div class="row">
                @foreach($blogs as $index=>$item)
                    <div class="col-lg-4 col-md-6 mb30">
                        <div class="bloglist item">
                            <div class="post-content">
                                <div class="post-image">
                                    <img alt="" src="{{$item->image}}">
                                </div>
                                <div class="post-text">
                                    <span class="p-tagline">Inspiration</span>
                                    <h4><a class="a-underline" href="{{route('blog',$item->id)}}">{{$item->title}}
                                            <span></span></a>
                                    </h4>
                                    <p>{!!$item->short_details!!}</p>
                                    <span class="p-date">{{$item->created_at}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
                {{$blogs->links()}}
        </div>
    </section>

@endsection


@section('script')
@endsection


