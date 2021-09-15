@extends('manager.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}"
          rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.order.index') }}">{{t('Orders')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($order) ? t('Edit Order') : t('Add Order') }}
        </li>
    @endpush

    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($order) ? t('Edit Order') : t('Add Order') }}</h3>
                    </div>
                </div>

                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.order.store') }}"
                      method="post">
                    {{ csrf_field() }}
                    @if(isset($order))
                        <input type="hidden" name="order_id" value="{{$order->id}}">
                    @endif


                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="row" id="one-day-offer">
                                    <div class="col-6">
                                        <label>{{ t('Distributor') }}</label>
                                        <select name="distributor_id" id="distributor" class="form-control">
                                            <option value="">{{t('Select Distributor')}}</option>
                                            @foreach($distributors as $index=>$distributor)
                                                <option
                                                    value="{{$distributor->id}}" {{isset($order)&& $distributor->id == $order->distributor_id?'selected':''}}>{{$distributor->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label>{{ t('Status') }}</label>
                                        <select name="status" id="status" class="form-control">
{{--                                            <option value="">{{t('Select Status')}}</option>--}}
                                            <option value="{{\App\Models\Order::PENDING}}"{{isset($order) && $order->status == \App\Models\Order::PENDING?'selected' : ''}}>{{t('Pending')}}</option>
                                            <option value="{{\App\Models\Order::TIMED_OUT}}"{{isset($order) && $order->status == \App\Models\Order::TIMED_OUT?'selected' : ''}}>{{t('Timed Out')}}</option>
                                            <option value="{{\App\Models\Order::ON_WAY}}"{{isset($order) && $order->status == \App\Models\Order::ON_WAY?'selected' : ''}}>{{t('On Way')}}</option>
                                            <option value="{{\App\Models\Order::COMPLETED}}"{{isset($order) && $order->status == \App\Models\Order::COMPLETED?'selected' : ''}}>{{t('Completed')}}</option>
                                            <option value="{{\App\Models\Order::CANCELED}}"{{isset($order) && $order->status == \App\Models\Order::CANCELED?'selected' : ''}}>{{t('Canceled')}}</option>
{{--                                            <option value="{{\App\Models\Order::PENDING_ON_WAY}}"{{isset($order) && $order->status == \App\Models\Order::PENDING_ON_WAY?'selected' : ''}}>{{t('Pending ')}}</option>--}}
                                        </select>
                                    </div>
{{--                                    <div class="col-6">--}}
{{--                                        <label class="col-3 col-form-label font-weight-bold">{{t('Canceled')}}</label>--}}
{{--                                        <div class="col-3">--}}
{{--                                                                            <span class="kt-switch">--}}
{{--                                                                                <label>--}}
{{--                                                                                <input type="checkbox" value="1"--}}
{{--                                                                                       {{ isset($order) && $order->status == \App\Models\Order::CANCELED ? 'checked' :'' }} name="canceled">--}}
{{--                                                                                <span></span>--}}
{{--                                                                                </label>--}}
{{--                                                                            </span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"
                                            class="btn btn-danger">{{ isset($order) ? t('Update'):t('Create') }}</button>&nbsp;
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
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"
            type="text/javascript"></script>

@endsection
