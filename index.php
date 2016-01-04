<!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
<link rel="icon" href="favicon.ico" type="image/x-icon" />
<link rel="Shortcut Icon" type="image/x-icon" href="favicon.ico" />
<link href="style.css" rel="stylesheet" type="text/css" />
<title>提供目ーカー</title>
<!-- Google Analytics -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-33131588-1']);
  _gaq.push(['_setDomainName', 'onra.in']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!-- /Google Analytics -->
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
<p><a href="./">提供目ーカー</a>は、誰でも簡単に<a href="http://dic.nicovideo.jp/a/%3C%E6%8F%90%3E%3C%E4%BE%9B%3E" target="_blank">提供目</a>をつくることができるサービスです。<br />
複数人が写っている写真にも対応しています。（<a href="./?type=twitter&twitterID=t_ishin&sponsor%5B%5D=%E5%A4%A7%E9%98%AA%E9%83%BD">サンプル1</a>）（<a href="./?type=imageUrl&imageUrl=http%3A%2F%2Fnews.walkerplus.com%2F2011%2F0917%2F2%2F20110915171944_00_400.jpg&sponsor%5B%5D=TATSUYA+KAWAGOE&sponsor%5B%5D=%E5%90%89%E9%87%8E%E5%AE%B6">サンプル2</a>）</p>

<form name="form1" method="get" action="./">
	<ol><li>画像を選ぶ（PNG, JPEG）</li>
	<label><input id="urlRadio" type="radio" name="type" value="imageUrl" onclick="chkUrl()">URLから生成
	<input id="imageUrlText" type="url" name="imageUrl" size="70" value="" placeholder="画像のURLを貼り付けてください" onclick="chkUrl()" /></label><br />
	<label><input id="twitterRadio" type="radio" name="type" value="twitter" onclick="chkTwitter()"<? if($_GET['type']=='twitter') print(' checked'); ?> />Twitterアイコンから生成
	<input id="twitterText" type="text" name="twitterID" size="30" value="<?php print(($_GET['twitterID']!='')? $_GET['twitterID'] : '') ?>"<? if($_GET['type']!='twitter') print(' disabled'); ?> placeholder="Twitter IDを入力してください" /></label>
	<li>スポンサーを入力<a href="javascript:addSponsor();"><div class="addSponsorBtn">+スポンサーの追加（最大6社）</div></a></li>
	<div id="sponsorsInput">
	  <div><input type="text" name="sponsor[]" placeholder="例) 株式会社月極" /><span class="deleteSponsorBtn" onClick="deleteSponsor(this);">×</span></div>
	</div>
	<div class="submit"><input type="submit" value="生成！" /></div>
	</ol>
</form>

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
<div class="footer">Copyright 2012 - 2016 音羅院一族</div>
</footer>

<!-- /.wrapper --></div>

<script src="js/lib/jquery.min.js"></script>
<script src="js/lib/FCClientJS.js"></script>
<script>
'use strict';
function addSponsor() {
	if($('#sponsorsInput > div').size()<6) {
		$('#sponsorsInput').append('	<div><input type="text" name="sponsor[]" placeholder="例) 株式会社月極" /><span class="deleteSponsorBtn" onClick="deleteSponsor(this);">×</span></div>');
	}
}

function deleteSponsor(elem) {
	elem.parentNode.parentNode.removeChild(elem.parentNode);
}

var imageUrl = "http://news.walkerplus.com/2011/0917/2/20110915171944_00_400.jpg";

var client = new FCClientJS('', '');
var options = new Object();
// options.detect_all_feature_points = true;
client.facesDetect(imageUrl, null, options, callback)

function callback(data) {
  console.log(data);

  $(function(){
    data.photos.forEach((photo) => {
      $('.main').append(`<div id="result"></div>
<div class="stage">
<img class="picture" src="${imageUrl}" />
</div><div class="attention">※注意 画像を保存しても提供は付きません。</div>`);

      var width = photo.width;
      var height = photo.height;

      var faceArr = photo.tags;
      faceArr.forEach((tag) => {
        var $teikyo = $('<div></div>')
        .addClass('teikyo');
        var $tei = $('<div>提</div>')
        .addClass('tei');
        var $kyo = $('<div>供</div>')
        .addClass('kyo');
        var $sponsor = $('<div>カヤック</div>')
        .addClass('sponsor');
        $teikyo.append($tei, $kyo, $sponsor);

        var lx = tag.eye_left.x * width / 100;
        var ly = tag.eye_left.y * height / 100;
        var rx = tag.eye_right.x * width / 100;
        var ry = tag.eye_right.y * height /100;
        var interval = Math.sqrt(Math.pow(lx - rx, 2)+Math.pow(ly - ry, 2));
        var size = interval / 2;
        var rotation = Math.atan2(ly - ry, lx - rx);

        $tei.css({
          position: 'absolute',
          left: rx - size / 2,
          top: ry - size / 2,
          'font-size': size,
          transform: `rotate(${rotation}rad)`,
        });

        $kyo.css({
          position: 'absolute',
          left: lx - size / 2,
          top: ly - size / 2,
          'font-size': size,
          transform: `rotate(${rotation}rad)`,
        });

        $sponsor.css({
          position: 'absolute',
          width: '100px',
          top: 0.6 * $(this).height() - size / 2 + "px",
          'text-align': 'center',
          'font-size': size + 'px'
        });

        $('.main').find('.stage').append($teikyo);

        // if(count($face)==0 && $imageUrl!="") {
        //   // 顔が検出されなかったとき
        //   print(' <div class="teikyo" id="teikyo">
        //   <div class="tei">提</div>
        //   <div class="kyo">供</div>
        //   <div class="sponsor">'.$_GET['sponsor'][0].'</div>
        // </div>');
        // }
      });

      if(faceArr.length === 0) {
        $("#result").text("顔が検出されませんでした。");
        $(".picture").bind("load", function() {
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
        });
      }
    });
  });
}
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

</script>
</body>
</html>