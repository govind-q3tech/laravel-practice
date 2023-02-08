<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
        <meta name="viewport"  content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>{{ config("get.PAGE_TITLE") }} | @yield('title')</title>
          <!-- Meta -->
    <meta name="title" property="title" content="@yield('title')">
    <meta name="description" property="description" content="@yield('meta_description')">
    <meta name="keywords" property="keywords" content="@yield('meta_keywords')">
         
    {{--<meta name="description" content="@yield('meta_description')">
    <meta name="author" content="Market Place">
    <meta property="og:title" content="@yield('title')">
    
    <meta property="og:image:width" content="480">
    <meta property="og:image:height" content="283">
    <meta property="og:image" content="@hasSection('ogimage') @yield('ogimage')@else {{ asset('images/logo.png') }}@endif">

    <meta property="og:url" content="{{ route('frontend.home') }}">--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
	<link rel="stylesheet" href="{{ asset('css/front/bootstrap.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/front/fontawesome.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('plugins/jquery-confirm-v3.3.4/css/jquery-confirm.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/front/custom.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/front/responsive.css') }}" >
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/front/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/front/developer.css') }}" >
	
    
    <script src="{{ asset('js/minfied.js') }}" ></script> 
    <script src="{{ asset('js/moment.min.js') }}"></script>
    
    @stack('styles')
    <!-- Scripts -->
    @livewireStyles
    <!-- </script> -->
{{-- <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=61aef8f040cc1b001996c093&product=sop' async='async'></script> --}}
</head>
<?php
$currentRoutes = Route::currentRouteName();
$bodyClass = '';
if($currentRoutes == 'frontend.login' || 
$currentRoutes == 'frontend.forgot-password' || 
$currentRoutes == 'frontend.register' || 
$currentRoutes == 'frontend.password.reset' ||
$currentRoutes == 'frontend.advertisements.create-step-one' ||
$currentRoutes == 'frontend.advertisements.create-step-two' ||
$currentRoutes == 'frontend.advertisements.edit-step-one' ||
$currentRoutes == 'frontend.advertisements.edit-step-two'){
    $bodyClass = 'inner-area';
}
?>

<body class="{{ $bodyClass }} {{ auth()->check()?'login-user':'' }}">
    <x-elements.front.header />
        <!--bredcrumb start-->
        {{ $breadcrumb ?? '' }}
        <!--bredcrumb end-->
        <div style="text-align: center;width:100%">Coming Soon!!</div>
        {{ $content ?? '' }}
    <x-elements.front.footer /> 
    <!-- Scripts -->
    <script src="{{ asset('js/front/jquery-3.5.1.min.js') }}"></script> 
    <script src="{{ asset('js/front/bootstrap.bundle.min.js') }}" ></script> 
    <script src="{{ asset('js/front/viewportchecker.js') }}"></script> 
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('js/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-confirm-v3.3.4/js/jquery-confirm.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script> 
    <script src="{{ asset('js/front/custom.js') }}"></script>
    @include('flash.popup-alert')
    @stack('scripts')
    {{--@livewireScripts--}}
</body>

</html>