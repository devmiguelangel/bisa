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
		$data = array();

		switch ($product) {
		case 'A':
			$data['type_vehicle'] 	= $res['data'][$product]['tipoVehiculo'];
			$data['plate'] 		= $res['data'][$product]['placa'];
			$data['chassis'] 	= $res['data'][$product]['chasis'];
			$data['motor'] 		= $res['data'][$product]['motor'];
			$data['color'] 		= $res['data'][$product]['color'];
			$data['plaza'] 		= $res['data'][$product]['plazaCirculacion'];
			$data['country'] 	= $res['data'][$product]['paisOrigen'];
			$data['year'] 		= $res['data'][$product]['anio'];
			$data['warranty'] 	= $res['data'][$product]['garantia'];
			break;
		case 'T':
			$data['depto'] 			= $res['data'][$product]['departamento'];
			$data['address'] 		= $res['data'][$product]['direccion'];
			$data['locality'] 		= $res['data'][$product]['localidad'];
			$data['value_insured'] 	= $res['data'][$product]['valorComercial'];
			$data['currency'] 		= $res['data'][$product]['moneda'];
			$data['date_record'] 	= $res['data'][$product]['fechaRegistroLegal'];
			$data['record'] 		= $res['data'][$product]['registroLegal'];
			$data['warranty'] 		= $res['data'][$product]['garantia'];
			break;
		}
		
		$res['data'][$product] = $data;
	} else {
		$res['error'] = 'No existen datos de la garantía';
	}

}

echo json_encode($res);

?>