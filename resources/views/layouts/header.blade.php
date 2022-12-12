<!DOCTYPE html>
<html lang="en">
<head>

  @php
  $company=App\Models\CompanyInformations::first();
  @endphp
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{$company->company_name}}</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/jqvmap/jqvmap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('storage/adminlte/dist/css/adminlte.min.css')}}">
  {{-- simple calculator --}}
  <link rel="stylesheet" href="{{asset('storage/adminlte/dist/css/SimpleCalculadorajQuery.css')}}">
  {{-- <link rel="stylesheet" href="{{asset('storage/adminlte/dist/css/jquery.calculator.css')}}"> --}}
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/summernote/summernote-bs4.min.css')}}">
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('storage/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.4/sweetalert2.css" integrity="sha512-40/Lc5CTd+76RzYwpttkBAJU68jKKQy4mnPI52KKOHwRBsGcvQct9cIqpWT/XGLSsQFAcuty1fIuNgqRoZTiGQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="shortcut icon" href="{{asset('storage/icon/'.$company->icon)}}" type="image/x-icon">
  @yield('link')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav style='z-index:0;' class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav ">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      {{--  --}}
      @include('layouts.quickbutton')
      {{--  --}}
    </ul>
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
  

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i> <span class="ml-1">{{auth()->user()->name}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
          <a href="{{URL::to('/home')}}" class="dropdown-item">
            <i class="fa fa-home"></i>
            <span class="ml-2">home </span>
          </a>
          <a href="{{URL::to('/admin/password')}}" class="dropdown-item">
            <i class="fa fa-lock"></i>
            <span class="ml-2">Change Password </span>
          </a>
          <a class="dropdown-item" href="{{ route('logout') }}"
          onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
              <i class="fa fa-power-off"></i> <span class="ml-2">{{ __('Logout') }}</span>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
          </form>
        </div>
      </li>
      {{--  --}}
    </ul>
    {{-- <div class="float-right">
      <a href="{{URL::to('admin/invoice')}}" class="btn btn-warning" >
        Invoice 
      </a>
      <a href="{{URL::to('admin/purchase')}}" class="btn btn-warning" >
        Purchase
      </a>
      <a href="{{URL::to('admin/s-payment')}}" class="btn btn-warning" >
        Supplier Payment
      </a>
      <a href="{{URL::to('admin/c-receive')}}" class="btn btn-warning" >
        Customer Receive
      </a>
    </div> --}}
    
    <!-- SEARCH FORM -->
    <!-- Right navbar links -->
  </nav>
  <!-- /.navbar -->