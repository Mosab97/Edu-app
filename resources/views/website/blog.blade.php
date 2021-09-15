@extends('layouts.container')
@section('style')
@endsection


@section('content')
    <div class="sinlgeDetails mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-5 mb-lg-5">
                    <div class="product_view owl-carousel owl_4">
                        <div class="single_view">
                            <img src="{{$blog->image}}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single_details_col">
                        <div class="Products_name mt-3">
                            <h2> {{optional($blog)->title}}</h2>
                        </div>
                        <hr class="w-75 float-left">
                        <div class="clearfix"></div>
                        <div class="Productcaption d-block">
                            <p class="Details">{{w('Details')}}</p>
                            <p>{{$blog->description}} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
@endsection
