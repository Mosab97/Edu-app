@extends('manager.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css') }}"
          rel="stylesheet" type="text/css"/>

@endsection

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{t('Financial system')}}
        </li>
    @endpush
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{ t('Financial system') }}
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20">
                        <div class="row kt-margin-b-20">
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('User Name') }}:</label>
                                <input type="text" name="username" id="username" class="form-control kt-input"
                                       placeholder="{{t('User Name')}}" value="{{request()->username}}">
                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('User Mobile') }}:</label>
                                <input type="text" name="user_mobile" id="user_mobile" class="form-control kt-input"
                                       placeholder="{{ t('User Mobile') }}" value="{{request()->user_mobile}}">
                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Order UUID') }}:</label>
                                <input type="text" name="uuid" id="uuid" class="form-control kt-input"
                                       placeholder="{{t('Order UUID')}}" value="{{request()->uuid}}">
                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Status') }}:</label>
                                <select class="form-control" name="status" id="status">
                                    <option
                                        {{!request()->status? 'selected' : ''}}  value="">{{t('Select Status')}}</option>
                                    <option
                                        {{!is_null(request()->status) && request()->status == \App\Models\Order::PENDING? 'selected' : ''}}  value="{{\App\Models\Order::PENDING}}">{{t('Pending')}}</option>
                                    <option
                                        {{!is_null(request()->status) && request()->status == \App\Models\Order::TIMED_OUT? 'selected' : ''}}value="{{\App\Models\Order::TIMED_OUT}}">{{t('Timed Out')}}</option>
                                    <option
                                        {{!is_null(request()->status) && request()->status == \App\Models\Order::ON_WAY? 'selected' : ''}} value="{{\App\Models\Order::ON_WAY}}">{{t('On Way')}}</option>
                                    <option
                                        {{!is_null(request()->status) && request()->status == \App\Models\Order::COMPLETED? 'selected' : ''}} value="{{\App\Models\Order::COMPLETED}}">{{t('Completed')}}</option>
                                    <option
                                        {{!is_null(request()->status) && request()->status == \App\Models\Order::CANCELED? 'selected' : ''}} value="{{\App\Models\Order::CANCELED}}">{{t('Canceled')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row kt-margin-b-20">

                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Order Price Range') }}:</label>
                                <div class="input-group" id="">
                                    <input type="text" class="form-control" name="price_start" id="price_start"
                                           placeholder="{{t('From')}}" value="{{request()->price_start}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                    </div>
                                    <input type="text" name="price_end" class="form-control" id="price_end"
                                           placeholder="{{t('To')}}" value="{{request()->price_end}}">
                                </div>
                            </div>

                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Order Date From') }}:</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control date_time" name="date_start" readonly=""
                                           placeholder="{{t('Select date')}}" value="{{request()->date_start}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i
                                                class="la la-calendar-o glyphicon-th"></i></span>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Order Date To') }}:</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control date_time" name="date_end" readonly=""
                                           placeholder="{{t('Select date')}}" value="{{request()->date_end}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i
                                                class="la la-calendar-o glyphicon-th"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Action') }}:</label>
                                <br/>
                                <button type="submit" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_search">
                                    <i class="la la-search"></i>
                                    {{t('Search')}}
                                </button>
                            </div>
{{--                            <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">--}}
{{--                                <label>{{ t('Total') }}:</label>--}}
{{--                                <br/>--}}
{{--                                <h3 class="w-100 text-center">{{t('Total Orders')}} : <span--}}
{{--                                        class="text-warning">{{$total}}</span>--}}
{{--                                </h3>--}}
{{--                            </div>--}}
                        </div>
                    </form>
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th>{{ t('Id') }}</th>
                        <th>{{ t('Account Holder') }}</th>
                        <th>{{ t('Order Id') }}</th>
                        <th>{{ t('Price') }}</th>
                        <th>{{ t('Payment Type') }}</th>
                        <th>{{ t('Date') }}</th>
{{--                        <th>{{ t('Actions') }}</th>--}}
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"
            type="text/javascript"></script>

    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function () {
            $('.date_time').datetimepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'yyyy-mm-dd hh:ii'
            });
            $(function () {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    searching: false,
                    dom: 'lBfrtip',
                    buttons: [
                        'excel', 'print'
                    ],
                    ajax: {
                        url: '{{ route('manager.financial_system.index') }}',
                        data: function (d) {
                            d.uuid = "{{request()->get('uuid')}}";
                            d.username = "{{request()->get('username')}}";
                            d.user_mobile = "{{request()->get('user_mobile')}}";
                            d.restaurant = "{{request()->get('restaurant')}}";
                            d.branch = "{{request()->get('branch')}}";
                            d.status = "{{request()->get('status')}}";
                            d.date_start = "{{request()->get('date_start')}}";
                            d.date_end = "{{request()->get('date_end')}}";
                            d.price_start = "{{request()->get('price_start')}}";
                            d.price_end = "{{request()->get('price_end')}}";
                        }
                    },
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'user', name: 'user'},
                        {data: 'order_id', name: 'order_id'},
                        {data: 'total', name: 'total'},
                        {data: 'payment_type', name: 'payment_type'},
                        {data: 'created_at', name: 'created_at'},
                        // {data: 'actions', name: 'actions'}
                    ],
                    createdRow: function (row, data, index) {
                        $('td', row).eq(5).addClass('ltr');
                        $('td', row).eq(6).addClass('ltr');
                    },

                });
            });
            // $('#kt_search').click(function(e){
            //     e.preventDefault();
            //     $('#users-table').DataTable().draw(true);
            // });
        });
    </script>
@endsection
