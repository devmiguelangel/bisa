<?php

require __DIR__ . '/classes/Logs.php';
require __DIR__ . '/classes/Collections.php';
require __DIR__ . '/classes/BisaWs.php';
require 'session.class.php';

$session = new Session();
$session->getSessionCookie();
$token = $session->check_session();

$arrAU = array(0 => 0, 1 => 'R', 2 => 'Error');
$log_msg = '';
$record = 0;

if ($token) {
	require('sibas-db.class.php');
	$link = new SibasDB();
	$db = new Log($link);

	if(isset($_POST['flag']) && isset($_POST['de-ide']) 
			&& isset($_POST['ms']) && isset($_POST['page']) 
			&& isset($_POST['pr']) && isset($_POST['cia'])) {
		if($_POST['pr'] === base64_encode('AU|05')){

			$ID = $link->real_escape_string(trim(base64_decode($_POST['de-ide'])));

			if (empty($ID) === false) {
				$sql = 'select 
					sae.id_emision as ide,
					sae.no_emision,
					sae.forma_pago, 
					sae.prima_total,
					sae.fecha_emision,
					sae.garantia,
					sae.operacion,
					su.usuario as u_usuario
				from 
					s_au_em_cabecera as sae
						inner join
					s_usuario as su ON (su.id_usuario = sae.id_usuario)
				where
					sae.id_emision = "' . $ID . '"
				limit 0, 1
				;';

				if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
					if ($rs->num_rows === 1) {
						$row = $rs->fetch_array(MYSQLI_ASSOC);
						$rs->free();

						$record = $row['no_emision'];

						$ws_db = $link->checkWebService($_SESSION['idEF'], 'AU');
						$row['ws_db'] = $ws_db;
						
						if ($ws_db && (boolean)$row['garantia']) {
							$operation = json_decode($row['operacion'], true);

							if (count($operation) > 0) {
								$req = array(
									'operacion' 	=> $operation['operacion'],
								);

								$req_lu = array(
									'operacion' 	=> $operation['operacion'],
									'garantia' 		=> $operation['garantia'],
									'tipoSeguro' 	=> 'A',
									'usuario' 		=> $row['u_usuario'],
									'ip' 			=> $db->getUserIP(),
									'accion' 		=> 'A'
								);

								$ws = new BisaWs($link, 'PP', $req);

								if ($ws->getPaymentPlan()) {
									$row['data'] = $ws->data;
									goto Issue;
								} else {
									$arrAU[2] = 'No se pudo obtener el plan de pagos.';
								}
							} else {
								$arrAU[2] = 'No se tiene una operacion asociada.';
							}
						} else {
							Issue:

							$collection = new Collection($link, $row, 'AU');

							if ($collection->putPolicy($ID, $_SESSION['idEF'])) {
								$arrAU[0] = 1;
								$arrAU[1] = 'certificate-policy.php?ms=' . $_POST['ms'] 
									. '&page=' . $_POST['page'] . '&pr=' . base64_encode('AU') 
									. '&ide=' . base64_encode($ID);
								$arrAU[2] = 'LA PÓLIZA FUE EMITIDA CON EXITO !!!';

								if ($ws_db && (boolean)$row['garantia']) {
									$ws = new BisaWs($link, 'LU', $req_lu);

									if ($ws->postLinkUp()) {
										$arrAU[2] = 'LA PÓLIZA FUE VINCULADA CON EXITO !!!';
										
										goto Issue2;
									} else {
										$arrAU[0] = 0;
										$arrAU[2] = 'LA PÓLIZA NO PUDO SER VINCULADA';
									}
								} else {
									Issue2:
																		
									$log_msg = 'AU - Em. ' . $record . ' / Emision';

									$db->postLog($_SESSION['idUser'], $log_msg);
								}
							} else {
								$arrAU[2] = $collection->mess;
							}
						}
					} else {
						$arrAU[2] = 'Error. No se pudo emitir la Póliza.';
					}
				} else {
					$arrAU[2] = 'Error. No se pudo emitir la Póliza!';
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
}

echo json_encode($arrAU);

?>