<nav id="page-header" class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a id="header-rfq-link-btn" class="nav-link" href="{{ route('admin.rfq.index') }}" role="button">
                <span class="mr-2 d-none d-lg-inline text-gray-900 font-weight-bold font-size-1">RFQ (Q)</span>
            </a>
        </li>
        <li class="nav-item">
            <a id="header-estimate-link-btn" class="nav-link" href="{{ route('admin.quotation.index') }}"
                role="button">
                <span class="mr-2 d-none d-lg-inline text-gray-900 font-weight-bold font-size-1">@lang('Estimate')
                    (W)</span>
            </a>
        </li>
        <li class="nav-item">
            <a id="header-order-receive-link-btn" class="nav-link" href="{{ route('admin.order.index') }}"
                role="button">
                <span class="mr-2 d-none d-lg-inline text-gray-900 font-weight-bold font-size-1">@lang('Orders received') (R)</span>
            </a>
        </li>
        <li class="nav-item">
            <a id="header-order-link-btn" class="nav-link" href="{{ route('admin.ship_order.index') }}" role="button">
                <span class="mr-2 d-none d-lg-inline text-gray-900 font-weight-bold font-size-1">@lang('Order')
                    (Y)</span>
            </a>
        </li>
        <li class="nav-item">
            <a id="header-stock-link-btn" class="nav-link" href="{{ route('admin.stock.index') }}" role="button">
                <span class="mr-2 d-none d-lg-inline text-gray-900 font-weight-bold font-size-1">@lang('In stock')
                    (U)</span>
            </a>
        </li>
        <li class="nav-item">
            <a id="header-shipment-link-btn" class="nav-link" href="{{ route('admin.shipment.index') }}"
                role="button">
                <span class="mr-2 d-none d-lg-inline text-gray-900 font-weight-bold font-size-1">@lang('Shipment')
                    (A)</span>
            </a>
        </li>
        <li class="nav-item">
            <a id="header-manage-link-btn" class="nav-link" href="{{ route('admin.management.index') }}"
                role="button">
                <span class="mr-2 d-none d-lg-inline text-gray-900 font-weight-bold font-size-1">
                    @lang('Management screen') (M)</span>
            </a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span
                    class="mr-2 d-none d-lg-inline text-gray-900 font-weight-bold font-size-1">{{ auth()->user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown"
                style="padding: 20px">
                <h6 class="font-weight-bold default-color">アカウント</h6>
                <div class="row">
                    <div class="col-5">ID:</div>
                    <div class="col-7">1</div>
                </div>
                <div class="row">
                    <div class="col-5">表示名:</div>
                    <div class="col-7">{{ auth()->user()->name }}</div>
                </div>
                <div class="row">
                    <div class="col-5">メールアドレス:</div>
                    <div class="col-7">{{ auth()->user()->email }}</div>
                </div>
                <div class="row float-right" style="margin-top: 5px; padding: 0 12px">
                    <a class="btn btn-sm btn-primary" href="{{ route('logout') }}">
                        <i class="fa fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                        ログアウト
                    </a>
                </div>
            </div>
        </li>
    </ul>
</nav>
