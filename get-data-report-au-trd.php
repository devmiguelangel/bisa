<?php
if (isset($_GET['idef']) && isset($_GET['iduser'])) {
?>
<?php
require('sibas-db.class.php');
$link = new SibasDB();

//Reporte Produccion
if($_GET['frp-type']=='production'){

	$xls = FALSE;
	if(isset($_GET['frp-xls'])) {
		$xls = TRUE;
	}

	if($xls === TRUE){
		header("Content-Type:   application/vnd.ms-excel; charset=iso-8859-1");
		header("Content-Disposition: attachment; filename=produccion.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else{
		echo '<!doctype html>';
		echo '<head><link type="text/css" href="css/style.css" rel="stylesheet" /></head>';
		echo '<h2 style="text-align:center; font-weight:bold">INFORME EJECUTIVO DE PRODUCCION</h2>';
	}
?>
	<table class="result-list" style="width:100%">
		<thead>
	      <tr>
		    <td>&nbsp;</td>
		    <td colspan="3">AUTOMOTORES</td>
		    <td colspan="3">TOTO RIESGO</td>
		    <td colspan="3" rowspan="2">TOTAL</td>
		  </tr>
		  <tr>
		    <td>Ejecutivo</td>
		    <td colspan="1">NRO </td>	
		    <td colspan="1">MONTO TOTAL</td>	
		    <td colspan="1">MONTO COBRADO</td>
		    <td colspan="1">NRO </td>	
		    <td colspan="1">MONTO TOTAL</td>	
		    <td colspan="1">MONTO COBRADO</td>

		  </tr>
		</thead>

	<?php

		$s_agencia = $link->real_escape_string(trim($_GET['frp-agency']));

		$date_b = $link->real_escape_string(trim($_GET['frp-date-b']));
		$date_e = $link->real_escape_string(trim($_GET['frp-date-e']));


		$sqlUs = 'select ssu.nombre, ssu.id_usuario
			from s_usuario as ssu
			inner join s_agencia as sag ON (sag.id_agencia = ssu.id_agencia)
			where sag.agencia = "' .$s_agencia. '"
			order by ssu.nombre desc
			;';

		if(($rsUs = $link->query($sqlUs,MYSQLI_STORE_RESULT))){
			if($rsUs->num_rows > 0){
				$swBG = FALSE;
				$unread = '';
		?>
		<tbody>
		<?php
				while($rowUs = $rsUs->fetch_array(MYSQLI_ASSOC)){

					if($swBG === FALSE){
						$bg = 'background: #EEF9F8;';
					}elseif($swBG === TRUE){
						$bg = 'background: #D1EDEA;';
					}

					$rowSpan = FALSE;

					$sqlAu = 'select count(distinct sae.id_emision) as cant_polizas,
							sum(sac.monto_cuota) as monto_total,
							sum(sac.monto_transaccion) as monto_transaccion
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
							s_au_cobranza as sac ON (sac.id_emision = sae.id_emision)
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
							sua.id_usuario = "'.$rowUs['id_usuario'].'" and
							sae.fecha_emision between "'.$date_b.'" and "'.$date_e.'"
						;';


					$cant_polizas_au = 0;		
					$monto_total_au = 0;
					$monto_transaccion_au = 0;

					if(($rsAu = $link->query($sqlAu,MYSQLI_STORE_RESULT))){
						while($rowAu = $rsAu->fetch_array(MYSQLI_ASSOC)){

							$cant_polizas_au = $rowAu['cant_polizas'];
							$monto_total_au = $rowAu['monto_total'];
							$monto_transaccion_au = $rowAu['monto_transaccion'];
						}
					}

					$sqlTrd = 'select count(distinct stre.id_emision) as cant_polizas,
							sum(sac.monto_cuota) as monto_total,
							sum(sac.monto_transaccion) as monto_transaccion
						from
						    s_trd_em_cabecera as stre
						        inner join
						    s_trd_em_detalle as strd ON (strd.id_emision = stre.id_emision)
						        inner join
						    s_cliente as scl ON (scl.id_cliente = stre.id_cliente)
								inner join
							s_trd_cobranza as sac ON (sac.id_emision = stre.id_emision)
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
							sua.id_usuario = "'.$rowUs['id_usuario'].'" and
							stre.fecha_emision between "'.$date_b.'" and "'.$date_e.'"
						;';

					$cant_polizas_trd = 0;		
					$monto_total_trd = 0;
					$monto_transaccion_trd = 0;
	
					if(($rsTrd = $link->query($sqlTrd,MYSQLI_STORE_RESULT))){
						while($rowTrd = $rsTrd->fetch_array(MYSQLI_ASSOC)){

							$cant_polizas_trd = $rowTrd['cant_polizas'];
							$monto_total_trd = $rowTrd['monto_total'];
							$monto_transaccion_trd = $rowTrd['monto_transaccion'];

						}

					}


		?>
			<tr style=" <?=$bg;?> " class="row quote" rel="0"
				data-nc="<?=base64_encode($rowUs['ids']);?>" >

	        	<td <?=$rowSpan;?>><?=$rowUs['nombre'];?></td>


			    <td><?=$cant_polizas_au?></td>
			    <td><?=number_format($monto_total_au, 2, '.', '');?></td>
			    <td><?=number_format($monto_transaccion_au, 2, '.', '');?></td>

			    <td><?=$cant_polizas_trd?></td>
			    <td><?=number_format($monto_total_trd, 2, '.', '');?></td>
			    <td><?=number_format($monto_transaccion_trd, 2, '.', '');?></td>

			    <td><?=($cant_polizas_au + $cant_polizas_trd)?></td>
			    <td><?=number_format(($monto_total_au + $monto_total_trd), 2, '.', '');?></td>
			    <td><?=number_format(($monto_transaccion_au + $monto_transaccion_trd), 2, '.', '');?></td>

			</tr>
	<?php
				}
	?>
		</tbody>
		<tfoot>
	    <tr>
	        	<td colspan="29" style="text-align:left;">
	<?php
			if($xls === FALSE){
	?>

			<a href="get-data-report-ap-vi.php?idef=<?=$_GET['idef'];?>&
				iduser=<?=$_GET['iduser'];?>&
				frp-agency=<?=$_GET['frp-agency'];?>&
				frp-date-b=<?=$_GET['frp-date-b'];?>&
				frp-date-e=<?=$_GET['frp-date-e'];?>&
				frp-type=<?=$_GET['frp-type'];?>&
				frp-xls=''
				" class="send-xls" target="_blank">Exportar a Formato Excel</a>

	<?php
			}
	?>
				</td>
	        </tr>
	    </tfoot>

	<?php
			}
		}
	?>
</table>

<?php
	}

//Reporte Comision
elseif($_GET['frp-type']=='users'){

	$xls = FALSE;
	if(isset($_GET['frp-xls'])) {
		$xls = TRUE;
	}

	if($xls === true){
		header("Content-Type:   application/vnd.ms-excel; charset=iso-8859-1");
		header("Content-Disposition: attachment; filename=usuarios.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else{
		echo '<!doctype html>';
		echo '<head><link type="text/css" href="css/style.css" rel="stylesheet" /></head>';
		echo '<h2 style="text-align:center; font-weight:bold">REPORTE DE USUARIOS HABILITADOS</h2>';
	}
?>
	<table class="result-list" style="width:100%">
		<thead>
		    <td>Usuario</td>
		    <td>Nombre</td>
		    <td>Email</td>
		    <td>Sucursal</td>
		    <td>Agencia</td>
		    <td>Fecha Creacion</td>
		  </tr>
		</thead>

	<?php

		$s_agencia = $link->real_escape_string(trim($_GET['frp-agency']));

		$date_b = $link->real_escape_string(trim($_GET['frp-date-b']));
		$date_e = $link->real_escape_string(trim($_GET['frp-date-e']));


		$sqlUs = 'select sua.usuario, sua.nombre, sua.email, sag.agencia, sua.fecha_creacion, sdep.departamento
			from
				s_usuario sua
			left join
				s_agencia as sag ON (sag.id_agencia = sua.id_agencia)
			left join
				s_departamento as sdep ON (sdep.id_depto = sua.id_depto)';

		if($s_agencia == 'Sin Sucursal')
			$sqlUs .= 'where sdep.id_depto IS NULL and
				sua.id_tipo=5';
		else
			$sqlUs .= 'where sag.agencia = "' .$s_agencia. '" and
				sua.id_tipo=5';

//echo $sqlUs;
		if(($rsUs = $link->query($sqlUs,MYSQLI_STORE_RESULT))){
			if($rsUs->num_rows > 0){
				$swBG = FALSE;
				$unread = '';
		?>
		<tbody>
		<?php
				while($rowUs = $rsUs->fetch_array(MYSQLI_ASSOC)){

					if($swBG === FALSE){
						$bg = 'background: #EEF9F8;';
					}elseif($swBG === TRUE){
						$bg = 'background: #D1EDEA;';
					}

					$rowSpan = FALSE;
?>

			<tr style=" <?=$bg;?> " class="row quote" rel="0"
				data-nc="<?=base64_encode($rowUs['id']);?>" >

			    <td><?=$rowUs['usuario'];?></td>
			    <td><?=$rowUs['nombre'];?></td>
			    <td><?=$rowUs['email'];?></td>
			    <td><?=$rowUs['departamento'];?></td>
			    <td><?=$rowUs['agencia'];?></td>
			    <td><?=$rowUs['fecha_creacion'];?></td>
			</tr>
	<?php
				}



	?>
		</tbody>
		<tfoot>
	    <tr>
	        	<td colspan="29" style="text-align:left;">
	<?php
			if($xls === FALSE){
	?>
				<a href="get-data-report-ap-vi.php?idef=<?=$_GET['idef'];?>&
					iduser=<?=$_GET['iduser'];?>&
					frp-agency=<?=$_GET['frp-agency'];?>&
					frp-date-b=<?=$_GET['frp-date-b'];?>&
					frp-date-e=<?=$_GET['frp-date-e'];?>&
					frp-type=<?=$_GET['frp-type'];?>&
					frp-xls=''
					" class="send-xls" target="_blank">Exportar a Formato Excel</a>

	<?php
			}
	?>
				</td>
	        </tr>
	    </tfoot>

	<?php
			}
		}
	?>
</table>

	<?php
	}



}
?>