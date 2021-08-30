@extends('manager.layout.container')
@section('style')
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route(\App\Models\Statistic::manager_route . 'index') }}">{{t('Statistics')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($statistic) ? t('Edit Statistic') : t('Add Statistic') }}
        </li>
    @endpush

    @php
        $key = isset($statistic) ? $statistic->getTranslations()['key'] : null;
        $value = isset($statistic) ? $statistic->getTranslations()['value'] : null;
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($statistic) ? t('Edit Statistic') : t('Add Statistic') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route(\App\Models\Statistic::manager_route . 'store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($statistic))
                        <input type="hidden" name="statistic_id" value="{{$statistic->id}}">
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
                                                        <label>{{ t('Key') }} <small>({{ $local }}
                                                                )</small></label>
                                                        <input name="key[{{$local}}]" type="text"
                                                               class="form-control" placeholder=""
                                                               value="{{  optional($key)[$local]}}"
                                                        >
                                                    </div>
                                                </div>
                                            @endforeach
                                            @foreach(config('translatable.locales') as $local)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ t('Value') }} <small>({{ $local }}
                                                                )</small></label>
                                                        <input name="value[{{$local}}]" type="text"
                                                               class="form-control" placeholder=""
                                                               value="{{  optional($value)[$local]}}"
                                                        >
                                                    </div>
                                                </div>
                                            @endforeach
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
                                            class="btn btn-danger">{{ isset($statistic) ? t('Update'):t('Create') }}</button>&nbsp;
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
