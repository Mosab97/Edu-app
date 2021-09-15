@extends('manager.layout.container')
@section('style')
    <link href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}"
          rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.distributor.index') }}">{{t('Distributors')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($distributor) ? t('Edit Distributor') : t('Add Distributor') }}
        </li>
    @endpush
    @php
        $f_name = isset($distributor) ? $distributor->getTranslations()['first_name'] : null;
        $l_name = isset($distributor) ? $distributor->getTranslations()['last_name'] : null;
    @endphp

    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($distributor) ? t('Edit Distributor') : t('Add Distributor') }}</h3>
                    </div>
                </div>

                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.distributor.store') }}"
                      method="post">
                    {{ csrf_field() }}
                    @if(isset($distributor))
                        <input type="hidden" name="distributor_id" value="{{$distributor->id}}">
                    @endif


                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('image') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="upload-btn-wrapper">
                                            <button class="btn btn-brand">{{ t('upload file') }}</button>
                                            <input name="image" class="imgInp" id="imgInp" type="file"/>
                                        </div>
                                        <img id="blah"
                                             @if(!isset($distributor) || is_null($distributor->getOriginal('image'))) style="display:none"
                                             @endif src="{{ isset($distributor) && !is_null($distributor->getOriginal('image'))  ? url($distributor->image):'' }}"
                                             width="150" alt="No file chosen"/>
                                    </div>
                                </div>

                                @foreach(config('translatable.locales') as $local)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('First Name') }}
                                            <small>({{ $local }})</small></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="first_name[{{$local}}]" type="text"
                                                   value="{{ isset($f_name) ? $f_name[$local] : old("first_name[$local]") }}">
                                        </div>
                                    </div>
                                @endforeach
                                @foreach(config('translatable.locales') as $local)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Last Name') }}
                                            <small>({{ $local }})</small></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="last_name[{{$local}}]" type="text"
                                                   value="{{ isset($l_name) ? $l_name[$local] : old("last_name[$local]") }}">
                                        </div>
                                    </div>
                                @endforeach


                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Mobile') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" dir="ltr" placeholder="+966XXXXXXXXX" name="phone"
                                               type="text"
                                               value="{{ isset($distributor) ? $distributor->mobile :old('mobile') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Email') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" dir="ltr" placeholder="xx@xx.xxx" name="email"
                                               type="text"
                                               value="{{ isset($distributor) ? $distributor->email :old('email') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Profit Ratio') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" dir="ltr" placeholder="1"
                                               name="profit_percentage"
                                               type="number" step="1"
                                               value="{{ isset($distributor) ? optional($distributor)->distributor->profit_percentage :old('profit_percentage') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Password') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control" name="password" type="password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Stakeholder Type') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="stakeholder_type">
                                            <option value="" selected disabled>{{t('Select Stakeholder')}}</option>
                                            @foreach(\App\Models\Distributor::stakeholders as $index=>$item)
                                                <option
                                                    value="{{$item}}" {{isset($distributor) && optional(optional($distributor)->distributor)->stakeholder_type == $item ? 'selected':''}}>{{t(ucfirst($index))}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">{{t('Active')}}</label>
                                    <div class="col-3">
                                        <span class="kt-switch">
                                            <label>
                                            <input type="checkbox" value="1"
                                                   @if(isset($distributor))
                                                   @if($distributor->isBlocked == false) checked @endif
                                                   @else
                                                   checked
                                                   @endif
                                                   {{--                                                   {{ isset($distributor) && $distributor->isBlocked == false ? 'checked' :'' }} --}}
                                                   name="active">
                                            <span></span>
                                            </label>
                                        </span>
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
                                            class="btn btn-danger">{{ isset($distributor) ? t('Update'):t('Create') }}</button>&nbsp;
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
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"
            type="text/javascript"></script>

    {!! $validator->selector('#form_information') !!}
@endsection
