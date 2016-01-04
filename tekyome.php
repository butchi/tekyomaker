<?php
// 2012年4月27日 8:00-8:30
$xml = simplexml_load_file("https://kaolabo.com/api/detect?apikey=15ff38a942356b754466cdf3735862a7&url=http://butchi.jp/img/myavg.jpg") or die("XMLパースエラー");
//var_dump($xml);
var_dump($xml->faces[0]->face->left-eye);
?>
