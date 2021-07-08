@extends('layouts.japon')
@section('title', '会社概要')
@section('keywords', 'ジビエレストラン,検索,問い合わせ')
@section('description', '問い合わせ')

@section('content')
@include('components.libon')
<div class="py-4">
		<div class="row p-3">
      <div class="col-md-2"></div>
      <div class="col-md-8 border-md-2">
        <table class="table table-striped">
          <tbody>
            <tr>
              <th class="h2"><i class="fas fa-phone"></i> 電話:</th>
              <td class="h2"><a href="tel:0426-33-9005">0426-33-9005</a></td>
            </tr>
            <tr>
              <th class="h2"><i class="fas fa-fax"></i> FAX:</th>
              <td class="h2">0426-32-8742</td>
            </tr>
            <tr>
              <th class="h2"><i class="far fa-envelope"></i> E-Mail:</th>
              <td class="h2"><a href="mailto:order@gibier-japon.com">order@gibier-japon.com</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
</div>
@endsection
