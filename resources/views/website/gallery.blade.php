@extends('layouts.container')
@section('style')
@endsection


@section('content')
    <div class="galleryNews mt-5  wow fadeInUp" data-wow-delay="0.2s">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="singleNewsRated">
                        <a href="{{route('blog',$first_post->id)}}">
                            <div class="NewsRelaterImg">
                                <img src="{{$first_post->image}}" class="img-fluid" alt="">
                            </div>
                            <div class="NewsRelatedDetails">
                                <h2>{{$first_post->title}}</h2>
                                <p>
                                    <i class="far fa-clock"></i> {{\Carbon\Carbon::parse($first_post->created_at)->format(DATE_FORMAT_WORDS)}}
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row mt-lg-0 mt-5">
                        @foreach($first_4_posts as $index=>$item)
                            <div class="col-lg-6 col-sm-6 mb-Defult">
                                <a href="{{route('blog',$item->id)}}">
                                    <div class="singleLatestNews">
                                        <div class="latesImg">
                                            <img src="{{$item->image}}" class="img-fluid" alt="">
                                        </div>
                                        <div class="latesDetails">
                                            <h5>{{$item->title}}</h5>
                                            <p>
                                                <i class="far fa-clock"></i> {{\Carbon\Carbon::parse($item->created_at)->format(DATE_FORMAT_WORDS)}}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="latestPosts">
        <div class="container">
            <div class="latestPosts_head mb-4">
                <h5>{{w('Latest Posts')}}</h5>
            </div>
            <div class="row  wow fadeInDown" data-wow-delay="0.2s">
                @foreach($latest_posts_section as $index=>$item)
                    <div class="col-lg-3 col-sm-6 mb-4">
                        <a href="{{route('blog',$item->id)}}">
                            <div class="singleLatestNews">
                                <div class="latesImg">
                                    <img src="{{$item->image}}" class="img-fluid" alt="">
                                </div>

                                <div class="latesDetails">
                                    <h5>{{$item->title}}</h5>
                                    <p>
                                        <i class="far fa-clock"></i> {{\Carbon\Carbon::parse($item->created_at)->format(DATE_FORMAT_WORDS)}}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="row mt-5  wow fadeInUp" data-wow-delay="0.2s" id="remaining">
                @foreach($remaining as $index=>$item)
                    <div class="col-sm-6 mb-5">
                        <a href="{{route('blog',$item->id)}}">
                            <div class="singleLatestNews">
                                <div class="latesImg mb-3">
                                    <img src="{{$item->image}}" class="img-fluid" alt="">
                                </div>
                                <div class="latesDetails">
                                    <p>
                                        <i class="far fa-clock"></i> {{\Carbon\Carbon::parse($item->created_at)->format(DATE_FORMAT_WORDS)}}
                                    </p>
                                    <h5>{{$item->title}}</h5>
                                    <p class="caption">{{$item->details}}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
                <div class="col-12 mt-4 text-center">
                    <button class="btn load" onclick="loadMore(12)">{{w('LOAD MORE')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')

    <script>
        function loadMore(item) {
            $.get("/loadMoreGallery/" + item, function (data) {
                $("#remaining").html(data);
            });
        }
    </script>
@endsection
