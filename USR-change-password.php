<?php

require __DIR__ . '/classes/Logs.php';
require 'sibas-db.class.php';

session_start();

$arrUSR = array(0 => 0, 1 => 'R', 2 => 'La Contraseña no puede ser cambiada');
$log_msg = '';

if (isset($_GET['user']) && isset($_GET['cp_new_password']) && isset($_GET['url'])) {
	$link = new SibasDB();
	
	$idUser = $link->real_escape_string(trim(base64_decode($_GET['user'])));
	$password = $link->real_escape_string(trim($_GET['cp_new_password']));
	$url = $link->real_escape_string(trim(base64_decode($_GET['url'])));
	$email = $link->real_escape_string(trim($_GET['cp_email']));
	$idef = $_SESSION['idEF'];

	$sql = 'select 
		su.id_usuario as idUser,
		su.usuario as u_usuario,
		su.nombre as u_nombre,
		su.password as u_password,
		su.email as u_email,
		su.history_password
	from
		s_usuario as su
	where
		su.id_usuario = "' . $idUser . '"
	limit 0, 1
	;';

	if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
		if ($rs->num_rows === 1) {
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();

			$len_pass = strlen($password);

			if ($len_pass >= 6 && $len_pass <= 10) {
				if (getDataChar($password)) {

					if (getHistoryPassword($row['history_password'], $password)) {
						$password = crypt_blowfish_bycarluys($password);

						$histories = setHistoryPassword($row['history_password'], $password);
						$histories = $link->real_escape_string($histories);

						$sql = "update 
							s_usuario as su
								inner join 
							s_ef_usuario as seu ON (seu.id_usuario = su.id_usuario)
								inner join 
							s_entidad_financiera as sef ON (sef.id_ef = seu.id_ef)
						set 
							su.password = '" . $password . "', 
							su.email = '" . $email . "', 
							su.cambio_password = true,
							su.history_password = '" . $histories . "',
							su.date_password = '" . date('Y-m-d H:i:s') . "'
						where
							su.id_usuario = '" . $idUser . "'
								and sef.id_ef = '" . base64_decode($idef) . "'
						;";
						
						if ($link->query($sql)) {
							$arrUSR[0] = 1;
							$arrUSR[1] = $url;
							$arrUSR[2] = 'La contraseña se actualizó correctamente <br> Por favor espere..';
						} else {
							$arrUSR[2] = 'No se pudo actualizar la contraseña';
						}
					} else {
						$arrUSR[2] = 'La nueva contraseña no puede ser igual a las ultimas 10';
					}
				} else {
					$arrUSR[2] = 'La nueva contraseña no debe tener caracteres consecutivos similares';
				}
			} else {
				$arrUSR[2] = 'La nueva contraseña debe tener un mínimo de 6 caracteres <br>
					y un máximo de 10 caracteres';
			}
		} else {
			$arrUSR[2] = 'La contraseña no puede ser actualizada';
		}
	}
}

echo json_encode($arrUSR);

function crypt_blowfish_bycarluys($password, $digito = 7) {
	//	El salt para Blowfish debe ser escrito de la siguiente manera: 
	//	$2a$, $2x$ o $2y$ + 2 números de iteración entre 04 y 31 + 22 caracteres
	$set_salt = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$salt = sprintf('$2a$%02d$', $digito);
	
	for($i = 0; $i < 22; $i++){
		$salt .= $set_salt[mt_rand(0, 63)];
	}
	
	return crypt($password, $salt);
}

function getDataChar($password) {
	$len_pass = strlen($password) - 1;

	for ($i = 0; $i <= $len_pass; $i++) { 
		if ($i < $len_pass) {
			if ($password[$i] === $password[$i + 1]) {
				return false;
			}
		}
	}

	return true;
}

function getHistoryPassword($histories, $password) {
	$histories = json_decode($histories, true);

	if (count($histories) > 0) {
		foreach ($histories as $key => $history) {
			if (crypt($password, $history['password']) == $history['password']) {
				return false;
			}
		}
	}

	return true;
}

function setHistoryPassword($histories, $password) {
	$histories = json_decode($histories, true);
	$no_histories = count($histories);

	if ($no_histories === 10) {
		$histories = array();
		$no_histories = 0;
	}

	$histories[$no_histories + 1] = [
		'password'  => $password,
		'ts'		=> date('Y-m-d H:i:s')
	];
	
	return json_encode($histories);
}


?>