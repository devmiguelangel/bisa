<?php
// ini_set('display_errors', 1);
require __DIR__ . '/../nusoap/nusoap.php'; 

class BisaWs
{
	private 
		$cx,
		$soapAction,
		$wsdl,
		$client,
		$err,
		$message;

	private $method = [
		'CD' => [
			'method'	=> 'datosClienteRequest',
			'var' 		=> []
		],
		'AC' => [
			'method'	=> '',
			'var' 		=> []
		],
	];

	public 
		$data,
		$err_mess,
		$err_flag = true;

	public function __construct($cx)
	{
		$this->cx			= $cx;
		$this->soapAction 	= 'http://aqua.bisa.com/servicios/swissre/ws/';
		$this->wsdl			= 'http://10.200.3.82:8810/AquaWar/soap/definition-sudprueba.wsdl';
	}

	private function getMessage($m, $var)
	{
		$this->method[$m]['var'] = $var;

		$this->message = "<soapenv:Envelope xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' 
			xmlns:xsd='http://www.w3.org/2001/XMLSchema' 
			xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' 
			xmlns:ws='http://aqua.bisa.com/servicios/swissre/ws'>
			<soapenv:Header/>
			<soapenv:Body>
				<ws:" . $this->method[$m]['method'] . ">";
			foreach ($this->method[$m]['var'] as $key => $value) {
				$this->message .= "<ws:" . $key . ">" . $value . "</ws:" . $key . ">";
			}
		$this->message .="</ws:" . $this->method[$m]['method'] . ">
			</soapenv:Body>
		</soapenv:Envelope> ";
	}

	public function getData($m, $var)
	{
		$this->client = new nusoap_client($this->wsdl, false);

		$this->client->soap_defencoding = 'UTF-8';
		$this->client->setCredentials('sudprueba', 'HZ+hRGJnkiCK5bRsnnQcpw==');

		$this->err = $this->client->getError();
		if (!$this->err) {
			$this->getMessage($m, $var);
			
			$this->data = $this->client->send($this->message, $this->soapAction, '', '');

		    if ($this->client->fault) {
			    $this->err_mess = $this->data;
			} else {
			    $this->err = $this->client->getError();
			    if ($this->err) {
			        $this->err_mess = $this->err;
			    } else {
			    	$this->err_flag = false;

			    	switch ($m) {
		    		case 'CD':
		    			$this->getDataCustomer();
		    			break;
		    		case 'AD':
		    			
		    			break;
			    	}
			    }
			}
		} else {
			$this->err_mess = $this->$err;
		}
	}

	private function getDataCustomer()
	{
		if (count($this->data) > 2) {
			$aux = $this->data;
		
			foreach ($aux as $key => $value) {
				$this->data[$key] = trim($value);
			}

			if ($this->method['CD']['var']['tipoCliente'] === 'P') {
				if (($row = $this->cx->getExtenssionCode(substr($this->data['sigla'], 1))) !== false) {
					$this->data['sigla'] = $row['id_depto'];
				} else {
					$this->data['sigla'] = 1;
				}

				$this->data['fecNacimiento'] = date('Y-m-d', strtotime($this->data['fecNacimiento']));
				$this->data['estCivil'] = $this->cx->status[$this->data['estCivil']][0];
			}
		} else {
			$this->err_flag = true;
			$this->err_mess = 'El Cliente no Existe';
		}
	}

	public function getDataAccount()
	{
		
	}	

	

}

?>