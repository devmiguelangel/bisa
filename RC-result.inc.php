<?php
require('sibas-db.class.php');
$s_ns = '';
$s_user = '';
$s_name = '';
$s_dni = '';
$s_branch = '';

$bg = '';
$nSi = 0;
?>
<input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
<table class="result-list">
	<thead>
    	<tr>
        	<td>No. Siniestro</td>
            <td>No. Certificado</td>
            <td>No. Póliza</td>
            <td>Nombre Cliente</td>
            <td>C.I. Cliente</td>
            <td>Fecha de Registro</td>
            <td>Fecha de Siniestro</td>
            <td>Circunstancia</td>
            <td>Ramo</td>
            <td>Plazo del Crédito</td>
            <td>Monto Desembolsado</td>
            <td>Fecha de Desembolso</td>
            <td>Denunciado por</td>
            <td>Sucursal</td>
            <td>Agencia</td>
		</tr>
	</thead>
<?php
if(isset($_GET['ms']) && isset($_GET['page']) && isset($_GET['frc']) && $_GET['idef']){
	$link = new SibasDB();
	
	$idef = $link->real_escape_string(trim(base64_decode($_GET['idef'])));
	$s_ns = $link->real_escape_string(trim($_GET['frc-ns']));
	$s_user = $link->real_escape_string(trim(base64_decode($_GET['frc-user'])));
	$s_name = $link->real_escape_string(trim($_GET['frc-name']));
	$s_dni = $link->real_escape_string(trim($_GET['frc-dni']));
	$s_branch = $link->real_escape_string(trim($_GET['frc-branch']));
	
	if ($s_branch === 'DE') {
		$s_branch = '';
	}
	
	$sqlSi = 'select 
			ssi.id_siniestro as ids,
			count(ssd.id_siniestro) as noSi,
			ssi.no_siniestro as s_no_siniestro,
			date_format(ssi.fecha_registro, "%d/%m/%Y") as s_fecha_registro,
			ssi.id_cliente as idCl,
			ssi.ci_cliente as s_ci,
			ssi.nombre_cliente as s_nombre,
			date_format(ssi.fecha_siniestro, "%d/%m/%Y") as s_fecha_siniestro,
			ssi.circunstancia as s_circunstancia,
			su.id_usuario as s_usuario,
			su.nombre as s_usuario_nombre,
			su.email as s_usuario_email,
			sdp.departamento as s_sucursal,
		    sag.agencia as s_agencia
		from
			s_siniestro as ssi
				inner join
			s_siniestro_detalle as ssd ON (ssd.id_siniestro = ssi.id_siniestro)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = ssi.id_ef)
				inner join
			s_usuario as su ON (su.id_usuario = ssi.denunciado_por)
				inner join
			s_departamento as sdp ON (sdp.id_depto = ssi.sucursal)
				left join
			s_agencia as sag ON (sag.id_agencia = ssi.agencia)
		where
			sef.id_ef = "'.$idef.'"
				and sef.activado = true
				and ssi.no_siniestro like "%'.$s_ns.'%"
				and su.id_usuario like "%'.$s_user.'%"
				and ssi.nombre_cliente like "%'.$s_name.'%"
				and ssi.ci_cliente like "%'.$s_dni.'%"
				and ssd.producto like "%'.$s_branch.'%"
		group by ssi.id_siniestro
		order by ssi.id_siniestro desc
		;';
	
	if(($rsSi = $link->query($sqlSi,MYSQLI_STORE_RESULT))){
		if($rsSi->num_rows > 0){
			$swBG = FALSE;
			$unread = '';
?>
	<tbody>
<?php
			while($rowSi = $rsSi->fetch_array(MYSQLI_ASSOC)){
				$nSi = (int)$rowSi['noSi'];
				
				if($swBG === FALSE){
					$bg = 'background: #EEF9F8;';
				}elseif($swBG === TRUE){
					$bg = 'background: #D1EDEA;';
				}
				
				$rowSpan = FALSE;
				if($nSi > 1)
					$rowSpan = TRUE;
				
				$sqlSd = 'select 
						ssi.id_siniestro as ids,
						ssd.id_detalle as idsd,
						ssd.id_emision as ide,
						ssd.no_emision as d_no_emision,
						ssd.no_poliza as d_no_poliza,
						ssd.producto as d_producto,
						(case ssd.producto 
							when "DE" then "Desgravamen"
							when "CCB" then "Desgravamen"
							when "CCD" then "Desgravamen"
							when "CDB" then "Desgravamen"
							when "CDD" then "Desgravamen"
							when "VG" then "Desgravamen"
							when "AU" then "Automotores"
							when "TRD" then "Todo Riesgo Domiciliario"
							when "TRM" then "Ramos Ténicos"
						end) as d_producto_text,
						ssd.no_operacion as d_no_operacion,
						ssd.plazo as d_plazo,
						(case ssd.tipo_plazo 
							when "Y" then "Años"
							when "M" then "Meses"
							when "W" then "Semanas"
							when "D" then "Días"
						end) as d_tipo_plazo,
						date_format(ssd.fecha_desembolso, "%d/%m/%Y") as d_fecha_desembolso,
						ssd.monto_desembolso as d_monto_desembolso,
						(case ssd.moneda 
							when "BS" then "Bolivianos"
							when "USD" then "Dólares"
						end) as d_moneda
					from
						s_siniestro_detalle as ssd
							inner join
						s_siniestro as ssi ON (ssi.id_siniestro = ssd.id_siniestro)
					where
						ssi.id_siniestro = "'.$rowSi['ids'].'"
							and ssd.producto like "%'.$s_branch.'%"
					order by ssd.id_detalle asc
					;';
					
				if(($rsSd = $link->query($sqlSd,MYSQLI_STORE_RESULT))){
					if($rsSd->num_rows === $nSi){
						while($rowSd = $rsSd->fetch_array(MYSQLI_ASSOC)){
							if($rowSpan === TRUE){
								$rowSpan = 'rowspan="'.$nSi.'"';
							}elseif($rowSpan === FALSE){
								$rowSpan = '';
							}elseif($rowSpan === 'rowspan="'.$nSi.'"'){
								$rowSpan = 'style="display:none;"';
							}
?>
		<tr style=" <?=$bg;?> " class="row" rel="0">
        	<td <?=$rowSpan;?>><?=$rowSi['s_no_siniestro'];?></td>
            <td ><?=$rowSd['d_no_emision'];?></td>
            <td ><?=$rowSd['d_no_poliza'];?></td>
            <td ><?=$rowSi['s_nombre'];?></td>
            <td ><?=$rowSi['s_ci'];?></td>
            <td ><?=$rowSi['s_fecha_registro'];?></td>
            <td ><?=$rowSi['s_fecha_siniestro'];?></td>
            <td ><?=$rowSi['s_circunstancia'];?></td>
            <td ><?=$rowSd['d_producto_text'];?></td>
            <td ><?=$rowSd['d_plazo'].' '.$rowSd['d_tipo_plazo'];?></td>
            <td ><?=$rowSd['d_monto_desembolso'].' '.$rowSd['d_moneda'];?></td>
            <td ><?=$rowSd['d_fecha_desembolso'];?></td>
            <td ><?=$rowSi['s_usuario_nombre'];?></td>
            <td ><?=$rowSi['s_sucursal'];?></td>
            <td ><?=$rowSi['s_agencia'];?></td>
		</tr>
<?php
						}
					}
				}
				if($swBG === FALSE)
					$swBG = TRUE;
				elseif($swBG === TRUE)
					$swBG = FALSE;
			}
?>
	</tbody>
<?php
		}
	}
}
?>
</table>