{{--@extends('layouts.container')--}}
{{--@section('title','Member'  )--}}
{{--@section('content')--}}
{{--    <section class="contact-area pt-5">--}}
{{--        <div class="container">--}}
{{--            <div class="row justify-content-center">--}}
{{--                <div class="col-12 col-sm-9 col-md-6 col-lg-8">--}}
{{--                    <!-- Contact Form Area -->--}}
{{--                    <div class="contact-form-area mb-70">--}}
{{--                        <h4 class="mb-50">Payment with PayPal</h4>--}}

{{--                        @if(session()->has('success_message'))--}}
{{--                            <div class="alert alert-success">--}}
{{--                                <strong>{{ session('success_message') }}</strong>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                        @if(session()->has('error_message'))--}}
{{--                            <div class="alert alert-danger">--}}
{{--                                <strong>{{ session('error') }}</strong>--}}
{{--                            </div>--}}
{{--                        @endif--}}


{{--                        <form action="{{ route('site.payment.paypal') }}" method="post">--}}
{{--                            @csrf--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <input type="text" class="form-control" name="amount" id="amount" placeholder="amount" value="180" disabled>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <input type="text" class="form-control" name="currency" id="currency" value="euro" disabled>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-12">--}}
{{--                                    <button class="btn newsbox-btn mt-30" type="submit">--}}
{{--                                        <i class="fa fa-paypal"></i>--}}
{{--                                        Pay--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--@endsection--}}
