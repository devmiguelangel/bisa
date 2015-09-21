<?php
if (isset($_GET['idef']) && isset($_GET['iduser'])) {
require_once('sibas-db.class.php');
$link = new SibasDB();

	if($_GET['gr-flag']==1){

		$subsidiary = $link->real_escape_string(trim($_GET['frp-subsidiary']));
		$date_b = $link->real_escape_string(trim($_GET['frp-date-b']));
		$date_e = $link->real_escape_string(trim($_GET['frp-date-e']));

		$result = array();
		$rowsSi = array();

		$sqlAg = "select
			sag.id_agencia, sag.agencia
		from
			s_agencia as sag
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = sag.id_ef)
		where
			sag.id_depto = " .base64_decode($subsidiary). "
		;";

		if(($rsAg = $link->query($sqlAg,MYSQLI_STORE_RESULT))){
			while($rowAg = $rsAg->fetch_array(MYSQLI_ASSOC)){
			$cant_polizas = 0;


					$sqlAu = "select
						count(sae.id_emision) as cant_polizas
					from
					    s_au_em_cabecera as sae
					        inner join
					    s_au_em_detalle as sad ON (sad.id_emision = sae.id_emision)
					        left join
					    s_au_facultativo as saf ON (saf.id_vehiculo = sad.id_vehiculo
					        and saf.id_emision = sae.id_emision)
							left join
						s_au_pendiente as sap ON (sap.id_vehiculo = sad.id_vehiculo
							and sap.id_emision = sae.id_emision)
							left join
						s_estado as sds ON (sds.id_estado = sap.id_estado)
					        inner join
					    s_cliente as scl ON (scl.id_cliente = sae.id_cliente)
					        inner join
					    s_entidad_financiera as sef ON (sef.id_ef = sae.id_ef)
					        inner join
					    s_departamento as sdep ON (sdep.id_depto = scl.extension)
					        inner join
					    s_usuario as su ON (su.id_usuario = sae.id_usuario)
					    	inner join
					    s_usuario_tipo as sut ON (sut.id_tipo = su.id_tipo)
					        inner join
					    s_departamento as sdepu ON (sdepu.id_depto = su.id_depto)
							left join
						s_agencia as sag ON (sag.id_agencia = su.id_agencia)
							inner join
						s_usuario as sua ON (sua.id_usuario = sae.and_usuario)
					where
						sae.emitir = true and
						sae.anulado = false and
						sae.fecha_emision between '".$date_b."' and '".$date_e."' and
						sag.id_agencia = '" .$rowAg['id_agencia']. "'
					";

					if(($rsAp = $link->query($sqlAu,MYSQLI_STORE_RESULT))){
						while($rowAp = $rsAp->fetch_array(MYSQLI_ASSOC)){
							$cant_polizas = $cant_polizas + $rowAp['cant_polizas'];
						}
					}

					$sqlTrd = "select
						count(stre.id_emision) as cant_polizas
					from
					    s_trd_em_cabecera as stre
					        inner join
					    s_trd_em_detalle as strd ON (strd.id_emision = stre.id_emision)
					        inner join
					    s_cliente as scl ON (scl.id_cliente = stre.id_cliente)
					    	left join
					    s_trd_facultativo as strf ON (strf.id_emision = stre.id_emision)
					        left join
					    s_trd_pendiente as strp ON (strp.id_emision = stre.id_emision)
					        inner join
					    s_entidad_financiera as sef ON (sef.id_ef = stre.id_ef)
					        inner join
					    s_departamento as sdep ON (sdep.id_depto = scl.extension)
					        inner join
					    s_usuario as su ON (su.id_usuario = stre.id_usuario)
					        inner join
					    s_departamento as sdepu ON (sdepu.id_depto = su.id_depto)
					        left join
					    s_agencia as sag ON (sag.id_agencia = su.id_agencia)
					        inner join
					    s_usuario as sua ON (sua.id_usuario = stre.and_usuario)		
					where
						stre.emitir = true and
						stre.anulado = false and
						stre.fecha_emision between '".$date_b."' and '".$date_e."' and
						sag.id_agencia = '" .$rowAg['id_agencia']. "'
					";

					if(($rsVi = $link->query($sqlTrd,MYSQLI_STORE_RESULT))){
						while($rowVi = $rsVi->fetch_array(MYSQLI_ASSOC)){
							$cant_polizas = $cant_polizas + $rowVi['cant_polizas'];
						}
					}

				if($cant_polizas > 0){
					$rowsSi['varpo'] = (int)10;
					$rowsSi['name'] = $rowAg['agencia'];
					$rowsSi['data'][0] = (int)$cant_polizas;
					array_push($result, $rowsSi);
				}
			}
		}
		//echo json_encode($result, JSON_NUMERIC_CHECK);
		echo json_encode($result);

	}


	if($_GET['gr-flag']==2){

		$subsidiary = $link->real_escape_string(trim($_GET['frp-subsidiary']));
		$date_b = $link->real_escape_string(trim($_GET['frp-date-b']));
		$date_e = $link->real_escape_string(trim($_GET['frp-date-e']));

		$cant_polizas = 0;

			$sqlAu = "select
				count(sae.id_emision) as cant_polizas
			from
				s_au_em_cabecera as sae
					inner join
				s_au_em_detalle as sad ON (sad.id_emision = sae.id_emision)
					left join
				s_au_facultativo as saf ON (saf.id_vehiculo = sad.id_vehiculo
					and saf.id_emision = sae.id_emision)
					left join
				s_au_pendiente as sap ON (sap.id_vehiculo = sad.id_vehiculo
					and sap.id_emision = sae.id_emision)
					left join
				s_estado as sds ON (sds.id_estado = sap.id_estado)
					inner join
				s_cliente as scl ON (scl.id_cliente = sae.id_cliente)
					inner join
				s_entidad_financiera as sef ON (sef.id_ef = sae.id_ef)
					inner join
				s_departamento as sdep ON (sdep.id_depto = scl.extension)
					inner join
				s_usuario as su ON (su.id_usuario = sae.id_usuario)
					inner join
				s_usuario_tipo as sut ON (sut.id_tipo = su.id_tipo)
					inner join
				s_departamento as sdepu ON (sdepu.id_depto = su.id_depto)
					left join
				s_agencia as sag ON (sag.id_agencia = su.id_agencia)
					inner join
				s_usuario as sua ON (sua.id_usuario = sae.and_usuario)
			where
				sae.emitir = true and
				sae.anulado = false and
				sae.fecha_emision between '".$date_b."' and '".$date_e."' and
				sdepu.id_depto = " .base64_decode($subsidiary). "
			";

			if(($rsAp = $link->query($sqlAu,MYSQLI_STORE_RESULT))){
				while($rowAp = $rsAp->fetch_array(MYSQLI_ASSOC)){
					$cant_polizas = $cant_polizas + $rowAp['cant_polizas'];
				}
			}


		echo $cant_polizas;

	}


	if($_GET['gr-flag']==3){

		$subsidiary = $link->real_escape_string(trim($_GET['frp-subsidiary']));
		$date_b = $link->real_escape_string(trim($_GET['frp-date-b']));
		$date_e = $link->real_escape_string(trim($_GET['frp-date-e']));

		$cant_polizas = 0;

			$sqlAu = "select
						count(stre.id_emision) as cant_polizas
					from
					    s_trd_em_cabecera as stre
					        inner join
					    s_trd_em_detalle as strd ON (strd.id_emision = stre.id_emision)
					        inner join
					    s_cliente as scl ON (scl.id_cliente = stre.id_cliente)
					    	left join
					    s_trd_facultativo as strf ON (strf.id_emision = stre.id_emision)
					        left join
					    s_trd_pendiente as strp ON (strp.id_emision = stre.id_emision)
					        inner join
					    s_entidad_financiera as sef ON (sef.id_ef = stre.id_ef)
					        inner join
					    s_departamento as sdep ON (sdep.id_depto = scl.extension)
					        inner join
					    s_usuario as su ON (su.id_usuario = stre.id_usuario)
					        inner join
					    s_departamento as sdepu ON (sdepu.id_depto = su.id_depto)
					        left join
					    s_agencia as sag ON (sag.id_agencia = su.id_agencia)
					        inner join
					    s_usuario as sua ON (sua.id_usuario = stre.and_usuario)
					where
						stre.emitir = true and
						stre.anulado = false and
				stre.fecha_emision between '".$date_b."' and '".$date_e."' and
				sdepu.id_depto = " .base64_decode($subsidiary). "
			";

			if(($rsAp = $link->query($sqlAu,MYSQLI_STORE_RESULT))){
				while($rowAp = $rsAp->fetch_array(MYSQLI_ASSOC)){
					$cant_polizas = $cant_polizas + $rowAp['cant_polizas'];
				}
			}


		echo $cant_polizas;

	}

	if($_GET['gr-flag']==4){

		$agency = $link->real_escape_string(trim($_GET['frp-agency']));
		$date_b = $link->real_escape_string(trim($_GET['frp-date-b']));
		$date_e = $link->real_escape_string(trim($_GET['frp-date-e']));

		$cant_polizas = 0;

			$sqlAu = "select
					count(sae.id_emision) as cant_polizas
				from
					s_au_em_cabecera as sae
						inner join
					s_au_em_detalle as sad ON (sad.id_emision = sae.id_emision)
						left join
					s_au_facultativo as saf ON (saf.id_vehiculo = sad.id_vehiculo
						and saf.id_emision = sae.id_emision)
						left join
					s_au_pendiente as sap ON (sap.id_vehiculo = sad.id_vehiculo
						and sap.id_emision = sae.id_emision)
						left join
					s_estado as sds ON (sds.id_estado = sap.id_estado)
						inner join
					s_cliente as scl ON (scl.id_cliente = sae.id_cliente)
						inner join
					s_entidad_financiera as sef ON (sef.id_ef = sae.id_ef)
						inner join
					s_departamento as sdep ON (sdep.id_depto = scl.extension)
						inner join
					s_usuario as su ON (su.id_usuario = sae.id_usuario)
						inner join
					s_usuario_tipo as sut ON (sut.id_tipo = su.id_tipo)
						inner join
					s_departamento as sdepu ON (sdepu.id_depto = su.id_depto)
						left join
					s_agencia as sag ON (sag.id_agencia = su.id_agencia)
						inner join
					s_usuario as sua ON (sua.id_usuario = sae.and_usuario)
				where
					sae.emitir = true and
					sae.anulado = false and
					sae.fecha_emision between '".$date_b."' and '".$date_e."' and
					sag.agencia = '" . $agency .  "'
			";

			if(($rsAp = $link->query($sqlAu,MYSQLI_STORE_RESULT))){
				while($rowAp = $rsAp->fetch_array(MYSQLI_ASSOC)){
					$cant_polizas = $cant_polizas + $rowAp['cant_polizas'];
				}
			}


		echo $cant_polizas;

	}

	if($_GET['gr-flag']==5){

		$agency = $link->real_escape_string(trim($_GET['frp-agency']));
		$date_b = $link->real_escape_string(trim($_GET['frp-date-b']));
		$date_e = $link->real_escape_string(trim($_GET['frp-date-e']));

		$cant_polizas = 0;

			$sqlAu = "select
					count(stre.id_emision) as cant_polizas
				from
					s_trd_em_cabecera as stre
						inner join
					s_trd_em_detalle as strd ON (strd.id_emision = stre.id_emision)
						inner join
					s_cliente as scl ON (scl.id_cliente = stre.id_cliente)
						left join
					s_trd_facultativo as strf ON (strf.id_emision = stre.id_emision)
						left join
					s_trd_pendiente as strp ON (strp.id_emision = stre.id_emision)
						inner join
					s_entidad_financiera as sef ON (sef.id_ef = stre.id_ef)
						inner join
					s_departamento as sdep ON (sdep.id_depto = scl.extension)
						inner join
					s_usuario as su ON (su.id_usuario = stre.id_usuario)
						inner join
					s_departamento as sdepu ON (sdepu.id_depto = su.id_depto)
						left join
					s_agencia as sag ON (sag.id_agencia = su.id_agencia)
						inner join
					s_usuario as sua ON (sua.id_usuario = stre.and_usuario)	
				where
					stre.emitir = true and
					stre.anulado = false and
					stre.fecha_emision between '".$date_b."' and '".$date_e."' and
					sag.agencia = '" . $agency .  "'
			";

			if(($rsAp = $link->query($sqlAu,MYSQLI_STORE_RESULT))){
				while($rowAp = $rsAp->fetch_array(MYSQLI_ASSOC)){
					$cant_polizas = $cant_polizas + $rowAp['cant_polizas'];
				}
			}
		echo $cant_polizas;
	}

	if($_GET['gr-flag']==6){

		$subsidiary = $link->real_escape_string(trim($_GET['frp-subsidiary']));
		$date_b = $link->real_escape_string(trim($_GET['frp-date-b']));
		$date_e = $link->real_escape_string(trim($_GET['frp-date-e']));

		$result = array();
		$rowsSi = array();

		$cant_users = 0;

		$sqlUs = "select sag.agencia, count(id_usuario) as cant_usuarios
			from
				s_usuario sua
			left join
				s_agencia as sag ON (sag.id_agencia = sua.id_agencia)
			left join
				s_departamento as sdep ON (sdep.id_depto = sua.id_depto)
			";
		if(base64_decode($subsidiary) == 12)
			$sqlUs .= "where sdep.id_depto IS NULL and
				sua.id_tipo=5
			group by
				 sag.id_agencia";
		else
			$sqlUs .= "where sdep.id_depto = '" .base64_decode($subsidiary). "' and
				sua.id_tipo=5
			group by
				 sag.id_agencia";

		if(($rsUs = $link->query($sqlUs,MYSQLI_STORE_RESULT))){
			while($rowUs = $rsUs->fetch_array(MYSQLI_ASSOC)){
			//	$cant_users = $cant_users + $rowUs['cant_usuarios'];
			if($rowUs['agencia'] == '')
				$rowsSi['name'] = 'Sin Sucursal';
			else
				$rowsSi['name'] = $rowUs['agencia'];

			$rowsSi['data'][0] = (int)$rowUs['cant_usuarios'];
			array_push($result, $rowsSi);
			}
		}

		//echo json_encode($result, JSON_NUMERIC_CHECK);
		echo json_encode($result);

	}

	if($_GET['gr-flag']==7){

		$subsidiary = $link->real_escape_string(trim($_GET['frp-subsidiary']));
		$date_b = $link->real_escape_string(trim($_GET['frp-date-b']));
		$date_e = $link->real_escape_string(trim($_GET['frp-date-e']));

		$result = array();
		$rowsSi = array();

		$cant_users = 0;

		$sqlUs = "select count(id_usuario) as cant_usuarios
			from
				s_usuario sua
			left join
				s_agencia as sag ON (sag.id_agencia = sua.id_agencia)
			left join
				s_departamento as sdep ON (sdep.id_depto = sua.id_depto)
			";
		if(base64_decode($subsidiary) == 12)
			$sqlUs .= "where sdep.id_depto IS NULL and
				sua.id_tipo=5
			";
		else
			$sqlUs .= "where sdep.id_depto = '" .base64_decode($subsidiary). "' and
				sua.id_tipo=5
			";

		if(($rsUs = $link->query($sqlUs,MYSQLI_STORE_RESULT))){
			while($rowUs = $rsUs->fetch_array(MYSQLI_ASSOC)){
				$cant_users= $rowUs['cant_usuarios'];
			}
		}

		echo $cant_users;

	}



}
?>