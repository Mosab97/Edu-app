@extends('manager.layout.container')
@section('style')
    <link href="{{ asset('assets/css/demo6/pages/general/invoices/invoice-1.'.direction('.').'css') }}" rel="stylesheet"
          type="text/css"/>
@endsection
@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.order.index') }}">{{ t('Orders') }}</a>
        </li>
        <li class="breadcrumb-item">
            {{ t('Order Review') }}
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
                                {{--                                <a href="javascript:;">--}}
                                {{--                                    <h1 class="mb-4">{{ t('Order Details') }}</h1>--}}
                                {{--                                    <span class="text-white" style="font-weight: 500">{{ t('Distributor :').' '.optional(optional($order)->distributor)->name }}</span>--}}
                                {{--                                    <br/>--}}
                                {{--                                    <span class="text-white"--}}
                                {{--                                          style="font-weight: 500">{{ t('Mobile :').' '.optional(optional($order)->distributor)->mobile }}</span>--}}
                                {{--                                    <br/>--}}
                                {{--                                </a>--}}
                                {{--                                <a>--}}
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="text-white">{{t('Distributor')}}</label>
                                            <br>

                                            @if(optional(optional($order)->distributor)->image)
                                                <img src="{{ asset(optional(optional($order)->distributor)->image) }}"
                                                     width="30px">
                                            @endif
                                            <span class="text-white text-right"
                                                  style="font-weight: 500">{{ optional(optional($order)->distributor)->name }}</span>
                                            <br>
                                            <span class="text-white ml-5"
                                                  style="font-weight: 500">{{ optional(optional($order)->distributor)->mobile }}</span>
                                        </div>
                                        <div class="col-6">
                                            <label class="text-white">{{t('Customer')}}</label>
                                            <br>
                                            @if(optional(optional($order)->user)->image)
                                                <img src="{{ asset(optional(optional($order)->user)->image) }}"
                                                     width="30px">
                                            @endif
                                            <span class="text-white text-right"
                                                  style="font-weight: 500">{{ optional(optional($order)->user)->name }}</span>
                                            <br>
                                            <span class="text-white ml-5"
                                                  style="font-weight: 500">{{ optional(optional($order)->user)->mobile }}</span>
                                        </div>
                                    </div>
                                </div>
                                {{--                                </a>--}}
                            </div>
                            <div class="kt-invoice__items"
                                 style="border-top: 1.5px solid #ffffff;padding: 2rem 0 2rem 0;">
                                <div class="kt-invoice__item">
                                    <span
                                        class="kt-invoice__subtitle">{{ $order->order_products()->count() .' ' .  t('Products') }}</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle">{{ $order->paid_name }}</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle">{{ $order->total_cost . '$' }}</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle">{{ 'Ship/$ ' . $order->shipping_cost }}</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle">{{ $order->delivery_time }}</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle">{{ $order->distance . 'KM' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-invoice__body kt-invoice__body--centered">
                     <div class="d-flex justify-content-between mb-5">
                         <div>
                             {{t('Delivery Speed: ').$order->delivery_speed}}
                         </div>
                         <div>                        {{t('Attitude: ').$order->attitude}}
                         </div>
                         <div>                        {{t('Respond Time: ').$order->respond_time}}
                         </div>
                     </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{ t('Meals') }}</th>
                                    <th class="text-center">{{ t('Quantity') }}</th>
                                    <th class="text-center">{{ t('Price') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($order->order_products as $item)
                                    <tr>
                                        <td>{{ optional(optional(optional($item)->product_price)->product)->name }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-center">{{ optional($item)->price }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="4">{{t('No Meals')}}</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($order->note)
                            <hr/>
                            <div class="kt-invoice__content mt-4">
                                <h5 class="kt-invoice__price">{{t('Note')}}:</h5>
                                <p>{{$order->note}}</p>
                            </div>
                        @endif
                        @if($order->rateed)
                            <hr/>
                            <div class="kt-invoice__content mt-4">
                                <h5 class="kt-invoice__price">{{t('User Rate')}}
                                    : {!! rating_d(optional($order->rate)->rate) !!}</h5>
                                <p>{{optional($order->rate)->comment}}</p>
                            </div>
                        @endif
                    </div>
                    <div class="kt-invoice__footer">
                        <div class="kt-invoice__container kt-invoice__container--centered">
                            <div class="kt-invoice__content">
                                <span>{{ t('Order details') }}</span>
                                <span><span>{{ t('Sub Total') }}:</span><span class="kt-invoice__price"
                                                                              dir="ltr">{{ $order->products_cost }}</span></span>
                                <span><span>{{ t('Vat') }}:</span><span class="kt-invoice__price"
                                                                        dir="ltr">{{ $order->vat }}</span></span>
                                <span><span>{{ t('Shipping Charges') }}:</span><span class="kt-invoice__price"
                                                                                     dir="ltr">{{ $order->shipping_cost }}</span></span>
                                <span><span>{{ t('Discount') }}:</span><span class="kt-invoice__price"
                                                                             dir="ltr">{{ $order->discount }}</span></span>
                            </div>
                            <div class="kt-invoice__content">
                                <span>{{ t('Status') }}</span>
                                <span class="kt-invoice__price">{{ $order->status_name }}</span>
                                <span>{{ t('Total') }}</span>
                                <span class="kt-invoice__price">{{ $order->total_cost }}</span>
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

