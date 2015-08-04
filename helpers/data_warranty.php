<?php

require __DIR__ . '/../sibas-db.class.php';
require __DIR__ . '/../classes/BisaWs.php';

$res = array(
	'status'	=> 404,
	'error' 	=> 'Error de Conexión.',
	'data'		=> array(
		'A' => array(),
		'T' => array()
	)
);

if (isset($_GET['cod_cl'], $_GET['value'], $_GET['product'])) {
	$cx = new SibasDB();
	session_start();

	$code_cl 	= $cx->real_escape_string(trim($_GET['cod_cl']));
	$value 		= $cx->real_escape_string(trim($_GET['value']));
	$product 	= $cx->real_escape_string(trim($_GET['product']));

	$req = array(
		'codigoCliente' 	=> $code_cl,
		'tipoSeguro' 		=> $product,
		'valorBusqueda' 	=> $value
	);

	if ($cx->checkWebService($_SESSION['idEF'], 'AU')) {
		$ws = new BisaWs($cx, 'AI', $req);
		if ($ws->getDataWarranty($product)) {

			$res['data'][$product] = $ws->data;
			$data = array();

			$deptos = $cx->get_depto(null, true);
			
			switch ($product) {
			case 'A':
				$plaza = 'RP';

				if ($deptos !== false) {
					foreach ($deptos as $key => $depto) {
						if ($depto['codigo_ws'] == $res['data'][$product]['plazaCirculacion']) {
							$plaza = $depto['codigo'];
							break;
						}
					}
				}

				$data['dv_type_vehicle'] 	= $res['data'][$product]['tipoVehiculo'];
				$data['dv_plate'] 		= $res['data'][$product]['placa'];
				$data['dv_chassis'] 	= $res['data'][$product]['chasis'];
				$data['dv_motor'] 		= $res['data'][$product]['motor'];
				$data['dv_color'] 		= $res['data'][$product]['color'];
				$data['dv_plaza'] 		= $plaza;
				$data['dv_country'] 	= $res['data'][$product]['paisOrigen'];
				$data['dv_year'] 		= $res['data'][$product]['anio'];
				// $data['dv_warranty'] 	= $res['data'][$product]['garantia'];
				break;
			case 'T':
				if ($deptos !== false) {
					foreach ($deptos as $key => $depto) {
						if ($depto['codigo_ws'] === $res['data'][$product]['departamento']) {
							$res['data'][$product]['departamento'] = base64_encode($depto['id_depto']);
						}
					}
				}

				$locality = '';
				if (array_key_exists($res['data'][$product]['localidad'], $ws->locality)) {
					$locality = $ws->locality[$res['data'][$product]['localidad']];
				}

				$data['dp_depto'] 			= $res['data'][$product]['departamento'];
				$data['dp_address'] 		= $res['data'][$product]['direccion'];
				$data['dp_locality'] 		= $locality;
				$data['dp_value_insured'] 	= $res['data'][$product]['valorComercial'];
				// $data['dp_currency'] 		= $res['data'][$product]['moneda'];
				// $data['dp_date_record'] 	= $res['data'][$product]['fechaRegistroLegal'];
				// $data['dp_record'] 			= $res['data'][$product]['registroLegal'];
				// $data['dp_warranty'] 		= $res['data'][$product]['garantia'];
				break;
			}
			
			$res['data'][$product] = $data;

			$res['status'] = 200;
			$res['error'] = '';
		} else {
			$res['error'] = 'No existen datos de la garantía';
		}
	} else {
		$res['error'] = 'No existen datos de la garantía.';
	}
}

echo json_encode($res);

?>