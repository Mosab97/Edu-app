@extends('layouts.container')
@section('style')
@endsection


@section('content')


    <div class="sinlgeDetails mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-5 mb-lg-5">
                    <div class="product_view owl-carousel owl_4">
                        @foreach($product->images()->where('type',\App\Models\ProductImages::type['CUSTOMER'])->get() as $index=>$image)
                            <div class="single_view">
                                <img src="{{$image->image}}" alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single_details_col">
                        <div class="Categroy_name">
                            <span>{{optional($product->category)->name}}</span>
                        </div>
                        <div class="Products_name mt-3">
                            <h2> {{optional($product)->name}}</h2>
                        </div>
                        <div class="stars_P mt-2">
                            @for($i = 1; $i <= $product->rate; $i++ )
                                <i class="fas fa-star mr-2"></i>
                            @endfor
                        </div>
                        <div class="price mt-4 d-flex align-items-center ">
                            <h3>{{optional($product->price)->piece_cost}} {{optional(getCurrentCountry()->currency)->name}}</h3>
                            <h3 class="ml-4">
                                {{--                                <del>250$</del>--}}
                            </h3>
                        </div>
                        <div class="Add">
                            <button class="addCart" data-toggle="modal"
                                    data-target="#modalSpecial">{{w('Add to Cart')}}</button>
                        </div>
                        <hr class="w-75 float-left">
                        <div class="clearfix"></div>
                        <div class="Productcaption d-block ">
                            <p class="Details">{{w('Details')}}</p>
                            <p>{{$product->description}} </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="sliderProducts singlePageSlider pt-5">
        <div class="container">
            <div class="productstext text-left   red">
                <h2 class="hero">{{w('Related Products')}}</h2>
                <p>{{w('More featured products')}}</p>

            </div>

            <div class="productsSlide owl-carousel owl_5 mt-4">
                @foreach($related_product as $index=>$item)
                    <a href="{{route('product',$item->id)}}">
                        <div class="singleProduct">
                            <div class="productImg">
                                <img src="{{$item->image}}" alt="">
                            </div>
                            <div class="productOver">
                                <div class="prev d-block">
                                    <i class="fas fa-star mr-1"></i>
                                    <span>{{$item->rate}}</span>
                                </div>
                                <div class="productDetails">
                                    <div class="Pname">
                                        <p>{{$item->name}}</p>
                                    </div>
                                    <div class="Pprice">
                                        <p>{{optional($item->price)->piece_cost}} {{optional(getCurrentCountry()->currency)->name}}</p>
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

    <!-- Button trigger modal -->
    <div class="modal fade" id="modalSpecial" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content sahdow-none border-0 rounded_lg bg-white">
                <div class="modal-body px-lg-5">

                    <div class="row align-items-center">
                        <div class="col-12 text-center">
                            <img src="{{asset_site('assest/images/app.png')}}" alt="">
                            <p class="my-4"> {{w('Download The App to Purchase and preview thousands of hight quality Products, get it NOW!')}} </p>
                            <div class="appBtns mt-3">
                                <a href="{{setting('ios_app_url')}}"><img src="{{asset_site('assest/images/btn1.png')}}"
                                                                          alt=""></a>
                                <a href="{{setting('android_app_url')}}"><img
                                        src="{{asset_site('assest/images/btn2.png')}}" alt=""></a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="image"><img src="{{asset_site('assets/images/image-special.svg')}}" alt=""/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
@endsection
