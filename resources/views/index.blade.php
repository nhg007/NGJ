@extends('layouts.japon')
@section('title', 'Top')

@section('content')
@include('components.frame.navbar')
<main>
@include('components.mainvisual')
@include('components.menu')
</main>

@endsection
@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        utils.topSearchEvent()
    })
</script>
@endsection
