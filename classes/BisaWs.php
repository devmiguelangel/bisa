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

					return true;
				}
			}
		}

		return false;
	}

}

?>