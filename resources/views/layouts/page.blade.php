<!--
=========================================================
* Argon Dashboard - v1.2.0
=========================================================
* Product Page: https://www.creative-tim.com/product/argon-dashboard


* Copyright  Creative Tim (http://www.creative-tim.com)
* Coded by www.creative-tim.com



=========================================================
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <title>Son Thanh::Management tool</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img') }}/brand/favicon.png" type="image/png">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor') }}/nucleo/css/nucleo.css" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor') }}/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
    <!-- Argon CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/argon.css?v=1.2.0" type="text/css">
    <script src="{{ asset('assets/vendor') }}/jquery/dist/jquery.min.js"></script>
</head>

<body>
<!-- Sidenav -->
@include('layouts.bars.sidebar')
<!-- Main content -->
<div class="main-content" id="panel">
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <!-- Topnav -->
    @include('layouts.bars.topbar')
    <!-- Header -->
    <!-- Header -->
    @yield('breakscrum')
    <!-- Page content -->
    <div class="container-fluid mt--6">
        @yield('content')

        <!-- Footer -->
        @include('layouts.footers.nav')
    </div>
</div>
<!-- Argon Scripts -->
<!-- Core -->
<input type="hidden" value="{{Request::url()}}" id="current_route" />
<script src="{{ asset('assets/vendor') }}/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/vendor') }}/js-cookie/js.cookie.js"></script>
<script src="{{ asset('assets/vendor') }}/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="{{ asset('assets/vendor') }}/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
<script src="{{ asset('js/lib/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ asset('js/moment.min.js')}}"></script>
<script src="{{ asset('js/bootstrap-datetimepicker.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('js')
<!-- Argon JS -->
<script src="{{ asset('assets') }}/js/argon.js?v=1.2.0"></script>
<script src="{{ asset('js/system.js')}}"></script>
</body>

</html>
