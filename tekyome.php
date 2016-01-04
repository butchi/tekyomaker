<!DOCTYPE html>
<!-- スポンサーの座標指定修正 -->
<?php
class Face {
	public $rx;
	public $ry;
	public $lx;
	public $ly;
	public $interval;
	public $size;
	public $rotation;
}

$imageUrl = 'http://butchi.jp/documents/program/tekyome/perfume.jpg';
$xml = simplexml_load_file('https://kaolabo.com/api/detect?apikey=15ff38a942356b754466cdf3735862a7&url='.$imageUrl) or die("XMLパースエラー");
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
.picture { position: absolute; left: 0px; top: 0px; }
.teikyo {
	position:absolute;
	color:white;
	top:0px;
	left:0px;
	text-shadow:0px 0px 3px #000000;
}
.sponsor {  }
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
}
');
}
?>
</style>
<?php
print('<script>'."\n");
print('$(function(){'."\n");
for ($iCounter = 0; $iCounter<count($face); $iCounter++){
	print('	$("#sponsor'.$iCounter.'").css({
		"position": "absolute",
		"left": ('.(-sin($face[$iCounter]->rotation)*$face[$iCounter]->interval*1+($face[$iCounter]->rx+$face[$iCounter]->lx)/2).'-$(\'#sponsor'.$iCounter.'\').width()/2)+"px",
		"top": ('.(cos($face[$iCounter]->rotation)*$face[$iCounter]->interval*1+($face[$iCounter]->ry+$face[$iCounter]->ly)/2).'-$(\'#sponsor'.$iCounter.'\').height()/2)+"px",
//		"margin-left": -$(\'#sponsor'.$iCounter.'\').width()/2+"px",
//		"margin-top": -$(\'#sponsor'.$iCounter.'\').height()/2+"px",
		"-moz-transform": "rotate('.($face[$iCounter]->rotation).'rad)",
		"-webkit-transform": "rotate('.($face[$iCounter]->rotation).'rad)",
		"-o-transform": "rotate('.($face[$iCounter]->rotation).'rad)",
		"-ms-transform": "rotate('.($face[$iCounter]->rotation).'rad)"
	})'."\n");
}
print('});'."\n");
print('</script>'."\n");
?>
</head>
<body>
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
</body>
</html>