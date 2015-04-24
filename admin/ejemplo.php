<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>

<body>
<?php
require_once('config.class.php');
$conexion = new SibasDB();
$myarray = array(1 => 10001, 2 => 20000, 3 => 20001, 4 => 30000);
$myJson = json_encode($myarray);
echo $myJson;echo'<br/>'; 

$nonsequential = array(0=>"foo", 1=>"bar", 2=>"baz", 3=>"blong");
$resp = json_encode($nonsequential);	
echo $resp;
?>
</body>
</html>