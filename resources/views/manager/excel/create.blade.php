@extends('manager.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{t('Distributors')}}
        </li>
    @endpush

    <div class="row">
        <div class="col-md-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">
                    <form class="kt-form kt-form--fit kt-margin-b-20" action="{{route('manager.excel-create')}}"
                          method="post" enctype="multipart/form-data">
                        @csrf

                        <input type="file" name="excel">
                        <button type="submit" class="btn btn-primary">{{t('Save')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

