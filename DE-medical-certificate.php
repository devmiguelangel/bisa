<?php
require('medical-certified.class.php');

if(isset($_GET['cm']) && isset($_GET['cia']) && isset($_GET['ide']) && isset($_GET['ef'])){
	$cia = $_GET['cia'];
	$ef = $_GET['ef'];
	$ide = $_GET['ide'];
	$cm = new MedicalCertified($ide, $cia, $ef);
	
	if($cm->err === FALSE){
		echo $cm->get_certified();
	}else{
		echo 'No se puede obtener el Formulario';
	}
}else{
	echo 'No se puede obtener el Certificado';
}


?>