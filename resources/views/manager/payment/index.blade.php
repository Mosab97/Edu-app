@extends('manager.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{t('Payments')}}
        </li>
    @endpush

    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{t('Payments')}}
                        </h3>
                    </div>
                    {{--                    <div class="kt-portlet__head-toolbar">--}}
                    {{--                        <div class="kt-portlet__head-wrapper">--}}
                    {{--                            <div class="kt-portlet__head-actions">--}}
                    {{--                                <a href="{{ route(\App\Models\Payment::manager_route.'create') }}"--}}
                    {{--                                   class="btn btn-danger btn-elevate btn-icon-sm">--}}
                    {{--                                    <i class="la la-plus"></i>--}}
                    {{--                                    {{t('Add Payment')}}--}}
                    {{--                                </a>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20">
                        <div class="row kt-margin-b-20">
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Search') }}:</label>
                                <input type="text" name="name" id="name" class="form-control kt-input"
                                       placeholder="{{t('Search')}}">
                            </div>

                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Action') }}:</label>
                                <br/>
                                <button type="submit" class="btn btn-danger btn-elevate btn-icon-sm" id="kt_search">
                                    <i class="la la-search"></i>
                                    {{t('Search')}}
                                </button>
                                &nbsp;&nbsp
                            </div>
                        </div>
                    </form>
                    <table class="table text-center" id="users-table">
                        <thead>
                        <th>{{t('Payment ID')}}</th>
                        <th>{{t('Currency')}}</th>
                        <th>{{t('Amount')}}</th>
                        <th>{{t('User ID')}}</th>
                        <th>{{t('User')}}</th>
                        <th>{{t('payer_id')}}</th>
                        <th>{{t('payment_id')}}</th>
                        <th>{{t('Created At')}}</th>
{{--                        <th>{{t('Actions')}}</th>--}}
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{w('Cancel')}}</button>
                        <button type="submit" class="btn btn-warning">{{w('Delete')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script>
        $(document).ready(function () {
            {{--$(document).on('click', '.deleteRecord', (function () {--}}
            {{--    var id = $(this).data("id");--}}
            {{--    var url = '{{ route(\App\Models\Payment::manager_route.".destroy", ":id") }}';--}}
            {{--    url = url.replace(':id', id);--}}
            {{--    $('#delete_form').attr('action', url);--}}
            {{--}));--}}
            $(function () {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    searching: false,
                    ajax: {
                        url: '{{ route('manager.'.\App\Models\Payment::manager_route.'.index') }}',
                        data: function (d) {
                            d.name = $("#name").val();
                            d.status = $("#status").val();
                        }
                    },
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'currency', name: 'currency'},
                        {data: 'amount', name: 'amount'},
                        {data: 'user_id', name: 'user_id'},
                        {data: 'user', name: 'user'},
                        {data: 'payer_id', name: 'payer_id'},
                        {data: 'payment_id', name: 'payment_id'},
                        {data: 'created_at', name: 'created_at'},
                        // {data: 'actions', name: 'actions'}
                    ],
                });
            });
            $('#kt_search').click(function (e) {
                e.preventDefault();
                $('#users-table').DataTable().draw(true);
            });


        });
    </script>
@endsection
