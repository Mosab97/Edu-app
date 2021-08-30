@extends('manager.layout.container')
@section('style')
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route(\App\Models\CustomerReviews::manager_route . 'index') }}">{{t('Customer Reviews')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($customerReview) ? t('Edit CustomerReview') : t('Add CustomerReview') }}
        </li>
    @endpush

    @php
        $customer_name = isset($customerReview) ? $customerReview->getTranslations()['customer_name'] : null;
        $details = isset($customerReview) ? $customerReview->getTranslations()['details'] : null;
        $title_customerReview = isset($customerReview) ? $customerReview->getTranslations()['title'] : null;
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($customerReview) ? t('Edit Customer Review') : t('Add Customer Review') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route(\App\Models\CustomerReviews::manager_route . 'store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($customerReview))
                        <input type="hidden" name="customer_review_id" value="{{$customerReview->id}}">
                    @endif


                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                                <div class="kt-section kt-section--first">
                                    <div class="kt-section__body">
                                        <div class="row">
                                            @foreach(config('translatable.locales') as $local)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ t('Title') }} <small>({{ $local }}
                                                                )</small></label>
                                                        <input name="title[{{$local}}]" type="text"
                                                               class="form-control" placeholder=""
                                                               value="{{  optional($title_customerReview)[$local]}}"
                                                        >
                                                    </div>
                                                </div>
                                            @endforeach
                                            @foreach(config('translatable.locales') as $local)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ t('Customer Name') }} <small>({{ $local }}
                                                                )</small></label>
                                                        <input name="customer_name[{{$local}}]" type="text"
                                                               class="form-control" placeholder=""
                                                               value="{{  optional($customer_name)[$local]}}"
                                                        >
                                                    </div>
                                                </div>
                                            @endforeach
                                            @foreach(config('translatable.locales') as $local)
                                                <div class="col-6">
                                                    <label>{{ t('Details') }} <small>({{ $local }})</small></label>
                                                    <textarea name="details[{{$local}}]" id="details" cols="30"
                                                              rows="10"
                                                              class="form-control">{{optional($details)[$local]}}</textarea>
                                                </div>
                                            @endforeach
                                            <div class="col-6">
                                                <label>{{ t('Rate') }} </label>
                                                <input type="number" name="rate" id="rate" class="form-control"
                                                       value="{{isset($customerReview)? $customerReview->rate :old('rate')}}">
                                            </div>

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
                                            class="btn btn-danger">{{ isset($customerReview) ? t('Update'):t('Create') }}</button>&nbsp;
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
    <script src="{{ asset('assets/vendors/general/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"
            type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#form_information') !!}
@endsection
