<?php
// ini_set('display_errors', 1);
require __DIR__ . '/../nusoap/nusoap.php'; 

class BisaWs
{
	private $soapAction = 'http://aqua.bisa.com/servicios/swissre/ws/';
	private $wsdl = 'https://10.200.3.152/AquaWar/soap/definition-sudamericana.wsdl';
	private 
		$cx,
		$client,
		$err,
		$message,
		$op;
	private 
		$currency = array(
			1 => 'USD',
			2 => 'BS'
		);

	private $method = array(
		'CD' => array(
			'method'	=> 'datosClienteRequest',
			'var' 		=> array()
		),
		'AD' => array(
			'method'	=> 'cuentasporClienteRequest',
			'var' 		=> array()
		),
		'WD' => array(
			'method'	=> 'operacionesYGarantiasRequest',
			'var' 		=> array()
		),
		'PP' => array(
			'method'	=> 'planDePagosRequest',
			'var' 		=> array()
		),
		'LU' => array(
			'method'	=> 'vinculacionRequest',
			'var' 		=> array()
		),
		'AI' => array(
			'method'	=> 'datosComplementariosGarantiaRequest',
			'var' 		=> array()
		),
	);

	public $locality = array(
		'0101' => 'SUCRE',
		'0102' => 'MONTEAGUDO',
		'0103' => 'PADILLA',
		'0105' => 'ZUDANEZ',
		'0106' => 'YAMPARAES',
		'0109' => 'CAMARGO',
		'0110' => 'CULPINA',
		'0111' => 'MUYUPAMPA',
		'0112' => 'LAS CARRERAS',
		'0113' => 'TARABUCO',
		'0114' => 'VILLA SERRANO',
		'0115' => 'TOMINA',
		'0116' => 'HUACARETA',
		'0117' => 'SAN LUCAS',
		'0118' => 'VILLA (VILLORRIO) ABECIA',
		'0119' => 'INCAHUASI',
		'0120' => 'VILLA MACHARETI',
		'0121' => 'MACHARETI',
		'0122' => 'EL VILLAR',
		'0123' => 'ICLA',
		'0124' => 'MOJOCOYA',
		'0125' => 'PRESTO',
		'0126' => 'TARVITA',
		'0127' => 'ALCALA',
		'0128' => 'YOTALA',
		'0201' => 'LA PAZ',
		'0202' => 'VIACHA',
		'0203' => 'CARANAVI',
		'0204' => 'PATACAMAYA',
		'0205' => 'COPACABANA',
		'0206' => 'EL ALTO',
		'0207' => 'COROICO',
		'0208' => 'CHULUMANI',
		'0209' => 'TIPUANI',
		'0210' => 'PALOS BLANCOS',
		'0211' => 'SORATA',
		'0212' => 'LICOMA',
		'0213' => 'ACHACACHI',
		'0214' => 'BATALLAS',
		'0215' => 'DESAGUADERO',
		'0216' => 'ACHOCALLA',
		'0217' => 'GUANAY',
		'0219' => 'MAPIRI',
		'0220' => 'APOLO',
		'0222' => 'QUIME',
		'0223' => 'CAMPAMENTO COLQUIRI',
		'0224' => 'SICA SICA',
		'0225' => 'LAHUACHACA',
		'0226' => 'CALAMARCA (PIZACAVINA)',
		'0227' => 'COLQUENCHA',
		'0228' => 'CORIPATA',
		'0229' => 'ARAPATA',
		'0230' => 'SAN BUENA VENTURA',
		'0231' => 'MECAPACA',
		'0232' => 'CAQUIAVIRI',
		'0233' => 'PUERTO ACOSTA',
		'0234' => 'PUCARANI',
		'0235' => 'MOCOMOCO',
		'0236' => 'PUERTO CARABUCO',
		'0237' => 'CHUMA (MUNECAS)',
		'0238' => 'AYATA',
		'0239' => 'AUCAPATA',
		'0240' => 'PELECHUCO',
		'0241' => 'GUAQUI',
		'0242' => 'LURIBAY',
		'0243' => 'INQUISIVI',
		'0244' => 'IRUPANA',
		'0245' => 'CHUMA (SUD YUNGAS)',
		'0246' => 'LA ASUNTA',
		'0248' => 'CHARAZANI',
		'0249' => 'CURVA',
		'0250' => 'SAN PEDRO DE TIQUINA',
		'0251' => 'SAN PEDRO DE CURAHUARA',
		'0252' => 'COMUNIDAD SAN JOSE DE TIAHUANACU',
		'0253' => 'HUAJCHILLA',
		'0254' => 'LAJA',
		'0255' => 'PUERTO PEREZ',
		'0256' => 'TITO YUPANQUI',
		'0257' => 'COMANCHE',
		'0258' => 'CORO CORO',
		'0259' => 'SAPAHAQUI',
		'0260' => 'COLLANA',
		'0261' => 'PALCA',
		'0262' => 'ANCORAIMES',
		'0263' => 'YANACACHI',
		'0264' => 'AYO AYO',
		'0265' => 'CAIROMA',
		'0266' => 'CAJUATA',
		'0267' => 'UMALA',
		'0268' => 'YACO',
		'0269' => 'ESCOMA',
		'0270' => 'HUMANATA',
		'0271' => 'CHURUBAMBA PACAJES',
		'0272' => 'IXIAMAS',
		'0273' => 'TIAWANACU',
		'0301' => 'COCHABAMBA',
		'0302' => 'SACABA',
		'0304' => 'AIQUILE',
		'0305' => 'CAPINOTA',
		'0306' => 'PUNATA',
		'0307' => 'QUILLACOLLO',
		'0308' => 'VILLA TUNARI',
		'0309' => 'MIZQUE',
		'0310' => 'CLIZA',
		'0311' => 'COLOMI',
		'0312' => 'IVIRGARZAMA',
		'0313' => 'TIQUIPAYA',
		'0314' => 'CHIMORE',
		'0315' => 'CAVICAVINI (ECHUCANI)',
		'0316' => 'INDEPENDENCIA',
		'0317' => 'KERAYA',
		'0318' => 'TIQUIRPAYA',
		'0319' => 'TARATA',
		'0320' => 'ARANI',
		'0321' => 'IRPA IRPA',
		'0322' => 'UCURENA',
		'0323' => 'TOLATA',
		'0324' => 'ILLATACO (OTB)',
		'0325' => 'IRONCOLLO (OTB)',
		'0326' => 'EL PASO',
		'0327' => 'SIPE SIPE',
		'0328' => 'VILLA MONTENEGRO (OTB)',
		'0329' => 'VINTO',
		'0330' => 'COMUNIDAD SEXTA PARTE (SUB CENTRAL VINTO',
		'0331' => 'COMUNIDAD CHULLA (OTB)',
		'0332' => 'COLCAPIRHUA',
		'0333' => 'ETERAZAMA',
		'0334' => 'BULO BULO',
		'0335' => 'ENTRE RIOS',
		'0336' => 'SAN BENITO',
		'0337' => 'SHINAHOTA',
		'0338' => 'TAPACARI',
		'0339' => 'TOTORA',
		'0340' => 'POCONA',
		'0341' => 'TIRAQUE',
		'0342' => 'ANZALDO',
		'0343' => 'OMEREQUE',
		'0344' => 'POJO',
		'0345' => 'TOKO',
		'0346' => 'VACAS',
		'0347' => 'VILLA RIVERO',
		'0348' => 'AYOPAYA',
		'0349' => 'MOROCHATA',
		'0350' => 'SANTIVANEZ',
		'0351' => 'PUERTO VILLARROEL',
		'0401' => 'ORURO',
		'0402' => 'CARACOLLO',
		'0403' => 'CHALLAPATA',
		'0404' => 'HUANUNI',
		'0405' => 'LA JOYA',
		'0407' => 'KAKACHACA',
		'0408' => 'POOPO',
		'0410' => 'MACHACAMARCA',
		'0411' => 'EUCALIPTUS',
		'0412' => 'SANTIAGO DE HUARI',
		'0413' => 'CURAHUARA DE CARANGAS',
		'0414' => 'SALINAS DE GARCI MENDOZA',
		'0415' => 'ANTEQUERA',
		'0416' => 'EL CHORO',
		'0417' => 'PAMPA AULLAGAS',
		'0418' => 'PAZNA',
		'0419' => 'SABAYA',
		'0420' => 'SORACACHI',
		'0421' => 'TOLEDO',
		'0422' => 'TURCO',
		'0423' => 'SALINAS DE GARCI MENDOZA',
		'0501' => 'POTOSI',
		'0502' => 'ATOCHA',
		'0503' => 'CHAYANTA',
		'0504' => 'TUPIZA',
		'0505' => 'UYUNI',
		'0506' => 'VILLAZON',
		'0507' => 'BETANZOS',
		'0508' => 'COTAGAITA',
		'0509' => 'UNCIA',
		'0510' => 'LLALLAGUA',
		'0511' => 'PUNA',
		'0512' => 'SIGLO XX',
		'0513' => 'CATAVI',
		'0514' => 'SANTA BARBARA',
		'0515' => 'VITICHI',
		'0516' => 'SACACA',
		'0517' => 'SAN PEDRO',
		'0518' => 'ACASIO',
		'0519' => 'LLICA',
		'0520' => 'CAIZA D',
		'0521' => 'BELEN',
		'0522' => 'COLCHA K',
		'0523' => 'CHAQUI',
		'0524' => 'YOCALLA',
		'0525' => 'TORO TORO',
		'0526' => 'COLQUECHACA',
		'0527' => 'POCOATA',
		'0528' => 'RAVELO',
		'0601' => 'TARIJA',
		'0602' => 'BERMEJO',
		'0603' => 'ENTRE RIOS',
		'0604' => 'VILLAMONTES',
		'0605' => 'YACUIBA',
		'0606' => 'TOLOMOSA GRANDE',
		'0607' => 'PADCAYA',
		'0608' => 'SAN LORENZO',
		'0609' => 'EL PUENTE',
		'0610' => 'VALLE DE CONCEPCION',
		'0611' => 'CARAPARI',
		'0701' => 'SANTA CRUZ',
		'0702' => 'CAMIRI',
		'0703' => 'MONTERO',
		'0704' => 'MINERO',
		'0705' => 'PORTACHUELO',
		'0706' => 'PUERTO SUAREZ',
		'0707' => 'ROBORE',
		'0708' => 'SAN IGNACIO DE VELASCO',
		'0709' => 'SAN MATIAS',
		'0710' => 'VALLEGRANDE',
		'0711' => 'SAN JOSE DE CHIQUITOS',
		'0712' => 'COMARAPA',
		'0713' => 'SAMAIPATA',
		'0714' => 'BUENA VISTA',
		'0715' => 'CONCEPCION',
		'0716' => 'WARNES',
		'0717' => 'SAN JULIAN',
		'0718' => 'ARROYO CONCEPCION',
		'0719' => 'EL TORNO',
		'0721' => 'PUERTO QUIJARRO',
		'0722' => 'SAN JUAN DE YAPACANI',
		'0723' => 'COTOCA',
		'0724' => 'GUARAYOS',
		'0725' => 'MAIRANA',
		'0726' => 'SAN PEDRO',
		'0727' => 'SAIPINA',
		'0728' => 'SAN ISIDRO',
		'0729' => 'LOS NEGROS',
		'0730' => 'CAMPANERO',
		'0731' => 'MAPAISO DE LAS PIEDADES',
		'0732' => 'PUERTO PAILAS',
		'0733' => 'LA GUARDIA',
		'0734' => 'VILLA SIMON BOLIVAR',
		'0735' => 'EL CARMEN (PROV. ANDRES IBANEZ)',
		'0736' => 'KM 12',
		'0737' => 'SAN JOSE',
		'0738' => 'SANTA RITA',
		'0739' => 'JOROCHITO',
		'0740' => 'LIMONCITO',
		'0741' => 'VALLE SANCHEZ',
		'0742' => 'OKINAWA I',
		'0743' => 'SAN MIGUEL',
		'0744' => 'SAN RAFAEL',
		'0745' => 'SAN CARLOS',
		'0746' => 'SANTA FE DE YAPACANI',
		'0747' => 'YAPACANI',
		'0748' => 'NUEVA ESPERANZA (COLONIA MENONITA)',
		'0749' => 'PAILON',
		'0750' => 'COLONIA MANITOBA',
		'0751' => 'COLONIA MENONITA BELICE',
		'0752' => 'SANTA ROSA DEL SARA',
		'0753' => 'LA BELGICA',
		'0754' => 'CHARAGUA',
		'0755' => 'COLONIA DURANGO',
		'0756' => 'COLONIA PINONDI',
		'0757' => 'ABAPO',
		'0758' => 'COLONIA MENONITA RIVAS PALACIOS',
		'0759' => 'COLONIA MENONITA SWIFT CURRENT',
		'0760' => 'BOYUIBE',
		'0762' => 'GENERAL SAAVEDRA',
		'0763' => 'CHANE INDEPENDENCIA',
		'0764' => 'PUESTO FERNANDEZ ALONZO',
		'0765' => 'HARDEMAN',
		'0766' => 'SAN JAVIER',
		'0767' => 'SAN RAMON',
		'0768' => 'CENTRAL 2',
		'0769' => 'CUATRO CANADAS',
		'0770' => 'COLONIA MENONITA VALLE ESPERANZA',
		'0772' => 'EL CARMEN (PROV. GERMAN BUSCH)',
		'0773' => 'URUBICHA',
		'0774' => 'EL PUENTE',
		'0775' => 'SANTA ROSA',
		'0776' => 'LAGUNILLAS (PROV. CORDILLERA)',
		'0777' => 'CABEZAS',
		'0778' => 'CUEVO',
		'0779' => 'GUTIERREZ',
		'0780' => 'COMUNIDAD LAGUNILLAS (PROV. VALLEGRANDE)',
		'0781' => 'SAN ANTONIO DE LOMERIO',
		'0782' => 'MINEROS',
		'0801' => 'TRINIDAD',
		'0802' => 'GUAYARAMERIN',
		'0803' => 'RIBERALTA',
		'0804' => 'RURRENABAQUE',
		'0805' => 'SANTA ROSA DE YACUMA',
		'0806' => 'SAN BORJA',
		'0807' => 'SAN JOAQUIN',
		'0808' => 'REYES',
		'0809' => 'MAGDALENA',
		'0810' => 'SANTA ANA DEL YACUMA',
		'0811' => 'SAN IGNACIO DE MOXOS',
		'0812' => 'YUCUMO',
		'0813' => 'EXALTACION',
		'0815' => 'SAN RAMON',
		'0816' => 'BELLA VISTA',
		'0817' => 'BAURES',
		'0818' => 'HUACARAJE',
		'0819' => 'SAN ANDRES',
		'0820' => 'SAN JAVIER',
		'0901' => 'COBIJA',
		'0902' => 'PUERTO RICO',
		'0903' => 'COMUNIDAD FILADELFIA',
	);

	public 
		$data,
		$err_mess,
		$err_flag = false;

	public function __construct($cx, $op, $req)
	{
		$this->cx = $cx;
		$this->op = $op;
		$this->method[$this->op]['var'] = $req;

		$this->message = "<soapenv:Envelope
			xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' 
			xmlns:ws='http://aqua.bisa.com/servicios/seguros/sudamericana/ws'>
			<soapenv:Header/>
			<soapenv:Body>
				<ws:" . $this->method[$this->op]['method'] . ">";
			foreach ($this->method[$this->op]['var'] as $key => $value) {
				$this->message .= "<ws:" . $key . ">" . $value . "</ws:" . $key . ">";
			}
		$this->message .="</ws:" . $this->method[$this->op]['method'] . ">
			</soapenv:Body>
		</soapenv:Envelope>";
	}

	private function wsConnect()
	{
		$this->client = new nusoap_client($this->wsdl, false);

		$this->client->authtype = 'certificate';
		$this->client->soap_defencoding = 'UTF-8';
		$this->client->setCredentials('sudamericana', 'sudamericana');
		$this->client->certRequest['sslcertfile'] = 'desarrolloBisa.cer';
		
		$this->err = $this->client->getError();

		if (!$this->err) {
			$this->data = $this->client->send($this->message, $this->soapAction, '', '');

		    if ($this->client->fault) {
			    $this->err_mess = $this->data;
			} else {
			    $this->err = $this->client->getError();
			    if ($this->err) {
			        $this->err_mess = $this->err;
			    } else {
			    	return true;
			    }
			}
		} else {
			$this->err_mess = $this->$err;
		}

		return false;
	}

	public function getDataCustomer()
	{
		if ($this->wsConnect()) {
			$this->data = $this->data['cliente'];

			if (!empty($this->data)) {
				$aux = $this->data;
				
				foreach ($aux as $key => $value) {
					$this->data[$key] = trim($value);
				}

				$this->data['ext'] = '';
				$this->data['type'] = $this->method['CD']['var']['tipoCliente'];

				if ($this->data['type'] === 'P') {
					if (($row = $this->cx->getExtenssionCode(substr($this->data['sigla'], 1))) !== false) {
						$this->data['sigla'] = $row['id_depto'];
						$this->data['ext'] = $row['d_codigo'];
					} else {
						$this->data['sigla'] = 1;
					}

					$this->data['fecNacimiento'] = date('Y-m-d', strtotime($this->data['fecNacimiento']));
					$this->data['estCivil'] = $this->cx->status[$this->data['estCivil']][0];
				}

				$this->data['full_name'] = $this->data['primerNombre'] . ' ' . $this->data['segundoNombre'] 
					. ' ' . $this->data['apPaterno'] . ' ' . $this->data['apMaterno'];

				return true;
			} else {
				$this->err_mess = 'El Cliente no Existe';
			}
		}

		return false;

	}

	public function getDataAccount()
	{
		if ($this->wsConnect()) {
			$this->data = $this->data['cuentas'];

			if (!empty($this->data)) {
				$accounts = $this->data;
				$this->data = array();

				if (is_array($accounts)) {
					foreach ($accounts['cuenta'] as $key => $value) {
						if (is_array($value)) {
							$value['account'] = serialize($value);
							$this->data[] = $value;
						} else {
							$accounts['cuenta']['account'] = serialize($accounts['cuenta']);
							$this->data[] = $accounts['cuenta'];
							break;
						}
					}
				}
			}
		}
	}

	public function getDataOperation()
	{
		if ($this->wsConnect()) {
			$this->data = $this->data['operacionesYGarantias'];

			if (!empty($this->data)) {
				$ops = $this->data;
				$this->data = array();

				if (is_array($ops)) {
					foreach ($ops['registro'] as $key => $value) {
						if (is_array($value)) {
							$value['moneda'] = $this->currency[$value['moneda']];
							$value['opperation'] = serialize($value);
							$this->data[] = $value;
						} else {
							$ops['registro']['moneda'] = $this->currency[$ops['registro']['moneda']];
							$ops['registro']['opperation'] = serialize($ops['registro']);
							$this->data[] = $ops['registro'];
							break;
						}
					}
				}
			}

		}
	}

	public function getPaymentPlan()
	{
		if ($this->wsConnect()) {
			$this->data = $this->data['plan'];

			if (!empty($this->data)) {
				$payment_plan = $this->data;
				$this->data = array();

				if (is_array($payment_plan)) {
					foreach ($payment_plan['pago'] as $key => $value) {
						if (is_array($value)) {
							$value['payment_plan'] = serialize($value);
							$this->data[] = $value;
						} else {
							$payment_plan['pago']['payment_plan'] = serialize($payment_plan['pago']);
							$this->data[] = $payment_plan['pago'];
							break;
						}
					}

					if (count($this->data) > 0) {
						return true;
					}
				}
			}
		}

		return false;
	}

	public function postLinkUp()
	{
		if ($this->wsConnect()) {
			if (count($this->data) > 0) {
				if ($this->data['resultado']['codigo'] === '0000') {
					return true;
				}
			}
		}

		return false;
	}

	public function getDataWarranty($pr)
	{
		$res = array(
			'A' => 'datosAutomotor',
			'T' => 'datosTodoRiesgo',
		);

		if ($this->wsConnect()) {
			if (count($this->data) > 0) {
				if (is_array($this->data[$res[$pr]])) {
					$this->data = $this->data[$res[$pr]]['registro'];

					foreach ($this->data as $key => $value) {
						$this->data[$key] = trim($value);
					}

					return true;
				}
			}
		}

		return false;
	}

}

?>