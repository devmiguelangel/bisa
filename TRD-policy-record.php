<?php

require __DIR__ . '/classes/Logs.php';
require 'session.class.php';

$session = new Session();
$session->getSessionCookie();
$token = $session->check_session();

$arrTR = array(0 => 0, 1 => 'R', 2 => 'Error');
$log_msg = '';
$record = 0;

if ($token) {
	require('sibas-db.class.php');
	$link = new SibasDB();

	if(isset($_POST['flag']) && isset($_POST['de-ide']) 
			&& isset($_POST['ms']) && isset($_POST['page']) 
			&& isset($_POST['pr']) && isset($_POST['cia'])) {
		if($_POST['pr'] === base64_encode('TRD|05')){
			$fecha_emision = '';
			$target = false;

			$ID 			= $link->real_escape_string(trim(base64_decode($_POST['de-ide'])));
			$ws_token 		= false;
			$ws_usuario 	= '';
			$no_transaction = 0;
			$fecha_trans 	= '';
			$monto_trans 	= 0;
			$cashed 		= 0;
			$payment 		= '';
			$period 		= '';

			if (empty($ID) === false) {
				$sql = 'select 
					sae.id_emision as ide,
					sae.no_emision,
					sae.forma_pago, 
					sae.prima_total,
					sae.fecha_emision,
					sae.garantia
				from 
					s_trd_em_cabecera as sae
				where
					sae.id_emision = "' . $ID . '"
				limit 0, 1
				;';

				if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
					if ($rs->num_rows === 1) {
						$row = $rs->fetch_array(MYSQLI_ASSOC);
						$rs->free();

						$payment = $row['forma_pago'];
						$record = $row['no_emision'];

						$ws_db = $link->checkWebService($_SESSION['idEF'], 'TRD');
						
						if ($ws_db && (boolean)$row['garantia']) {
							goto Issue;
						} else {
							Issue:

							$fecha_emision = date('Y-m-d');

							$sql = 'update 
							s_trd_em_cabecera as sae
								inner join 
							s_entidad_financiera as sef ON (sef.id_ef = sae.id_ef)
							set 
								sae.emitir = true, 
								sae.fecha_emision = "' . $fecha_emision . '",
								sae.estado = "V", 
								sae.leido = false
							where 
								sae.id_emision = "' . $ID . '" 
									and sef.id_ef = "' . base64_decode($_SESSION['idEF']) . '"
									and sef.activado = true ;';

							if ($link->query($sql)) {
								switch ($payment) {
								case 'CO':
									$period = 'Y';
									break;
								case 'CR':
									$period = 'M';
									break;
								}

								$queryset = 'insert into s_trd_cobranza
								(id, id_emision, numero_cuota, fecha_cuota, 
									monto_cuota, numero_transaccion, fecha_transaccion, 
									monto_transaccion, cobrado)
								values
								';

								$nc = 0;
								$prima = 0;
								switch ($period) {
								case 'M':
									$nc = 12;
									$prima = $row['prima_total'] / $nc;
									break;
								case 'Y':
									$nc = 1;
									$prima = $row['prima_total'];
									break;
								}

								$idc = date('U');

								$fecha 			= $fecha_emision;
								$fecha_cuota 	= strtotime('+0 day', strtotime($fecha));
								$fecha_cuota 	= date('Y-m-d', $fecha_cuota);

								for ($i = 1; $i <= $nc ; $i++) {
									$idc += $i;

									if ($i !== 1) {
										$no_transaction	= 0;
										$fecha_trans 	= '';
										$monto_trans 	= 0;
										$cashed 		= 0;
									}

									$queryset .= '
									("' . $idc . '", "' . $ID . '", "' . $i . '", 
										"' . $fecha_cuota . '", "' . $prima . '", 
										"' . $no_transaction . '", 
										"' . $fecha_trans . '" , "' . $monto_trans . '", 
										"' . $cashed . '"),';
									
									$fecha = strtotime($fecha_cuota);
									$month = date('n', $fecha);
									$year = date('Y', $fecha);
									$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

									$fecha_cuota = strtotime('+' . $days . ' day', $fecha);
									$fecha_cuota = date('Y-m-d', $fecha_cuota);
								}

								$queryset = trim($queryset, ',') . ';';
								
								if ($link->query($queryset)) {
									$arrTR[0] = 1;
									$arrTR[1] = 'certificate-policy.php?ms=' . $_POST['ms'] 
										. '&page=' . $_POST['page'] . '&pr=' . base64_encode('TRD') 
										. '&ide=' . base64_encode($ID);
									$arrTR[2] = 'LA PÓLIZA FUE EMITIDA CON EXITO !!!';

									$log_msg = 'TRD - Em. ' . $record . ' / Emision';

									$db = new Log($link);
									$db->postLog($_SESSION['idUser'], $log_msg);
								} else {
									$arrTR[2] = 'Error. No se pudo registrar las cuotas .';
								}
							} else {
								$arrTR[2] = 'La Póliza no pudo ser Emitida';
							}
						}
					} else {
						$arrTR[2] = 'Error. No se pudo emitir la Póliza.';
					}
				} else {
					$arrTR[2] = 'Error. No se pudo emitir la Póliza!';
				}
			} else {
				$arrTR[2] = 'La Póliza no puede ser Emitida';
			}
		}else{
			$arrTR[2] = 'Error: La Póliza no puede ser Emitida';
		}
	}else{
		$arrTR[2] = 'Error: La Póliza no puede ser Emitida |';
	}
}

echo json_encode($arrTR);

?>