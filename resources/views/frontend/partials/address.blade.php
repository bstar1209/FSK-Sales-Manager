@inject('address', 'App\Models\Address')

@php
$address_info = $address->where([['user_info_id', '=', Auth::user()->customer->user_info_id], ['address_type', '=', $type + 1], ['address_index', '=', $index]])->first();
$flag = isset($address_info) ? true : false;
@endphp

<div class="col-3 address-template @if ($type==0) billing-address-{{ $index }} @else delivery-address-{{ $index }} @endif"
    @if ($flag) data-address_id="{{ $address_info->id }}" @endif
    >
    <h6>
        <strong>
            @if ($type == 0)
                請求先住所{{ $index }}
            @else
                納品先住所{{ $index }}
            @endif
        </strong>
    </h6>
    <hr>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">会社名(*)</span>
        </div>
        <input type="text" class="form-control billing-address-company-name" @if ($flag) value="{{ $address_info->comp_type }}" @endif>
    </div>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">名前</span>
        </div>
        <input type="text" class="form-control billing-address-names" @if ($flag) value="{{ $address_info->customer_name }}" @endif>
    </div>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">部署名</span>
        </div>
        <input type="text" class="form-control billing-address-department-name" @if ($flag) value="{{ $address_info->part_name }}" @endif>
    </div>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">郵便番号(*)</span>
        </div>
        <input type="text" class="form-control billing-address-zip-code" @if ($flag) value="{{ $address_info->zip }}" @endif>
    </div>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">都道府県(*)</span>
        </div>
        <input type="text" class="form-control billing-address-prefecture" @if ($flag) value="{{ $address_info->address1 }}" @endif>
    </div>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">市区町村(*)</span>
        </div>
        <input type="text" class="form-control billing-address-municipality" @if ($flag) value="{{ $address_info->address2 }}" @endif>
    </div>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">番地(*)</span>
        </div>
        <input type="text" class="form-control billing-address-address" @if ($flag) value="{{ $address_info->address4 }}" @endif>
    </div>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">ビル名</span>
        </div>
        <input type="text" class="form-control billing-address-building-name" @if ($flag) value="{{ $address_info->address3 }}" @endif>
    </div>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">TEL(*)</span>
        </div>
        <input type="text" class="form-control billing-address-tel" @if ($flag) value="{{ $address_info->tel }}" @endif>
    </div>
    <div class="input-group input-group-sm mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text text-truncate">FAX</span>
        </div>
        <input type="number" class="form-control billing-address-fax" @if ($flag) value="{{ $address_info->fax }}" @endif>
    </div>
    <div class="float-left">
        <button type="button" class="btn btn-primary btn-sm edit-address" data-index="{{ $index }}"
            data-customer="{{ Auth::user()->customer->user_info_id }}" data-type="{{ $type }}">
            @if ($flag)変更 @else 保存 @endif
        </button>
    </div>
    <div class="float-right">
        @if ($status && $flag)
            <button type="button" class="btn btn-secondary btn-sm text-white choice-btn @if ($type==0) billing-choice @else delivery-choice @endif"
                data-address="{{ $address_info }}"
                data-address_id="{{ $address_info->id }}">@lang('Choice')</button>
            @if ($type == 0)
                
            @endif
        @endif
    </div>
    @if ($status && $flag)
        @if ($type == 0)    
        <button type="button" class="btn btn-success btn-sm text-white copy-to-delivery-address"
            data-index="{{ $index }}" data-customer="{{ Auth::user()->customer->user_info_id }}"
            data-type="{{ $type }}">内容を納品先住所{{ $index }}へコピーする.</button>
        @endif
    @endif
</div>
