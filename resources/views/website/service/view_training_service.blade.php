@extends('layouts.container')
@section('style')
@endsection

@php
    $breadcrumb = true;
    $title = w('Training Service');
@endphp
@section('content')
    @php
        $price = isset($service)?$service->price:0;

                                $commission_active = setting('commission_active');
$commission = $commission_active?setting('commission'):0;
        $commission_cost =$commission_active?( $price * ($commission / 100)):0;
        $total_price = $price + $commission_cost;
    @endphp

    <section aria-label="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mb-sm-30">
                    <form name="contactForm" id="form_information" class="form-border" method="post"
                          {{--                          action="{{route('post_Help_in_choosing_the_accounting_program')}}"--}}
                          action="{{route('payment.beforePayment')}}"
{{--                          action="{{route('payment.paypal-services')}}"--}}
                    >
                        @csrf
                        <input type="hidden" name="service_id" value="{{isset($service)?$service->id:''}}">
                        <input type="hidden" name="commission" value="{{$commission}}">
                        <input type="hidden" name="commission_cost" value="{{$commission_cost}}">
                        <input type="hidden" name="total_price" value="{{$total_price}}">
                        <div class="row">
                            <div class="col-6">
                                <label for="training_title">{{w('Training title')}}</label>
                                <input type="text" name="training_title" id="training_title" class="form-control"/>
                            </div>
                            <div class="col-6">
                                <label for="mechanism_of_action">{{w('Mechanism of Action')}}</label>
                                <select name="mechanism_of_action" id="mechanism_of_action"
                                        class="form-control">
                                    @foreach(mechanism_of_action as $index => $item)
                                        <option value="{{$item}}">{{w($index)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="number_people">{{w('Number people')}}</label>
                                <input type="number" name="number_people" id="number_people" class="form-control"/>
                            </div>
                            <div class="col-6">
                                <label for="number_of_hours_required">{{w('Number of hours required')}}</label>
                                <input type="number" name="number_of_hours_required" id="number_of_hours_required"
                                       class="form-control"
                                    {{--                                       onchange="calc(this)"--}}
                                />
                            </div>
                            <div class="col-6">
                                <label for="training_requirements">{{w('Training requirements')}}</label>
                                <textarea name="training_requirements" id="training_requirements" cols="30" rows="10"
                                          class="form-control"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">{{w('Buy')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
@endsection


@section('script')
    {!! $service_validator->selector('#form_information') !!}
@endsection
