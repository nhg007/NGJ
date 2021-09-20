<html lang="ja">
@include('components.frame.head')
@stack('css')
<body>
  @include('components.frame.logo')
<div id="wrapper">
    @yield('content')
    @include('components.footer')
</div>
@yield('script')

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
