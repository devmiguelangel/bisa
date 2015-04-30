<?php

require __DIR__ . '/classes/Logs.php';
require('session.class.php');

$session = new Session();
$session->getSessionCookie();
$token = $session->check_session();

$arrAU = array(0 => 0, 1 => 'R', 2 => 'Error');
$log_msg = '';
$record = 0;

if($token === TRUE){
require('sibas-db.class.php');
$link = new SibasDB();
	if(isset($_POST['flag']) && isset($_POST['de-ide']) && isset($_POST['ms']) 
			&& isset($_POST['page']) && isset($_POST['pr']) && isset($_POST['cia'])){
		if($_POST['pr'] === base64_encode('AU|05')){
			$ID = $link->real_escape_string(trim(base64_decode($_POST['de-ide'])));

			if(empty($ID) === FALSE){
				$sql = 'UPDATE s_au_em_cabecera as sae
					INNER JOIN s_entidad_financiera as sef ON (sef.id_ef = sae.id_ef)
				SET sae.emitir = TRUE, sae.fecha_emision = curdate(), 
					sae.aprobado = TRUE, sae.leido = FALSE
				WHERE sae.id_emision = "' . $ID . '" 
					AND sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
					AND sef.activado = TRUE ;';

				if($link->query($sql) === TRUE){
					$arrAU[0] = 1;
					$arrAU[1] = 'certificate-policy.php?ms=' . $_POST['ms'] 
						. '&page=' . $_POST['page'] . '&pr=' . base64_encode('AU') 
						. '&ide=' . base64_encode($ID);
					$arrAU[2] = 'LA PÓLIZA FUE EMITIDA CON EXITO !!!';

					$sql = 'select 
						sae.no_emision
					from 
						s_au_em_cabecera sae
					where 
						sae.id_emision = "' . $ID . '"
					limit 0, 1
					;';

					if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
						if ($rs->num_rows === 1) {
							$row = $rs->fetch_array(MYSQLI_ASSOC);
							$rs->free();
							$record = $row['no_emision'];
						}
					}

					$log_msg = 'AU - Em. ' . $record . ' / Emision';

					$db = new Log($link);
					$db->postLog($_SESSION['idUser'], $log_msg);
				}else {
					$arrAU[2] = 'La Póliza no pudo ser Emitida';
				}
			} else {
				$arrAU[2] = 'La Póliza no puede ser Emitida';
			}
		}else{
			$arrAU[2] = 'Error: La Póliza no puede ser Emitida';
		}
	}else{
		$arrAU[2] = 'Error: La Póliza no puede ser Emitida |';
	}
	echo json_encode($arrAU);
}else{
	echo json_encode($arrAU);
}
?>