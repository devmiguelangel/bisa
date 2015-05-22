<?php

require __DIR__ . '/classes/Logs.php';
require __DIR__ . '/classes/BisaWs.php';
require 'sibas-db.class.php';
require 'session.class.php';

$session = new Session();
$session->getSessionCookie();
$token = $session->check_session();

$arrAU = array(0 => 0, 1 => 'R', 2 => 'Error: No se pudo registrar la Póliza');
$log_msg = '';

if((isset($_POST['de-ide']) || isset($_POST['de-idc'])) && isset($_POST['dc-type-client']) 
		&& isset($_POST['ms']) && isset($_POST['page']) && isset($_POST['pr']) && isset($_POST['cia'])){
	
	if($_POST['pr'] === base64_encode('AU|05') && $token === TRUE){
		$link = new SibasDB();
		
		$_FAC = FALSE;
		$_FAC_REASON = '';
		$PRIMA = 0;
		
		$swMo = false;
		$record = 0;
		
		$ms = $link->real_escape_string(trim($_POST['ms']));
		$page = $link->real_escape_string(trim($_POST['page']));
		$pr = $link->real_escape_string(trim($_POST['pr']));
		
		$max_item = $max_amount = $max_anio = 0;
		if (($rowAU = $link->get_max_amount_optional($_SESSION['idEF'], 'AU')) !== FALSE) {
			$max_item = (int)$rowAU['max_item'];
			$max_amount = (int)$rowAU['max_monto'];
			$max_anio = (int)$rowAU['max_anio'];
		}
		
		$target = '';
		if(isset($_POST['target'])) {
			$target = '&target='.$link->real_escape_string(trim($_POST['target']));
		}
		
		$flag = $_POST['flag'];
		
		$sw = 0;
		switch($flag){
			case md5('i-new'):		//POLIZA NUEVA
				$sw = 1;
				break;
			case md5('i-read'):		//
				$sw = 2;
				break;
			case md5('i-edit'):		//POLIZA ACTUALIZADA
				$sw = 3;
				break;
		}
		
		$ide = $idc = $idcia = '';
		if(isset($_POST['de-ide'])) {
			$ide = $link->real_escape_string(trim(base64_decode($_POST['de-ide'])));
		} elseif(isset($_POST['de-idc'])) {
			$idc = $link->real_escape_string(trim(base64_decode($_POST['de-idc'])));
			$ide = uniqid('@S#2$2013',true);
		}

		$ws_db = $link->checkWebService($_SESSION['idEF'], 'AU');
		
		if($sw !== 0){
			$data = array();

			$idcia = $link->real_escape_string(trim(base64_decode($_POST['cia'])));
			$dcr_amount = 0;
			$dcr_warranty = (int)$link->real_escape_string(trim(base64_decode($_POST['di-warranty'])));
			$approved = 1;
			if ($dcr_warranty === 1) {
				$approved = 0;
			}
			$dcr_date_begin = $link->real_escape_string(trim(base64_decode($_POST['di-date-inception'])));
			$dcr_date_end = $link->real_escape_string(trim(base64_decode($_POST['di-end-inception'])));
			$dcr_term = 1;
			$dcr_type_term = 'Y';
			if ($dcr_warranty === 1) {
				$dcr_term = $link->real_escape_string(trim($_POST['di-term']));
				$dcr_type_term = $link->real_escape_string(trim($_POST['di-type-term']));
			}
			$dcr_method_payment = $link->real_escape_string(trim($_POST['di-method-payment']));
			$codeMethodPayment = '';
			$dcr_opp = '';
			if (isset($_POST['di-opp'])) {
				$dcr_opp = trim($_POST['di-opp']);
				$dcr_opp = unserialize($dcr_opp);
				$dcr_opp = $link->real_escape_string(json_encode($dcr_opp));
			}
			$dcr_policy = 'null';
			if (isset($_POST['di-policy'])) {
				$dcr_policy = '"' . $link->real_escape_string(trim(base64_decode($_POST['di-policy']))) . '"';
			}
			
			$prefix = array();
			$arrPrefix = 'null';
			
			$bl_name = $bl_nit = $taken_name = $taken_nit = '';
			$taken_name = $link->real_escape_string(trim($_POST['taken-name']));
			$taken_nit = $link->real_escape_string(trim($_POST['taken-nit']));
			
			if(isset($_POST['bl-name']) && isset($_POST['bl-nit'])){
				$bl_name = $link->real_escape_string(trim($_POST['bl-name']));
				$bl_nit = $link->real_escape_string(trim($_POST['bl-nit']));
			}
			
			$cp = NULL;
			if($sw === 1 && isset($_POST['cp'])) {
				$cp = (int)$link->real_escape_string(trim(base64_decode($_POST['cp'])));
			}	
			
			$cl_type_client = (boolean)$link->real_escape_string(trim(base64_decode($_POST['dc-type-client'])));
			$cl_code = $link->real_escape_string(trim(base64_decode($_POST['dc-code'])));
			$idcl = 
			$cl_name = 
			$cl_patern = 
			$cl_matern = 
			$cl_married = 
			$cl_dni = 
			$cl_ci = 
			$cl_nit = 
			$cl_comp = 
			$cl_ext = 
			$cl_date_birth = 
			$cl_country = 
			$cl_status = 
			$cl_gender = 
			$cl_place_res = 
			$cl_locality = 
			$cl_phone_home = 
			$cl_phone_cel = 
			$cl_avc = 
			$cl_address_home = 
			$cl_nhome = 
			$cl_occupation = 
			$cl_desc_occ = 
			$cl_address_work = 
			$cl_phone_office = 
			$cl_email = 
			$cl_company_name = 
			$cl_position = 
			$cl_monthly_income = 
			$cl_executive = 
			$cl_ex_ci =
			$cl_ex_ext =
			$cl_ex_birth =
			$cl_ex_profession =
			$cl_activity = 
			$account = 
			$attached = '';
			$cl_place_res = 'null';
			$cl_occupation = 'null';
			
			if(isset($_POST['dc-idcl'])) {
				$idcl = $link->real_escape_string(trim(base64_decode($_POST['dc-idcl'])));
			} else { $idcl = uniqid('@S#2$2013', true); }
			
			
			if($cl_type_client === FALSE){
				$cl_name = $link->real_escape_string(trim($_POST['dc-name']));
				$cl_patern = $link->real_escape_string(trim($_POST['dc-ln-patern']));
				$cl_matern = $link->real_escape_string(trim($_POST['dc-ln-matern']));
				$cl_married = '';
				$cl_ci = $link->real_escape_string(trim($_POST['dc-doc-id']));
				$cl_comp = $link->real_escape_string(trim($_POST['dc-comp']));
				$cl_ext = $link->real_escape_string(trim($_POST['dc-ext']));
				$cl_gender = '';
				$cl_date_birth = $link->real_escape_string(trim($_POST['dc-date-birth']));
				$cl_country = $link->real_escape_string(trim($_POST['dc-country']));
				$cl_status = $link->real_escape_string(trim($_POST['dc-status']));
				$cl_locality = '';
				$cl_phone_home = $link->real_escape_string(trim($_POST['dc-phone-1']));
				$cl_phone_cel = $link->real_escape_string(trim($_POST['dc-phone-2']));
				$cl_email = $link->real_escape_string(trim($_POST['dc-email']));
				$cl_avc = '';
				$cl_address_home = $link->real_escape_string(trim($_POST['dc-address-home']));
				$cl_nhome = '';
				$cl_desc_occ = $link->real_escape_string(trim($_POST['dc-desc-occ']));
				$cl_position = $link->real_escape_string(trim($_POST['dc-position']));
				$cl_monthly_income = $link->real_escape_string(trim($_POST['dc-monthly-income']));
				$cl_address_work = $link->real_escape_string(trim($_POST['dc-address-work']));
				$cl_phone_office = $link->real_escape_string(trim($_POST['dc-phone-office']));
				$cl_dni = $cl_ci;
				$account = trim($_POST['dc-account-nat']);
			}else{
				$cl_company_name = $link->real_escape_string(trim($_POST['dc-company-name']));
				$cl_nit = $link->real_escape_string(trim($_POST['dc-nit']));
				$cl_ext = $link->real_escape_string(trim($_POST['dc-depto']));
				$cl_phone_office = $link->real_escape_string(trim($_POST['dc-company-phone-office']));
				$cl_email = $link->real_escape_string(trim($_POST['dc-company-email']));
				$cl_address_home = $link->real_escape_string(trim($_POST['dc-company-address-home']));
				$cl_address_work = $link->real_escape_string(trim($_POST['dc-company-address-work']));
				$cl_date_birth = date('Y-m-d', time());
				$cl_dni = $cl_nit;
				$cl_activity = $link->real_escape_string(trim($_POST['dc-activity']));
				$cl_type_company = $link->real_escape_string(trim($_POST['dc-type-company']));
				$cl_registration_number = $link->real_escape_string(trim($_POST['dc-registration-number']));
				$cl_license_number = $link->real_escape_string(trim($_POST['dc-license-number']));
				$cl_number_vifpe = $link->real_escape_string(trim($_POST['dc-number-vifpe']));
				$cl_antiquity = $link->real_escape_string(trim($_POST['dc-antiquity']));
				$cl_executive = $link->real_escape_string(trim($_POST['dc-executive']));
				$cl_ex_ci = $link->real_escape_string(trim($_POST['dc-ex-ci']));
				$cl_ex_ext = $link->real_escape_string(trim($_POST['dc-ex-ext']));
				$cl_ex_birth = $link->real_escape_string(trim($_POST['dc-ex-birth']));
				$cl_ex_profession = $link->real_escape_string(trim($_POST['dc-ex-profession']));
				$cl_position = $link->real_escape_string(trim($_POST['dc-position2']));
				$cl_monthly_income = $link->real_escape_string(trim($_POST['dc-monthly-income2']));
				$account = trim($_POST['dc-account-jur']);

				$data = [
					'type_company' 			=> $cl_type_company,
					'registration_number' 	=> $cl_registration_number,
					'license_number' 		=> $cl_license_number,
					'number_vifpe' 			=> $cl_number_vifpe,
					'antiquity' 			=> $cl_antiquity,
					'executive_ci'			=> $cl_ex_ci,
					'executive_ext'			=> $cl_ex_ext,
					'executive_birth'		=> $cl_ex_birth,
					'executive_profession'	=> $cl_ex_profession
				];

				//$cl_company_name = $link->real_escape_string(trim($_POST['dc-']));
			}

			if ($sw === 1 && $ws_db) {
				$account = unserialize($account);
				$account = $link->real_escape_string(json_encode($account));
			}

			
			// $cl_attached = $link->real_escape_string(trim(base64_decode($_POST['dc-attached'])));
			$cl_attached = '';
			$nVh = (int)$link->real_escape_string(trim(base64_decode($_POST['nVh'])));

			$swCl = FALSE;
			$sqlSCl = '';
			if($sw === 1) {
				$sqlSCl = 'select 
						scl.id_cliente as idCl, scl.ci as cl_ci, scl.extension as cl_extension
					from
						s_cliente as scl
							inner join s_entidad_financiera as sef ON (sef.id_ef = scl.id_ef)
					where
						scl.ci = "'.$cl_dni.'" and scl.extension = '.$cl_ext.' and scl.tipo = '.(int)$cl_type_client.'
							and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
					;';
				
				if(($rsSCl = $link->query($sqlSCl,MYSQLI_STORE_RESULT))){
					if($rsSCl->num_rows === 1){
						$rowSCl = $rsSCl->fetch_array(MYSQLI_ASSOC);
						$rsSCl->free();
						$idcl = $rowSCl['idCl'];
						$swCl = TRUE;
					}
				}
			}
			
			$arr_vh = array();
			
			if($nVh <= $max_item){
				for($k = 1; $k <= $nVh; $k++){
					if(isset($_POST['dv-'.$k.'-idvh'])) { 
						$arr_vh[$k]['idvh'] = $link->real_escape_string(trim(base64_decode($_POST['dv-'.$k.'-idvh'])));
					} else { $arr_vh[$k]['idvh'] = uniqid('@S#2$2013'.$k, true); }
					
					$arr_vh[$k]['type-vehicle'] = $link->real_escape_string(trim(base64_decode($_POST['dv-'.$k.'-type-vehicle'])));
					$arr_vh[$k]['category'] = '';
					$arr_vh[$k]['make'] = $link->real_escape_string(trim(base64_decode($_POST['dv-'.$k.'-make'])));
					$arr_vh[$k]['model'] = $link->real_escape_string(trim(base64_decode($_POST['dv-'.$k.'-model'])));
					$arr_vh[$k]['year'] = $link->real_escape_string(trim(base64_decode($_POST['dv-'.$k.'-year'])));
					$arr_vh[$k]['use'] = $link->real_escape_string(trim(base64_decode($_POST['dv-'.$k.'-use'])));
					$arr_vh[$k]['plate'] = $link->real_escape_string(trim($_POST['dv-'.$k.'-plate']));
					$arr_vh[$k]['traction'] = $link->real_escape_string(trim(base64_decode($_POST['dv-'.$k.'-traction'])));
					$arr_vh[$k]['km'] = '';
					
					$arr_vh[$k]['color'] = $link->real_escape_string(trim($_POST['dv-'.$k.'-color']));
					$arr_vh[$k]['motor'] = $link->real_escape_string(trim($_POST['dv-'.$k.'-motor']));
					$arr_vh[$k]['chassis'] = $link->real_escape_string(trim($_POST['dv-'.$k.'-chassis']));
					$arr_vh[$k]['capton'] = $link->real_escape_string(trim($_POST['dv-'.$k.'-capton']));
					$arr_vh[$k]['nseat'] = $link->real_escape_string(trim($_POST['dv-'.$k.'-nseat']));
					$arr_vh[$k]['modality'] = 'null';
					if (isset($_POST['dv-' . $k . '-modality'])) {
						$swMo = true;
						$arr_vh[$k]['modality'] = '"' 
							. $link->real_escape_string(trim(base64_decode($_POST['dv-'.$k.'-modality']))) . '"';
					}
					
					$arr_vh[$k]['value-insured'] = $link->real_escape_string(trim(base64_decode($_POST['dv-'.$k.'-value-insured'])));
					$arr_vh[$k]['rate'] = $link->real_escape_string(trim(base64_decode($_POST['dv-'.$k.'-rate'])));
					$arr_vh[$k]['premium'] = $link->real_escape_string(trim(base64_decode($_POST['dv-'.$k.'-premium'])));
					// $arr_vh[$k]['attached'] = $link->real_escape_string(trim(base64_decode($_POST['dv-'.$k.'-attached'])));
					$arr_vh[$k]['attached'] = '';
					
					$arr_vh[$k]['FAC'] = FALSE;
					$arr_vh[$k]['reason'] = '';
					$arr_vh[$k]['approved'] = TRUE;
					
					if($arr_vh[$k]['value-insured'] > $max_amount){
						$arr_vh[$k]['FAC'] = TRUE;
						$_FAC = TRUE;
						$arr_vh[$k]['reason'] .= '| El valor asegurado del Vehículo excede el 
							máximo valor permitido. Valor permitido: ' 
							. number_format($max_amount, 2, '.', ',') . ' USD';
					}
					
					if($arr_vh[$k]['FAC'] === TRUE) { $arr_vh[$k]['approved'] = FALSE; }
					
					$_FAC_REASON .= $arr_vh[$k]['reason'];
					$PRIMA += $arr_vh[$k]['premium'];
				}
			}else {
				$arrAU[2] = 'Los Vehículos no pueden ser Registrados';
			}

			if ($_FAC) {
				$approved = 0;
			}
			
			$swReg = FALSE;
			$sql = $sqlCl = '';
			if($sw === 1) {
				if($swCl === FALSE) {	// REGISTRAR POLIZAc
					$sqlCl = 'INSERT INTO s_cliente 
					(id_cliente, id_ef, tipo, codigo_bb, razon_social, paterno, materno, 
						nombre, ap_casada, fecha_nacimiento, lugar_nacimiento, 
						ci, extension, complemento, tipo_documento, estado_civil, 
						ci_archivo, lugar_residencia, localidad, avenida, direccion, 
						no_domicilio, direccion_laboral, pais, id_ocupacion, 
						desc_ocupacion, ingreso_mensual, actividad, ejecutivo, 
						cargo, telefono_domicilio, telefono_oficina, 
						telefono_celular, email, peso, estatura, genero, edad, 
						mano, data_jur, created_at) 
					VALUES 
					("'.$idcl.'", "'.base64_decode($_SESSION['idEF']).'", 
						"'.(int)$cl_type_client.'", "' . $cl_code . '", 
						"'.$cl_company_name.'", "'.$cl_patern.'", 
						"'.$cl_matern.'", "'.$cl_name.'", 
						"'.$cl_married.'", "'.$cl_date_birth.'", "", 
						"'.$cl_dni.'", '.$cl_ext.', "'.$cl_comp.'", "", 
						"' . $cl_status . '", 
						"'.$cl_attached.'", '.$cl_place_res.', 
						"'.$cl_locality.'", "'.$cl_avc.'", "'.$cl_address_home.'", 
						"'.$cl_nhome.'", "'.$cl_address_work.'", 
						"BOLIVIA", '.$cl_occupation.', "'.$cl_desc_occ.'",
						"' . $cl_monthly_income . '", "' . $cl_activity . '", 
						"' . $cl_executive . '", "' . $cl_position . '", 
						"'.$cl_phone_home.'", "'.$cl_phone_office.'", 
						"'.$cl_phone_cel.'", "'.$cl_email.'", "0", "0", "'.$cl_gender.'", 
						TIMESTAMPDIFF(YEAR, "'.$cl_date_birth.'", curdate()), "", 
						"' . $link->real_escape_string(json_encode($data)) . '", now()) ;';
				} else {
					$sqlCl = 'UPDATE s_cliente 
					SET codigo_bb = "' . $cl_code . '",
						razon_social = "'.$cl_company_name.'", 
						paterno = "'.$cl_patern.'", materno = "'.$cl_matern.'", 
						nombre = "'.$cl_name.'", ap_casada = "'.$cl_married.'", 
						fecha_nacimiento = "'.$cl_date_birth.'", 
						extension = '.$cl_ext.', complemento = "'.$cl_comp.'", 
						estado_civil = "' . $cl_status . '",
						ci_archivo = "'.$cl_attached.'", 
						lugar_residencia = '.$cl_place_res.', 
						localidad = "'.$cl_locality.'", avenida = "'.$cl_avc.'", 
						direccion = "'.$cl_address_home.'", 
						no_domicilio = "'.$cl_nhome.'", 
						direccion_laboral = "'.$cl_address_work.'",
						pais = "' . $cl_country . '", 
						id_ocupacion = '.$cl_occupation.', 
						desc_ocupacion = "'.$cl_desc_occ.'",
						ingreso_mensual = "' . $cl_monthly_income . '",
						actividad = "' . $cl_activity . '",
						ejecutivo = "' . $cl_executive . '",
						cargo = "' . $cl_position . '",
						telefono_domicilio = "'.$cl_phone_home.'", 
						telefono_oficina = "'.$cl_phone_office.'", 
						telefono_celular = "'.$cl_phone_cel.'", 
						email = "'.$cl_email.'", genero = "'.$cl_gender.'", 
						edad = TIMESTAMPDIFF(YEAR, "'.$cl_date_birth.'", curdate()),
						data_jur = "' . $link->real_escape_string(json_encode($data)) . '"
					WHERE id_cliente = "'.$idcl.'" 
						and id_ef = "'.base64_decode($_SESSION['idEF']).'"
						and tipo = '.(int)$cl_type_client.' ;';
				}
				
				if($link->query($sqlCl)) {
					$record = $link->getRegistrationNumber($_SESSION['idEF'], 'AU', 1, 'AU');
					
					$sql = 'insert into s_au_em_cabecera 
					(id_emision, no_emision, id_ef, id_cotizacion, 
						certificado_provisional, garantia, tipo, 
						id_cliente, operacion, prefijo, ini_vigencia, 
						fin_vigencia, forma_pago, plazo, tipo_plazo, 
						factura_nombre, factura_nit, tomador_nombre,
						tomador_ci_nit, cuenta, fecha_creacion, 
						id_usuario, anulado, and_usuario, fecha_anulado, 
						motivo_anulado, emitir, fecha_emision, id_compania, 
						id_poliza, no_copia, facultativo, motivo_facultativo, 
						prima_total, leido, aprobado, created_at) 
					values 
					("'.$ide.'", '.$record.', 
						"'.base64_decode($_SESSION['idEF']).'", "'.$idc.'", '.$cp.', 
						"'.$dcr_warranty.'", '.(int)$cl_type_client.', "'.$idcl.'", 
						"'.$dcr_opp.'", "AU", "'.$dcr_date_begin.'", "'.$dcr_date_end.'", 
						"'.$dcr_method_payment.'", '.$dcr_term.', "'.$dcr_type_term.'", 
						"'.$bl_name.'", "'.$bl_nit.'", "' . $taken_name . '", 
						"' . $taken_nit . '", "' . $account . '", curdate(), 
						"'.base64_decode($_SESSION['idUser']).'", 0, 
						"'.base64_decode($_SESSION['idUser']).'", "", "", false, 
						"", "'.$idcia.'", '.$dcr_policy.', 0, '.(int)$_FAC.', 
						"' . $_FAC_REASON . '", ' . $PRIMA . ', false, 
						"' . $approved . '", now()) ;';
					
					if($link->query($sql) === TRUE) {
						$auxPrefix = null;
						
						$sqlVh = 'insert into s_au_em_detalle 
						(id_vehiculo, id_emision, no_detalle, 
							prefijo, prefix, id_tipo_vh, categoria,  
							id_marca, id_modelo, anio, placa, uso, 
							traccion, km, color, motor, chasis, 
							cap_ton, no_asiento, modalidad, valor_asegurado, 
							tasa, prima, facultativo, motivo_facultativo, 
							aprobado, leido, vh_archivo, created_at) 
						values ';
						
						$record_det = $record;

						for($k = 1; $k <= $nVh; $k++) {
							$prefix[0] = 'AU';
							$prefix[1] = '';
							
							if ($arrPrefix === $prefix[0]) {
								$record_det += 1;
							} else {
								$record_det = $link->getRegistrationNumber($_SESSION['idEF'], 'AU', 2, $prefix[0]);
							}
							
							$auxPrefix = $prefix[0];

							$arrPrefix = array (
								'policy' => $prefix[1],
								'prefix' => $prefix[0]
								);
							$arrPrefix = '"' . $link->real_escape_string(json_encode($arrPrefix)) . '"';
							
							$sqlVh .= '("'.$arr_vh[$k]['idvh'].'", "'.$ide.'",
							'.$record_det.', "'.$prefix[0].'", '.$arrPrefix.', 
							"'.$arr_vh[$k]['type-vehicle'].'", 
							"'.$arr_vh[$k]['category'].'", "'.$arr_vh[$k]['make'].'", 
							"'.$arr_vh[$k]['model'].'", '.$arr_vh[$k]['year'].', 
							"'.$arr_vh[$k]['plate'].'", "'.$arr_vh[$k]['use'].'", 
							"'.$arr_vh[$k]['traction'].'", "'.$arr_vh[$k]['km'].'", 
							"'.$arr_vh[$k]['color'].'", "'.$arr_vh[$k]['motor'].'", 
							"'.$arr_vh[$k]['chassis'].'", "'.$arr_vh[$k]['capton'].'", 
							"'.$arr_vh[$k]['nseat'].'", '.$arr_vh[$k]['modality'].', 
							'.$arr_vh[$k]['value-insured'].', '.$arr_vh[$k]['rate'].', 
							'.$arr_vh[$k]['premium'].', '.(int)$arr_vh[$k]['FAC'].', 
							"'.$arr_vh[$k]['reason'].'", '.(int)$arr_vh[$k]['approved'].', 
							false, "'.$arr_vh[$k]['attached'].'", now()) ';
							
							if($k < $nVh) { $sqlVh .= ', '; } elseif($k === $nVh) { $sqlVh .= ';'; };
						}
						
						if ($link->query($sqlVh)){
							$swReg = TRUE;
							$arrAU[1] = 'au-quote.php?ms=' . $ms . '&page=' . $page 
								. '&pr=' . $pr . '&ide=' . base64_encode($ide) 
								. '&flag=' . md5('i-read') . '&cia=' . base64_encode($idcia);
							$arrAU[2] = 'La Póliza fue registrada con exito !';

							$log_msg = 'AU - Em. ' . $record . ' / Record Certificate Client, Vehicles';

							$db = new Log($link);
							$db->postLog($_SESSION['idUser'], $log_msg);

						} else {
							$arrAU[2] = 'Los Vehículos no pudieron ser registrados';
						}
					} else {
						$arrAU[2] = 'La Póliza no pudo ser registrada';
					}
				} else {
					$arrAU[2] = 'El Prestatario no pudo ser registrado'; 
				}
			}elseif($sw === 3) {	// ACTUALIZAR POLIZA
				$sql = 'select 
					sae.no_emision
				from 
					s_au_em_cabecera sae
				where 
					sae.id_emision = "' . $ide . '"
				limit 0, 1
				;';

				if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
					if ($rs->num_rows === 1) {
						$row = $rs->fetch_array(MYSQLI_ASSOC);
						$rs->free();
						$record = $row['no_emision'];
					}
				}

				$sqlCl = 'UPDATE s_cliente 
				SET codigo_bb = "' . $cl_code . '",
					razon_social = "'.$cl_company_name.'", paterno = "'.$cl_patern.'", materno = "'.$cl_matern.'", 
					nombre = "'.$cl_name.'", fecha_nacimiento = "'.$cl_date_birth.'", 
					extension = '.$cl_ext.', complemento = "'.$cl_comp.'",
					estado_civil = "' . $cl_status . '", ci_archivo = "'.$cl_attached.'", 
					lugar_residencia = '.$cl_place_res.', localidad = "'.$cl_locality.'", avenida = "'.$cl_avc.'", 
					direccion = "'.$cl_address_home.'", no_domicilio = "'.$cl_nhome.'", 
					direccion_laboral = "'.$cl_address_work.'", id_ocupacion = '.$cl_occupation.', 
					desc_ocupacion = "'.$cl_desc_occ.'", ingreso_mensual = "' . $cl_monthly_income . '", 
					actividad = "' . $cl_activity . '", ejecutivo = "' . $cl_executive . '", 
					cargo = "' . $cl_position . '", telefono_domicilio = "'.$cl_phone_home.'", 
					telefono_oficina = "'.$cl_phone_office.'", telefono_celular = "'.$cl_phone_cel.'", 
					email = "'.$cl_email.'", genero = "'.$cl_gender.'", 
					edad = TIMESTAMPDIFF(YEAR, "'.$cl_date_birth.'", curdate()),
					data_jur = "' . $link->real_escape_string(json_encode($data)) . '"
				WHERE id_cliente = "'.$idcl.'" and id_ef = "'.base64_decode($_SESSION['idEF']).'"
					and tipo = '.(int)$cl_type_client.' ;';
					
				if($link->query($sqlCl)) {
					$sql = 'UPDATE s_au_em_cabecera 
					SET operacion = "'.$dcr_opp.'", 
						ini_vigencia = "'.$dcr_date_begin.'", 
						fin_vigencia = "'.$dcr_date_end.'", 
						forma_pago = "'.$dcr_method_payment.'", 
						plazo = '.$dcr_term.', 
						tipo_plazo = "'.$dcr_type_term.'", 
						factura_nombre = "'.$bl_name.'", 
						factura_nit =  "'.$bl_nit.'",
						tomador_nombre = "' . $taken_name . '",
						tomador_ci_nit = "' . $taken_nit . '",
						id_poliza = '.$dcr_policy.', 
						no_copia = 0, facultativo = '.(int)$_FAC.', 
						motivo_facultativo = "'.$_FAC_REASON.'", 
						prima_total = '.$PRIMA.', 
						leido = FALSE
					WHERE id_emision = "'.$ide.'" and id_ef = "'.base64_decode($_SESSION['idEF']).'" ;';
					
					if($link->query($sql)) {
						$sqlVh = '';
						for($k = 1; $k <= $nVh; $k++) {
							$sqlVh .= 'update s_au_em_detalle 
							set id_tipo_vh = "'.$arr_vh[$k]['type-vehicle'].'", 
								categoria = "'.$arr_vh[$k]['category'].'", id_marca = "'.$arr_vh[$k]['make'].'", 
								id_modelo = "'.$arr_vh[$k]['model'].'", anio = '.$arr_vh[$k]['year'].', 
								placa = "'.$arr_vh[$k]['plate'].'", uso = "'.$arr_vh[$k]['use'].'", 
								traccion = "'.$arr_vh[$k]['traction'].'", km = "'.$arr_vh[$k]['km'].'", 
								color = "'.$arr_vh[$k]['color'].'", motor = "'.$arr_vh[$k]['motor'].'", 
								chasis = "'.$arr_vh[$k]['chassis'].'", cap_ton = "'.$arr_vh[$k]['capton'].'", 
								no_asiento = "'.$arr_vh[$k]['nseat'].'",modalidad = '.$arr_vh[$k]['modality'].',
								valor_asegurado = '.$arr_vh[$k]['value-insured'].', tasa = '.$arr_vh[$k]['rate'].',
								prima = '.$arr_vh[$k]['premium'].', facultativo = '.(int)$arr_vh[$k]['FAC'].',
								motivo_facultativo = "'.$arr_vh[$k]['reason'].'", aprobado = '.(int)$arr_vh[$k]['approved'].',
								leido = FALSE, vh_archivo = "'.$arr_vh[$k]['attached'].'"
							where id_vehiculo = "'.$arr_vh[$k]['idvh'].'" and id_emision = "'.$ide.'" ;';
						}
						
						if($link->multi_query($sqlVh) === TRUE) {
							$swVh = FALSE;
							do{
								if($link->errno !== 0)
									$swVh = TRUE;
							}while($link->next_result());
							
							if($swVh === FALSE) {
								$swReg = TRUE;
								$arrAU[1] = 'au-quote.php?ms='.$ms.'&page='.$page.'&pr='.$pr.'&ide='.base64_encode($ide).'&flag='.md5('i-read').'&cia='.base64_encode($idcia).$target;
								$arrAU[2] = 'La Póliza fue actualizada correctamente !';

								$log_msg = 'AU - Em. ' . $record . ' / Update Certificate Client, Vehicles';

								$db = new Log($link);
								$db->postLog($_SESSION['idUser'], $log_msg);

							} else {
								$arrAU[2] = 'Los datos de los Vehículos no fueron actualizados';
							}
						} else {
							$arrAU[2] = 'Los datos del Vehículo no fueron actualizados';
						}
					} else {
						$arrAU[2] = 'Los Póliza no pudo ser actualizada';
					}
				} else {
					$arrAU[2] = 'Los datos del Prestatario no fueron actualizados';
				}
			}
			
			if($swReg === TRUE) {
				$arrAU[0] = 1;
			} else { $arrAU[2] = 'Error. No se pudo registrar la Póliza'; }
		}else {
			$arrAU[2] = 'La Póliza no puede ser registrada';
		}
	}else{
		$arrAU[2] = 'La Póliza no puede ser registrada.';
	}
}else{
	$arrAU[2] = 'La Póliza no puede ser registrada. |';
}

echo json_encode($arrAU);

?>