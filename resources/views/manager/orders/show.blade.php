@extends('manager.layout.container')
@section('style')
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route(\App\Models\Order::manager_route . 'index') }}">{{w('Order')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ t('Show Order') }}
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
                        <h3 class="kt-portlet__head-title">{{ t('Show Order') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right">
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                                <div class="kt-section kt-section--first">
                                    <div class="kt-section__body">
                                        @if(optional($order->service)->id == \App\Models\Service::f3Types['Help in choosing the accounting program'] )
                                            <div class="row">
                                                <div class="col-6">
                                                    <label for="project_activity">{{w('Project Activity')}}</label>
                                                    <input disabled class="form-control"
                                                           value="{{isset($order)?$order->project_activity:'' }}">
                                                </div>
                                                <div class="col-6">
                                                    <label>{{w('Profile')}}</label>
                                                    <a href="{{isset($order)?$order->profile:'javascript:;'}}"
                                                       target="_blank" class="btn btn-success">{{w('Profile Url')}}</a>
                                                </div>
                                                <div class="col-6">
                                                    <label>{{w('Catalog')}}</label>
                                                    <a href="{{isset($order)?$order->catalog:'javascript:;'}}"
                                                       target="_blank" class="btn btn-success">{{w('Catalog Url')}}</a>
                                                </div>
                                                <div class="col-6">
                                                    <label>{{w('Company Offers')}}</label>
                                                    <select class="form-control" disabled>
                                                        @foreach(\App\Models\Service::company_offers as $index => $item)
                                                            <option {{isset($order)&&$order->company_offers == $item?'selected':''}}>{{w($index)}}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <div class="col-6">
                                                    <label for="employee_number">{{w('Employee Number')}}</label>
                                                    <select name="employee_number" id="employee_number"
                                                            class="form-control">
                                                        @foreach(employee_number as $index => $item)
                                                            <option {{isset($order)&&$order->employee_number == $item?'selected':''}}>{{w($index)}}</option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                                <div class="col-6">
                                                    <label
                                                        for="training_requirements">{{w('Training Requirements')}}</label>
                                                    <textarea disabled cols="30" rows="10"
                                                              class="form-control">{{isset($order)?$order->training_requirements:'' }}</textarea>
                                                </div>


                                                <div class="col-6">
                                                    <label
                                                        for="size_of_the_estimated_revenue_of_the_project">{{w('The size of the estimated revenue of the project')}}</label>
                                                    <input type="number"
                                                           name="size_of_the_estimated_revenue_of_the_project"
                                                           id="size_of_the_estimated_revenue_of_the_project"
                                                           class="form-control"
                                                           disabled
                                                           value="{{isset($order)?$order->size_of_the_estimated_revenue_of_the_project:''}}"
                                                    />
                                                </div>
                                                <div class="col-6">
                                                    <label
                                                        for="size_of_the_estimated_expenses_of_the_project">{{w('The size of the estimated expenses of the project')}}</label>
                                                    <input type="number"
                                                           name="size_of_the_estimated_expenses_of_the_project"
                                                           id="size_of_the_estimated_expenses_of_the_project"
                                                           class="form-control"
                                                           disabled
                                                           value="{{isset($order)?$order->size_of_the_estimated_expenses_of_the_project:''}}"
                                                    />
                                                </div>
                                                <div class="col-6">
                                                    <label
                                                        for="monthly_budget_allocated_to_the_accounting_program">{{w('The monthly budget allocated to the accounting program')}}</label>
                                                    <input type="number"
                                                           name="monthly_budget_allocated_to_the_accounting_program"
                                                           id="monthly_budget_allocated_to_the_accounting_program"
                                                           class="form-control"
                                                           disabled
                                                           value="{{isset($order)?$order->monthly_budget_allocated_to_the_accounting_program:''}}"
                                                    />
                                                </div>
                                                <div class="col-6">
                                                    <label for="lang">{{w('Project Language')}}</label>
                                                    <select name="lang" id="lang" disabled
                                                            class="form-control">
                                                        <option value="">{{w('Select Language')}}</option>
                                                        @foreach(PROJECT_LANG as $index => $item)
                                                            <option {{isset($order)&&$order->lang == $item?'selected':''}}>{{w($index)}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label
                                                        for="other_helpful_attachments">{{w('Other helpful attachments')}}</label>
                                                    <input type="file" name="other_helpful_attachments"
                                                           id="other_helpful_attachments" class="form-control"
                                                           disabled
                                                           value="{{isset($order)?$order->other_helpful_attachments:''}}"
                                                    />
                                                </div>
                                                <div class="col-6">
                                                    <label for="details">{{w('Details')}}</label>
                                                    <textarea name="details" id="details" class="form-control"
                                                              disabled> {{isset($order)?$order->Details:''}}</textarea>
                                                </div>
                                                <div class="col-6">
                                                    <label
                                                        for="describe_your_need_for_the_program">{{w('Describe your need for the program')}}</label>
                                                    <textarea name="describe_your_need_for_the_program"
                                                              id="describe_your_need_for_the_program"
                                                              class="form-control"
                                                              disabled> {{isset($order)?$order->describe_your_need_for_the_program:''}}</textarea>
                                                </div>


                                            </div>

                                        @elseif(optional($order->service)->id == \App\Models\Service::f3Types['Training service'] )
                                            <div class="row">
                                                <div class="col-6">
                                                    <label for="training_title">{{w('Training Title')}}</label>
                                                    <input disabled class="form-control"
                                                           value="{{isset($order)?$order->training_title:'' }}">
                                                </div>
                                                <div class="col-6">
                                                    <label
                                                        for="mechanism_of_action">{{w('Mechanism Of Action')}}</label>
                                                    <input disabled class="form-control"
                                                           value="{{isset($order)?$order->mechanism_of_action:'' }}">
                                                </div>
                                                <div class="col-6">
                                                    <label for="number_people">{{w('Number People')}}</label>
                                                    <input disabled class="form-control"
                                                           value="{{isset($order)?$order->number_people:'' }}">
                                                </div>
                                                <div class="col-6">
                                                    <label
                                                        for="number_of_hours_required">{{w('Number Of Hours Required')}}</label>
                                                    <input disabled class="form-control"
                                                           value="{{isset($order)?$order->number_of_hours_required:'' }}">
                                                </div>
                                                <div class="col-6">
                                                    <label
                                                        for="training_requirements">{{w('Training Requirements')}}</label>
                                                    <textarea disabled cols="30" rows="10"
                                                              class="form-control">{{isset($order)?$order->training_requirements:'' }}</textarea>
                                                </div>
                                            </div>


                                        @elseif(optional($order->service)->id == \App\Models\Service::f3Types['Establishing the chart of accounts'] )
                                            <div class="row">
                                                <div class="col-6">
                                                    <label for="work_activity">{{w('Work activity')}}</label>
                                                    <input type="text" name="work_activity" id="work_activity" class="form-control"
                                                         disabled  value="{{isset($order)?$order->work_activity:'' }}"
                                                    >
                                                </div>
                                                <div class="col-6">
                                                    <label for="current_accounting_program">{{w('Current accounting program')}}</label>
                                                    <input type="text" name="current_accounting_program" id="current_accounting_program"
                                                           class="form-control"
                                                           disabled  value="{{isset($order)?$order->current_accounting_program:'' }}"
                                                    />
                                                </div>
                                                <div class="col-6">
                                                    <label for="other_help_attachments">{{w('Other help attachments')}}</label>
                                                    <input type="file" name="other_help_attachments[]" id="other_help_attachments"
                                                           class="form-control"
                                                           disabled  value="{{isset($order)?$order->other_help_attachments:'' }}"
                                                    />
                                                </div>
                                                <div class="col-6">
                                                    <label for="mechanism_of_action">{{w('Mechanism of Action')}}</label>
                                                    <select name="mechanism_of_action" id="mechanism_of_action"
                                                            class="form-control">
                                                        @foreach(mechanism_of_action as $index => $item)
                                                            <option {{isset($order)&&$order->mechanism_of_action == $item?'selected':''}}>{{w($index)}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label for="service_description">{{w('Service description')}}</label>
                                                    <textarea name="service_description" id="service_description" cols="30" rows="10"
                                                              class="form-control">{{isset($order)?$order->service_description:'' }}</textarea>
                                                </div>
                                            </div>

                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <a href="{{route(\App\Models\Order::manager_route . 'index')}}"
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
