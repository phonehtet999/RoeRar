<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    @php
        $user = auth()->user();
    @endphp

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('images/icon/roe-rar-icon-2.jpg') }}" width="50px" class="image-border-thumbnail">
        </div>
        <div class="sidebar-brand-text mx-3">Roe Rar</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @if (getUserType($user) == 'customer')
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-cart-plus"></i>
            <span>Products</span></a>
    </li>
    @else
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    @endif
    

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Pages Collapse Menu -->
    @if (getUserType($user) == 'staff')
        <li class="nav-item">
            @php
                $noCollapsed = (strpos(\Request::route()->getName(), 'brands') === 0 or
                    strpos(\Request::route()->getName(), 'categories') === 0 or
                    strpos(\Request::route()->getName(), 'suppliers') === 0 or
                    strpos(\Request::route()->getName(), 'staffs') === 0 or
                    strpos(\Request::route()->getName(), 'customers') === 0
                );
            @endphp
            <a class="nav-link {{ $noCollapsed ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>Adminstration</span>
            </a>
            
            <div id="collapseTwo" class="collapse {{ $noCollapsed ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Staff Adminstration:</h6>
                    <a class="collapse-item {{ strpos(\Request::route()->getName(), 'suppliers') === 0? 'active' : '' }}" href="{{ route('suppliers.index') }}">Suppliers</a>
                    <a class="collapse-item {{ strpos(\Request::route()->getName(), 'staffs') === 0? 'active' : '' }}" href="{{ route('staffs.index') }}">Staffs</a>
                    <a class="collapse-item {{ strpos(\Request::route()->getName(), 'customers') === 0? 'active' : '' }}" href="{{ route('customers.index') }}">Customers</a>
                    <a class="collapse-item {{ strpos(\Request::route()->getName(), 'brands') === 0? 'active' : '' }}" href="{{ route('brands.index') }}">Brands</a>
                    <a class="collapse-item {{ strpos(\Request::route()->getName(), 'categories') === 0? 'active' : '' }}" href="{{ route('categories.index') }}">Categories</a>
                </div>
            </div>
        </li>
    @endif

    @if (getUserType($user) == 'staff')
        <li class="nav-item">
            @php
                $noCollapsed = (
                    strpos(\Request::route()->getName(), 'products') === 0 or
                    strpos(\Request::route()->getName(), 'purchases') === 0
                );
            @endphp
            <a class="nav-link {{ $noCollapsed ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseThree"
                aria-expanded="true" aria-controls="collapseThree">
                <i class="fas fa-fw fa-industry"></i>
                <span>Inventory</span>
            </a>

            <div id="collapseThree" class="collapse {{ $noCollapsed ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Staff Inventory:</h6>
                    <a class="collapse-item {{ strpos(\Request::route()->getName(), 'products') === 0? 'active' : '' }}" href="{{ route('products.index') }}">Products</a>
                    <a class="collapse-item {{ strpos(\Request::route()->getName(), 'purchases') === 0? 'active' : '' }}" href="{{ route('purchases.index') }}">Purchases</a>
                </div>
            </div>
        </li>
    @endif

    @if (getUserType($user) == 'staff')
        <li class="nav-item">
            @php
                $noCollapsed = (
                    strpos(\Request::route()->getName(), 'sales') === 0 or
                    strpos(\Request::route()->getName(), 'deliveries') === 0 or
                    strpos(\Request::route()->getName(), 'payments') === 0
                );
            @endphp
            <a class="nav-link {{ $noCollapsed ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseFour"
                aria-expanded="true" aria-controls="collapseFour">
                <i class="fas fa-fw fa-truck"></i>
                <span>Sales and Deliveries</span>
            </a>

            <div id="collapseFour" class="collapse {{ $noCollapsed ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Customer Sales:</h6>
                    <a class="collapse-item {{ strpos(\Request::route()->getName(), 'sales') === 0? 'active' : '' }}" href="{{ route('sales.index') }}">Sales</a>
                    <a class="collapse-item {{ strpos(\Request::route()->getName(), 'deliveries') === 0? 'active' : '' }}" href="{{ route('deliveries.index') }}">Deliveries</a>
                    <a class="collapse-item {{ strpos(\Request::route()->getName(), 'payments') === 0? 'active' : '' }}" href="{{ route('payments.index') }}">Payments</a>
                </div>
            </div>
        </li>
    @endif

    @if (getUserType($user) == 'staff')
        <li class="nav-item">
            @php
                $noCollapsed = (
                    strpos(\Request::route()->getName(), 'sale_returns') === 0 or
                    strpos(\Request::route()->getName(), 'promotions') === 0
                );
            @endphp
            <a class="nav-link {{ $noCollapsed ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseFive"
                aria-expanded="true" aria-controls="collapseFive">
                <i class="fas fa-fw fa-truck"></i>
                <span>Returns and Promotions</span>
            </a>

            <div id="collapseFive" class="collapse {{ $noCollapsed ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Sale Returns:</h6>
                    <a class="collapse-item {{ strpos(\Request::route()->getName(), 'sale_returns') === 0? 'active' : '' }}" href="{{ route('sale_returns.index') }}">Sale Returns</a>
                    <a class="collapse-item {{ strpos(\Request::route()->getName(), 'promotions') === 0? 'active' : '' }}" href="{{ route('promotions.index') }}">Promotions</a>
                </div>
            </div>
        </li>
    @endif

    @if (getUserType($user) == 'customer')
        <li class="nav-item">
            @php
                $noCollapsed = (
                    strpos(\Request::route()->getName(), 'sales') === 0 or
                    strpos(\Request::route()->getName(), 'profile') === 0
                );
            @endphp
            <a class="nav-link {{ $noCollapsed ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseSix"
                aria-expanded="true" aria-controls="collapseSix">
                <i class="fas fa-fw fa-cog"></i>
                <span>Orders and Account</span>
            </a>

            <div id="collapseSix" class="collapse {{ $noCollapsed ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ strpos(\Request::route()->getName(), 'sales') === 0? 'active' : '' }}" href="{{ route('sales.index') }}">Orders</a>
                    <a class="collapse-item {{ strpos(\Request::route()->getName(), 'profile') === 0? 'active' : '' }}" href="{{ route('profile') }}">Your Profile</a>
                </div>
            </div>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>