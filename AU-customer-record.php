<?php

require __DIR__ . '/classes/Logs.php';
require 'sibas-db.class.php';
require 'session.class.php';

$session = new Session();
$session->getSessionCookie();
$token = $session->check_session();

$arrAU = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo registrar el Cliente');
$log_msg = '';

if (isset($_POST['dc-token']) && isset($_POST['dc-idc']) 
		&& isset($_POST['ms']) && isset($_POST['page']) 
		&& isset($_POST['pr']) && isset($_POST['id-ef'])){
	
	if ($_POST['pr'] === base64_encode('AU|03')){
		$link = new SibasDB();

		$record = 0;
		
		$idc = $link->real_escape_string(trim(base64_decode($_POST['dc-idc'])));
		$idef = $link->real_escape_string(trim(base64_decode($_POST['id-ef'])));

		$sql = 'select 
			sac.no_cotizacion 
		from 
			s_au_cot_cabecera as sac
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
		
		$ws = $link->checkWebService($_SESSION['idEF'], 'AU');

		$idClient = 0;
		$flag = false;
		if (isset($_POST['dc-idCl'])) {
			$flag = true;
			$idClient = $link->real_escape_string(trim(base64_decode($_POST['dc-idCl'])));
		}
		
		$di_date_inception = date('Y-m-d');
		$di_method_payment = $link->real_escape_string(trim($_POST['di-method-payment']));
		$di_warranty = $link->real_escape_string(trim($_POST['di-warranty']));
		$di_term = 1;
		$di_type_term = 'Y';
		
		$dc_type_client = $link->real_escape_string(trim($_POST['dc-type-client']));
		$dc_code = $link->real_escape_string(trim(base64_decode($_POST['dc-code'])));
		$dc_name = $link->real_escape_string(trim($_POST['dc-name']));
		$dc_lnpatern = $link->real_escape_string(trim($_POST['dc-ln-patern']));
		$dc_lnmatern = $link->real_escape_string(trim($_POST['dc-ln-matern']));
		$dc_lnmarried = '';
		$dc_doc_id = $link->real_escape_string(trim($_POST['dc-doc-id']));
		$dc_comp = $link->real_escape_string(trim($_POST['dc-comp']));
		$dc_ext = $link->real_escape_string(trim($_POST['dc-ext']));
		$dc_birth = $link->real_escape_string(trim($_POST['dc-date-birth']));
		$dc_country = $link->real_escape_string(trim($_POST['dc-country']));
		$dc_status = $link->real_escape_string(trim($_POST['dc-status']));
		$dc_gender = '';
		$dc_address_home = $link->real_escape_string(trim($_POST['dc-address-home']));
		$dc_address_work = $link->real_escape_string(trim($_POST['dc-address-work']));
		$dc_desc_occ = $link->real_escape_string(trim($_POST['dc-desc-occ']));
		$dc_position = $link->real_escape_string(trim($_POST['dc-position']));
		$dc_monthly_income = $link->real_escape_string(trim($_POST['dc-monthly-income']));
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
		$dc_ex_ci = $link->real_escape_string(trim($_POST['dc-ex-ci']));
		$dc_ex_ext = $link->real_escape_string(trim($_POST['dc-ex-ext']));
		$dc_ex_birth = $link->real_escape_string(trim($_POST['dc-ex-birth']));
		$dc_ex_profession = $link->real_escape_string(trim($_POST['dc-ex-profession']));
		$dc_position2 = $link->real_escape_string(trim($_POST['dc-position2']));
		$dc_phone_office2 = $link->real_escape_string(trim($_POST['dc-phone-office2']));
		$dc_company_email = $link->real_escape_string(trim($_POST['dc-company-email']));
		$dc_type_company = $link->real_escape_string(trim($_POST['dc-type-company']));
		$dc_registration_number = $link->real_escape_string(trim($_POST['dc-registration-number']));
		$dc_license_number = $link->real_escape_string(trim($_POST['dc-license-number']));
		$dc_number_vifpe = $link->real_escape_string(trim($_POST['dc-number-vifpe']));
		$dc_antiquity = $link->real_escape_string(trim($_POST['dc-antiquity']));
		$dc_monthly_income2 = $link->real_escape_string(trim($_POST['dc-monthly-income2']));
		$data = array();
		
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));
		
		if ($dc_type_client === 'NAT'){
			$dc_type_client = false;
		} elseif ($dc_type_client === 'JUR'){
			$dc_type_client = true;
			$dc_doc_id = $dc_nit;
			$dc_ext = $dc_depto;
			$dc_address_home = $dc_address_home2;
			$dc_address_work = $dc_address_work2;
			$dc_position = $dc_position2;
			$dc_email = $dc_company_email;
			$dc_phone_office = $dc_phone_office2;
			$dc_monthly_income = $dc_monthly_income2;

			$data = array(
				'type_company' 			=> $dc_type_company,
				'registration_number' 	=> $dc_registration_number,
				'license_number' 		=> $dc_license_number,
				'number_vifpe' 			=> $dc_number_vifpe,
				'antiquity' 			=> $dc_antiquity,
				'executive_ci'			=> $dc_ex_ci,
				'executive_ext'			=> $dc_ex_ext,
				'executive_birth'		=> $dc_ex_birth,
				'executive_profession'	=> $dc_ex_profession
			);
		}
		
		$year = $link->get_year_final($di_term, $di_type_term);
		$di_date_end = '';

		$di_date_end = date('Y-m-d', strtotime($di_date_inception . ' + ' . $year . ' year'));
		
		if (($ws && empty($dc_code) === false) || ($ws === false)) {
			$swAge = 0;
			if ($dc_type_client === false){
				$sqlAge = 'select 
					count(ssh.id_home) as token,
					ssh.edad_max,
					ssh.edad_min,
					(TIMESTAMPDIFF(year,
						"' . $dc_birth . '",
						curdate()) between ssh.edad_min and ssh.edad_max) as flag
				from
					s_sgc_home as ssh
						inner join 
					s_entidad_financiera as sef ON (sef.id_ef = ssh.id_ef)
				where
					ssh.producto = "AU"
						and sef.id_ef = "' . $idef . '"
						and sef.activado = true
				;';
				
				$rsAge = $link->query($sqlAge,MYSQLI_STORE_RESULT);
				$rowAge = $rsAge->fetch_array(MYSQLI_ASSOC);
				$swAge = (int)$rowAge['flag'];
				$rsAge->free();
			} else {
				$swAge = 1;
			}
			
			if ($swAge === 1){
				$sql = '';
				$vc = $link->verify_customer($dc_doc_id, $dc_ext, $idef, 'AU');
				
				if ($vc[0]) {
					$idClient = $vc[1];
					
					$sql = 'update s_au_cot_cliente 
					set codigo_bb = "' . $dc_code . '",
						razon_social = "' . $dc_company_name . '", 
						paterno = "' . $dc_lnpatern . '", 
						materno = "' . $dc_lnmatern . '", 
						nombre = "' . $dc_name . '", 
						ap_casada = "' . $dc_lnmarried . '", 
						fecha_nacimiento = "' . $dc_birth . '", 
						pais = "' . $dc_country . '", 
						ci = "' . $dc_doc_id . '", 
						extension = "' . $dc_ext . '", 
						complemento = "' . $dc_comp . '", 
						genero = "' . $dc_gender . '",
						estado_civil = "' . $dc_status . '",
						direccion_domicilio = "' . $dc_address_home . '",
						direccion_laboral = "' . $dc_address_work . '",
						desc_ocupacion = "' . $dc_desc_occ . '",
						ingreso_mensual = "' . $dc_monthly_income . '",
						actividad = "' . $dc_activity . '", 
						ejecutivo = "' . $dc_executive . '", 
						cargo = "' . $dc_position . '",
						telefono_domicilio = "' . $dc_phone_1 . '", 
						telefono_oficina = "' . $dc_phone_office . '", 
						telefono_celular = "' . $dc_phone_2 . '", 
						email = "' . $dc_email . '",
						data_jur = "' . $link->real_escape_string(json_encode($data)) . '"
					where 
						id_cliente = "' . $idClient . '" 
							and id_ef = "' . $idef . '" ;';
				} else {
					$idClient = uniqid('@S#2$2013', true);
					
					$sql = 'insert into s_au_cot_cliente 
					(id_cliente, id_ef, tipo, codigo_bb, razon_social, paterno, materno, 
						nombre, ap_casada, fecha_nacimiento, pais, ci, extension, 
						complemento, genero, estado_civil, direccion_domicilio, 
						direccion_laboral, desc_ocupacion, ingreso_mensual,
						actividad, ejecutivo, cargo, 
						telefono_domicilio, telefono_oficina, 
						telefono_celular, email, data_jur, created_at) 
					values 
					("' . $idClient . '", "' . $idef . '", 
						"' . (int)$dc_type_client . '",
						"' . $dc_code . '", 
						"' . $dc_company_name . '", "' . $dc_lnpatern . '", 
						"' . $dc_lnmatern . '", "' . $dc_name . '", 
						"' . $dc_lnmarried . '", "' . $dc_birth . '", 
						"' . $dc_country . '", "' . $dc_doc_id . '", 
						"' . $dc_ext . '", "' . $dc_comp . '", 
						"' . $dc_gender . '", "' . $dc_status . '", 
						"' . $dc_address_home . '", "' . $dc_address_work . '", 
						"' . $dc_desc_occ . '", "' . $dc_monthly_income . '", 
						"' . $dc_activity . '", "' . $dc_executive . '", 
						"' . $dc_position . '", "' . $dc_phone_1 . '", 
						"' . $dc_phone_office . '", "' . $dc_phone_2 . '", 
						"' . $dc_email . '", 
						"' . $link->real_escape_string(json_encode($data)) . '", 
						now()) ;';
				}
				
				if ($link->query($sql)){
					$sqlIn = 'update s_au_cot_cabecera 
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
					
					if ($link->query($sqlIn)){
						$arrAU[0] = 1;
						$arrAU[1] = 'au-quote.php?ms=' . $ms . '&page=' . $page 
							. '&pr=' . $pr . '&idc=' . base64_encode($idc);
						$arrAU[2] = 'Cliente registrado con Exito';

						$log_msg = 'AU - Cot. ' . $record . ' / Record Client';

						$db = new Log($link);
						$db->postLog($_SESSION['idUser'], $log_msg);

					} else {
						$arrAU[2] = 'No se pudo registrar los datos del Seguro Solicitado';
					}
				} else {
					$arrAU[2] = 'No se pudo registrar el Cliente';
				}
			} else {
				$arrAU[2] = 'La Fecha de Nacimiento no esta en el rango permitido de Edades [ ' 
					. $rowAge['edad_min'] . ' - ' . $rowAge['edad_max'] . ' ]';
			}
		} else {
			$arrAU[2] = 'El Cliente no pertenece al Banco';
		}

	}
}

echo json_encode($arrAU);

?>