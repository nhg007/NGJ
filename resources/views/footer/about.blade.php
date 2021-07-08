@extends('layouts.japon')
@section('title', '会社概要')
@section('keywords', 'ジビエレストラン,検索,ジビエ・ジャポンについて')
@section('description', 'ジビエ・ジャポンの紹介')

@section('content')
@include('components.libon')
<div class="py-4">
		<div class="container">
			<div class="row p-3">
				<div class="col-md-3">
					<img class="img-fluid" src="/img/cuisine.jpg" alt="料理写真">
				</div>
				<div class="col-md-9 order-md-2">
					<p>
						<strong class="answel">「アナグマはどのレストランに行けば食べられる？」</strong>
					</p>
					<p>
						こんな疑問をもった食通やジビエ好きなお客様は、多いのではないでしょうか？
						ジビエジャポンは、そんなお客様の探求心を満足させるべく、
						<strong>今すぐジビエ料理が食べられるレストランを見つけることができるジビエ専門検索サイト</strong>です。
					</p>
					<p>これまで他のグルメサイトで、検索して</p>
					</p>
						<ul>
							<li>「実際に電話してみると10年前の話だった」</li>
							<li>「熊が食べたいのに熊本ラーメン店が表記される」</li>
							<li>「実際にジビエを仕入れていない」</li>
						</ul>
						などの経験がありませんか？　

						<p><strong>ジビエジャポン</strong>は、全国50か所の処理施設と1200店舗のレストランを仲介している
						<strong>日本最大のジビエ市場である</strong><a href="https://gibier-marche.com" class="font-weight-bold">
						ジビエマルシェ</a>の売買データと連動して、直近でジビエ料理が食べられるレストランをご案内致します。</p>

						<p>現在、ジビエジャポンには国内６０品目のジビエ食材が登場し、<strong>「食材」＋「地域」＋「ジャンル」</strong>
						を選択するだけで、その食材が食べられるレストランが瞬時に表示されます。</p>

						<p>今後は、<strong>「世界のジビエ」「美味しい爬虫類」「珍味な養殖食材」「未来の昆虫食」</strong>など食分野の多様性を追求する
						食通の方々への情報提供を目指します。</p>

						<p>お探しのジビエがあれば、ご気軽にジビエジャポンにお問い合わせください。<br>
						ご参加をご希望のレストランもまずはご一報ください。</p>
				</div>
			</div>
	</div>
</div>
@endsection
