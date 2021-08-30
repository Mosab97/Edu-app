@extends('layouts.container')
@section('style')
@endsection

@php
    $breadcrumb = true;
    $title = w('Help in choosing the accounting program');
@endphp
@section('content')
    <section aria-label="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mb-sm-30">
                    <form name="contactForm" id="form_information" class="form-border" method="post"
                          action="{{route('payment.paypal-services',$order->id)}}">
                        @csrf
                        <input type="hidden" name="service_id" value="{{isset($service)?$service->id:''}}">
                        <div class="row">
                            @php
                                $price = ($service->id == \App\Models\Service::f3Types['Training service'])?($order->number_of_hours_required * $service->hour_price):$service->price;
                                $commission_active = setting('commission_active');
                                $commission = $commission_active? setting('commission'):0;
                                $commission_cost =$commission_active? ($price * ($commission / 100)):0;
                                $total_price = $price + $commission_cost;



                            @endphp
                            <div class="col-6">
                                <label>{{w('Service Name')}}</label>
                                <input disabled class="form-control" value="{{isset($service)?$service->name:''}}"/>
                            </div>
                            <div class="col-6">
                                <label>{{w('Service Price')}}</label>
                                <input disabled class="form-control" value="{{$price}}"/>
                            </div>
                            <div class="col-6 mt-3">
                                <label>{{w('Commission') . '%'}}</label>
                                <input disabled class="form-control" value="{{$commission}}"/>
                            </div>
                            <div class="col-6 mt-3">
                                <label>{{w('Commission Cost') . '%'}}</label>
                                <input disabled class="form-control" value="{{$commission_cost}}"/>
                            </div>
                            <div class="col-6 mt-3">
                                <label>{{w('Total Price')}}</label>
                                <input disabled class="form-control" value="{{$total_price}}"/>
                            </div>

                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-success">{{w('Next')}}</button>
                                <a href="{{route('payment.cancelBeforePayment',$order->id)}}"
                                   class="btn btn-info">{{w('Cancel')}}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
@endsection
