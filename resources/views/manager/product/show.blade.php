@extends('manager.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}"
          rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    @push('breadcrumb')

        <li class="breadcrumb-item">
            <a href="{{ route('manager.product.index') }}">{{ t('Products') }}</a>
        </li>
        <li class="breadcrumb-item">
            {{ t('Show Product') }}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <!--begin:: Widgets/Applications/Product/Profile3-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">
                    <div class="kt-widget kt-widget--user-profile-3">
                        <div class="kt-widget__top">
                            <div class="kt-widget__media kt-hidden-">
                                <img src="{{ asset($product->image) }}" alt="image">
                            </div>
                            <div class="kt-widget__content">
                                <div class="kt-widget__head">
                                    <label href="#" class="kt-widget__username">
                                        {{ $product->name }}
                                        {{--                                        @if($product->status == \App\Models\Product::ACTIVE)--}}
                                        {{--                                            <i class="flaticon2-correct"></i>--}}
                                        {{--                                        @endif--}}
                                    </label>
                                </div>
                                <div class="kt-widget__subhead">
                                    <a href="javascript:;" class="btn btn-primary"
                                       style="background: blue;color: #fff;  text-transform: uppercase;">{{ optional($product->category)->name }}</a>
                                </div>
                                <div class="kt-widget__info">
                                    <div class="kt-widget__desc">
                                        <label href="javascript:;" dir="rtl"
                                               class="mr-2">{{ t('Id : #') .' '. $product->id }}</label>
                                        <br>
                                        <label href="javascript:;"
                                               class="mr-2">{{ t('Quantity : #') .' '. optional($product_price)->quantity }}</label>
                                        <br>
                                        <label href="javascript:;" class="mr-2">{{ t('Sold : #') .' '. optional($product_price)->sold }}</label>
                                        <br>
                                        <label href="javascript:;"
                                               class="mr-2">{{ t('Remaining : #') .' '. optional($product_price)->remaining }}</label>
                                        <br>
                                        <label href="javascript:;"
                                               class="mr-2">{{ t('Original Price : #') .' '. optional($product_price)->piece_cost }}</label>
                                        <br>
                                        <label href="javascript:;"
                                               class="mr-2">{{ t('Created At : ') .' '. $product->created_at->format('d-m-Y') }}</label>
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
                                        {{--                                        <button data-toggle="modal" data-target="#distributorModel" type="button"--}}
                                        {{--                                                class="btn btn-brand btn-sm btn-upper"--}}
                                        {{--                                                style="text-transform: capitalize "><i--}}
                                        {{--                                                class="fa fa-newspaper"></i>{{ t('Assign to distributor') }}</button>--}}
                                        {{--                                        <button data-toggle="modal" data-target="#walletModel" type="button"--}}
                                        {{--                                                class="btn btn-brand btn-sm btn-upper"><i class="fa fa-coins"></i>{{ t('Add Balance') }}</button>--}}
                                    </div>
                                </div>
                                <div class="kt-widget__subhead">
                                    {{--                                    <label><span>{{t('Mobile') }}</span>: {{ '$user->phone' }}</label>--}}
                                </div>
                            </div>

                        </div>
                        <div class="kt-widget__bottom mt-0">
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-coins"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Price Per Piece') }}</span>
                                    <span class="kt-widget__value"><span> </span> {{optional($product_price)->piece_cost}} </span>
                                </div>
                            </div>
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-coins"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Package Price - Retailer') }}</span>
                                    <span
                                        class="kt-widget__value">{{optional($product_price)->retailer_package_cost}}</span>
                                </div>
                            </div>
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-coins"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">{{ t('Package Price - Wholesaler') }}</span>
                                    <span
                                        class="kt-widget__value">{{optional($product_price)->wholesaler_package_cost}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="distributorModel" tabindex="-1" role="dialog" aria-labelledby="distributorModel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ t('Assign to distributor') }}
                        #{{ $product->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="{{route_manager('assign_to_distributor',['product_id' => $product->id])}}"
                      id="form_information" enctype="multipart/form-data">
                    {{--                    <input type="hidden" name="user_id" value="{{$product->id}}">--}}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="container">
                            <div class="row form-group">
                                <label for="distributors_select">{{ t('Distributors') }}:</label>
                                <select name="distributor" id="distributors_select" class="form-control">
                                    <option value="">{{t("Select distributor")}}</option>
                                    @foreach($distributors as $index=>$distributor)
                                        <option value="{{$distributor->id}}">{{$distributor->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row form-group">
                                <label for="distributor_amount">{{ t('Amount') }}:</label>
                                <input type="number" class="form-control" step="1"
                                       max="{{optional($product->price)->remaining}}" min="0" name="amount"
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
    <div class="modal fade" id="walletModel" tabindex="-1" role="dialog" aria-labelledby="walletModel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ t('Add Balance') }} #{{ $product->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="#" id="wallet_form">
                    <input type="hidden" name="user_id" value="{{$product->id}}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="container">
                            <div class="row form-group">
                                <label>{{ t('Amount') }}:</label>
                                <input type="number" min="0" name="amount" class="form-control">
                            </div>
                            <div class="row form-group">
                                <label>{{ t('Note') }}:</label>
                                <textarea name="note" class="form-control"></textarea>
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
    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            {{ t('Product Details') }}
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <ul class="nav nav-tabs  nav-tabs-line nav-tabs-line-warning" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#distributor"
                               role="tab">{{ t('Distributor') }}</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link " data-toggle="tab" href="#images" role="tab">{{ t('Images') }}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#details"
                               role="tab">{{ t('Description') }}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane " id="images" role="tabpanel">
                            <button data-toggle="modal" data-target="#editModel"
                                    class="addRecord  btn btn-warning pull-{{lang() == 'ar'? 'left' : 'right'}}">{{t("Add Image")}}
                            </button>
                            <table class="table text-center" id="images-table">
                                <thead>
                                <th>{{ t('Image') }}</th>
                                <th>{{ t('Created At') }}</th>
                                <th>{{ t('Actions') }}</th>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane active " id="distributor" role="tabpanel">
                            <button data-toggle="modal" data-target="#distributorModel" type="button"
                                    class="addRecord  btn btn-warning pull-{{lang() == 'ar'? 'left' : 'right'}}"
                                    style="text-transform: capitalize "><i
                                    class="fa fa-newspaper"></i>{{ t('Assign to distributor') }}</button>

                            <table class="table text-center" id="distributors-table">
                                <thead>
                                <th>{{ t('Name') }}</th>
                                <th>{{ t('Amount') }}</th>
                                <th>{{ t('Created At') }}</th>
                                <th>{{ t('Actions') }}</th>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="details" role="tabpanel">
                            <p>
                                {!! $product->description !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModel" tabindex="-1" role="dialog" aria-labelledby="editModel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ t('Add image') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form method="post" action="{{ route('manager.product_images.store') }}" id="edit_form"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="image" class="form-control-label">{{ t('Image') }}:</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </div>
                        <div class="form-group" style="display: none" id="user_id">
                            <input type="number" class="form-control" name="product_id" value="{{$product->id}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ t('Cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ t('Send') }}</button>
                    </div>
                </form>
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
            $(document).on('click', '.editDistributorRecord', (function () {
                var id = $(this).data("id");
                var amount = $(this).data("amount");
                var url = '{{ route_manager("product.update_amount", ":id") }}';
                url = url.replace(':id', id);
                $('#form_information').attr('action', url);
                $('#distributors_select').attr('disabled', true);
                $('#distributor_amount').attr('value', amount);
                console.log(id, amount);
            }));
            $(document).on('click', '.deleteDistributorProductRecord', (function () {
                var id = $(this).data("id");
                var url = '{{ route_manager("product.deleteDistributorProduct", ":id") }}';
                url = url.replace(':id', id);
                $('#delete_form').attr('action', url);
            }));
            $(document).on('click', '.deleteRecord', (function () {
                var id = $(this).data("id");
                var url = '{{ route("manager.product_images.destroy", ":id") }}';
                url = url.replace(':id', id);
                $('#delete_form').attr('action', url);
            }));
            $(document).on('click', '.editRecord', (function () {
                var id = $(this).data("id");
                var url = '{{ route("manager.product_images.update", ":id") }}';
                url = url.replace(':id', id);
                $('#edit_form').attr('action', url);
            }));
            $('.datepicker_3').datepicker({
                format: 'dd-mm-yyyy',
            });
            $(function () {
                $('#images-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    searching: false,
                    dom: 'lBfrtip',
                    buttons: [
                        // 'csv', 'excel', 'print'
                    ],
                    ajax: {
                        url: '{{ route('manager.product_images.index',$product->id) }}',
                    },
                    columns: [
                        {data: 'image', name: 'image'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
                $('#distributors-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    searching: false,
                    dom: 'lBfrtip',
                    buttons: [
                        // 'csv', 'excel', 'print'
                    ],
                    ajax: {
                        url: '{{ route('manager.product.product_distributors',optional(optional($product)->price)->id) }}',
                    },
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'amount', name: 'amount'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'actions', name: 'actions'}
                    ],
                });
            });

        });
    </script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $distributor_validator->selector('#form_information') !!}
@endsection
