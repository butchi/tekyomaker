<?php
// 2012年4月29日 24:00-24:30
$imageUrl = 'http://www.wallpaperlink.com/images/wallpaper/2007/0705/03414x.jpg';
$xml = simplexml_load_file('https://kaolabo.com/api/detect?apikey=15ff38a942356b754466cdf3735862a7&url='.$imageUrl) or die("XMLパースエラー");
$rx = $xml->faces[0]->face->{'right-eye'}['x'];
$ry = $xml->faces[0]->face->{'right-eye'}['y'];
$lx = $xml->faces[0]->face->{'left-eye'}['x'];
$ly = $xml->faces[0]->face->{'left-eye'}['y'];
$interval = sqrt(pow($lx-$rx,2)+pow($ly-$ry,2));
$size = $interval/2;
$rotation = atan2($ly-$ry,$lx-$rx);
?>
<html>
<meta charset="utf-8">
<head>
<script src="jquery-1.7.2.min.js"></script>
<style>
.picture { position: absolute; left: 0px; top: 0px; }
.teikyo { position: absolute; color: white; text-shadow: 0px 0px 3px #000000; font-size: <?php echo($size) ?>px;
 -moz-transform: rotate(<?php echo($rotation) ?>rad);
 -webkit-transform: rotate(<?php echo($rotation) ?>rad);
 -o-transform: rotate(<?php echo($rotation) ?>rad);
 -ms-transform: rotate(<?php echo($rotation) ?>rad); }
.tei { left: <?php echo($rx-$size/2) ?>px; top: <?php echo($ry-$size/2) ?>px; }
.kyo { left: <?php echo($lx-$size/2) ?>px; top: <?php echo($ly-$size/2) ?>px; }
.sponsor { left: <?php echo(($rx+$lx)/2-50) ?>px; top: <?php echo(($ry+$ly)/2+$interval) ?>px;}
</style>
</head>
<body>
<img class="picture" src="<?php echo($imageUrl) ?>" />
<div class="teikyo tei">提</div>
<div class="teikyo kyo">供</div>
<div id="sponsor1" class="teikyo sponsor">butchi.jp</div>
<script>
$("#sponsor1").css("left",<?php echo($rx+$interval/2) ?>-$("#sponsor1").width()/2);
</script>
</body>
</html>
