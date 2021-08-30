@extends('manager.layout.container')
@section('style')
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route(\App\Models\PackageValues::manager_route . 'index') }}">{{t('Package Values')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($package_value) ? t('Edit Package Values') : t('Add Package Values') }}
        </li>
    @endpush

    @php
        $value = isset($package_value) ? $package_value->getTranslations()['value'] : null;
    @endphp

    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($package_value) ? t('Edit Package Values') : t('Add Package Values') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route(\App\Models\PackageValues::manager_route . 'store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($package_value))
                        <input type="hidden" name="package_value_id" value="{{$package_value->id}}">
                    @endif


                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                                <div class="kt-section kt-section--first">
                                    <div class="kt-section__body">
                                        <div class="row">
                                            @foreach(config('translatable.locales') as $local)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ t('Value') }} <small>({{ $local }})</small></label>
                                                        <input name="value[{{$local}}]" type="text"
                                                               class="form-control" placeholder=""
                                                               value="{{  isset($value) ? optional($value)[$local]:''}}"
                                                        >
                                                    </div>
                                                </div>
                                            @endforeach

                                            <div class="col-6">
                                                <label for="package">{{t('Package')}}</label>
                                                <select name="package" id="package" class="form-control">
                                                    <option value="">{{t('Select Package')}}</option>
                                                    @foreach($packages as $index=>$package)
                                                        <option
                                                            value="{{$package->id}}" {{isset($package_value) && $package_value->package_id == $package->id? 'selected' : ''}}>{{$package->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>



                                        </div>
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
                                            class="btn btn-danger">{{ isset($package_value) ? t('Update'):t('Create') }}</button>&nbsp;
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
    {!! $validator->selector('#form_information') !!}
@endsection
