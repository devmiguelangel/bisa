<?php

require __DIR__ . '/classes/Logs.php';
require 'sibas-db.class.php';
require 'session.class.php';

$session = new Session();
$session->getSessionCookie();
$token = $session->check_session();

$arrTR = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo registrar el Cliente');
$log_msg = '';

if(isset($_POST['dc-token']) && isset($_POST['dc-idc']) && isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr']) && isset($_POST['id-ef'])){
	
	if($_POST['pr'] === base64_encode('TRD|03')){
		$link = new SibasDB();
		
		$idc = $link->real_escape_string(trim(base64_decode($_POST['dc-idc'])));
		$idef = $link->real_escape_string(trim(base64_decode($_POST['id-ef'])));
		$record = 0;

		$sql = 'select 
			sac.no_cotizacion 
		from 
			s_trd_cot_cabecera as sac
		where 
			sac.id_cotizacion = "' . $idc . '"
		limit 0, 1
		;';

		if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
			if ($rs->num_rows === 1) {
				$row = $rs->fetch_array(MYSQLI_ASSOC);
				$rs->free();
				$record = $row['no_cotizacion'];
			}
		}
		
		$idClient = 0;
		$flag = FALSE;
		if(isset($_POST['dc-idCl'])){
			$flag = TRUE;
			$idClient = $link->real_escape_string(trim(base64_decode($_POST['dc-idCl'])));
		}
		
		$di_date_inception = date('Y-m-d');
		$di_term = 1;
		$di_type_term = 'Y';
		$di_method_payment = $link->real_escape_string(trim($_POST['di-method-payment']));
		$di_warranty = (int)$link->real_escape_string(trim($_POST['di-warranty']));
		
		$dc_type_client = $link->real_escape_string(trim($_POST['dc-type-client']));
		$dc_name = $link->real_escape_string(trim($_POST['dc-name']));
		$dc_lnpatern = $link->real_escape_string(trim($_POST['dc-ln-patern']));
		$dc_lnmatern = $link->real_escape_string(trim($_POST['dc-ln-matern']));
		$dc_lnmarried = '';
		$dc_doc_id = $link->real_escape_string(trim($_POST['dc-doc-id']));
		$dc_comp = $link->real_escape_string(trim($_POST['dc-comp']));
		$dc_ext = $link->real_escape_string(trim($_POST['dc-ext']));
		$dc_birth = $link->real_escape_string(trim($_POST['dc-date-birth']));
		$dc_gender = '';
		$dc_address_home = $link->real_escape_string(trim($_POST['dc-address-home']));
		$dc_address_work = $link->real_escape_string(trim($_POST['dc-address-work']));
		$dc_phone_1 = $link->real_escape_string(trim($_POST['dc-phone-1']));
		$dc_phone_office = $link->real_escape_string(trim($_POST['dc-phone-office']));
		$dc_phone_2 = $link->real_escape_string(trim($_POST['dc-phone-2']));
		$dc_email = $link->real_escape_string(trim($_POST['dc-email']));
		
		$dc_company_name = $link->real_escape_string(trim($_POST['dc-company-name']));
		$dc_nit = $link->real_escape_string(trim($_POST['dc-nit']));
		$dc_depto = $link->real_escape_string(trim($_POST['dc-depto']));
		$dc_address_home2 = $link->real_escape_string(trim($_POST['dc-address-home2']));
		$dc_address_work2 = $link->real_escape_string(trim($_POST['dc-address-work2']));
		$dc_activity = $link->real_escape_string(trim($_POST['dc-activity']));
		$dc_executive = $link->real_escape_string(trim($_POST['dc-executive']));
		$dc_position = $link->real_escape_string(trim($_POST['dc-position']));
		$dc_phone_office2 = $link->real_escape_string(trim($_POST['dc-phone-office2']));
		$dc_company_email = $link->real_escape_string(trim($_POST['dc-company-email']));
		
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));
		
		if ($dc_type_client === 'NAT') {
			$dc_type_client = FALSE;
		} elseif ($dc_type_client === 'JUR') {
			$dc_type_client = TRUE;
			$dc_doc_id = $dc_nit;
			$dc_ext = $dc_depto;
			$dc_address_home = $dc_address_home2;
			$dc_address_work = $dc_address_work2;
			$dc_email = $dc_company_email;
			$dc_phone_office = $dc_phone_office2;
		}
		
		$year = $link->get_year_final($di_term, $di_type_term);
		$di_date_end = '';
		
		$di_date_end = date('Y-m-d', strtotime($di_date_inception.' + '.$year.' year'));
		
		$swAge = 0;
		if ($dc_type_client === FALSE) {
			$sqlAge = 'SELECT 
					COUNT(ssh.id_home) as token,
					ssh.edad_max,
					ssh.edad_min,
					(TIMESTAMPDIFF(year,
						"' . $dc_birth . '",
						curdate()) between ssh.edad_min and ssh.edad_max) as flag
				from
					s_sgc_home as ssh
						inner join s_entidad_financiera as sef ON (sef.id_ef = ssh.id_ef)
				where
					ssh.producto = "TRD"
						and sef.id_ef = "'.$idef.'"
						and sef.activado = true
				;';
			
			$rsAge = $link->query($sqlAge,MYSQLI_STORE_RESULT);
			$rowAge = $rsAge->fetch_array(MYSQLI_ASSOC);
			$swAge = (int)$rowAge['flag'];
			$rsAge->free();
		} else {
			$swAge = 1;
		}
		
		if($swAge === 1){
			$sql = '';
			
			$vc = $link->verify_customer($dc_doc_id, $dc_ext, $idef, 'TRD');
			
			if($vc[0]){
				$idClient = $vc[1];
				
				$sql = 'update s_trd_cot_cliente 
				set razon_social = "' . $dc_company_name . '", 
					paterno = "' . $dc_lnpatern . '", 
					materno = "' . $dc_lnmatern . '", 
					nombre = "' . $dc_name . '", 
					ap_casada = "' . $dc_lnmarried . '", 
					fecha_nacimiento = "' . $dc_birth . '", 
					ci = "' . $dc_doc_id . '", 
					extension = ' . $dc_ext . ', 
					complemento = "' . $dc_comp . '", 
					genero = "' . $dc_gender . '",
					direccion_domicilio = "' . $dc_address_home . '",
					direccion_laboral = "' . $dc_address_work . '",
					actividad = "' . $dc_activity . '", 
					ejecutivo = "' . $dc_executive . '", 
					cargo = "' . $dc_position . '",
					telefono_domicilio = "' . $dc_phone_1 . '", 
					telefono_oficina = "' . $dc_phone_office . '", 
					telefono_celular = "' . $dc_phone_2 . '", 
					email = "' . $dc_email . '"
				where 
					id_cliente = "' . $idClient . '" 
						and id_ef = "' . $idef . '" ;';
			} else {
				$idClient = uniqid('@S#3$2013',true);
				
				$sql = 'insert into s_trd_cot_cliente 
				(id_cliente, id_ef, tipo, razon_social, paterno, materno, 
					nombre, ap_casada, fecha_nacimiento, ci, extension, 
					complemento, genero, direccion_domicilio, direccion_laboral,
					actividad, ejecutivo, cargo, 
					telefono_domicilio, telefono_oficina, 
					telefono_celular, email) 
				values 
				("' . $idClient . '", "' . $idef . '", ' . (int)$dc_type_client . ', 
					"' . $dc_company_name . '", "' . $dc_lnpatern . '", 
					"' . $dc_lnmatern . '", "' . $dc_name . '", 
					"' . $dc_lnmarried . '", "' . $dc_birth . '", 
					"' . $dc_doc_id . '", "' . $dc_ext . '", "' . $dc_comp . '", 
					"' . $dc_gender . '", "' . $dc_address_home . '", 
					"' . $dc_address_work . '", "' . $dc_activity . '", 
					"' . $dc_executive . '", "' . $dc_position . '", 
					"' . $dc_phone_1 . '", "' . $dc_phone_office . '", 
					"' . $dc_phone_2 . '", "' . $dc_email . '") ;';
			}
			
			if($link->query($sql)){
				$sqlIn = 'update s_trd_cot_cabecera 
				set id_cliente = "' . $idClient . '", 
					tipo = "' . (int)$dc_type_client . '", 
					garantia = "' . $di_warranty . '", 
					ini_vigencia = "' . $di_date_inception . '", 
					fin_vigencia = "' . $di_date_end . '", 
					forma_pago = "' . $di_method_payment . '", 
					plazo = "' . $di_term . '", 
					tipo_plazo = "' . $di_type_term . '" 
				where 
					id_cotizacion = "' . $idc . '" ;';
				
				if($link->query($sqlIn)){
					$arrTR[0] = 1;
					$arrTR[1] = 'trd-quote.php?ms=' . $ms . '&page=' . $page 
						. '&pr=' . $pr . '&idc=' . base64_encode($idc);
					$arrTR[2] = 'Cliente registrado con Exito';

					$log_msg = 'TRD - Cot. ' . $record . ' / Record Client';

					$db = new Log($link);
					$db->postLog($_SESSION['idUser'], $log_msg);

					var_dump($arrTR);
					exit();
				} else {
					$arrTR[2] = 'No se pudo registrar los datos del Seguro Solicitado';
				}
			}else {
				$arrTR[2] = 'No se pudo registrar el Cliente';
			}
		}else{
			$arrTR[2] = 'La Fecha de Nacimiento no esta en el rango permitido de Edades [ '.$rowAge['edad_min'].' - '.$rowAge['edad_max'].' ]';
		}
		echo json_encode($arrTR);
	}else{
		echo json_encode($arrTR);
	}
}else{
	echo json_encode($arrTR);
}
?>