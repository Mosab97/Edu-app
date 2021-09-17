{{--{{dd(\App\Models\Setting::getCurrency(setting('currency'))[lang()])}}--}}
@extends('layouts.container')
@section('style')
@endsection

@php
    $breadcrumb = true;
    $title = w('Help in choosing the accounting program');
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
                                <label for="work_activity">{{w('Work activity')}}</label>
                                <input type="text" name="work_activity" id="work_activity" class="form-control"/>
                            </div>
                            <div class="col-6">
                                <label for="current_accounting_program">{{w('Current accounting program')}}</label>
                                <input type="text" name="current_accounting_program" id="current_accounting_program"
                                       class="form-control"/>
                            </div>
                            <div class="col-6">
                                <label for="other_help_attachments">{{w('Other help attachments')}}</label>
                                <input type="file" name="other_help_attachments[]" id="other_help_attachments"
                                       class="form-control"/>
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
                                <label for="service_description">{{w('Service description')}}</label>
                                <textarea name="service_description" id="service_description" cols="30" rows="10"
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


