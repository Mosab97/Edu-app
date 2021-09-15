@if(isset($products)&& count($products) > 0)
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
                            <h3>{{optional(optional($item)->price)->piece_cost}} {{optional(getCurrentCountry()->currency)->name}}</h3>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endforeach
    <div class="col-12 text-center">
        @php
            $price = isset($price)?$price:null;
            if(isset($categories)){
                $categories = implode(',',$categories);
            }else{
                $categories = null;
            }
      //  dd($categories,is_array($categories),$price);
        @endphp
        <button type="button" class="btn load"
                onclick="loadMore('{{$items}}','{{$categories}}','{{$price}}')">{{w('LOAD MORE')}}</button>
    </div>
@endif
