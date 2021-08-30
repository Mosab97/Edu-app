@extends('manager.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{t('Special Services')}}
        </li>
    @endpush

    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{t('Special Services')}}
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                {{--                                <a href="{{ route(\App\Models\Order::manager_route . 'create') }}"--}}
                                {{--                                   class="btn btn-danger btn-elevate btn-icon-sm">--}}
                                {{--                                    <i class="la la-plus"></i>--}}
                                {{--                                    {{t('Add Order')}}--}}
                                {{--                                </a>--}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20">
                        <div class="row kt-margin-b-20">
                            <div class="col-lg-2 kt-margin-b-10-tablet-and-mobile">
                                <label>{{ t('Name') }}:</label>
                                <input type="text" name="name" id="name" class="form-control kt-input"
                                       placeholder="{{t('Name')}}">
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
                        <th>#</th>
                        <th>{{t('Project Title')}}</th>
                        <th>{{t('User Id')}}</th>
                        <th>{{t('User Name')}}</th>
                        <th>{{t('User Phone')}}</th>
{{--                        <th>{{t('Project Details')}}</th>--}}
                        <th>{{t('Service Type')}}</th>
                        <th>{{t('Expected Budget')}}</th>
                        <th>{{t('Expected Delivery Time')}}</th>
{{--                        <th>{{t('Other Help Attachments')}}</th>--}}
                        <th>{{t('Created At')}}</th>
                        <th>{{t('Actions')}}</th>
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
            $(document).on('click', '.deleteRecord', (function () {
                var id = $(this).data("id");
                var url = '{{ route(\App\Models\SpecialService::manager_route . "destroy", ":id") }}';
                url = url.replace(':id', id);
                $('#delete_form').attr('action', url);
            }));
            $(function () {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    searching: false,
                    ajax: {
                        url: '{{ route(\App\Models\SpecialService::manager_route . 'index') }}',
                        data: function (d) {
                            d.name = $("#name").val();
                            d.status = $("#status").val();
                        }
                    },
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'project_title', name: 'project_title'},
                        {data: 'user_id', name: 'user_id'},
                        {data: 'user_name', name: 'user_name'},
                        {data: 'user_phone', name: 'user_phone'},
                        // {data: 'project_details', name: 'project_details'},
                        {data: 'service_type', name: 'service_type'},
                        {data: 'expected_budget', name: 'expected_budget'},
                        {data: 'expected_delivery_time', name: 'expected_delivery_time'},
                        // {data: 'other_help_attachments', name: 'other_help_attachments'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions'}
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
