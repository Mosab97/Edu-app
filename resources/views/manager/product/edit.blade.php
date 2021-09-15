@extends('manager.layout.container')

@section('content')
    @push('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('manager.product.index') }}">{{t('Products')}}</a>
        </li>
        <li class="breadcrumb-item">
            {{ isset($product) ? t('Edit Product') : t('Add Product') }}
        </li>
    @endpush

    @php
        $price = isset($product_price)?$product_price:null;
    $product =  isset($product_price)?optional($product_price)->product:null;
        $name = isset($product) ? $product->getTranslations()['name'] : null;
        $description = isset($product) ? $product->getTranslations()['description'] : null;
        //dd($price);
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ isset($product) ? t('Edit Product') : t('Add Product') }}</h3>
                    </div>
                </div>
                <form enctype="multipart/form-data" id="form_information" class="kt-form kt-form--label-right"
                      action="{{route('manager.product.store') }}" method="post">
                    {{ csrf_field() }}
                    @if(isset($product))
                        <input type="hidden" name="product_id" value="{{$product->id}}">
                    @endif
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Customer images') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="file" name="images[]" multiple class="form-control">
                                        @isset($product)
                                            @foreach(optional($product)->customer_images as $index=>$item)
                                                <a href="{{$item->image}}" target="_blank">{{$index + 1}}</a>
                                            @endforeach
                                        @endisset
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Merchant images') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="file" name="merchant_images[]" multiple class="form-control">
                                        @isset($product)
                                            @foreach(optional($product)->merchant_images as $index=>$item)
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
                                                   value="{{  isset($name) && is_array($name) && array_key_exists($local,$name)? $name[$local]: old("name[$local]")}}"
                                            >
                                        </div>
                                    </div>
                                @endforeach

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Category') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control" name="category_id">
                                            <option value="" selected
                                                    disabled>{{t('Select Category')}}</option>
                                            @foreach($categories as $category)
                                                <option
                                                    value="{{$category->id}}"
                                                    {{isset($product) && $product->category_id == $category->id ? 'selected':''}}>{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label
                                        class="col-xl-3 col-lg-3 col-form-label">{{ t('Total Number Of Pieces') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control" name="quantity"
                                               value="{{isset($price)?$price->quantity : old('quantity')}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label
                                        class="col-xl-3 col-lg-3 col-form-label">{{ t('Piece Per Package') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control" name="piece_per_package"
                                               value="{{isset($price)?$price->piece_per_package : old('piece_per_package')}}">
                                    </div>
                                </div>


                                <hr>
                                <div class="form-group row">
                                    <label
                                        class="col-xl-3 col-lg-3 col-form-label">{{t('Prices for  ') . getCurrentCountry()->name}}</label>
                                </div>


                                <div class="form-group row">
                                    <label
                                        class="col-xl-3 col-lg-3 col-form-label">{{ t('Piece Cost') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control" name="piece_cost"
                                               value="{{isset($price)?$price->piece_cost : old('piece_cost')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        class="col-xl-3 col-lg-3 col-form-label">{{ t('Wholesaler Package Cost') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control" name="wholesaler_package_cost"
                                               value="{{isset($price)?$price->wholesaler_package_cost : old('wholesaler_package_cost')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        class="col-xl-3 col-lg-3 col-form-label">{{ t('Retailer Package Cost') }}</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input type="number" class="form-control" name="retailer_package_cost"
                                               value="{{isset($price)?$price->retailer_package_cost : old('retailer_package_cost')}}">
                                    </div>
                                </div>


                                @foreach(config('translatable.locales') as $local)
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">{{ t('Description') }}
                                            <small>({{ $local }})</small></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <textarea name="description[{{$local}}]" id="description-{{$local}}"
                                                      onkeypress="onTestChange()"

                                                      cols="30" rows="10"
                                                      class="form-control">{{  isset($description) &&is_array($description) && array_key_exists($local,$description)? $description[$local]: old("description[$local]")}}</textarea>
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
                                            class="btn btn-danger">{{ isset($product) ? t('Update'):t('Create') }}</button>&nbsp;
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


    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator->selector('#form_information') !!}
@endsection
