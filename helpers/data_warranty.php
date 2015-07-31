<?php

require __DIR__ . '/../sibas-db.class.php';
require __DIR__ . '/../classes/BisaWs.php';

$res = array(
	'status'	=> 404,
	'error' 	=> 'Error.',
	'data'		=> array(
		'A' => array(),
		'T' => array()
	)
);

if (isset($_GET['cod_cl'], $_GET['value'], $_GET['product'])) {
	$cx = new SibasDB();

	$code_cl 	= $cx->real_escape_string(trim($_GET['cod_cl']));
	$value 		= $cx->real_escape_string(trim($_GET['value']));
	$product 	= $cx->real_escape_string(trim($_GET['product']));

	$req = array(
		'codigoCliente' 	=> $code_cl,
		'tipoSeguro' 		=> $product,
		'valorBusqueda' 	=> $value
	);

	$ws = new BisaWs($cx, 'AI', $req);

	if ($ws->getDataWarranty($product)) {
		$res['data'][$product] = $ws->data;
	} else {
		$res['error'] = 'No existen datos de la garantía';
	}

}

echo json_encode($res);

?>