@inject('order_header', 'App\Models\OrderHeader')
@php
$date = date('Y-m-d', strtotime("-". $period ." Months"));

$order_query = $order_header
    ->with('order_details')
    ->where('customer_id', '=', Auth::user()->customer->id)
    ->whereHas('order_details', function ($query) use ($date) {
        $query->whereDate('created_at', '>', $date);
    })
    ->orderBy('receive_order_date', 'desc');

if ($order_date && $order_date != '') {
    $order_query = $order_query->whereHas('order_details', function ($query) use ($order_date) {
        $query->whereDate('receive_order_date', '=', $order_date);
    });
}

$order_list = $order_query->get();
@endphp

<table class="table table-bordered table-striped table-sm mb-2" id="order-table" cellspacing="0" tabindex="0">
    <thead>
        <tr>
            <th>注文日</th>
            <th>注文番号</th>
            <th>型番</th>
            <th>メーカー</th>
            <th>注文数</th>
            <th>単価</th>
            <th>小計</th>
            <th>目安納期</th>
            <th></th>
        </tr>
    </thead>
    <tbody>

        @if (count($order_list) == 0)
            <td class="text-center" colspan="9">テーブル内のデータなし.</td>
        @else
            @foreach ($order_list as $header)
                @php
                    $order_detail_list = $header->order_details->where('solved', '=', 0);
                    
                    if ($order_number && $order_number != '') {
                        $order_detail_list = $order_detail_list->filter(function ($item, $key) use ($order_number) {
                            return str_contains($item->order_no_by_customer, $order_number);
                        });
                    }
                    
                    if ($model_number && $model_number != '') {
                        $order_detail_list = $order_detail_list->filter(function ($item, $key) use ($model_number) {
                            return str_contains($item->katashiki, $model_number);
                        });
                    }
                @endphp
                @foreach ($order_detail_list as $key => $detail)
                    <tr data-rowinfo="{{ $order_detail_list }}" data-order-num="{{ $header->order_no_by_customer }}"
                        data-payment="{{ $header->cond_payment }}" @if ($detail->send_address) data-send-address="{{ $detail->send_address->address1 }}" @endif @if ($detail->request_address)
                        data-request-address="{{ $detail->request_address->address1 }}" @endif
                        data-tax="{{ $header->tax_info->tax }}" data-fee-shipping="{{ $header->fee_shipping }}">
                        <td>{{ date_create($detail->created_at)->format('Y-m-d') }}</td>
                        <td>{{ $detail->order_no_by_customer }}</td>
                        <td>{{ $detail->katashiki }}</td>
                        <td>{{ $detail->maker }}</td>
                        <td>{{ number_format($detail->sale_qty) }}</td>
                        <td>{{ number_format($detail->sale_cost) }} 円</td>
                        <td>{{ number_format($detail->sale_qty * $detail->sale_cost) }}</td>
                        <td>{{ date_create($detail->est_date)->format('Y-m-d') }}</td>
                        @if ($loop->first)
                            <td rowspan="{{ $order_detail_list->where('solved', '=', 0)->count() }}"><a
                                    class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#order-detail-modal">詳細</a></td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        @endif
    </tbody>
</table>
