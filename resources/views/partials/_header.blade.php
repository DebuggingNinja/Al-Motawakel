<!doctype html>
<html style="  @if(isset($settings['colorPrimary']))
--color-primary: {{ $settings['colorPrimary'] }};
@endif

@if(isset($settings['colorPrimaryRgba']))
--color-primary-rgba: {{ $settings['colorPrimaryRgba'] }};
@endif

@if(isset($settings['bgPrimaryHover']))
--bg-primary-hover: {{ $settings['bgPrimaryHover'] }};
@endif

@if(isset($settings['headerBg']))
--header-bg: {{ $settings['headerBg'] }};
@endif

@if(isset($settings['logoWrapperBg']))
--logo-wrapper-bg: {{ $settings['logoWrapperBg'] }};
@endif
@endphp;" lang="" {{ str_replace('_', '-' , app()->getLocale()) }}" dir="{{ (Session::get('layout')=='rtl' ? 'rtl' :
'ltr')
}}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="@yield('description')" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Sama AL-Mutewkl | @yield('title')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <style>
    .table-responsive{
      max-height: 70vh;
    }
  </style>
  @if(app()->getLocale() == 'ar')
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"
    rel="stylesheet">
  <style>
    * {
      font-family: "Tajawal", sans-serif;
    }

    h4,
    h1,
    h2,
    h3,
    h5,
    h6 {
      font-family: "Tajawal", sans-serif !important;
    }
  </style>


  @endif


  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset( 'assets/css/plugin' . Helper::rlt_ext() . '.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style' . Helper::rlt_ext() . '.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/variables' . Helper::rlt_ext() . '.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app' . Helper::rlt_ext() . '.min.css') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('logo.png') }}">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.0/css/line.css">
  <!-- toastr v2.1.3 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
  <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
  <style>

    .table-responsive .table thead th {
      position: sticky;
      top: 0;
      background-color: #fff; /* or your desired color */
      z-index: 1;
    }

  </style>
  @yield('style')
</head>
