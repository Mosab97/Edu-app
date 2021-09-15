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
                        <h3>{{optional($price)->piece_cost}} {{optional(getCurrentCountry()->currency)->name}}</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endforeach
<div class="col-12 text-center">
    <button class="btn load" onclick="loadMore({{$items}})">{{w('LOAD MORE')}}</button>
</div>
