<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
<script src="jquery-1.7.2.min.js"></script>
<?php
// 18 ソーシャルメディア対応、URLからindex.phpを除去（相対パス）
class Face {
	public $rx;
	public $ry;
	public $lx;
	public $ly;
	public $interval;
	public $size;
	public $rotation;
}

if(isset($_GET['imageUrl']) && $_GET['imageUrl']!='') {
	$imageUrl = $_GET['imageUrl'];
} else if(isset($_GET['twitterID']) && $_GET['twitterID']!='') {
	$imageUrl = 'http://gadgtwit.appspot.com/twicon/'.urlencode($_GET['twitterID']).'/original';
//	$imageUrl = 'http://api.osae.me/retwipi/'.urlencode($_GET['twitterID']).'/original';
}

if($imageUrl!='') {
	$faceUrl = 'http://detectface.com/api/detect?url='.urlencode($imageUrl).'&f=2';
	$xml = @simplexml_load_file($faceUrl);
	for ($iCounter = 0; $iCounter< count($xml->face); $iCounter++){
		$face[$iCounter] = new Face();
		$rxTmp = $xml->xpath('face[@id='.$iCounter.']/features/point[@id="PR"]/@x');
		$ryTmp = $xml->xpath('face[@id='.$iCounter.']/features/point[@id="PR"]/@y');
		$lxTmp = $xml->xpath('face[@id='.$iCounter.']/features/point[@id="PL"]/@x');
		$lyTmp = $xml->xpath('face[@id='.$iCounter.']/features/point[@id="PL"]/@y');
		$face[$iCounter]->rx = $rxTmp[0];
		$face[$iCounter]->ry = $ryTmp[0];
		$face[$iCounter]->lx = $lxTmp[0];
		$face[$iCounter]->ly = $lyTmp[0];
		$face[$iCounter]->interval = sqrt(pow($face[$iCounter]->lx-$face[$iCounter]->rx,2)+pow($face[$iCounter]->ly-$face[$iCounter]->ry,2));
		$face[$iCounter]->size = $face[$iCounter]->interval/2;
		$face[$iCounter]->rotation = atan2($face[$iCounter]->ly-$face[$iCounter]->ry,$face[$iCounter]->lx-$face[$iCounter]->rx);
		$test = ($xml->face[$iCounter]->xpath('//point[@id="PR"]/@x'));
	}
}

/*
$defaultSponsors = array('butchi.jp', 'カヤック', 'ブッチブログ');
if(isset($_GET['sponsor'])) {
	$sponsor[0] = ($_GET['sponsor'][0]!='')? $_GET['sponsor'][0] : $defaultSponsors[0];
	$sponsor[1] = ($_GET['sponsor'][1]!='')? $_GET['sponsor'][1] : $defaultSponsors[1];
	$sponsor[2] = ($_GET['sponsor'][2]!='')? $_GET['sponsor'][2] : $defaultSponsors[2];
} else {
	$sponsor = $defaultSponsors;
}
*/
?>
<link href="style.css" rel="stylesheet" type="text/css" />
<style>
<?php
for ($iCounter = 0; $iCounter<count($face); $iCounter++){
	print('#tei'.$iCounter.' {
	position: absolute;
	left:'.($face[$iCounter]->rx-$face[$iCounter]->size/2).'px;
	top:'.($face[$iCounter]->ry-$face[$iCounter]->size/2).'px;
	font-size:'.($face[$iCounter]->size).'px;
	-moz-transform: rotate('.($face[$iCounter]->rotation).'rad);
	-webkit-transform: rotate('.($face[$iCounter]->rotation).'rad);
	-o-transform: rotate('.($face[$iCounter]->rotation).'rad);
	-ms-transform: rotate('.($face[$iCounter]->rotation).'rad);
}
#kyo'.$iCounter.' {
	position: absolute;
	left:'.($face[$iCounter]->lx-$face[$iCounter]->size/2).'px;
	top:'.($face[$iCounter]->ly-$face[$iCounter]->size/2).'px;
	font-size:'.($face[$iCounter]->size).'px;
	-moz-transform: rotate('.($face[$iCounter]->rotation).'rad);
	-webkit-transform: rotate('.($face[$iCounter]->rotation).'rad);
	-o-transform: rotate('.($face[$iCounter]->rotation).'rad);
	-ms-transform: rotate('.($face[$iCounter]->rotation).'rad);
}
#sponsor'.$iCounter.' {
	font-size:'.($face[$iCounter]->size).'px;
	width:2000px;
}
');
}
?>
</style>
<script>
function chkUrl(){
	var checked = $('#urlRadio').attr('checked');
	$('#imageUrlText').attr('disabled', !checked);
	$('#twitterText').attr('disabled', checked);
}
function chkTwitter(){
	var checked = $('#twitterRadio').attr('checked');
	$('#imageUrlText').attr('disabled', checked);
	$('#twitterText').attr('disabled', !checked);
}
$(function(){
<?php
for ($iCounter = 0; $iCounter<count($face); $iCounter++){
	print('	$("#sponsor'.$iCounter.'").css({
		"position": "absolute",
		"font-size":"'.($face[$iCounter]->size).'px",
		"left": ('.(-sin($face[$iCounter]->rotation)*$face[$iCounter]->interval*1+($face[$iCounter]->rx+$face[$iCounter]->lx)/2).'-$(\'#sponsor'.$iCounter.'\').width()/2)+"px",
		"top": ('.(cos($face[$iCounter]->rotation)*$face[$iCounter]->interval*1+($face[$iCounter]->ry+$face[$iCounter]->ly)/2).'-$(\'#sponsor'.$iCounter.'\').height()/2)+"px",
		"width": "2000px",
		"text-align":"center",
		"-moz-transform": "rotate('.($face[$iCounter]->rotation).'rad)",
		"-webkit-transform": "rotate('.($face[$iCounter]->rotation).'rad)",
		"-o-transform": "rotate('.($face[$iCounter]->rotation).'rad)",
		"-ms-transform": "rotate('.($face[$iCounter]->rotation).'rad)"
	})'."\n");
}
if(count($face)==0 && $imageUrl!="") {
	print('	$("#result").text("顔が検出されませんでした。");'."\n");
	print('	$(".picture").bind("load", function() {
		var size = ($(".picture").width() < $(".picture").height())? $(".picture").width()*0.1 : $(".picture").width()*0.1;
		$(".tei").css({
			"position": "absolute",
			"left": 0.4*$(this).width()-size/2+"px",
			"top": 0.4*$(this).height()-size/2+"px",
			"font-size": size+"px"
		});
		$(".kyo").css({
			"position": "absolute",
			"left": 0.6*$(this).width()-size/2+"px",
			"top": 0.4*$(this).height()-size/2+"px",
			"font-size": size+"px"
		});
		$(".sponsor").css({
			"position": "absolute",
			"width": $(".picture").width()+"px",
			"top": 0.6*$(this).height()-size/2+"px",
			"text-align": "center",
			"font-size": size+"px"
		});
	});');
}
?>

});
</script>
<title>提供目ーカー</title>
</head>
<body>
<!-- Facebook -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- /Facebook -->

<header>
<h1>提供目ーカー</h1>
<ul class="social">
	<li><a href="http://b.hatena.ne.jp/entry/http://tekyomaker.onra.in/" class="hatena-bookmark-button" data-hatena-bookmark-title="提供目ーカー" data-hatena-bookmark-layout="standard" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only.gif" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a><script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script></li>
	<li><div class="fb-like" data-href="http://tekyomaker.onra.in/" data-send="false" data-layout="button_count" data-width="120" data-show-faces="false"></div></li>
	<li><a href="https://twitter.com/share" class="twitter-share-button" data-url="http://tekyomaker.onra.in/" data-lang="ja" data-hashtags="tekyomaker">ツイート</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>
    
</ul>
</header>

<div class="wrapper">
<div class="main">
<p>提供目ーカーは、誰でも簡単に<a href="http://dic.nicovideo.jp/a/%3C%E6%8F%90%3E%3C%E4%BE%9B%3E" target="_blank">提供目</a>をつくることができるサービスです。<br />
複数人が写っている写真にも対応しています。（<a href="./?type=twitter&twitterID=t_ishin&sponsor%5B%5D=%E5%A4%A7%E9%98%AA%E9%83%BD">サンプル1</a>）（サンプル2）</p>

<form name="form1" method="get" action="./">
	<ol><li>画像を選ぶ（PNG, JPEG）</li>
	<label><input id="urlRadio" type="radio" name="type" value="imageUrl" onclick="chkUrl()"<?php if($_GET['type']=='imageUrl' || !isset($_GET['type'])) print(' checked'); ?>>URLから生成
	<input id="imageUrlText" type="url" name="imageUrl" size="70" value="<?php print(($_GET['imageUrl']!='')? $_GET['imageUrl'] : ''); ?>"<?php if(isset($_GET['type']) && $_GET['type']!='imageUrl') print(' disabled'); ?> placeholder="画像のURLを貼り付けてください" onclick="chkUrl()" /></label><br />
	<label><input id="twitterRadio" type="radio" name="type" value="twitter" onclick="chkTwitter()"<? if($_GET['type']=='twitter') print(' checked'); ?> />Twitterアイコンから生成
	<input id="twitterText" type="text" name="twitterID" size="30" value="<?php print(($_GET['twitterID']!='')? $_GET['twitterID'] : '') ?>"<? if($_GET['type']!='twitter') print(' disabled'); ?> placeholder="Twitter IDを入力してください" /></label>
	<li>スポンサーを入力<a href="javascript:addSponsor();"><div class="addSponsorBtn">+スポンサーの追加（最大6社）</div></a></li>
	<div id="sponsorsInput">
		<div><input type="text" name="sponsor[]" <?php
			if(isset($_GET['sponsor'])) {
				print('value="'.$_GET['sponsor'][0].'"');
			} else {
				print('placeholder="例) 株式会社月極"');
			}
		?> /></div>
<?php
	// クエリーからのスポンサーの読み込み
	for($iCounter=1; $iCounter<count($_GET['sponsor']); $iCounter++) {
		print('		<div><input type="text" name="sponsor[]" value="'.$_GET['sponsor'][$iCounter].'" /></div>');
	}
?>
	</div>
	<div class="submit"><input type="submit" value="生成！" /></div>
	</ol>
</form>

<?php
if($imageUrl){
	print('<a href="http://twitter.com/home/?status='.urlencode(' http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING']).urlencode(' #tekyomaker ').'">&gt;&gt;この提供目をツイートする</a>');
	print('<div id="result"></div>
<div class="stage">
	<img class="picture" src="'.$imageUrl.'" />');
	for ($iCounter = 0; $iCounter< count($xml->face); $iCounter++){
		print('	<div class="teikyo" id="teikyo'.$iCounter.'">');
		print('<div class="tei" id="tei'.$iCounter.'">提</div>');
		print('<div class="kyo" id="kyo'.$iCounter.'">供</div>');
		print('<div class="sponsor" id="sponsor'.$iCounter.'">'.$_GET['sponsor'][$iCounter%count($_GET['sponsor'])].'</div>');
		print('</div>');
		print("\n");
	}

	if(count($face)==0 && $imageUrl!="") {
		// 顔が検出されなかったとき
		print('	<div class="teikyo" id="teikyo">
		<div class="tei">提</div>
		<div class="kyo">供</div>
		<div class="sponsor">'.$_GET['sponsor'][0].'</div>
	</div>');
	}
	
	print('</div>');
	
	print('<div class="attention">※注意 画像を保存しても提供は付きません。</div>');
}
?>

<div class="serviceAttention">※サービス内容は予告なく変更する場合があります。</div>

<!-- /.main --></div>

<div class="sub">
<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'search',
  search: '#tekyomaker',
  interval: 30000,
  title: '#tekyomaker',
  subject: 'みんなの提供目',
  width: 250,
  height: 300,
  theme: {
    shell: {
      background: '#336699',
      color: '#ffffff'
    },
    tweets: {
      background: '#ffffff',
      color: '#444444',
      links: '#1985b5'
    }
  },
  features: {
    scrollbar: false,
    loop: true,
    live: true,
    behavior: 'default'
  }
}).render().start();
</script>
<!-- /.sub --></div>

<footer>
<div class="subFooter">提供目ーカーは、<a href="http://onra.in/">音羅院一族</a>と、ゴランノス・ポンサーの提供でお送りしています。</div>
<div class="footer">Copyright 2012 音羅院一族</div>
</footer>

<!-- /.wrapper --></div>

<script>
function addSponsor() {
	if($('#sponsorsInput > div').size()<6) {
		$('#sponsorsInput').append('	<div><input type="text" name="sponsor[]" /></div>');
	}
}
</script>
</body>
</html>