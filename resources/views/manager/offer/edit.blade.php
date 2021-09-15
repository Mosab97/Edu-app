@extends('manager.layout.container')

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.offer.index') }}">{{t('Offers')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($offer) ? t('Edit Offer') : t('Add Offer') }}
        </li>
    @endpush



    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($offer) ? t('Edit Offer') : t('Add Offer') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.offer.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($offer))
                        <input type="hidden" name="offer_id" value="{{$offer->id}}">
                    @endif
                    @php
                        $days = [
'1' => 'Saturday',
'2' => 'Sunday',
'3' => 'Monday',
'4' => 'Tuesday',
'5' => 'Wednesday',
'6' => 'Thursday',
'7' => 'Friday',
];
                    @endphp
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">

                                <div class=" row">
                                    <div class="col-6">
                                        <label>{{ t('Product') }}</label>
                                        <select name="product" id="product" class="form-control"
                                                @if(isset($offer)) disabled @endif>
                                            <option value="">{{t('Select product')}}</option>
                                            @foreach($products as $index=>$item)
                                                <option
                                                    value="{{$item->id}}" {{isset($offer) && optional(optional($offer)->price)->product_id == $item->id? 'selected':''}} >{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label>{{ t('Type') }}</label>
                                        <select name="offer_type" id="offer_type" class="form-control">
                                            <option value="">{{t('Select Offer Type')}}</option>
                                            <option
                                                value="{{\App\Models\Offer::type['ONE_DAY']}}" {{isset($offer) && $offer->type == \App\Models\Offer::type['ONE_DAY']? 'selected':''}}>{{t('One Day Offer')}}</option>
                                            <option
                                                value="{{\App\Models\Offer::type['LIMITED']}}" {{isset($offer) && $offer->type == \App\Models\Offer::type['LIMITED']? 'selected':''}}>{{t('Limited Offer')}}</option>
                                            <option
                                                value="{{\App\Models\Offer::type['NORMAL']}}" {{isset($offer) && $offer->type == \App\Models\Offer::type['NORMAL']? 'selected':''}}>{{t('Normal Offer')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class=" row mt-3">
                                    <div class="col-4">
                                        <label>{{ t('Customer Percent') }}</label>
                                        <input type="number" name="customer_percent" id="customer_percent"
                                               value="{{isset($offer)? $offer->customer_percent : old('customer_percent')}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-4">
                                        <label>{{ t('Wholesaler Percent') }}</label>
                                        <input type="number" name="wholesaler_percent"
                                               value="{{isset($offer)? $offer->wholesaler_percent : old('wholesaler_percent')}}"
                                               id="wholesaler_percent"
                                               class="form-control">
                                    </div>
                                    <div class="col-4">
                                        <label>{{ t('Retailer Percent') }}</label>
                                        <input type="number" name="retailer_percent"
                                               value="{{isset($offer)? $offer->retailer_percent : old('retailer_percent')}}"
                                               id="retailer_percent"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="row pt-3" id="one-day-offer"
                                     style="display: {{isset($offer) && $offer->type == \App\Models\Offer::type['ONE_DAY']?'':'none'}}">
                                    <div class="col-6">
                                        <label>{{ t('Select Day') }}</label>
                                        <select name="day" id="day" class="form-control">
                                            <option value="">{{t('Select Day')}}</option>
                                            @foreach($days as $index=>$day)
                                                <option
                                                    value="{{$index}}" {{isset($offer)&& $offer->day == $index?'selected':''}}>{{t($day)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row pt-3" id="limited-offer"
                                     style="display: {{isset($offer) && $offer->type == \App\Models\Offer::type['LIMITED']?'':'none'}}">
                                    <div class="col-6">
                                        <label>{{ t('From') }}</label>
                                        <input type="date" name="from" id="from" class="form-control"
                                               value="{{isset($offer)? $offer->from :''}}">
                                    </div>
                                    <div class="col-6">
                                        <label>{{ t('To') }}</label>
                                        <input type="date" name="to" id="to" class="form-control"
                                               value="{{isset($offer)? $offer->to :''}}">

                                    </div>
                                </div>

                                <div class="row mt-4 ">
                                    <div class="col-4">
                                        <label class=" col-form-label font-weight-bold">{{t('Active')}}</label>
                                        <div>
                                                                                                                        <span
                                                                                                                            class="kt-switch">
                                                                                <label><input type="checkbox" value="1"
                                                                                              {{ isset($offer) && $offer->active == 1 ? 'checked' :'' }} name="active"><span></span></label>
                                                                            </span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"
                                            class="btn btn-danger">{{ isset($offer) ? t('Update'):t('Create') }}</button>&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#form_information') !!}
    <script>
        $('#offer_type').change(function () {
            let selected = $(this).val();
            console.log(selected);
            if (selected == {{\App\Models\Offer::type['ONE_DAY']}}) {
                $("#limited-offer").hide();
                $("#one-day-offer").show();
            } else if (selected == {{\App\Models\Offer::type['LIMITED']}}) {
                $("#one-day-offer").hide();
                $("#limited-offer").show();
            } else {
                $("#limited-offer").hide();
                $("#one-day-offer").hide();
            }
        });


        $(function () {
            var dtToday = new Date();

            var month = dtToday.getMonth() + 1;
            var month_to = dtToday.getMonth() + 1;
            var day = dtToday.getDate();
            var day_to = dtToday.getDate() + 1;
            var year = dtToday.getFullYear();
            if (month < 10) month = '0' + month.toString();
            if (day < 10) day = '0' + day.toString();

            if (month_to < 10) month_to = '0' + month_to.toString();
            if (day_to < 10) day_to = '0' + day_to.toString();

            var maxDate = year + '-' + month + '-' + day;
            var maxDate_to = year + '-' + month_to + '-' + day_to;

            // or instead:
            // var maxDate = dtToday.toISOString().substr(0, 10);

            // alert(maxDate);
            $('#from').attr('min', maxDate);
            $('#to').attr('min', maxDate_to);
        });

    </script>
@endsection
