@extends('layouts.container')
@section('style')
@endsection

@php
    $breadcrumb = true;
    $title = w('All Faq');
@endphp



@section('content')
    <section aria-label="section" data-bgcolor="#ffffff">
        <div class="container">
            <div class="row">
                    <div class="col-md-12">
                        <div id="accordion-1" class="accordion">
                            @foreach($faq as $faq_index=>$faq_item)
                                {{--                                {{dd(count($faq),$loop->iteration,$faq_index)}}--}}

                                <div class="card">
                                    <div id="heading-a{{(0 + 1)}}" class="card-header bg-white shadow-sm border-0">
                                        <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse"
                                                                             data-target="#collapse-a{{($faq_index + 1)}}"
                                                                             aria-expanded="false"
                                                                             aria-controls="collapse-a{{($faq_index + 1)}}"
                                                                             class="d-block position-relative collapsed text-dark collapsible-link py-2">{{$faq_item->key}}</a>
                                        </h6>
                                    </div>
                                    <div id="collapse-a{{($faq_index + 1)}}"
                                         aria-labelledby="heading-a{{($faq_index + 1)}}"
                                         data-parent="#accordion-{{(0 + 1)}}"
                                         class="collapse">
                                        <div class="card-body p-4">
                                            <p class="m-0">{{$faq_item->value}}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
            </div>
        </div>
    </section>
@endsection


@section('script')
@endsection


