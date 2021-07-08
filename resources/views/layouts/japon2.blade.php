<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>@yield('title') | GIBIER JAPON</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--   <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no"> -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <meta property='og:type' content='website'>
    <meta property='og:title' content='GIBIER JAPON'>
    <meta property='og:url' content='mmrensyu.html.xdomain.jp'>
    <meta property='og:description' content='ジビエ・ジャポン オフィシャルサイト'>
    <meta property="og:image" content="img/animal.jpg">
    <meta name="description" content="ジビエ・ジャポン オフィシャルサイト"/>
    <link rel="stylesheet" href="/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="/fontawesome/css/all.css">
    <link href="/css/chosen.css" rel="stylesheet">
    <link href="{{ asset('/css/reset.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/css/styleold.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="favicon.ico"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
    <script src="/js/search.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDK6GPBCxZEmYmCPckjSG4cjonOespvxE8&callback=utils.getCurrentPos&language=ja"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146674332-4"></script>

    <link rel="shortcut icon" href="favicon.ico">
    <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
</head>
<body>
<div id="wrapper">
    @include('components.top')
    @yield('content')
    @include('components.footer')
</div>
@yield('script')

<script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-146674332-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());
    gtag('config', 'UA-146674332-1');
</script>
<script src="{{ mix('js/app.js') }}" defer></script>
<script src="{{url('js/common.js')}}"></script>
<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-left",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>
<script>
    $(document).ready(function () {
        globalUtils.init();
    })
</script>
@stack('scripts')
</body>
</html>
