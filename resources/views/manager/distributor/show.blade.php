@extends('manager.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}"
          rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.distributor.index') }}">{{ t('Distributors') }}</a>
        </li>
        <li class="breadcrumb-item">
            {{ t('Show Distributor') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <!--begin:: Widgets/Applications/Distributor/Profile3-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">
                    <div class="kt-widget kt-widget--user-profile-3">
                        <div class="kt-widget__top">
                            <div class="kt-widget__media kt-hidden-">
                                <img src="{{ asset($distributor->image) }}" alt="image">
                            </div>
                            <div class="kt-widget__content">
                                <div class="kt-widget__head">
                                    <label href="#" class="kt-widget__username">
                                        {{ $distributor->name }}
                                        @if(!$distributor->isBlocked)
                                            <i class="flaticon2-correct"></i>
                                        @endif
                                    </label>
                                </div>
                                @if($distributor->email)
                                    <div class="kt-widget__subhead">
                                        <a href="mailTo:{{ $distributor->email }}">{{ $distributor->email }}</a>
                                    </div>
                                @endif
                                <div class="kt-widget__info">
                                    <div class="kt-widget__desc">
                                        <label href="#"
                                               class="mr-2">{{ t('Member Since : ') .' '. $distributor->created_at->format('d-m-Y') }}</label>
                                        <br>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-widget__media kt-hidden-">
                            </div>
                            <div class="kt-widget__content">
                                <div class="kt-widget__head">
                                    <label href="#" class="kt-widget__username">
                                    </label>
                                    <div class="kt-widget__action">

                                        <button data-toggle="modal" data-target="#distributorModel" type="button"
                                                class="btn btn-brand btn-sm btn-upper"
                                                style="text-transform: capitalize "><i
                                                class="fa fa-newspaper"></i>{{ t('Assign Products') }}</button>

                                        {{--                                        <button data-toggle="modal" data-target="#notificationModel" type="button"--}}
                                        {{--                                                class="btn btn-brand btn-sm btn-upper"><i--}}
                                        {{--                                                class="fa fa-newspaper"></i>{{ t('send notification') }}</button>--}}
                                        <a class="btn btn-brand btn-sm btn-upper"
                                           href="{{route('manager.bills',$distributor->id)}}" style="color: #fff">
                                            <i class="fa fa-coins"></i>{{ t('Bills') }}</a>
                                    </div>
                                </div>
                                <div class="kt-widget__subhead">
                                    <label><span>{{t('Mobile') }}</span>: {{ $distributor->mobile }}</label>
                                    <br>
                                    @php
                                        $orderid = optional($distributor)->distributor_orders()->where('status',\App\Models\Order::ON_WAY)->first()
                                    @endphp
                                    <label><span>{{t('Order Id') }}</span>: {{isset($orderid)?$orderid:t('No Item')  }}
                                    </label>
                                    <br>
                                </div>
                            </div>
                        </div>
                        <div class="kt-widget__bottom mt-0">
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon2-shopping-cart-1"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Orders') }}</span>
                                    <span class="kt-widget__value">{{$orders}}</span>
                                </div>
                            </div>
                        </div>
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
                            {{ t('Distributor Details') }}
                        </h3>
                    </div>

                </div>
                <div class="kt-portlet__body">
                    <ul class="nav nav-tabs  nav-tabs-line nav-tabs-line-warning" role="tablist">

                        <li class="nav-item active">
                            <a class="nav-link active" data-toggle="tab" href="#orders" role="tab">{{ t('Orders') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#products"
                               role="tab">{{ t('Products') }}</a>
                        </li>
                        {{--                        <li class="nav-item">--}}
                        {{--                            <a class="nav-link " data-toggle="tab" href="#notifications"--}}
                        {{--                               role="tab">{{ t('Notifications') }}</a>--}}
                        {{--                        </li>--}}
                        {{--                        <li class="nav-item">--}}
                        {{--                            <a class="nav-link " data-toggle="tab" href="#rates" role="tab">{{ t('Rates') }}</a>--}}
                        {{--                        </li>--}}
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane " id="notifications" role="tabpanel">
                            <table class="table text-center" id="notification-table">
                                <thead>
                                <th>{{ t('Title') }}</th>
                                <th>{{ t('Content') }}</th>
                                <th>{{ t('Received at') }}</th>
                                <th>{{ t('Actions') }}</th>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="products" role="tabpanel">

                            <table class="table text-center" id="products-table">
                                <thead>
                                <th>{{ t('Image') }}</th>
                                <th>{{ t('Name') }}</th>
                                <th>{{ t('Quantity') }}</th>
                                <th>{{ t('Price') }}</th>
                                <th>{{ t('Sold') }}</th>
                                <th>{{ t('Remaining') }}</th>
                                <th>{{ t('Rate') }}</th>
                                <th>{{ t('Created at') }}</th>
                                <th>{{ t('Actions') }}</th>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="addresses" role="tabpanel">
                            <table class="table text-center" id="addresses-table">
                                <thead>
                                <th>{{ t('Address Name') }}</th>
                                <th>{{ t('Address') }}</th>
                                <th>{{ t('Type') }}</th>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="rates" role="tabpanel">
                            <table class="table text-center" id="rates-table">
                                <thead>
                                <th>{{t('Driver Name')}}</th>
                                <th>{{t('Distributor Name')}}</th>
                                <th>{{t('Order UUID')}}</th>
                                <th>{{t('Rate')}}</th>
                                <th>{{t('Comment')}}</th>
                                <th>{{t('Rate Date')}}</th>
                                <th>{{t('Actions')}}</th>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane active" id="orders" role="tabpanel">
                            <table class="table text-center" id="orders-table">
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{w('Cancel')}}</button>
                        <button type="submit" class="btn btn-warning">{{w('Delete')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="notifyDeleteModel" tabindex="-1" role="dialog" aria-labelledby="notifyDeleteModel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{w('Confirm Delete')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="" id="notify_delete_form">
                    <input type="hidden" name="_method" value="delete">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>{{t('Are You Sure To Delete The Selected Row')}}</p>
                        <p>{{t('Deleting The Selected Row Results In Deleting All Records Related To It')}}.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{w('Cancel')}}</button>
                        <button type="submit" class="btn btn-warning">{{w('Delete')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="distributorModel" tabindex="-1" role="dialog" aria-labelledby="distributorModel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ t('Assign to distributor') }}
                        #{{ $distributor->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post"
                      action="{{route_manager('distributor.add_products',['distributor_id' => $distributor->id])}}"
                      id="form_information" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="container">
                            <div class="row form-group">
                                <label for="distributors_select">{{ t('Products') }}:</label>
                                <select name="product_id" id="product_select" class="form-control">
                                    <option value="">{{t("Select Product")}}</option>
                                    @foreach($products as $index=>$product_price)
                                        <option
                                            value="{{$product_price->id}}">{{optional($product_price->product)->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row form-group">
                                <label for="distributor_amount">{{ t('Amount') }}:</label>
                                <input type="number" class="form-control" step="1" name="amount"
                                       id="distributor_amount">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ t('Cancel') }}</button>
                        <button type="submit" class="btn btn-warning">{{ t('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/js/demo1/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/js/demo6/scripts.bundle.js') }}" type="text/javascript"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function () {
            $(document).on('click','.deleteRecord',(function(){
                var id = $(this).data("id");
                var url = '{{ route("manager.distributor.destroy_distributor_products", ":id") }}';
                url = url.replace(':id', id );
                $('#delete_form').attr('action',url);
            }));
            {{--$(document).on('click','.notifyDeleteRecord',(function(){--}}
            {{--    var id = $(this).data("id");--}}
            {{--    var url = '{{ route("manager.notification.destroy", ":id") }}';--}}
            {{--    url = url.replace(':id', id );--}}
            {{--    $('#notify_delete_form').attr('action',url);--}}
            {{--}));--}}
            $('.datepicker_3').datepicker({
                format: 'dd-mm-yyyy',
            });

            $(function () {
                var table = $('#products-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: true,
                    searching: false,
                    dom: 'lBfrtip',
                    buttons: [
                        'excel', 'print'
                    ],
                    @if(app()->getLocale() == 'ar')
                    language: {
                        url: "http://cdn.datatables.net/plug-ins/1.10.21/i18n/Arabic.json"
                    },
                    @endif
                    ajax: {
                        url: '{{ route('manager.distributor.products',$distributor) }}',
                        data: function (d) {
                            {{--                            d.user = "{{$distributor->id}}"--}}
                        }
                    },
                    columns: [
                        {data: 'image', name: 'image'},
                        {data: 'name', name: 'name'},
                        {data: 'amount', name: 'amount'},
                        {data: 'price', name: 'price'},
                        {data: 'sold', name: 'sold'},
                        {data: 'remaining', name: 'remaining'},
                        {data: 'rate', name: 'rate'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions'}
                    ],

                });
                table.buttons().container()
                    .appendTo('#example_wrapper .col-md-6:eq(0)');
            });
            $(function () {
                var table = $('#orders-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: true,
                    searching: false,
                    dom: 'lBfrtip',
                    buttons: [
                        'excel', 'print'
                    ],
                    @if(app()->getLocale() == 'ar')
                    language: {
                        url: "http://cdn.datatables.net/plug-ins/1.10.21/i18n/Arabic.json"
                    },
                    @endif
                    ajax: {
                        url: '{{ route('manager.order.index') }}',
                        data: function (d) {
                            d.distributor = "{{$distributor->id}}"
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

                });
                table.buttons().container()
                    .appendTo('#example_wrapper .col-md-6:eq(0)');
            });


        });
    </script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#wallet_form') !!}
    {!! $notify_validator->selector('#form_information') !!}
@endsection
