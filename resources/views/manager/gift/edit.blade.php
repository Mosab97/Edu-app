@extends('manager.layout.container')

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.gift.index') }}">{{t('Gifts')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($gift) ? t('Edit Gift') : t('Add Gift') }}
        </li>
    @endpush

    @php
        $gift =  isset($gift)?$gift:null;
            $name = isset($gift) ? $gift->getTranslations()['name'] : null;
            $description = isset($gift) ? $gift->getTranslations()['description'] : null;
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($gift) ? t('Edit Gift') : t('Add Gift') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.gift.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($gift))
                        <input type="hidden" name="gift_id" value="{{$gift->id}}">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('images') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="file" name="images[]" multiple class="form-control">
                                        @isset($gift)
                                            @foreach(optional($gift)->images as $index=>$item)
                                                <a href="{{$item->image}}" target="_blank">{{$index + 1}}</a>
                                            @endforeach
                                        @endisset
                                    </div>
                                </div>

                                @foreach(config('translatable.locales') as $local)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Name') }}
                                            <small>({{ $local }})</small></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" name="name[{{$local}}]" type="text"
                                                   value="{{  optional($name)[$local]}}"
                                            >
                                        </div>
                                    </div>
                                @endforeach
                                <div class="form-group row">
                                    <label
                                        class="col-xl-3 col-lg-3 col-form-label">{{ t('Points') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control" name="points"
                                               value="{{optional($gift)->points}}">
                                    </div>
                                </div>

                                @foreach(config('translatable.locales') as $local)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Description') }}
                                            <small>({{ $local }})</small></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <textarea name="description[{{$local}}]" id="description-{{$local}}"
                                                      cols="30" rows="10"
                                                      onkeypress="onTestChange()"
                                                      class="form-control">{{  optional($description)[$local]}}</textarea>
                                        </div>
                                    </div>
                                @endforeach


                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit"
                                            class="btn btn-danger">{{ isset($gift) ? t('Update'):t('Create') }}</button>&nbsp;
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

    <script>
        function onTestChange() {
            var key = window.event.keyCode;

            // If the user has pressed enter
            if (key === 13) {
                document.getElementById("description-ar").value = document.getElementById("description-ar").value + "\n";
                document.getElementById("description-en").value = document.getElementById("description-en").value + "\n";
                return false;
            } else {
                return true;
            }
        }
    </script>

    {{--    <script>--}}
    {{--        $(function () {--}}
    {{--            @foreach(config('translatable.locales') as $local)--}}
    {{--            $("#description-{{$local}}").keypress(function (e) {--}}
    {{--                if (e.which == 13) {--}}
    {{--                    //submit form via ajax, this is not JS but server side scripting so not showing here--}}
    {{--                    $("#description-{{$local}}").append($(this).val() + "<br/>");--}}
    {{--                    // $(this).val("");--}}
    {{--                    e.preventDefault();--}}
    {{--                }--}}
    {{--            });--}}
    {{--            @endforeach--}}
    {{--        });--}}
    {{--    </script>--}}

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#form_information') !!}



@endsection
