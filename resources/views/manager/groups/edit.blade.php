@extends('manager/layout/container')
@section('style')
@endsection


@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.'.\App\Models\Group::ui['manager_route'].'.index') }}">{{t(\App\Models\Group::ui['single_name'])}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($item) ? t('Edit ' . \App\Models\Group::ui['single_name']) : t('Add ' . \App\Models\Group::ui['single_name']) }}
        </li>
    @endpush



    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($item) ? t('Edit ' . \App\Models\Group::ui['single_name']) : t('Add ' . \App\Models\Group::ui['single_name']) }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.'.\App\Models\Group::ui['manager_route'].'.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($item))
                        <input type="hidden" name="{{strtolower(\App\Models\Group::ui['single_name'])}}_id"
                               value="{{$item->id}}">
                    @endif


                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Name') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="name" type="text"
                                               value="{{  isset($item)?$item->name: old("name")}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"
                                            class="btn btn-danger">{{ isset($item) ? t('Update'):t('Create') }}</button>&nbsp;
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
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script>
    </script>
    {!! $validator->selector('#form_information') !!}
@endsection
