{{--{{dd($data['date'])}}--}}
@extends('manager.layout.container')
@section('style')
    <link href="{{ asset('assets/css/demo6/pages/general/invoices/invoice-1.'.direction('.').'css') }}" rel="stylesheet"
          type="text/css"/>
@endsection
@section('content')
    <form action="{{route('manager.bills',$distributor->id)}}" method="get">
        <div class="row mb-5">
            <div class="col-6">
                <a href="{{route('manager.mark_as_paid',[$distributor->id,$data['date']])}}" class="btn "
                   style="background-color: green;color: #fff">{{t('Mark as Paid')}}</a>
                <a href="{{route('manager.mark_all_as_paid',$distributor->id)}}" class="btn "
                   style="background-color: blue;color: #fff">{{t('Make All As Paid')}}</a>
            </div>
            <div class="col-6 d-flex justify-content-between">
                {{--                @csrf--}}
                <button type="submit" class="btn btn-danger ml-5">{{t('Search')}}</button>
                <input type="date" name="date" id="date" placeholder="date" class="form-control"
                       value="{{$data['date']}}">

            </div>
        </div>
    </form>
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.home') }}">{{ t('Home') }}</a>
        </li>
    @endpush

    <!-- begin:: Content -->
    <div class="kt-portlet">
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="kt-invoice-1">
                <div class="kt-invoice__wrapper">
                    <div class="kt-invoice__head" style="background: linear-gradient(to right,#db1515,#ec5252);">
                        <div class="kt-invoice__container kt-invoice__container--centered">
                            <div class="kt-invoice__logo mb-3" style="padding-top: 2rem;">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="text-white">{{t('Distributor')}}</label>
                                            <br>
                                            @if(optional($distributor)->image)
                                                <img src="{{ asset(optional($distributor)->image) }}"
                                                     width="30px">
                                            @endif
                                            <span class="text-white text-right"
                                                  style="font-weight: 500">{{ optional($distributor)->name }}</span>
                                            <br>
                                            <span class="text-white ml-5"
                                                  style="font-weight: 500">{{ optional($distributor)->mobile }}</span>
                                        </div>
                                    </div>
                                </div>
                                {{--                                </a>--}}
                            </div>
                        </div>
                    </div>
                    <div class="kt-invoice__body kt-invoice__body--centered">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{ t('Order Number') }}</th>
                                    <th class="text-center">{{ t('Order Products') }}</th>
                                    <th class="text-center">{{ t('Total Cost') }}</th>
                                    <th class="text-center">{{ t('Is Paid') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $index=>$order)
                                    <tr>
                                        <td><a target="_blank"
                                               href="{{route('manager.order.show',$order->id)}}">{{$order->id}}</a>
                                        </td>
                                        <td class="text-center">{{ $order->order_products_count }}</td>
                                        <td class="text-center">{{ $order->total_cost }}</td>
                                        <td class="text-center">{{ $order->distributor_take_his_profit? t('Paid'):t('Not Paid') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="kt-invoice__footer">
                        <div class="kt-invoice__container kt-invoice__container--centered">
                            <div class="kt-invoice__content">
                                <span>{{ t('Bill details') }}</span>
                                @php
                                    $total = 0;
                                @endphp
                                <span><span>{{ t('Sub Total') }}:</span><span class="kt-invoice__price"
                                                                              dir="ltr">{{ $data['sub_total'] }}</span></span>
                                <span><span>{{ t('Vat') }}:</span><span class="kt-invoice__price"
                                                                        dir="ltr">{{ $data['vat'] }}</span></span>
{{--                                <span><span>{{ t('Shipping Charges') }}:</span><span class="kt-invoice__price"--}}
{{--                                                                                     dir="ltr">{{ $data['shipping']  }}</span></span>--}}
                            </div>
                            <div class="kt-invoice__content">
                                <span>{{ t('Total') }}</span>
                                <span class="kt-invoice__price">{{ $data['total'] }}</span>
                                <br>
                                <br>
                                <br>
                                <span>{{ t('Distributor Total Profit Cost This Date') }}</span>
                                <span class="kt-invoice__price">{{$data['distributor_total_profit_cost_this_date']}}</span>

                                <span>{{ t('Distributor Total Profit Cost') }}</span>
                                <span class="kt-invoice__price">{{$data['distributor_total_profit_cost']}}</span>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-invoice__actions row">
        <div class="kt-invoice__container  col-md-6">
            <button type="button" class="btn btn-danger btn-bold"
                    onclick="window.print();">{{ t('Print Order') }}</button>
        </div>
    </div>

    <!-- end:: Content -->
@endsection
@section('script')

@endsection

