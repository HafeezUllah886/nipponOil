<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="https://designreset.com/cork/html/src/assets/img/favicon.ico"/>
    <link href="{{ asset('../layouts/horizontal-light-menu/css/light/loader.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('../layouts/horizontal-light-menu/css/dark/loader.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('../layouts/horizontal-light-menu/loader.js') }}"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('../src/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('../layouts/horizontal-light-menu/css/light/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('../layouts/horizontal-light-menu/css/dark/plugins.css') }}" rel="stylesheet" type="text/css" />
{{--     <link href="{{ asset('../src/assets/css/light/authentication/auth-boxed.css') }}" rel="stylesheet">
    <link href="{{ asset('../src/assets/css/dark/authentication/auth-boxed.css') }}" rel="stylesheet"> --}}
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="{{ asset('../src/assets/css/light/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('../src/assets/css/dark/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('src/assets/css/font-awesome.min.css') }}" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    {{--    data tables--}}
    <link rel="stylesheet" href="{{ asset('src/plugins/src/table/datatable/datatables.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/light/table/datatable/dt-global_style.css') }}">


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('src/plugins/css/dark/flatpickr/custom-flatpickr.css') }}">
    <link rel="stylesheet" href="{{ asset('src/plugins/css/light/flatpickr/custom-flatpickr.css') }}">
    <link rel="stylesheet" href="{{ asset('src/plugins/src/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{asset('src/assets/css/light/components/modal.css')}}">
    <link rel="stylesheet" href="{{asset('src/assets/css/dark/components/modal.css')}}">
    <style>
         input:required:invalid {
    border-color: red;
  }
  select:required:invalid {
    border-color: red;
  }
    </style>
    @yield('more-css')
</head>
<body class="layout-boxed enable-secondaryNav">
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->
    <div class="header-container container-xxl">
        <header class="header navbar navbar-expand-sm expand-header">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </a>
            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-text">
                    <a href="/home" class="nav-link"> <img src="{{asset('images/logo.jpeg')}}" width="200"> {{ auth()->user()->warehouse->name }} - {{ auth()->user()->name }}</a>
                </li>
            </ul>

            <ul class="navbar-item flex-row ms-lg-auto ms-0 action-area">
                <li class="nav-item theme-toggle-item">
                    <a href="{{ url('/todo') }}" class="btn btn-outline-success btn-rounded">Todo</a>
                </li>
                @can('Create Sale')
                <li class="nav-item theme-toggle-item">
                    <a href="{{ url('/pos') }}" class="btn btn-outline-primary btn-rounded">POS</a>
                </li>
                @endcan
                <li class="nav-item theme-toggle-item">
                    <a href="javascript:void(0);" class="nav-link theme-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon dark-mode"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun light-mode"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    </a>
                </li>
                <li class="nav-item dropdown notification-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class="badge badge-success d-none" id="indicator"></span>
                    </a>

                    <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                        <div class="drodpown-title message">
                            <h6 class="d-flex justify-content-between"><span class="align-self-center">Notifications</span> <span class="badge badge-primary" id="count">0</span></h6>
                        </div>
                        <div class="notification-scroll" id="notifications_container">
                                <p>Nothing to worry about</p>
                        </div>
                    </div>

                </li>

                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar-container">
                            <div class="avatar avatar-sm avatar-indicators avatar-online">
                                <img alt="avatar" src=" {{ asset('../src/assets/img/profile.jpeg') }} " class="rounded-circle">
                            </div>
                        </div>
                    </a>

                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="user-profile-section">
                            <div class="media mx-auto">
                                <div class="emoji me-2">
                                    &#x1F44B;
                                </div>
                                <div class="media-body">
                                    <h5>{{ Auth()->user()->name }}</h5>
                                    <p>@foreach (auth()->user()->getRoleNames() as $role)
                                        {{ $role }},
                                    @endforeach</p>
                                </div>
                            </div>
                        </div>
                       @can('View Users')
                        <div class="dropdown-item">
                            <a href="{{ url('/users') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span>Users</span>
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a href="{{ url('/profile/edit') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span>Edit Profile</span>
                            </a>
                        </div>
                        @endcan
                        <div class="dropdown-item">
                            <a href="#" id="addTask">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> <span>Display Message</span>
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a href="{{url('/backup')}}">
                               <svg id="Layer_1" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" data-name="Layer 1"><path d="m24 11.5a4.476 4.476 0 0 0 -1.706-3.5 4.481 4.481 0 0 0 -2.794-8h-15a4.481 4.481 0 0 0 -2.794 8 4.443 4.443 0 0 0 0 7 4.481 4.481 0 0 0 2.794 8h15a4.481 4.481 0 0 0 2.794-8 4.476 4.476 0 0 0 1.706-3.5zm-22-7a2.5 2.5 0 0 1 2.5-2.5h.5v1a1 1 0 0 0 2 0v-1h2v1a1 1 0 0 0 2 0v-1h8.5a2.5 2.5 0 0 1 0 5h-15a2.5 2.5 0 0 1 -2.5-2.5zm20 14a2.5 2.5 0 0 1 -2.5 2.5h-15a2.5 2.5 0 0 1 0-5h.5v1a1 1 0 0 0 2 0v-1h2v1a1 1 0 0 0 2 0v-1h8.5a2.5 2.5 0 0 1 2.5 2.5zm-17.5-4.5a2.5 2.5 0 0 1 0-5h.5v1a1 1 0 0 0 2 0v-1h2v1a1 1 0 0 0 2 0v-1h8.5a2.5 2.5 0 0 1 0 5z"/></svg><span>Download Database</span>
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>Log Out</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>

                </li>
            </ul>
        </header>
    </div>
<!--  END NAVBAR  -->

<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">

    <div class="overlay"></div>
    <div class="search-overlay"></div>

    <!--  BEGIN SIDEBAR  -->
    <div class="sidebar-wrapper sidebar-theme">
        <nav id="sidebar">
            <div class="navbar-nav theme-brand flex-row  text-center">
                <div class="nav-logo">
                    <div class="nav-item theme-logo">
                        <a href="index-2.html">
                            <img src="https://designreset.com/cork/html/src/assets/img/logo.svg" class="navbar-logo" alt="logo">
                        </a>
                    </div>
                    <div class="nav-item theme-text">
                        <a href="index-2.html" class="nav-link"> CORK </a>
                    </div>
                </div>
                <div class="nav-item sidebar-toggle">
                    <div class="btn-toggle sidebarCollapse">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
                    </div>
                </div>
            </div>
            <div class="shadow-bottom"></div>
            <ul class="list-unstyled menu-categories" id="accordionExample">
                <li class="menu active">
                    <a href="{{url('/home')}}" class="dropdown-toggle">
                        <div class="">
                            <img src="{{ asset('svgs/home.svg') }}" alt="">
                            <span></span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                </li>

                @can('View Sales')
                <li class="menu">
                    <a href="#sales" onclick="subMenu('sales')" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <img src="{{ asset('svgs/sale.svg') }}" alt="">
                            <span>Sale</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    {{-- <ul class="dropdown-menu submenu list-unstyled" id="sales" data-bs-parent="#accordionExample">
                        <li>
                            <a href="{{ route('sale.index') }}"> Sale </a>
                        </li>
                        <li>
                            <a href="{{ route('saleReturn.index') }}"> Sale Return </a>
                        </li>
                    </ul> --}}
                </li>
                @endcan
                @can('View Purchases')
                <li class="menu">
                    <a href="#purchase" onclick="subMenu('purchases')" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <img src="{{ asset('svgs/purchase.svg') }}" alt="">
                            <span>Purchase</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                  {{--   <ul class="dropdown-menu submenu list-unstyled" id="purchases" data-bs-parent="#accordionExample">
                        <li>
                            <a href="{{ route('purchase.index') }}"> Purchase </a>
                        </li>
                        <li>
                            <a href="{{ route('purchaseReturn.index') }}"> Purchase Return </a>
                        </li>

                    </ul> --}}
                </li>
                @endcan


                @canany(['View Accounts', 'View Deposit/Withdrawals', 'View Transfers'])
                <li class="menu">
                    <a href="#accounts" onclick="subMenu('accounts')" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <img src="{{ asset('svgs/accounts.svg') }}" alt="">
                            <span>Accounts</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                   {{--  <ul class="dropdown-menu submenu list-unstyled" id="" data-bs-parent="#accordionExample">
                        @can('View Accounts')
                           <li><a href="{{ route('account.index') }}"> Account </a></li>
                        @endcan
                        @can('View Deposit/Withdrawals')
                            <li><a href="{{url('/account/depositWithdrawals')}}"> Deposit / Withdrawals </a></li>
                        @endcan
                        @can('View Transfer')
                            <li><a href="{{url('/account/transfer')}}"> Transfer </a></li>
                        @endcan
                        @can('View Expenses')
                            <li><a href="{{url('/account/expense')}}"> Expense </a></li>
                        @endcan
                    </ul> --}}
                </li>
                @endcan


                @can('View Sales')
                    <li class="menu">
                        <a href="#" onclick="subMenu('stock')" {{-- data-bs-toggle="dropdown" aria-expanded="false" --}} class="dropdown-toggle">
                            <div class="">
                                <img src="{{ asset('svgs/stock.svg') }}" alt="">
                                <span>Stock</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        {{-- <ul class="dropdown-menu submenu list-unstyled" id="stocks" data-bs-parent="#accordionExample">
                            <li>
                                <a href="{{ route('stock.index') }}"> Stock </a>
                            </li>
                        </ul> --}}
                    </li>
                @endcan
                <li class="menu">
                    <a href="#reports" onclick="subMenu('reports')"  data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <img src="{{ asset('svgs/reports.svg') }}" alt="">
                            <span>Reports</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                   {{--  <ul class="dropdown-menu submenu list-unstyled" id="reports" data-bs-parent="#accordionExample">
                        <li>
                            <a href="{{ url('/reports/summaryReport') }}"> Summary Report </a>
                        </li>
                        <li>
                            <a href="{{ url('/reports/productsSummary') }}"> Products Summary </a>
                        </li>
                        <li>
                            <a href="{{ url('/reports/productExpiry') }}"> Expiry Report </a>
                        </li>
                    </ul> --}}
                </li>
                <li class="menu">
                    <a href="#hrm" onclick="subMenu('hrm')" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <img src="{{ asset('svgs/hrm.svg') }}" alt="">
                            <span>HRM</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                   {{--  <ul class="dropdown-menu submenu list-unstyled" id="hrm" data-bs-parent="#accordionExample">
                        <li>
                            <a href="{{ url('/hrm/employees') }}"> Employees </a>
                        </li>
                        <li>
                            <a href="{{ url('/hrm/attendance') }}"> Attendance </a>
                        </li>
                        <li>
                            <a href="{{ url('/hrm/payroll') }}"> Payroll </a>
                        </li>
                    </ul> --}}
                </li>
                <li class="menu">
                    <a href="#databases" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
                            <span>Database</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    <ul class="dropdown-menu submenu list-unstyled" id="databases" data-bs-parent="#accordionExample">
                        <li>
                            <a href="{{ route('reset') }}"> Reset Database </a>
                        </li>
                    </ul>
                </li>
                <li class="menu">
                    <a href="#extra" data-bs-toggle="dropdown" onclick="subMenu('extra')" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <img src="{{ asset('svgs/extra.svg') }}" alt="">
                            <span>Extras</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                  {{--   <ul class="dropdown-menu submenu list-unstyled" id="" data-bs-parent="#accordionExample">
                        @can('View Brands')
                        <li>
                            <a href="{{ route('brand.index') }}"> Brands </a>
                        </li>
                        @endcan
                        @can('View Categories')
                        <li>
                            <a href="{{ route('category.index') }}"> Categories </a>
                        </li>
                        @endcan
                        @can('View Warehouses')
                        <li>
                            <a href="{{ route('warehouse.index') }}"> Warehouses </a>
                        </li>
                        @endcan
                        @can('View Units')
                        <li>
                            <a href="{{ route('unit.index') }}"> Units </a>
                        </li>
                        @endcan
                        @can('View Products')
                            <li>
                                <a href="{{ route('product.index') }}"> Products </a>
                            </li>
                        @endcan
                    </ul> --}}
                </li>
            </ul>
        </nav>

    </div>


    <!--  END SIDEBAR  -->

    <!--  BEGIN CONTENT AREA  -->

    <div id="content" class="main-content">
        <div class="submenu row" style="background:#191e3a;">
            <div class="middle-content container-xxl mb-2 w-100" style="padding: 5px 0px; position:fixed;z-index:99; background:#191e3a;" id="subMenu">
            </div>
        </div>

        <div class="middle-content container-xxl p-2" id="container">
            @if(session('message'))
                <div class="alert alert-success" role="alert">
                    <i class="fa-solid fa-circle-check alert-link"></i> {{ session('message') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    <i class="fa-solid fa-circle-xmark alert-link"></i> {{ session('error') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning" role="alert">
                    <i class="fa-solid fa-triangle-exclamation alert-link"></i> {{ session('warning') }}
                </div>
            @endif
            @yield('content')
{{--            <div class="p-2"></div>--}}
        </div>
        <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModal" aria-hidden="true">
            <div class="modal-dialog modal-md"> <!-- Add "modal-dialog-white" class -->
                <div class="modal-content" style="background-color: white; color: #000000"> <!-- Add "modal-content-white" class -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="payModalLabel" style="color: black; font-weight: bold">Display Message</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="{{ url('/message/store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="date">Auto Delete Date</label>
                               <input type="date" name="date" id="date" min="{{ date('Y-m-d') }}" required class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label for="msg">Message</label>
                               <textarea name="msg" id="msg" class="form-control" cols="30" required rows="10"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input class="btn btn-primary" type="submit" value="Save">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--  BEGIN FOOTER  -->
        <div class="footer-wrapper">
            <div class="footer-section f-section-1">
                <p class="">Copyright © <span class="dynamic-year">2023</span> <a href="#">Diamond Software House</a>, All rights reserved.</p>
            </div>
            <div class="footer-section f-section-2">
                <p class="">Diamond Software House <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></p>
            </div>
        </div>
        <!--  END FOOTER  -->
    </div>
    <!--  END CONTENT AREA  -->
</div>
<!-- END MAIN CONTAINER -->
<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src=" {{ asset('../src/bootstrap/js/bootstrap.bundle.min.js') }} "></script>
<script src=" {{ asset('../src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js') }} "></script>
<script src=" {{ asset('../src/plugins/src/mousetrap/mousetrap.min.js') }} "></script>
<script src=" {{ asset('../src/plugins/src/waves/waves.min.js') }} "></script>
<script src=" {{ asset('../layouts/horizontal-light-menu/app.js') }} "></script>

<!-- Datatables -->
<script src="{{ asset('src/assets/js/jquery.min.js') }}" ></script>
<script src="{{ asset('src/plugins/src/table/datatable/datatables.js') }}" ></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="{{ asset('src/plugins/src/bootstrap-select/bootstrap-select.min.js') }}"></script>

    @yield('more-script')
<script>
$(document).ready(function(){
    var notification_HTML = "";
    var color = "";
    $.ajax({
        url: "{{ url('/notifications/get') }}",
        method: 'GET',
        success: function(notifications){
            if(notifications.length > 0){
                $("#count").text(notifications.length);
                $("#indicator").removeClass("d-none");
                notifications.forEach(function(n){
                    if(n.level == "high")
                    {
                        color = "text-danger";
                    }
                    if(n.level == "medium")
                    {
                        color = "text-warning";
                    }
                    if(n.level == "low")
                    {
                        color = "text-info";
                    }
                    let url = "{{ url('/todo') }}";
                    notification_HTML += '<div class="dropdown-item" id="notif_'+n.id+'">';
                    notification_HTML += '<div class="media">';
                    notification_HTML += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon '+ color +'"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>';
                    notification_HTML += '<div class="media-body">';
                    notification_HTML += '<a href="'+url+'">';
                    notification_HTML += '<div class="data-info">';
                    notification_HTML += '<h6 class="">'+n.content+'</h6>';
                    notification_HTML += '<p class="text-danger">'+n.date+'</p></div></a>';
                    notification_HTML += '<div class="icon-status" onclick="markAsRead('+n.id+')">';
                    notification_HTML += '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
                    notification_HTML += '</div></div></div></div></div>';
                });
                $("#notifications_container").html(notification_HTML);
            }
            else{
                $("#notifications_container").addClass('d-flex align-items-center justify-content-center');
            }
        }
    });
});

function markAsRead(id){
    $("#notif_"+id).hide();
    $.ajax({
        url: "{{ url('/notifications/markAsRead/') }}/"+id,
        method: "get",
    });
}
$("#addTask").on("click", function(){

        $("#addTaskModal").modal("show");
    });
    function getCurrentDate() {
        const currentDate = new Date();
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        const day = String(currentDate.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
     $('.display').DataTable({
         "ordering": false
     });

    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    function subMenu(name){
        $(".submenu").css("height","50px");
        var subMenu = $("#subMenu");
        var menuHTML = null;
        if(name == "extra"){
            menuHTML = '@can("View Products")<a href="{{ route("product.index") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/product.svg") }}"> Products</a>@endcan';
            menuHTML += '@can("View Warehouses")<a href="{{ route("warehouse.index") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/warehouse.svg") }}"> Warehouses</a>@endcan';
            menuHTML += '@can("View Brands")<a href="{{ route("brand.index") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/brand.svg") }}"> Brands</a>@endcan';
            menuHTML += '@can("View Categories")<a href="{{ route("category.index") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/category.svg") }}"> Categories</a>@endcan';
            menuHTML += '@can("View Units")<a href="{{ route("unit.index") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/unit.svg") }}"> Units</a>@endcan';
            menuHTML += '<a href="{{ url("/visits") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/visits.svg") }}"> Visits</a>';
/*             menuHTML += '<a href="{{ url("/repair") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/repair.svg") }}"> Repair</a>'; */
            menuHTML += '<a href="{{ url("/target") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/target.svg") }}"> Target</a>';
        }
        if(name == "accounts"){
            menuHTML = '@can("View Accounts")<a href="{{ url("/account/index/business") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/accounts.svg") }}"> Business</a>@endcan';
            menuHTML += '@can("View Accounts")<a href="{{ url("/account/index/supplier") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/accounts.svg") }}"> Suppliers</a>@endcan';
            menuHTML += '@can("View Accounts")<a href="{{ url("/account/index/customer") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/accounts.svg") }}"> Customers</a>@endcan';
            menuHTML += '@can("View Deposit/Withdrawals")<a href="{{ url("/account/depositWithdrawals") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/depositWithdraw.svg") }}"> Deposit/Withdrawals</a>@endcan';
            menuHTML += '@can("View Transfer")<a href="{{ url("/account/transfer") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/transfer.svg") }}"> Transfer</a>@endcan';
            menuHTML += '@can("View Expenses")<a href="{{ url("/account/expense") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/expense.svg") }}"> Expenses</a>@endcan';
           /*  menuHTML += '<a href="{{ url("/account/fixedExpenses") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/expense2.svg") }}"> Fixed Expenses</a>'; */
        }
        if(name == "purchases"){
            menuHTML = '<a href="{{ route("purchase.create") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/purchase.svg") }}">Create Purchase</a>';
            menuHTML += '<a href="{{ route("purchase.index") }}" class="btn btn-dark m-2"><img src="{{ asset("svgs/purchaseHistory.svg") }}"> Purchase History</a>';
            menuHTML += '<a href="{{ route("purchaseReturn.index") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/purchaseReturn.svg") }}"> Purchase Return</a>';
        }
        if(name == "sales"){
            menuHTML = '<a href="{{ url("/sale/create") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/sale.svg") }}"> Create Sale</a>';
            menuHTML += '<a href="{{ url("/sale/1/1/0") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/saleHistory.svg") }}">Sale History</a>';
            menuHTML += '<a href="{{ route("saleReturn.index") }}" class="btn btn-dark m-2"><img src="{{ asset("svgs/saleReturn.svg") }}">Sale Return</a>';
            menuHTML += '<a href="{{ url("/quotation") }}" class="btn btn-dark m-2"><img src="{{ asset("svgs/quotation.svg") }}">Quotation</a>';
        }
        if(name == "reports"){
            menuHTML = '<a href="{{ url("/reports/summaryReport") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/summaryReport.svg") }}">Summary Report</a>';
            menuHTML += '<a href="{{ url("/reports/productsSummary") }}" class="btn btn-dark m-2"><img src="{{ asset("svgs/productSummary.svg") }}">Products Summary</a>';
            menuHTML += '<a href="{{ url("/reports/productExpiry") }}" class="btn btn-dark m-2"><img src="{{ asset("svgs/expiryReport.svg") }}">Expiry Report</a>';
            menuHTML += '<a href="{{ url("/reports/lowStock") }}" class="btn btn-dark m-2"><img src="{{ asset("svgs/lowStock.svg") }}">Low Stock</a>';
            menuHTML += '<a href="{{ url("/reports/profitLoss") }}" class="btn btn-dark m-2"><img src="{{ asset("svgs/profitLoss.svg") }}">Profit / Loss</a>';
            menuHTML += '<a href="{{ url("/reports/customerBalance") }}" class="btn btn-dark m-2"><img src="{{ asset("svgs/profitLoss.svg") }}">Customers Balance</a>';
            menuHTML += '<a href="{{ url("/reports/taxReport") }}" class="btn btn-dark m-2"><img src="{{ asset("svgs/profitLoss.svg") }}">Tax</a>';
        }
        if(name == "hrm"){
            menuHTML = '<a href="{{ url("/hrm/employees") }}" class="btn btn-dark m-2"><img src="{{ asset("svgs/employees.svg") }}">Employees</a>';
            menuHTML += '<a href="{{ url("/hrm/attendance") }}" class="btn btn-dark m-2"><img src="{{ asset("svgs/attandance.svg") }}">Attandance</a>';
            menuHTML += '<a href="{{ url("/hrm/payroll") }}" class="btn btn-dark m-2"><img src="{{ asset("svgs/payroll.svg") }}">Payroll</a>';
            menuHTML += '<a href="{{ url("/hrm/advances") }}" class="btn btn-dark m-2"><img src="{{ asset("svgs/advance.svg") }}">Advances</a>';
        }
        if(name == "stock"){
            menuHTML = '<a href="{{ route("stock.index") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/stock.svg") }}"> Stock</a>';
            menuHTML += '<a href="{{ url("/stock/transfer") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/stockTransfer.svg") }}"> Stock Transfer</a>';
            menuHTML += '<a href="{{ url("/obsolete") }}" class="btn btn-dark m-2"> <img src="{{ asset("svgs/obselete.svg") }}"> Obsolete Inventory</a>';
        }
        subMenu.html(menuHTML);
    }
</script>
</body>
</html>
