<?php
require('includes-ce/certificate-DE-EM.inc.php');
require('includes-ce/certificate-AU-EM.inc.php');
require('includes-ce/certificate-TRD-EM.inc.php');

require('includes-ce/certificate-AU-SC.inc.php');
require('includes-ce/certificate-TRD-SC.inc.php');

require('includes-ce/formulario-autorizacion-AU.inc.php');
require('includes-ce/formulario-autorizacion-TRD.inc.php');

require('includes-ce/formulario-UIF-AU.inc.php');
require('includes-ce/formulario-UIF-TRD.inc.php');

require('includes-ce/formulario-ASR-AU.inc.php');
require('includes-ce/formulario-ASR-TRD.inc.php');

require('includes-ce/formulario-CRT-AU.inc.php');
require('includes-ce/formulario-CRT-TRD.inc.php');


/**
 * 
 */
class CertificateHtml{
	protected $cx, $ide, $idc, $idef, $idcia, 
			$type, $category, $product, $page, $nCopy, $error, $implant, $fac, $reason,
			$sqlPo, $sqlDt, $rsPo, $rsDt, $rowPo, $rowDt, $url,
			$html, $self, $host;
	public $extra, $modality = false;
	private $host_ws = '';
	
	protected function __construct() {
		$self = $_SERVER['HTTP_HOST'];
		$this->url = 'http://' . $self . '/';
		
		if (($this->host_ws = $this->cx->getNameHostEF(base64_encode($this->rowPo['idef']))) !== false) {
			$this->host_ws .= '.';
		}
		
		if (strpos($self, 'localhost') !== false || filter_var($self, FILTER_VALIDATE_IP) !== false) {
			$this->url .= trim($this->host_ws, '.') . '/';
		} elseif (strpos($self, $this->host_ws . 'abrenet.com') === false){
			$this->url .= trim($this->host_ws, '.') . '/';
		} else {
			$this->url .= '';
		}
		
		if($this->type === 'PDF' || $this->type === 'ATCH'){
			$this->url = '';
		}
		
		switch ($this->category) {
		case 'SC':		//	Slip de Cotización
			$this->html = $this->get_html_sc();
			break;
		case 'CE':		//	Certificado
			$this->html = $this->get_html_ce();
			break;
		case 'FAT':    //formulario autorizacion
		    $this->html = $this->get_html_fat();
			break;
		case 'UIF':  //Formulario UIF
		    $this->html = $this->get_html_uif();		
			break;
		case 'ASR':  //Formulario Anexo de  Subrogacion
		    $this->html = $this->get_html_asr();
			break;
		case 'CRT':  //Carta de Sudamericana
		    $this->html = $this->get_html_crt();
			break;	
		}
	}
	
	//SLIP DE COTIZACION
	private function get_html_sc(){
		switch ($this->product){
		case 'DE':
		    return $this->set_html_de_sc();
		    break;
		case 'AU':
		    return $this->set_html_au_sc();
		    break;
	    case 'TRD':
		    return $this->set_html_trd_sc();
		    break;
		case 'TRM':
		    return $this->set_html_trm_sc();
		    break;
		} 
	}
			
	//CERTIFICADOS EMISION
	private function get_html_ce() {
		switch ($this->product) {
		case 'DE':
			return $this->set_html_de_em();
			break;
		case 'AU':
			 return $this->set_html_au_em();
			 break;
		case 'TRD':
			 return $this->set_html_trd_em();
			 break;
		case 'TRM':
			 return $this->set_html_trm_em();
			 break;
		case 'TH':
			 return $this->set_html_th_em();
			 break;
		}
	}
	
	//FORMULARIOS DE AUTORIZACION
	private function get_html_fat(){
		switch ($this->product){
		case 'AU':
		    return $this->set_html_au_fat();
		    break;
		case 'TRD':
		    return $this->set_html_trd_fat();
		    break;	
		} 
	}
	
	//FORMULARIOS DE IDENTIFICACION UIF
	private function get_html_uif(){
		switch ($this->product){
		case 'AU':
		    return $this->set_html_au_uif();
		    break;
		case 'TRD':
		    return $this->set_html_trd_uif();
		    break;	
		} 
	}
	
	//FORMULARIOS ANEXO DE SUBROGACION
	private function get_html_asr(){
		switch ($this->product){
		case 'AU':
		    return $this->set_html_au_asr();
		    break;
		case 'TRD':
		    return $this->set_html_trd_asr();
		    break;	
		} 
	}
	
	//CARTA SUDAMERICANA
	private function get_html_crt(){
		switch ($this->product){
		case 'AU':
		    return $this->set_html_au_crt();
		    break;
		case 'TRD':
		    return $this->set_html_trd_crt();
		    break;	
		} 
	}
	
	//SLIP DE COTIZACIONES
	private function set_html_de_sc(){ //DESGRAVAMEN SLIP
		if ($this->modality === false) {
			return de_sc_certificate($this->cx, $this->rowPo, $this->rsDt, $this->url, 
									$this->implant, $this->fac, $this->reason);
		} else {
			return de_sc_mo_certificate($this->cx, $this->rowPo, $this->rsDt, $this->url, 
									$this->implant, $this->fac, $this->reason);
		}
	}
	
	private function set_html_au_sc(){//AUTOMOTORES SLIP
	    return au_sc_certificate($this->cx, $this->rowPo, $this->rsDt, $this->url, 
	    						$this->implant, $this->fac, $this->reason);	
	}
	
	private function set_html_trd_sc(){//TODO RIESGO DOMICILIARIO SLIP
	    return trd_sc_certificate($this->cx, $this->rowPo, $this->rsDt, $this->url, 
	    						$this->implant, $this->fac, $this->reason);	
	}
	
	private function set_html_trm_sc(){//TODO RIESGO EQUIPO MOVIL SLIP
	    return trm_sc_certificate($this->cx, $this->rowPo, $this->rsDt, $this->url, 
	    						$this->implant, $this->fac, $this->reason);	
	}
			
	//CERTIFICADOS EMISIONES
	private function set_html_de_em() {	//	Desgravamen
		if ($this->modality === false) {
			switch ((int)$this->rowPo['id_certificado']) {
			case 1:
				return de_em_certificate($this->cx, $this->rowPo, $this->rsDt, $this->url, 
										$this->implant, $this->fac, $this->reason, $this->type);
				break;
			case 2:
				return de_em_certificate_aps($this->cx, $this->rowPo, $this->rsDt, $this->url, 
										$this->implant, $this->fac, $this->reason);
				break;
			default:
				break;
			}
		} else {
			return de_em_certificate_mo($this->cx, $this->rowPo, $this->rsDt, $this->url, 
									$this->implant, $this->fac, $this->reason);
		}
	}
	
	private function set_html_au_em() {	//	Automotores
		if ($this->modality === false) {
			return au_em_certificate($this->cx, $this->rowPo, $this->rsDt, $this->url, 
									$this->implant, $this->fac, $this->reason, $this->type);
		} else {
			return au_em_certificate_mo($this->cx, $this->rowPo, $this->rsDt, $this->url, 
									$this->implant, $this->fac, $this->reason, $this->type);
		}
	}
	
	private function set_html_trd_em() {	//	Todo Riesgo Domiciliario
		if ($this->modality === false) {
			return trd_em_certificate($this->cx, $this->rowPo, $this->rsDt, $this->url, 
									$this->implant, $this->fac, $this->reason, $this->type);
		} else {
			return trd_em_certificate_mo($this->cx, $this->rowPo, $this->rsDt, $this->url, 
									$this->implant, $this->fac, $this->reason, $this->type);
		}
	}
	
	private function set_html_trm_em() {	//	Todo Riesgo Equipo Movil
		if ($this->modality === false) {
			return trm_em_certificate($this->cx, $this->rowPo, $this->rsDt, $this->url, 
									$this->implant, $this->fac, $this->reason);
		} else {
			return trm_em_certificate_mo($this->cx, $this->rowPo, $this->rsDt, $this->url, 
									$this->implant, $this->fac, $this->reason);
		}
	}
	
	private function set_html_th_em() {	//	Tarjetahabiente
		if ($this->modality === false) {
			
		} else {
			$prefix = json_decode($this->rowPo['prefix'], true);
			if ($prefix['prefix'] === 'PTC') {
				return thc_em_certificate_mo($this->cx, $this->rowPo, $this->rsDt, $this->url, 
										$this->implant, $this->fac, $this->reason);
			} elseif ($prefix['prefix'] === 'PTD') {
				return thd_em_certificate_mo($this->cx, $this->rowPo, $this->rsDt, $this->url, 
										$this->implant, $this->fac, $this->reason);
			}
		}
	}
			
	//FORMULARIOS DE AUTORIZACION
	private function set_html_au_fat() { //Automotores
		return au_formulario_autorizacion($this->cx, $this->rowPo, $this->rsDt, $this->url, 
								$this->implant, $this->fac, $this->reason);
	}
	private function set_html_trd_fat() { //Todo Riesgo Domiciliario
		return trd_formulario_autorizacion($this->cx, $this->rowPo, $this->rsDt, $this->url, 
								$this->implant, $this->fac, $this->reason);
	}
	
	//FORMULARIOS DE IDENTIFICACION UIF
	private function set_html_au_uif() { //Automotores
		return au_formulario_uif($this->cx, $this->rowPo, $this->rsDt, $this->url, 
								$this->implant, $this->fac, $this->reason);
	}
	private function set_html_trd_uif() { //Todo Riesgo Domiciliario
		return trd_formulario_uif($this->cx, $this->rowPo, $this->rsDt, $this->url, 
								$this->implant, $this->fac, $this->reason);
	}
	
	//FORMULARIOS ANEXO DE SUBROGACION
	private function set_html_au_asr() { //Automotores
		return au_formulario_asr($this->cx, $this->rowPo, $this->rsDt, $this->url, 
								$this->implant, $this->fac, $this->reason);
	}
	private function set_html_trd_asr() { //Todo Riesgo Domiciliario
		return trd_formulario_asr($this->cx, $this->rowPo, $this->rsDt, $this->url, 
								$this->implant, $this->fac, $this->reason);
	}
	
	//CARTA DE SUDAMERICANA
	private function set_html_au_crt() { //Automotores
		return au_formulario_crt($this->cx, $this->rowPo, $this->rsDt, $this->url, 
								$this->implant, $this->fac, $this->reason);
	}
	private function set_html_trd_crt() { //Todo Riesgo Domiciliario
		return trd_formulario_crt($this->cx, $this->rowPo, $this->rsDt, $this->url, 
								$this->implant, $this->fac, $this->reason);
	}
	
}

?>