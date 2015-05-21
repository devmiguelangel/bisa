<?php

require 'sibas-db.class.php';
require 'classes/BisaWs.php';

$res = array(
	'token'	=> false,
	'mess' 	=> '', 
	'data' 	=> array(
		'clients' 	=> [],
		'accounts' 	=> []
	)
);

$link = new SibasDB();

if (isset($_GET['op'])) {
	switch (variable) {
	case 'C':
		if (isset($_GET['type']) && isset($_GET['dni']) && isset($_GET['ext'])) {
			$type = (boolean)$link->real_escape_string(trim(base64_decode($_GET['type'])));
			$dni = $link->real_escape_string(trim($_GET['dni']));
			$ext = $link->real_escape_string(trim($_GET['ext']));

			$req = [
				'tipoCliente' 	=> '',
				'nroDocumento' 	=> $dni,
				'sigla' 		=> $ext,
			];

			if ($type === true) {
				$req['tipoCliente'] = 'E';
			} elseif ($type === false) {
				$req['tipoCliente'] = 'P';
			}

			$ws = new BisaWs($link, 'CD', $req);

			if ($ws->getDataCustomer()) {
				$res['token'] = true;
				$res['data']['clients'][] = $ws->data;
			} else {
				$res['mess'] = $ws->err_mess;
			}

		}
		break;
	case 'A':
		if (isset($_GET['code'])) {
			$code = $link->real_escape_string(trim($_GET['code']));
			
			$req = [
				'codigoCliente' => $code,
			];

			$ws = new BisaWs($link, 'AD', $req);
			$ws->getDataAccount();
			$res['data']['accounts'] = $ws->data;
		}
		break;
	}
}



echo json_encode($res);

?>