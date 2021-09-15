@extends('manager.layout.container')
@section('style')
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection


@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            {{t('Map')}}
        </li>
    @endpush
    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ t('Map') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="javascript:;">

                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{t('Location On Map')}}
                                    </label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div id="map" style="width: 100%; min-height: 400px"></div>
                                    </div>
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
    {{--    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyAZlL0Ua_lbDzTQK28QTqyu1GwZEqleC-0"></script>--}}
    <script
        src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyAMTJx--2VY0SEyEqU740eC0xrYNDwAihA"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"
            type="text/javascript"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>


    <script type="text/javascript">
        $('.kt_timepicker_2').timepicker({
            minuteStep: 1,
            defaultTime: '',
            showSeconds: true,
            showMeridian: false,
            snapToStep: true,
            isRtl: true,
            icons: {
                up: 'la la-angle-up',
                down: 'la la-angle-down'
            }
        });
        $('.kt_timepicker_2').change(function () {
            var ele = $(this).parent().parent().find('input[type="checkbox"]');
            ele.attr('checked', true)
        });
        var map;
        var marker;
        var myLatLng = {
            lat: {{ isset($branch->lat) ? $branch->lat : 0 }},
            lng: {{ isset($branch->lng) ? $branch->lng : 0 }}
        };
        initMap();

        function initMap() {
            map = new google.maps.Map(
                document.getElementById('map'),
                {
                    @php
                        $order = $orders->last()
                    @endphp
                    center: new google.maps.LatLng({{ $order->lat?? 0 }}, {{$order->lng?? 0 }}),
                    zoom: 9,
                });


            // Create markers.
            // marker = new google.maps.Marker({
            //     position: myLatLng,
            //     map: map
            // });


            const icons = {
                pending_order: {
                    icon: "{{asset_public('map_images/pending_order.svg')}}",
                },
                on_way_order: {
                    icon: "{{asset_public('map_images/on_way_order.svg')}}",
                },
                merchant: {
                    icon: "{{asset_public('map_images/merchant.svg')}}",
                },
                distributor: {
                    icon: "{{asset_public('map_images/distributor.svg')}}",
                },
            };

            const features = [

                    @foreach($orders as $order)
                {
                    position: new google.maps.LatLng('{{$order->lat??0}}', '{{$order->lng??0}}'),
                    type: "pending_order",
                },
                {
                    position: new google.maps.LatLng('{{optional(optional($order)->distributor)->lat??0}}', '{{optional(optional($order)->distributor)->lng??0}}'),
                    type: "distributor",
                },
                @endforeach

            ];
            // Create markers.
            for (let i = 0; i < features.length; i++) {
                var c = new google.maps.Marker({
                    position: features[i].position,
                    icon: icons[features[i].type].icon,
                    map: map,
                });

                var infowindow = new google.maps.InfoWindow();

                google.maps.event.addListener(c, 'click', function () {
                    if (features[i].type ==  'distributor')
                    {
                        {{--infowindow.setContent('user name :  {{$order->distributor->first_name}}');--}}
                        infowindow.setContent('<div class="well well-sm"><div class="media"><a class="thumbnail pull-left" href="#"><img style="width: 61px;border-radius: 50%;" class="media-object" src="{{url($order->distributor->image)}}"></a><div style="text-align: center;"class="media-body"> <h4 class="media-heading">{{$order->distributor->first_name.' '.$order->distributor->last_name}}</h4> <p><span class="label label-warning">{{$order->distributor->mobile}}</span></p><p></p></div></div></div>');
                        infowindow.open(map, this);
                    }else{
                        infowindow.setContent('<div class="well well-sm"><div class="media"><a class="thumbnail pull-left" href="#"><img style="width: 61px;border-radius: 50%;" class="media-object" src="{{url($order->user->image)}}"></a><div style="text-align: center;"class="media-body"> <h4 class="media-heading">{{$order->user->first_name.' '.$order->user->last_name}}</h4> <p><span class="label label-warning">{{$order->user->mobile}}</span></p><p></p></div></div></div>');
                        infowindow.open(map, this);
                    }

                });
            }

            @foreach($orders as $order)
                {{--var flightPlanCoordinates = [--}}
                {{--    {lat: {{$order->lat}}, lng: {{$order->lng}}},--}}
                {{--    {--}}
                {{--        lat: {{optional(optional($order)->distributor)->lat}},--}}
                {{--        lng: {{optional(optional($order)->distributor)->lng}}--}}
                {{--    },--}}
                {{--];--}}
                {{--            {{dd($order)}}--}}

                @if($order->lat == 0 || $order->lng == 0 || optional($order->distributor)->lat??0 == 0 || optional($order->distributor)->lng??0 == 0)
            (new google.maps.Polyline({
                path: [
                    {lat: {{$order->lat??0}}, lng: {{$order->lng??0}}},
                    {
                        lat: {{optional($order->distributor)->lat??0}},
                        lng: {{optional($order->distributor)->lng??0}}
                    },
                ],
                geodesic: true,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 2,
            })).setMap(map);
            @endif

            @endforeach




            // const flightPath = new google.maps.Polyline({
            //     path: flightPlanCoordinates,
            //     geodesic: true,
            //     strokeColor: "#FF0000",
            //     strokeOpacity: 1.0,
            //     strokeWeight: 2,
            // });
            // flightPath.setMap(map);
            // flightPath2.setMap(map);


        }

    </script>
    {{--    {!! $validator->selector('#form_information') !!}--}}
@endsection
