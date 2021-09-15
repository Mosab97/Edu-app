@foreach($posts as $index=>$item)
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
    <button class="btn load" onclick="loadMore({{$items}})">{{w('LOAD MORE')}}</button>
</div>
