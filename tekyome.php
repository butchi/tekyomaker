<!DOCTYPE html>
<?php
// 09 フォームに合わせてposition修正、フォームに指定したURLの画像を利用可能に、顔ラボに渡す時にURLエンコード
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
} else {
	$imageUrl = 'http://ec2.images-amazon.com/images/I/51-z7BZTYrL.jpg';
}
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
		"left": ('.(-sin($face[$iCounter]->rotation)*$face[$iCounter]->interval*1+($face[$iCounter]->rx+$face[$iCounter]->lx)/2).'-$(\'#sponsor'.$iCounter.'\').width()/2)+"px",
		"top": ('.(cos($face[$iCounter]->rotation)*$face[$iCounter]->interval*1+($face[$iCounter]->ry+$face[$iCounter]->ly)/2).'-$(\'#sponsor'.$iCounter.'\').height()/2)+"px",
		"-moz-transform": "rotate('.($face[$iCounter]->rotation).'rad)",
		"-webkit-transform": "rotate('.($face[$iCounter]->rotation).'rad)",
		"-o-transform": "rotate('.($face[$iCounter]->rotation).'rad)",
		"-ms-transform": "rotate('.($face[$iCounter]->rotation).'rad)"
	})'."\n");
}
?>
});
</script>
<title>tekyome!</title>
</head>
<body>
<h1>tekyome!</h1>
<form name="form1" method="get" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
	<input id="urlText" type="url" name="url" size="70" value="<?php print($imageUrl) ?>" />
    <input type="submit" value="生成！" />
</form>
<div class="stage">
	<img class="picture" src="<?php echo($imageUrl) ?>" />
<?php
for ($iCounter = 0; $iCounter< count($xml->faces->face); $iCounter++){
	print('	<div class="teikyo" id="teikyo'.$iCounter.'">');
	print('<div class="tei" id="tei'.$iCounter.'">提</div>');
	print('<div class="kyo" id="kyo'.$iCounter.'">供</div>');
	print('<div class="sponsor" id="sponsor'.$iCounter.'">butchi.jp</div>');
	print('</div>');
	print("\n");
}
?>
</div>
<div>※注意 画像を保存しても提供は付きません。</div>
<div><a href="http://butchi.jp">Home</a></div>
</body>
</html>