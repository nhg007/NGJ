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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="/fontawesome/css/all.css">
    <link href="/css/chosen.css" rel="stylesheet">
    <link href="{{ asset('/css/reset.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/css/styleold.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="favicon.ico"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
    <script src="/js/layer/layer.js"></script>
    <script src="/js/search.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146674332-4"></script>
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
</head>
</html>
