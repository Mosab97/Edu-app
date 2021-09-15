@extends('layouts.container')
@section('style')
@endsection


@section('content')


    <section class="Offer mt-5">
        <div class="container">
            <div class="offerDet d-flex align-items-center justify-content-between flex-wrap">
                <div class="offerText text-center">
                    <h4>{{w('Download the app and get 30% off for a week',[
    'percent' => optional(getCurrentCountry())->new_registered_users_discount_percent,
    'day' => optional(getCurrentCountry())->new_registered_users_discount_time
])}}</h4>
                </div>
                <div class="appBtns mt-3">
                    <a href="{{setting('ios_app_url')}}"><img src="{{asset_site('assest/images/btn1.png')}}"
                                                              alt=""/></a>
                    <a href="{{setting('android_app_url')}}"><img src="{{asset_site('assest/images/btn2.png')}}"
                                                                  alt=""/></a>
                </div>
            </div>
        </div>
    </section>
    <section class="prductsAll mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 mb-5">
                    <div class="filter">
                        <div class="filterHead d-flex align-items-center justify-content-between">
                            <span><i class="fas fa-filter mr-3"></i> {{w('filter')}}</span>
                            <i class="fas fa-chevron-down Rotate"></i>
                        </div>
                        <div class="filterCont">
                            <p class="px-3 pt-3 catigorys">{{w('Categories')}}</p>
                            <div class="row px-3 pt-3">
                                @foreach($categories as $index=>$category)
                                    <div class="col-6 mb-3">
                                        <div class="form-check">
                                            <input
                                                onchange="checkedValue({{$category->id}})"
                                                class="form-check-input"
                                                type="checkbox"
                                                value="{{$category->id}}"
                                                id="{{'category-'.$category->id}}"
                                            />
                                            <label class="form-check-label ml-2" for="{{'category-'.$category->id}}">
                                                {{$category->name}}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
{{--                            <p class="px-3 pt-3 catigorys">{{w('Price')}}</p>--}}
{{--                            <div class="row px-3 pt-3" id="prices">--}}
{{--                                <div class="col-6 mb-3">--}}
{{--                                    <div class="form-check">--}}
{{--                                        <input--}}
{{--                                            onchange="checkedValue(1)"--}}
{{--                                            class="form-check-input"--}}
{{--                                            type="radio"--}}
{{--                                            name="prices"--}}
{{--                                            value="1"--}}
{{--                                            id="flexCheckDefault1"--}}
{{--                                        />--}}
{{--                                        <label class="form-check-label ml-2" for="flexCheckDefault1"> 0-100 </label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-6 mb-3">--}}
{{--                                    <div class="form-check">--}}
{{--                                        <input--}}
{{--                                            class="form-check-input"--}}
{{--                                            type="radio"--}}
{{--                                            name="prices"--}}
{{--                                            onchange="checkedValue(2)"--}}
{{--                                            value="2"--}}
{{--                                            id="flexCheckDefault"--}}
{{--                                        />--}}
{{--                                        <label class="form-check-label ml-2" for="flexCheckDefault">--}}
{{--                                            100-200--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-6 mb-3">--}}
{{--                                    <div class="form-check">--}}
{{--                                        <input--}}
{{--                                            class="form-check-input"--}}
{{--                                            type="radio"--}}
{{--                                            name="prices"--}}
{{--                                            value="3"--}}
{{--                                            onchange="checkedValue(3)"--}}
{{--                                            id="flexCheckDefault1"--}}
{{--                                        />--}}
{{--                                        <label--}}
{{--                                            class="form-check-label ml-2"--}}
{{--                                            for="flexCheckDefault1"--}}
{{--                                        >--}}
{{--                                            200-300--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-6 mb-3">--}}
{{--                                    <div class="form-check">--}}
{{--                                        <input--}}
{{--                                            class="form-check-input"--}}
{{--                                            type="radio"--}}
{{--                                            name="prices"--}}
{{--                                            value="4"--}}
{{--                                            onchange="checkedValue(4)"--}}
{{--                                            id="flexCheckDefault"--}}
{{--                                        />--}}
{{--                                        <label class="form-check-label ml-2" for="flexCheckDefault">--}}
{{--                                            300-400--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="row" id="products-container">
                        @foreach($products as $index=>$item)
                            <div class="col-md-4 mb-4 col-6">
                                <div class="singleProduct_sub">
                                    <a href="{{route('product',$item->id)}}">
                                        <div class="single_sub">
                                            <div class="subCont">
                                                <div class="subImg">
                                                    <img src="{{$item->image}}" alt="" style="
    width: 245px;
    height: 131px;
    object-fit: fill;
">
                                                    <div class="subRate">
                                                        <i class="fas fa-star mr-1"></i>
                                                        <span>{{$item->rate}}</span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="subText text-center mt-2">
                                                <p>{{$item->name}}</p>
                                                <h3>{{optional($item->price)->piece_cost}} {{optional(getCurrentCountry()->currency)->name}}</h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-12 text-center">
                            {{--                            <a href="{{route('products',['items' => (request()->items + 5)])}}"--}}
                            {{--                               class="btn load">{{w('LOAD MORE')}}</a>--}}
                            <button type="button" class="btn load"
                                    onclick="loadMore(10)">{{w('LOAD MORE')}}</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


@section('js')
    <script>


        function loadMore(item = null, categories = null, price = null) {
console.log(item,categories,price);
            $.get("/loadMore/" + item + '?search=' + '{{request()->search}}'
                , {
                    categories:categories,
                    price:price
                }
                ,

                function (data) {
                    $("#products-container").html(data);
                }
            )
            ;
        }

        function checkedValue(item) {
            let arr = [];
            var price = 0;
            $("input:checkbox:checked").each(function () {
                arr.push($(this).val());
            });
            $("input:radio:checked").each(function () {
                price = $(this).val();
            });
            // console.log('arr', arr.join(), arr);
            arr = arr.join();
            $.get("/filter/" + arr, {
                price
            }, function (data) {
                // console.log(data, 6506540);
                $("#products-container").html(data);
                // alert("Load was performed.");
            });
        }

        function checkedPriceValue(item) {
            $.get("/filter-price/" + item, function (data) {
                $("#products-container").html(data);
            });
        }


    </script>
@endsection
