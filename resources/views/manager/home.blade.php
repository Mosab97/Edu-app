@extends('manager.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        #chartdiv1, #chartdiv2, #chartdiv3, #chartdiv0, #chartdiv, #chartdiv_order {
            width: 100%;
            height: 400px;
        }
    </style>
@endsection
@section('content')
    @can('Dashboard')
        <div class="row">
            <div class="kt-portlet">
                <div class="kt-portlet__body  kt-portlet__body--fit">
                    <div class="row row-no-padding row-col-separator-lg">
                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <!--begin::Total Profit-->
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">
                                            {{ t('Total Earning') }}
                                        </h4>
                                        <span class="kt-widget24__desc">
					            {{ t('Total Earning Amount') }}
					        </span>
                                    </div>

                                    <span class="kt-widget24__stats kt-font-dark">
					        {{$total_earning}}
					    </span>
                                </div>

                            </div>
                            <!--end::Total Profit-->
                        </div>

                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <!--begin::New Feedbacks-->
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">
                                            {{ t('Today Earning') }}
                                        </h4>
                                        <span class="kt-widget24__desc">
					            {{ t('Today Earning Amount') }}
					        </span>
                                    </div>

                                    <span class="kt-widget24__stats kt-font-danger">
					       {{ $today_earning }}
					    </span>
                                </div>
                            </div>
                            <!--end::New Feedbacks-->
                        </div>

                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <!--begin::New Orders-->
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">
                                            {{ t('Total Active Users') }}
                                        </h4>
                                        <span class="kt-widget24__desc">
					            {{ t('Total Active Users') }}
					        </span>
                                    </div>

                                    <span class="kt-widget24__stats kt-font-dark">
					        {{ $total_active_users}}
					    </span>
                                </div>
                            </div>
                            <!--end::New Orders-->
                        </div>

                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <!--begin::New Orders-->
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">
                                            {{ t('Total Orders') }}
                                        </h4>
                                        <span class="kt-widget24__desc">
					            {{ t('Total Orders') }}
					        </span>
                                    </div>

                                    <span class="kt-widget24__stats kt-font-danger">
					        {{ $total_orders}}
					    </span>
                                </div>
                            </div>
                            <!--end::New Orders-->
                        </div>


                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <!--begin::New Orders-->
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">
                                            {{ t('Total Products') }}
                                        </h4>
                                        <span class="kt-widget24__desc">
					            {{ t('Total Products') }}
					        </span>
                                    </div>

                                    <span class="kt-widget24__stats kt-font-danger">
					        {{ $total_products}}
					    </span>
                                </div>
                            </div>
                            <!--end::New Orders-->
                        </div>


                        <div class="col-md-12 col-lg-6 col-xl-3">
                            <!--begin::New Orders-->
                            <div class="kt-widget24">
                                <div class="kt-widget24__details">
                                    <div class="kt-widget24__info">
                                        <h4 class="kt-widget24__title">
                                            {{ t('Total Opened tickets') }}
                                        </h4>
                                        <span class="kt-widget24__desc">
					            {{ t('Total Opened tickets') }}
					        </span>
                                    </div>

                                    <span class="kt-widget24__stats kt-font-danger">
					        {{ $total_opened_tickets}}
					    </span>
                                </div>
                            </div>
                            <!--end::New Orders-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                {{ t('Total orders during the current year') }}
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                        </div>
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fluid">
                        <div id="chartdiv_order">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                {{ t('Total user during the current year') }}
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                        </div>
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fluid">
                        <div id="chartdiv">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                {{ t('Orders') }}
                            </h3>
                        </div>

                    </div>
                    <div class="kt-portlet__body">
                        <table class="table text-center" id="users-table">
                            <thead>
                            <th>{{ t('UUID') }}</th>
                            <th>{{ t('Customer / Merchant') }}</th>
                            <th>{{ t('Mobile no') }}</th>
                            <th>{{ t('Distributor') }}</th>
                            <th>{{ t('Status') }}</th>
                            <th>{{ t('Total') }}</th>
                            <th>{{ t('Ordered At') }}</th>
                            <th>{{ t('Actions') }}</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="deleteModel"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{w('Confirm Delete')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form method="post" action="" id="delete_form">
                        <input type="hidden" name="_method" value="delete">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <p>{{t('Are You Sure To Delete The Selected Row')}}</p>
                            <p>{{t('Deleting The Selected Row Results In Deleting All Records Related To It')}}.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{w('Cancel')}}</button>
                            <button type="submit" class="btn btn-warning">{{w('Delete')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

@endsection
@section('script')
    @if(app()->getLocale() == 'ar')
        <script src="{{asset('core_ar.js')}}"></script>
    @else
        <script src="{{asset('core.js')}}"></script>
    @endif
    <script src="https://www.amcharts.com/lib/4/charts.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/material.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
    <script src="https://www.amcharts.com/lib/4/lang/ar.js"></script>


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


    <script>
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
                    url: '{{ route('manager.order.index') }}',
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
                    {data: 'mobile', name: 'mobile'},
                    {data: 'distributor', name: 'distributor'},
                    {data: 'status_name', name: 'status_name'},
                    {data: 'total', name: 'total'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'actions', name: 'actions'}
                ],
                createdRow: function (row, data, index) {
                    $('td', row).eq(5).addClass('ltr');
                    $('td', row).eq(6).addClass('ltr');
                },

            });
        });

        $(document).on('click', '.deleteRecord', (function () {
            var id = $(this).data("id");
            var url = '{{ route("manager.order.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#delete_form').attr('action', url);
        }));

    </script>



    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

    <script>
        am4core.ready(function () {

// Themes begin
            am4core.useTheme(am4themes_animated);
// Themes end

            var chart = am4core.create("chartdiv", am4charts.XYChart);


            @php
                $userCountByMonth = \App\Models\User::select(\Illuminate\Support\Facades\DB::raw('created_at, COUNT(*) as count'))
                            ->whereYear('created_at', date('Y'))
                            ->orderBy('created_at' , 'asc')
                            ->groupBy(\Illuminate\Support\Facades\DB::raw('Date(created_at)'))
                            ->get();
            @endphp
                chart.data = [
                    @foreach($userCountByMonth as $user)
                {
                    "date": "{{ \Carbon\Carbon::make($user->created_at)->format('Y-m-d') }}",
                    "value": {{ $user->count }}
                },
                @endforeach
            ];


// Create axes
            var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
            dateAxis.renderer.minGridDistance = 60;

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// Create series
            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = "value";
            series.dataFields.dateX = "date";
            series.tooltipText = "{value}"

            series.tooltip.pointerOrientation = "vertical";

            chart.cursor = new am4charts.XYCursor();
            chart.cursor.snapToSeries = series;
            chart.cursor.xAxis = dateAxis;

//chart.scrollbarY = new am4core.Scrollbar();
            chart.scrollbarX = new am4core.Scrollbar();

        }); // end am4core.ready()
    </script>

    <script>
        am4core.ready(function () {

// Themes begin
            am4core.useTheme(am4themes_animated);
// Themes end

            var chart = am4core.create("chartdiv_order", am4charts.XYChart);


            @php
                $orderCountByMonth = \App\Models\Order::select(\Illuminate\Support\Facades\DB::raw('created_at, COUNT(*) as count'))
                            ->whereYear('created_at', date('Y'))
                             ->orderBy('created_at' , 'asc')
                            ->groupBy(\Illuminate\Support\Facades\DB::raw('Date(created_at)'))
                            ->get();
            @endphp
                chart.data = [
                    @foreach($orderCountByMonth as $order)
                {
                    "date": "{{ \Carbon\Carbon::make($order->created_at)->format('Y-m-d') }}",
                    "value": {{ $order->count }}
                },
                @endforeach
            ];


// Create axes
            var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
            dateAxis.renderer.minGridDistance = 60;

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

// Create series
            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = "value";
            series.dataFields.dateX = "date";
            series.tooltipText = "{value}"

            series.tooltip.pointerOrientation = "vertical";

            chart.cursor = new am4charts.XYCursor();
            chart.cursor.snapToSeries = series;
            chart.cursor.xAxis = dateAxis;

//chart.scrollbarY = new am4core.Scrollbar();
            chart.scrollbarX = new am4core.Scrollbar();

        }); // end am4core.ready()
    </script>

@endsection
