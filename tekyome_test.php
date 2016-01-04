<!DOCTYPE html>
<?php
// 10 注意のフォントを小さい灰色に、デフォルトで画像を表示しない、スポンサーのサイズ変更追加
class Face {
	public $rx;
	public $ry;
	public $lx;
	public $ly;
	public $interval;
	public $size;
	public $rotation;
}

if(isset($_GET['url']) && $_GET['url']!="") {
	$imageUrl = $_GET['url'	];
	$xml = simplexml_load_file('https://kaolabo.com/api/detect?apikey=15ff38a942356b754466cdf3735862a7&url='.urlencode($imageUrl)) or die("XMLパースエラー");
	for ($iCounter = 0; $iCounter< count($xml->faces->face); $iCounter++){
		$face[$iCounter] = new Face();
		$face[$iCounter]->rx = $xml->faces->face[$iCounter]->{'right-eye'}['x'];
		$face[$iCounter]->ry = $xml->faces->face[$iCounter]->{'right-eye'}['y'];
		$face[$iCounter]->lx = $xml->faces->face[$iCounter]->{'left-eye'}['x'];
		$face[$iCounter]->ly = $xml->faces->face[$iCounter]->{'left-eye'}['y'];
		$face[$iCounter]->interval = sqrt(pow($face[$iCounter]->lx-$face[$iCounter]->rx,2)+pow($face[$iCounter]->ly-$face[$iCounter]->ry,2));
		$face[$iCounter]->size = $face[$iCounter]->interval/2;
		$face[$iCounter]->rotation = atan2($face[$iCounter]->ly-$face[$iCounter]->ry,$face[$iCounter]->lx-$face[$iCounter]->rx);
	}
} else {
	$imageUrl = '';
}
?>
<html>
<meta charset="utf-8">
<head>
<script src="jquery-1.7.2.min.js"></script>
<style>
h1 {color:#000000; background:#99ccff; padding:20px}
h2 {color:#ffffff; background:#336699; padding:5px}
.stage {
	position:relative;
}
.teikyo {
	position:absolute;
	color:white;
	top:0px;
	left:0px;
	text-shadow:0px 0px 3px #000000;
}
#result {
	color:#F00;
}
.attention {
	font-size:small;
	color:#999;
}
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
	width:200px;
	}
');
}
?>
</style>
<script>
$(function(){
<?php
for ($iCounter = 0; $iCounter<count($face); $iCounter++){
	print('	$("#sponsor'.$iCounter.'").css({
		"position": "absolute",
		"font-size":"'.($face[$iCounter]->size).'px",
		"left": ('.(-sin($face[$iCounter]->rotation)*$face[$iCounter]->interval*1+($face[$iCounter]->rx+$face[$iCounter]->lx)/2).'-$(\'#sponsor'.$iCounter.'\').width()/2)+"px",
		"top": ('.(cos($face[$iCounter]->rotation)*$face[$iCounter]->interval*1+($face[$iCounter]->ry+$face[$iCounter]->ly)/2).'-$(\'#sponsor'.$iCounter.'\').height()/2)+"px",
		"width": "200px",
		"text-align":"center",
		"-moz-transform": "rotate('.($face[$iCounter]->rotation).'rad)",
		"-webkit-transform": "rotate('.($face[$iCounter]->rotation).'rad)",
		"-o-transform": "rotate('.($face[$iCounter]->rotation).'rad)",
		"-ms-transform": "rotate('.($face[$iCounter]->rotation).'rad)"
	})'."\n");
}
if(count($face)==0 && $imageUrl!="") {
	print('$("#result").text("顔が検出されませんでした。")');
}
?>
});
</script>
<title>tekyome!</title>
</head>
<body>
<h1>tekyome!</h1>
<form name="form1" method="get" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
	<input id="urlText" type="url" name="url" size="70" value="<?php print(($imageUrl!="")? $imageUrl : 'http://ec2.images-amazon.com/images/I/51-z7BZTYrL.jpg') ?>" />
	<input type="submit" value="生成！" />
	<div><label>スポンサー1<input type="text" name="sponsor[]" value="butchi.jp" /></label></div>
	<div><label>スポンサー2<input type="text" name="sponsor[]" value="カヤック" /></label></div>
	<div><label>スポンサー3<input type="text" name="sponsor[]" value="ブッチブログ" /></label></div>
</form>
<div id="result"></div>
<div class="stage">
	<img class="picture" src="<?php echo($imageUrl) ?>" />
<?php
for ($iCounter = 0; $iCounter< count($xml->faces->face); $iCounter++){
	print('	<div class="teikyo" id="teikyo'.$iCounter.'">');
	print('<div class="tei" id="tei'.$iCounter.'">提</div>');
	print('<div class="kyo" id="kyo'.$iCounter.'">供</div>');
	print('<div class="sponsor" id="sponsor'.$iCounter.'">'.$_GET['sponsor'][$iCounter%count($_GET['sponsor'])].'</div>');
	print('</div>');
	print("\n");
}
?>
</div>
<div class="attention">※注意 画像を保存しても提供は付きません。</div>
<div><a href="http://butchi.jp">Home</a></div>
</body>
</html>