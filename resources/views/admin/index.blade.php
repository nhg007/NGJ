@extends('layouts.admin')
@section('content')
<div class="container">

    <div>
    <form class="form-inline" action="" method="get">
        <div class="form-group mb-2">
            <label for="staticEmail2" class="sr-only">レストラン名</label>
            <input type="text"  name="name" class="form-control" id="staticEmail2" placeholder="レストラン名を入力してください">
        </div>
        　
        <button type="submit" class="btn btn-primary mb-2">検索</button>
    </form>

{{ $restrantlist->links("pagination::bootstrap-4") }}
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Address</th>
            <th scope="col" style="width: 200px;">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($restrantlist as $restrant)
        <tr>
            <th scope="row">1</th>
            <td>{{$restrant->name}}</td>
            <td>{{$restrant->pref}}{{$restrant->city}}{{$restrant->street}}</td>
            <td><a href="/admin/restrantLogin?id={{$restrant->id}}"  target="_blank" class="btn btn-success">ログイン</a></td>
        </tr>
        </tbody>
        @endforeach
    </table>
    {{ $restrantlist->links("pagination::bootstrap-4") }}
    </div>
</div>

@endsection
