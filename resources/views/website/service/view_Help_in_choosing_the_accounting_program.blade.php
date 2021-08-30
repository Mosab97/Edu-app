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
                                <label for="project_activity">{{w('Project activity')}}</label>
                                <input type="text" name="project_activity" id="project_activity" class="form-control"
                                />
                            </div>
                            <div class="col-6">
                                <label for="company_offers">{{w('The company offers')}}</label>
                                <select name="company_offers" id="company_offers"
                                        class="form-control">
                                    <option value="">{{w('Select Choice')}}</option>
                                    @foreach(\App\Models\Service::company_offers as $index => $item)
                                        <option value="{{$item}}">{{w($index)}}</option>
                                    @endforeach
                                </select>
                            </div>
{{--                            <div class="col-6">--}}
{{--                                <label for="profile">{{w('Profile')}}</label>--}}
{{--                                <input type="url" name="profile" id="profile" class="form-control"--}}
{{--                                />--}}
{{--                            </div>--}}
                            <div class="col-6">
                                <label for="catalog">{{w('Catalog')}}</label>
                                <input type="file" name="catalog" id="catalog" class="form-control"
                                />
                            </div>
                            <div class="col-6">
                                <label for="employee_number">{{w('Employee Number')}}</label>
                                <select name="employee_number" id="employee_number"
                                        class="form-control">
                                    {{--                                    <option value="">{{w('Select Employee Number')}}</option>--}}
                                    @foreach(employee_number as $index => $item)
                                        <option value="{{$item}}">{{w($index)}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-6">
                                <label
                                    for="size_of_the_estimated_revenue_of_the_project">{{w('The size of the estimated revenue of the project')}}</label>
                                <input type="number" name="size_of_the_estimated_revenue_of_the_project"
                                       id="size_of_the_estimated_revenue_of_the_project" class="form-control"
                                />
                            </div>
                            <div class="col-6">
                                <label
                                    for="size_of_the_estimated_expenses_of_the_project">{{w('The size of the estimated expenses of the project')}}</label>
                                <input type="number" name="size_of_the_estimated_expenses_of_the_project"
                                       id="size_of_the_estimated_expenses_of_the_project" class="form-control"
                                />
                            </div>
                            <div class="col-6">
                                <label
                                    for="monthly_budget_allocated_to_the_accounting_program">{{w('The monthly budget allocated to the accounting program')}}</label>
                                <input type="number" name="monthly_budget_allocated_to_the_accounting_program"
                                       id="monthly_budget_allocated_to_the_accounting_program" class="form-control"
                                />
                            </div>
                            <div class="col-6">
                                <label for="lang">{{w('Project Language')}}</label>
                                <select name="lang" id="lang"
                                        class="form-control">
                                    <option value="">{{w('Select Language')}}</option>
                                    @foreach(PROJECT_LANG as $index => $item)
                                        <option value="{{$item}}">{{w($index)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label
                                    for="other_helpful_attachments">{{w('Other helpful attachments')}}</label>
                                <input type="file" name="other_helpful_attachments"
                                       id="other_helpful_attachments" class="form-control"
                                />
                            </div>
                            <div class="col-6">
                                <label for="details">{{w('Details')}}</label>
                                <textarea name="details" id="details" class="form-control"
                                ></textarea>
                            </div>
                            <div class="col-6">
                                <label
                                    for="describe_your_need_for_the_program">{{w('Describe your need for the program')}}</label>
                                <textarea name="describe_your_need_for_the_program"
                                          id="describe_your_need_for_the_program" class="form-control"
                                ></textarea>
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


