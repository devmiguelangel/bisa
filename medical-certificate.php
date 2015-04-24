<?php
require_once('medical-certified.class.php');

$type = '';
$_IDE = '';
$_CIA = '';
$_EF = '';
if(isset($_GET['type']) && isset($_GET['ide']) && isset($_GET['cia']) && isset($_GET['ef'])){
	$type = $_GET['type'];
	$_IDE = $_GET['ide'];
	$_CIA = $_GET['cia'];
	$_EF = $_GET['ef'];
}else{
	$type = base64_encode('PRINT');
	$_IDE = base64_encode($row['ide']);
	$_CIA = base64_encode($row['cia']);
	$_EF = $row['ef'];
}

$cm = new MedicalCertified($_IDE, $_CIA, $_EF, TRUE, $type);
if($cm->err === FALSE){
	echo $cm->get_certified();
}else{
	echo 'No se puede obtener el Certificado Médico';
}
?>