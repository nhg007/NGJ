@extends('layouts.japon')
@section('title', '会社概要')
@section('keywords', 'ジビエレストラン,検索,会社概要')
@section('description', 'ジビエジャポンの運営会社')

@section('content')
@include('components.libon')
<div class="py-4">
		<div class="container">
			<div class="row">
				<div class="col-sm-3"></div>
				<div class="col-sm-6">
	 				<div class="card border-dark mb-3">
  						<div class="card-body text-dark">
    						<h4 class="card-title">社名　合同会社ワイルドライフ</h4>
								<p class="card-text">〒192-0911</br>
								東京都八王子市打越町333-2ＴＫ北野ビル３階</br>
								TEL : <a href="tel:0426-33-9005">0426-33-9005</a></br>
								FAX : 0426-32-8742</br>
								Mail:<a href="mailto:order@gibier-japon.com">order@gibier-marche.com</a></br>
							</p>
  						</div>
						<div class="card-footer">
							<dl class="row">
								<dt class="col-sm-3">設 立</dt>
								<dd class="col-sm-9">令和元年４月</dd>
								<dt class="col-sm-3">資本金</dt>
								<dd class="col-sm-9">100万円</dd>
								<dt class="col-sm-3">代表者</dt>
								<dd class="col-sm-9">代表社員　高橋　潔</dd>
								<dt class="col-sm-3">従業員</dt>
								<dd class="col-sm-9">６名</dd>
								<dt class="col-sm-3">金融機関</dt>
								<dd class="col-sm-9">三菱UFJ銀行　八王子支店</dd>
							</dl>
 						</div>
					</div>
				</div>
			<div class="col-sm-3"></div>
		</div>
	</div>

@endsection
