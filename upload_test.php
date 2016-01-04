<?php
// from http://plog.pya.jp/program/php/lesson11/sample01.html
$img_name = $_FILES["img_path"]["name"];
$img_size = $_FILES["img_path"]["size"];
$img_type = $_FILES["img_path"]["type"];
$img_tmp = $_FILES["img_path"]["tmp_name"];

if($_REQUEST["up"] != ""){

if($img_tmp != "" and $img_size <= 300000){

$img_message = "名前は： $img_name <br>サイズは： $img_size <br>MIMEタイプは： $img_type <br>一時的に保存されているパスは： $img_tmp <br>";

}else{

$size_error = "サイズが大きすぎます。ファイルサイズは300キロバイト以下です。";

}
}
?>

<html>
<head>
<title>画像アップロード</title>
</head>
<body>
<h1>画像アップロード</h1>(サイズは300キロバイト以下)
<form name="form" action="<?php print $_SERVER['PHP_SELF']; ?>" method="POST"
ENCTYPE="MULTIPART/FORM-DATA">
<input name="img_path" type="file" size="40">
<input name="up" type="submit" value="アップロード"><hr>
<font color="#FF0000"><strong><?= $size_error ?></font></strong><?= $img_message ?>
</form>
</body>
</html>