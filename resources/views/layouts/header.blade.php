<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Modern admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities with bitcoin dashboard.">
    <meta name="keywords" content="admin template, modern admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
    <meta name="author" content="PIXINVENT">
    <title>Janata Bazar - Admin Dashboard</title>
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/weather-icons/climacons.min.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/fonts/meteocons/style.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/charts/morris.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/charts/chartist.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/charts/chartist-plugin-tooltip.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/components.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/fonts/simple-line-icons/style.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/timeline.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/dashboard-ecommerce.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/tables/datatable/datatables.min.css">

    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 2-columns   fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light bg-info navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                    <li class="nav-item"><a class="navbar-brand" href="{{url('/')}}"><img class="brand-logo" alt="modern admin logo" src="../../../app-assets/images/logo/logo.png">
                            <h3 class="brand-text">Janata Bazar</h3>
                        </a></li>
                    <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a></li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
                        <li class="nav-item nav-search"><a class="nav-link nav-link-search" href="#"><i class="ficon ft-search"></i></a>
                            <div class="search-input">
                                <input class="input" type="text" placeholder="Explore Modern..." tabindex="0" data-search="template-list">
                                <div class="search-input-close"><i class="ft-x"></i></div>
                            </div>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"><span class="mr-1 user-name text-bold-700">Admin</span><span class="avatar avatar-online"><img src="../../../app-assets/images/portrait/small/avatar-s-19.png" alt="avatar"><i></i></span></a>
                            <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="user-profile.html"><i class="ft-user"></i> Edit Profile</a>
                                <form action="{{route('logout')}}" method="POST">
                                    @csrf
                                    <button  class="btn" type="submit">Logout</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- END: Header-->
    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif
    
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>
    

    <!-- BEGIN: Main Menu-->

    <div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true">
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class=" nav-item"><a href="{{url('/')}}"><i class="la la-home"></i><span class="menu-title" data-i18n="Dashboard">Dashboard</span><span class="badge badge badge-info badge-pill float-right mr-2">3</span></a>
                </li>
                <li class=" nav-item"><a href="#"><i class="la la-user"></i><span class="menu-title" data-i18n="Users">Users</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('users')}}"><i></i><span data-i18n="Users List">Users List</span></a>
                        </li>
                        <li><a class="menu-item" href="{{route('add-user')}}"><i></i><span data-i18n="Users View">Add User</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="la la-user"></i><span class="menu-title" data-i18n="Users">Category And Itmes</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('category')}}"><i></i><span data-i18n="Users List">Category List</span></a>
                        </li>
                        <li><a class="menu-item" href="{{route('category')}}"><i></i><span data-i18n="Users List">Add Category</span></a>
                        </li>
                        <li><a class="menu-item" href="{{route('category')}}"><i></i><span data-i18n="Users List">Add Category items</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="la la-user"></i><span class="menu-title" data-i18n="Users">Store</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('store-list')}}"><i></i><span data-i18n="Users List">List</span></a>
                        </li>
                        <li><a class="menu-item" href="{{route('add- store')}}"><i></i><span data-i18n="Users List">Add New Store</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="la la-user"></i><span class="menu-title" data-i18n="Users">Delivery Partner</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('delivery-partner-list')}}"><i></i><span data-i18n="Users List">List</span></a>
                        </li>
                        <li><a class="menu-item" href="{{route('users')}}"><i></i><span data-i18n="Users List">Add Delivery Partner</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="la la-user"></i><span class="menu-title" data-i18n="Users">Daily Price</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('users')}}"><i></i><span data-i18n="Users List">Daily Price Upload</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="la la-user"></i><span class="menu-title" data-i18n="Users">Reports</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('users')}}"><i></i><span data-i18n="Users List">Item Wise</span></a>
                        </li>
                        <li><a class="menu-item" href="{{route('users')}}"><i></i><span data-i18n="Users List">Date Wise</span></a>
                        </li>
                        <li><a class="menu-item" href="{{route('users')}}"><i></i><span data-i18n="Users List">Per Day Wise</span></a>
                        </li>
                        <li><a class="menu-item" href="{{route('users')}}"><i></i><span data-i18n="Users List">Customer Wise Per Day</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="la la-user"></i><span class="menu-title" data-i18n="Users">Delivery Contact</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('users')}}"><i></i><span data-i18n="Users List">Contact List</span></a>
                        </li>
                        <li><a class="menu-item" href="{{route('users')}}"><i></i><span data-i18n="Users List">Add Contact</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="la la-user"></i><span class="menu-title" data-i18n="Users">Customers</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('users')}}"><i></i><span data-i18n="Users List">List</span></a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="la la-user"></i><span class="menu-title" data-i18n="Users">Order Details</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="{{route('users')}}"><i></i><span data-i18n="Users List">List</span></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>