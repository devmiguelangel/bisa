<?php
require('sibas-db.class.php');
require('PHPMailer/class.phpmailer.php');
session_start();

if(isset($_GET['rc'])){
	$link = new SibasDB();
	$flag = FALSE;
	if(isset($_GET['rc-send-mail'])) {
		if($_GET['rc-send-mail'] === md5('rc-send')) {
			$flag = TRUE;
		}
	}
	
	$idRc = $link->real_escape_string(trim(base64_decode($_GET['rc'])));
	
	$sql = 'select 
		ssi.id_siniestro as idRc,
		ssi.no_siniestro as s_no_siniestro,
		ssi.fecha_registro as s_fecha_registro,
		ssi.id_cliente as idCl,
		ssi.ci_cliente as s_ci,
		ssi.nombre_cliente as s_nombre,
		ssi.fecha_siniestro as s_fecha_siniestro,
		ssi.circunstancia as s_circunstancia,
		su.id_usuario as s_usuario,
		su.nombre as s_usuario_nombre,
		su.email as s_usuario_email,
		sdp.departamento as s_sucursal,
		sag.agencia as s_agencia,
		sef.id_ef as idef,
		sef.nombre as ef_nombre,
	    sef.logo as ef_logo
	from
		s_siniestro as ssi
			inner join 
		s_entidad_financiera as sef ON (sef.id_ef = ssi.id_ef)
			inner join
		s_usuario as su ON (su.id_usuario = ssi.denunciado_por)
			inner join
		s_departamento as sdp ON (sdp.id_depto = ssi.sucursal)
			left join
		s_agencia as sag ON (sag.id_agencia = ssi.agencia)
	where
		ssi.id_siniestro = "'.$idRc.'"
			and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			and sef.activado = true
	limit 0 , 1
	;';
	
	$body = '';
	if(($rs = $link->query($sql,MYSQLI_STORE_RESULT))){
		$row = $rs->fetch_array(MYSQLI_ASSOC);
		$rs->free();
		
		$body = get_body_sinister($row, $link, $flag);
		
		if($flag === TRUE){
			$mail = new PHPMailer();
			$mail->Host = $row['s_usuario_email'];
			$mail->From = $row['s_usuario_email'];
			$mail->FromName = $row['ef_nombre'];
			$mail->Subject = 'Siniestro No. '.$row['s_no_siniestro'].' '.$row['s_nombre'];
			
			//$mail->addAddress('miguel018mg@outlook.com', 'Miguel Outlook');
			$mail->addAddress($row['s_usuario_email'], $row['s_usuario_nombre']);
			//$mail->addCC($row['s_usuario_email'], $row['s_usuario_nombre']);
			
			$sqlc = 'SELECT sc.correo, sc.nombre 
				FROM s_correo as sc 
					inner join s_entidad_financiera as sef ON (sef.id_ef = sc.id_ef)
				WHERE (sc.producto = "DE" OR sc.producto = "AU" OR sc.producto = "TRD" OR sc.producto = "TRM") 
					AND sef.id_ef = "'.$row['idef'].'" ;';
			if(($rsc = $link->query($sqlc, MYSQLI_STORE_RESULT))){
				if($rsc->num_rows > 0){
					while($rowc = $rsc->fetch_array(MYSQLI_ASSOC)){
						$mail->addCC($rowc['correo'], $rowc['nombre']);
					}
				}
			}
			
			$mail->Body = $body;
			$mail->AltBody = $body;
			
			$arrRC = array(0 => 0, 1 => 'R', 2 => '');
			if($mail->send()){
				$arrRC[0] = 1;
				$arrRC[1] = 'index.php';
				$arrRC[2] = 'El Siniestro fue enviado correctamente !';
			}else {
				$arrRC[2] = 'El Siniestro no pudo ser enviado';
			}
			
			echo json_encode($arrRC);
		}else {
			echo $body;
		}
	}else{
		echo 'No existe el Siniestro';
	}
}

function get_body_sinister($row, $link, $flag){
	ob_start();
?>
<div style="width:700px; border:1px solid #069; padding:5px 8px; color:#000000; font-weight:bold; font-size:12px; text-align:left;">
	<h4>Detalle de Siniestro</h4><br>
	<table style="width:100%;">
    	<tr>
        	<td style="width:30%; padding:1px 0;">
            	<div style="padding:3px 8px; background:#006697; color:#FFFFFF;">N&omicron; de Siniestro: </div>
			</td>
            <td style="width:70%; padding:1px 0;">
            	<div style="padding:3px 8px; color:#006697; font-weight:bold;"><?=$row['s_no_siniestro'];?></div>
			</td>
        </tr>
        <tr>
        	<td style="width:30%; padding:1px 0;">
            	<div style="padding:3px 8px; background:#006697; color:#FFFFFF;">Fecha de Registro: </div>
			</td>
            <td style="width:70%; padding:1px 0;">
            	<div style="padding:3px 8px; color:#006697; font-weight:bold;"><?=$row['s_fecha_registro'];?></div>
			</td>
        </tr>
        <tr>
        	<td style="width:30%; padding:1px 0;">
            	<div style="padding:3px 8px; background:#006697; color:#FFFFFF;">C.I. de Cliente: </div>
			</td>
            <td style="width:70%; padding:1px 0;">
            	<div style="padding:3px 8px; color:#006697; font-weight:bold;"><?=$row['s_ci'];?></div>
			</td>
        </tr>
        <tr>
        	<td style="width:30%; padding:1px 0;">
            	<div style="padding:3px 8px; background:#006697; color:#FFFFFF;">Nombre de Cliente: </div>
			</td>
            <td style="width:70%; padding:1px 0;">
            	<div style="padding:3px 8px; color:#006697; font-weight:bold;"><?=$row['s_nombre'];?></div>
			</td>
        </tr>
        <tr>
        	<td style="width:30%; padding:1px 0;">
            	<div style="padding:3px 8px; background:#006697; color:#FFFFFF;">Fecha de Siniestro: </div>
			</td>
            <td style="width:70%; padding:1px 0;">
            	<div style="padding:3px 8px; color:#006697; font-weight:bold;"><?=$row['s_fecha_siniestro'];?></div>
			</td>
        </tr>
        <tr>
        	<td style="width:30%; padding:1px 0;">
            	<div style="padding:3px 8px; background:#006697; color:#FFFFFF;">Circunstancias: </div>
			</td>
            <td style="width:70%; padding:1px 0;">
            	<div style="padding:3px 8px; color:#006697; font-weight:bold; border:1px solid #CCCCCC;">&nbsp;
					<?=$row['s_circunstancia'];?>
				</div>
			</td>
        </tr>
        <tr>
        	<td style="width:30%; padding:1px 0;">
            	<div style="padding:3px 8px; background:#006697; color:#FFFFFF;">Denunciado por: </div>
			</td>
            <td style="width:70%; padding:1px 0;">
            	<div style="padding:3px 8px; color:#006697; font-weight:bold;"><?=$row['s_usuario_nombre'];?></div>
			</td>
        </tr>
        <tr>
        	<td style="width:30%; padding:1px 0;">
            	<div style="padding:3px 8px; background:#006697; color:#FFFFFF;">Sucursal: </div>
			</td>
            <td style="width:70%; padding:1px 0;">
            	<div style="padding:3px 8px; color:#006697; font-weight:bold;"><?=$row['s_sucursal'];?></div>
			</td>
        </tr>
        <tr>
        	<td style="width:30%; padding:1px 0;">
            	<div style="padding:3px 8px; background:#006697; color:#FFFFFF;">Agencia: </div>
			</td>
            <td style="width:70%; padding:1px 0;">
            	<div style="padding:3px 8px; color:#006697; font-weight:bold;"><?=$row['s_agencia'];?></div>
			</td>
        </tr>
        
    </table><br>
    
    <h5>P&oacute;lizas vigentes en el banco</h5>
    <table style="width:100%; font-size:10px;">
    	<thead>
        	<tr>
                <td style="width:10%; padding:2px 2px; text-align:center; font-weight:bold;">No. Certificado</td>
                <td style="width:10%; padding:2px 2px; text-align:center; font-weight:bold;">No. Póliza</td>
                <td style="width:20%; padding:2px 2px; text-align:center; font-weight:bold;">Ramo</td>
                <td style="width:10%; padding:2px 2px; text-align:center; font-weight:bold;">No. Operación</td>
                <td style="width:20%; padding:2px 2px; text-align:center; font-weight:bold;">Plazo del Crédito</td>
                <td style="width:10%; padding:2px 2px; text-align:center; font-weight:bold;">Fecha desembolso</td>
                <td style="width:20%; padding:2px 2px; text-align:center; font-weight:bold;">Monto desembolsado</td>
            </tr>
        </thead>
        <tbody>
<?php
	$sqlSd = 'select 
			ssi.id_siniestro as idRc,
			ssd.id_detalle as idRd,
			ssd.id_emision as ide,
			ssd.no_emision as d_no_emision,
			ssd.no_poliza as d_no_poliza,
			(case ssd.producto 
				when "DE" then "Desgravamen"
				when "CCB" then "Desgravamen"
				when "CCD" then "Desgravamen"
				when "CDB" then "Desgravamen"
				when "CDD" then "Desgravamen"
				when "VG" then "Desgravamen"
				when "AU" then "Automotores"
				when "TRD" then "Todo Riesgo Domiciliario"
				when "TRM" then "Ramos Técnicos"
			end) as d_producto,
			ssd.no_operacion as d_no_operacion,
			ssd.plazo as d_plazo,
			(case ssd.tipo_plazo 
				when "Y" then "Años"
				when "M" then "Meses"
				when "W" then "Semanas"
				when "D" then "Días"
			end) as d_tipo_plazo,
			ssd.fecha_desembolso as d_fecha_desembolso,
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
			ssi.id_siniestro = "'.$row['idRc'].'"
		order by ssd.id_detalle asc
		;';
	
	if(($rsSd = $link->query($sqlSd, MYSQLI_STORE_RESULT))){
		if($rsSd->num_rows > 0){
			while($rowSd = $rsSd->fetch_array(MYSQLI_ASSOC)){
?>
			<tr style="border-bottom:1px solid #999;">
                <td style="width:10%; padding:2px 2px; text-align:center;"><?=$rowSd['d_no_emision'];?></td>
                <td style="width:10%; padding:2px 2px; text-align:center;"><?=$rowSd['d_no_poliza'];?></td>
                <td style="width:10%; padding:2px 2px; text-align:center;"><?=$rowSd['d_producto'];?></td>
                <td style="width:10%; padding:2px 2px; text-align:center;"><?=$rowSd['d_no_operacion'];?></td>
                <td style="width:10%; padding:2px 2px; text-align:center;"><?=$rowSd['d_plazo'].' '.$rowSd['d_tipo_plazo'];?></td>
                <td style="width:10%; padding:2px 2px; text-align:center;"><?=date('d/m/Y',strtotime($rowSd['d_fecha_desembolso']));?></td>
                <td style="width:10%; padding:2px 2px; text-align:center;"><?=number_format($rowSd['d_monto_desembolso'],2,'.',',').' '.$rowSd['d_moneda'];?></td>
            </tr>
<?php
			}
		}
	}
?>
        </tbody>
    	
    </table>
<?php
	if($flag === FALSE){
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#frc-send").validateForm({
		action: 'rc-send-sinister.php',
		method: 'GET',
		nameLoading: '.loading-01',
	});
});
</script>
	<form id="frc-send" name="frc-send" class="form-quote form-customer">
    	<div style="text-align:center;">
        	<input type="hidden" id="rc" name="rc" value="<?=base64_encode($row['idRc']);?>" class="required">
            <input type="hidden" id="rc-send-mail" name="rc-send-mail" value="<?=md5('rc-send');?>">
        	<input type="submit" id="rc-send" name="rc-send" value="Enviar" class="btn-next btn-issue" >
        </div>
    </form>
    <div class="loading loading-01">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
<?php
	}
?>
</div>
<?php
	$html = ob_get_clean();
	return $html;
}
?>