@inject('fee_shipping', 'App\Models\FeeShipping')
@inject('daibiki', 'App\Models\Daibiki')

@php
$fee_shipping_list = $fee_shipping->all();
$daibiki_list = $daibiki->all();
@endphp

@extends('layouts.frontend.page')

@section('title', '送料、代引き手数料')

@section('main-container')
    <div class="page-content mt-5" style="padding: 10px 10px 20px">
        <div class="row">
            <div class="col-12">
                <h4 class="page-title text-primary">@lang('Shipping and cash on delivery fees')</h4>
                <hr>
            </div>
        </div>
        <div class="page-detail">
            <div class="row mt-3">
                <div class="col-12 table-responsive">
                    <table class="table table-bordered table-striped table-sm mb-2" id="order-table" cellspacing="0"
                        tabindex="0">
                        <thead>
                            <tr>
                                <th colspan="3" style="font-size: 20px !important">送料(税別)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th rowspan="{{ count($fee_shipping_list) + 1 }}">@lang('Product fee less than 20000 yen (excluding tax)')</th>
                                <th>@lang('Area')</th>
                                <th>@lang('Shipping (excluding tax)')</th>
                            </tr>
                            @foreach ($fee_shipping_list as $item)
                                <tr>
                                    <td>{{ $item->area }}</td>
                                    <td>{{ $item->fee }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>@lang('Product fee 20000 yen (excluding tax) or more')</td>
                                <td>全国</td>
                                <td>無料</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 table-responsive mt-5">
                    <table class="table table-bordered table-striped table-sm mb-2" id="order-table" cellspacing="0"
                        tabindex="0">
                        <thead>
                            <tr>
                                <th>@lang('Cash on delivery (Yamato collect service)')</th>
                                <th>@lang('Fee (excluding tax) Frontend')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($daibiki_list as $item)
                                <tr>
                                    <td>{{ $item->information }}</td>
                                    <td>{{ $item->fee }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('vendor/ckeditor/adapters/jquery.js') }}"></script>
    <script>
        $(function() {});
    </script>
@endsection
