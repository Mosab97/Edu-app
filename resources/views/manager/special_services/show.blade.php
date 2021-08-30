@extends('manager.layout.container')
@section('style')
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route(\App\Models\SpecialService::manager_route . 'index') }}">{{w('Special Service')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ t('Show Special Service') }}
        </li>
    @endpush
    @php
        //$name = isset($package) ? $package->getTranslations()['name'] : null;
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ t('Show Special Service') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right">
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                                <div class="kt-section kt-section--first">
                                    <div class="kt-section__body">

                                        <div class="row">
                                            <div class="col-6">
                                                <label>{{w('Id')}}</label>
                                                <input disabled class="form-control"
                                                       value="{{isset($item)?$item->id:'' }}">
                                            </div>
                                            <div class="col-6">
                                                <label>{{w('Project Title')}}</label>
                                                <input disabled class="form-control"
                                                       value="{{isset($item)?$item->project_title:'' }}">
                                            </div>
                                            <div class="col-6">
                                                <label>{{w('User Id')}}</label>
                                                <input disabled class="form-control"
                                                       value="{{isset($item)?$item->user_id:'' }}">
                                            </div>
                                            <div class="col-6">
                                                <label>{{w('User Name')}}</label>
                                                <input disabled class="form-control"
                                                       value="{{isset($item)?optional($item->user)->name:'' }}">
                                            </div>
                                            <div class="col-6">
                                                <label>{{w('User Phone')}}</label>
                                                <input disabled class="form-control"
                                                       value="{{isset($item)?optional($item->user)->phone:'' }}">
                                            </div>
                                            <div class="col-6">
                                                <label>{{w('Service Type')}}</label>
                                                <input disabled class="form-control"
                                                       value="{{isset($item)?$item->service_type:'' }}">
                                            </div>
                                            <div class="col-6">
                                                <label>{{w('Expected Budget')}}</label>
                                                <input disabled class="form-control"
                                                       value="{{isset($item)?$item->expected_budget:'' }}">
                                            </div>
                                            <div class="col-6">
                                                <label>{{w('Expected Delivery Time')}}</label>
                                                <input disabled class="form-control"
                                                       value="{{isset($item)?$item->expected_delivery_time:'' }}">
                                            </div>
                                            <div class="col-6 mt-4">
{{--                                                <label>{{w('Other Help Attachments')}}</label>--}}
{{--                                                <br>--}}
                                                <a target="_blank" href="{{$item->other_help_attachments}}">{{w('Other Help Attachments')}}</a>
                                            </div>
                                            <div class="col-6">
                                                <label>{{w('Created At')}}</label>
                                                <input disabled class="form-control"
                                                       value="{{isset($item)?$item->created_at:'' }}">
                                            </div>
                                            <div class="col-12">
                                                <label>{{w('Project Details')}}</label>
                                                <textarea disabled cols="30" rows="10"
                                                          class="form-control">{{isset($item)?$item->project_details:'' }}</textarea>
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
                                    <a href="{{route(\App\Models\SpecialService::manager_route . 'index')}}"
                                       class="btn btn-danger">{{w('Back')}}</a>
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
@endsection
