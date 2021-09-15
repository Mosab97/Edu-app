@extends('layouts.container')
@section('style')
@endsection


@section('content')

    <div class="offer_slider">
        <div class="container">
            <div class="offer_count owl-carousel owl_3">
                @foreach($ad_images as $index=>$ad)
                    <div class="single_offer">
                        {{--                        {{dd($ad_images,$ad->image)}}--}}
                        <img style="height: 470px;object-fit: cover" src="{{$ad->image}}" alt="offer{{$ad->id}}">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <section class="sliderProducts singlePageSlider">
        <div class="container">
            <div class="productstext text-left   red">
                <h2 class="hero">{{w('Limited Offers')}}</h2>
                <p>{{w('Take the opportunity !! An amazing offer that will not be repeated and for a limited time.')}}</p>
                <p>{{w('Order it now before stocks run out.')}}</p>

            </div>

            <div class="productsSlide owl-carousel owl_2 mt-4">
                @foreach($prices as $index=>$price)
                    <a href="{{route('offer',optional(optional($price)->offer)->id)}}">
                        <div class="singleProduct">
                            <div class="productImg">
                                <img src="{{optional($price->product)->image}}" alt="">
                            </div>
                            <div class="productOver">
                                <div class="prev d-block">
                                    <i class="fas fa-star mr-1"></i>
                                    <span>{{optional($price->product)->rate}}</span>
                                </div>
                                <div class="productDetails">
                                    <div class="Pname">
                                        <p>{{optional($price->product)->name}}</p>
                                    </div>
                                    <div class="Pprice">
                                        <p>{{optional(optional($price)->offer)->piece_cost}} {{optional(getCurrentCountry()->currency)->name}}</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>

    </section>
    <!-- end:: section -->
    <!-- begin:: section -->

    <section class="prductsAll">
        <div class="container">
            <div class="productstext red ">
                <h2 class="hero">{{w('Best Offers')}}</h2>
                <p>{{w('For lovers of offers ... we have dedicated these special products to you.')}}</p>
            </div>
            <div class="row mt-5" id="loadMoreBestOffers">
                @foreach($prices_best_offers as $index=>$price)
                    <div class="col-lg-3 col-md-4 mb-4 col-6">
                        <div class="singleProduct_sub">
                            <a href="{{route('product',optional($price->product)->id)}}">
                                <div class="single_sub">
                                    <div class="subCont">
                                        <div class="subImg">
                                            <img src="{{optional($price->product)->image}}" alt="">
                                            <div class="subRate">
                                                <i class="fas fa-star mr-1"></i>
                                                <span>{{optional($price->product)->rate}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="subText text-center mt-2">
                                        <p>{{optional($price->product)->name}}</p>
                                        <h3>{{optional(optional($price)->offer)->piece_cost}} {{optional(getCurrentCountry()->currency)->name}}</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
                <div class="col-12 text-center">
                    <button class="btn load" onclick="loadMore(10)">{{w('LOAD MORE')}}</button>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('js')
    <script>
        function loadMore(item) {
            $.get("/loadMoreBestOffers/" + item, function (data) {
                $("#loadMoreBestOffers").html(data);
            });
        }
    </script>
@endsection
