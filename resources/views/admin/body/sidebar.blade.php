<!-- partial:partials/_sidebar.html -->
<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
            APP -<span> TIMW</span>
        </a>

        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>
            <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>

            @if (Auth::user()->can('permission.menu'))
                <li class="nav-item nav-category">Role & Permission</li>
                <li class="nav-item {{ request()->is('*/permission') || request()->is('*/roles') ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#uiComponents" role="button"
                        aria-expanded="false" aria-controls="uiComponents">
                        <i class="link-icon" data-feather="feather"></i>
                        <span class="link-title">Role & Permission</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>

                    </a>
                    <div class="{{ request()->is('edit/permission/*') || request()->is('all/permission') || request()->is('add/permission') || request()->is('add/roles/permission') || request()->is('all/roles/permission') || request()->is('*/roles') || request()->is('admin/edit/roles/*') || request()->is('edit/roles/*') ? 'show' : 'collapse' }}"
                        id="uiComponents">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('all.permission') }}"
                                    class="nav-link {{ request()->is('all/permission') || request()->is('edit/permission/*') ? 'active' : '' }}">
                                    Permission</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('all.roles') }}"
                                    class="nav-link {{ request()->is('*/roles') || request()->is('edit/roles/*') ? 'active' : '' }}">
                                    Roles</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('add.roles.permission') }}"
                                    class="nav-link {{ request()->is('add/roles/permission') ? 'active' : '' }}">Add
                                    Role in Permission</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('all.roles.permission') }}"
                                    class="nav-link {{ request()->is('all/roles/permission') || request()->is('admin/edit/roles/*') ? 'active' : '' }}">
                                    Role in Permission</a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()->can('admin.menu'))
                <li class="nav-item {{ request()->is('admin') ? 'active' : '' }}">

                    <a class="nav-link" data-bs-toggle="collapse" href="#forms" role="button" aria-expanded="false"
                        aria-controls="forms">
                        <i class="link-icon" data-feather="inbox"></i>
                        <span class="link-title">Manage Admin</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>

                    <div class="{{ request()->is('*/admin') | request()->is('*/admin/*') ? 'show' : 'collapse' }}"
                        id="forms">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('all.admin') }}"
                                    class="nav-link {{ request()->is('*/admin') || 'edit/admin/*' ? 'active' : '' }}">
                                    Admin</a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()->can('employee.menu'))
                <li class="nav-item nav-category">Master</li>
            @endif

            @if (Auth::user()->can('category.menu'))
                @php
                    // Definisikan array URL
                    $urls = [
                        '*/category',
                        '*/category/*',
                        'all/category',
                        'edit/category/*',
                        'all/color',
                        'edit/color/*',
                        'all/employee',
                        'edit/employee/*',
                        'all/item',
                        'edit/item/*',
                        'all/itemvariant',
                        'edit/itemvariant/*',
                        'all/size',
                        'edit/size/*',
                        'all/supplier',
                        'edit/supplier/*',
                        'all/unit',
                        'edit/unit/*',
                        'all/rak',
                        'edit/rak/*',
                        '*/rak',
                    ];

                    // Fungsi untuk memeriksa apakah rute saat ini ada dalam array URL
                    $isActive = function ($urls) {
                        foreach ($urls as $url) {
                            if (request()->is($url)) {
                                return true;
                            }
                        }
                        return false;
                    };
                @endphp

                <li class="nav-item {{ $isActive($urls) ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#emailszeo" role="button" aria-expanded="false"
                        aria-controls="emailsze">
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">Master</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>

                    <div class="{{ $isActive($urls) ? 'show' : 'collapse' }}" id="emailszeo">
                        <ul class="nav sub-menu">

                            @if (Auth::user()->can('all.category'))
                                <li class="nav-item">
                                    <a href="{{ route('all.category') }}"
                                        class="nav-link {{ request()->is('all/category') || request()->is('edit/category/*') ? 'active' : '' }}">
                                        Category</a>
                                </li>
                            @endif

                            @if (Auth::user()->can('all.color'))
                                <li class="nav-item">
                                    <a href="{{ route('all.color') }}"
                                        class="nav-link {{ request()->is('all/color') || request()->is('edit/color/*') ? 'active' : '' }}">
                                        Color</a>
                                </li>
                            @endif

                            @if (Auth::user()->can('all.employee'))
                                <li class="nav-item">
                                    <a href="{{ route('all.employee') }}"
                                        class="nav-link {{ request()->is('all/employee') || request()->is('edit/employee/*') ? 'active' : '' }}">
                                        Employee</a>
                                </li>
                            @endif

                            @if (Auth::user()->can('all.item'))
                                <li class="nav-item">
                                    <a href="{{ route('all.item') }}"
                                        class="nav-link {{ request()->is('all/item') || request()->is('edit/item/*') ? 'active' : '' }}">
                                        Item</a>
                                </li>
                            @endif

                            @if (Auth::user()->can('all.rak'))
                                <li class="nav-item">
                                    <a href="{{ route('all.rak') }}"
                                        class="nav-link {{ request()->is('all/rak') || request()->is('*/rak') ? 'active' : '' }}">
                                        Rak</a>
                                </li>
                            @endif

                            @if (Auth::user()->can('all.size'))
                                <li class="nav-item">
                                    <a href="{{ route('all.size') }}"
                                        class="nav-link {{ request()->is('all/size') || request()->is('edit/size/*') ? 'active' : '' }}">
                                        Size</a>
                                </li>
                            @endif

                            @if (Auth::user()->can('all.supplier'))
                                <li class="nav-item">
                                    <a href="{{ route('all.supplier') }}"
                                        class="nav-link {{ request()->is('all/supplier') || request()->is('edit/supplier/*') ? 'active' : '' }}">
                                        Supplier</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('all.unit'))
                                <li class="nav-item">
                                    <a href="{{ route('all.unit') }}"
                                        class="nav-link {{ request()->is('all/unit') || request()->is('edit/unit/*') ? 'active' : '' }}">
                                        Unit</a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </li>
            @endif

            <li class="nav-item nav-category">Transaction</li>
            @if (Auth::user()->can('transaction.menu'))

                <li class="nav-item {{ request()->is('*/transaction') ? 'active' : '' }}">
                    @if (Auth::user()->can('all.transaction'))
                        <a class="nav-link" data-bs-toggle="collapse" href="#emailsyout" role="button"
                            aria-expanded="false" aria-controls="emailsyout">
                            <i class="link-icon" data-feather="check-circle"></i>
                            <span class="link-title">Transaction</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                    @endif
                    <div class="{{ request()->is('*/transaction') || request()->is('*/transaction/*') || request()->is('*/transaction') ? 'show' : 'collapse' }}"
                        id="emailsyout">
                        <ul class="nav sub-menu">

                            @if (Auth::user()->can('all.transaction'))
                                <li class="nav-item">
                                    <a href="{{ route('add.transaction') }}"
                                        class="nav-link {{ request()->is('add/transaction') ? 'active' : '' }}">Add
                                        Trasaction IN</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('all.transaction'))
                                <li class="nav-item">
                                    <a href="{{ route('add.transactionout') }}"
                                        class="nav-link {{ request()->is('add/transactionout') ? 'active' : '' }}">Add
                                        Trasaction OUT</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('all.transaction'))
                                <li class="nav-item">
                                    <a href="{{ route('all.transaction') }}"
                                        class="nav-link {{ request()->is('all/transaction') ? 'active' : '' }}">
                                        Transaction</a>
                                </li>
                            @endif

                        </ul>
                    </div>

                </li>

                @if (Auth::user()->can('cbd.menu'))
                    <li class="nav-item {{ request()->is('*/cbd') ? 'active' : '' }}">
                        <a class="nav-link position-relative" data-bs-toggle="collapse" href="#emailszeoc"
                            role="button" aria-expanded="false" aria-controls="emailsze">
                            <i class="link-icon" data-feather="box"></i>

                            <span class="link-title">CBD Order</span>
                            {{-- <span class="position-absolute start-50 mx-1 badge rounded-pill bg-danger">
                        40
                      </span> --}}

                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                        <div class="{{ request()->is('*/cbd') || request()->is('*/cbd/*') || request()->is('*/cbd') ? 'show' : 'collapse' }}"
                            id="emailszeoc">
                            <ul class="nav sub-menu">

                                @if (Auth::user()->can('all.cbd'))
                                    <li class="nav-item">
                                        <a href="{{ route('all.cbd') }}"
                                            class="nav-link {{ request()->is('all/cbd') || request()->is('edit/cbd/*') ? 'active' : '' }}">
                                            CBD</a>

                                    </li>
                                @endif

                            </ul>
                        </div>
                    </li>
                @endif

                <li
                    class="nav-item {{ request()->is('*/purchaserequest') || request()->is('edit/purchaserequest/*') || request()->is('*/purchaserequest/*')  ? 'active' : '' }}">
                    @if (Auth::user()->can('all.purchaserequest'))
                        <a class="nav-link" data-bs-toggle="collapse" href="#emailsyout1" role="button"
                            aria-expanded="false" aria-controls="emailsyout1">
                            <i class="link-icon" data-feather="check-circle"></i>
                            <span class="link-title">Purchase Request</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                    @endif
                    <div class="{{ request()->is('*/purchaserequest') || request()->is('*/purchaserequest/*') || request()->is('*/purchaserequest') || request()->is('*/photoreturn') || request()->is('*/serahterima') ? 'show' : 'collapse' }}"
                        id="emailsyout1">
                        <ul class="nav sub-menu">

                            @if (Auth::user()->can('purchaserequest.menu'))
                                <li class="nav-item">
                                    <a href="{{ route('all.purchaserequest') }}"
                                        class="nav-link {{ request()->is('all/purchaserequest') ? 'active' : '' }}">
                                        Purchase Request (PR)</a>
                                </li>
                            @endif

                        </ul>
                    </div>

                </li>

            @endif

        </ul>
    </div>
</nav>
