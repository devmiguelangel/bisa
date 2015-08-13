<?php
require_once('sibas-db.class.php');
$link = new SibasDB();
$ide = 0;
$idc = 0;
if(isset($_GET['ide'])) {
	$ide = $link->real_escape_string(base64_decode($_GET['ide']));
} elseif(isset($_GET['idc'])) {
	$idc = $link->real_escape_string(base64_decode($_GET['idc']));
}

$max_item = 0;
if (($rowDE = $link->get_max_amount_optional($_SESSION['idEF'], 'DE')) !== FALSE) {
	$max_item = (int)$rowDE['max_item'];
}

$flag = $_GET['flag'];
$action = '';

$read_new = '';
$read_save = '';
$read_edit = '';
$title = '';
$title_btn = '';
$cp = null;

$sw = 0;

switch($flag){
	case md5('i-new'):
		$action = 'DE-issue-record.php';
		$title = 'Emisión de Póliza de Desgravamen';
		$title_btn = 'Guardar';
		
		$read_new = 'readonly';
		$sw = 1;
		break;
	case md5('i-read'):
		$action = 'DE-policy-record.php';
		$title = 'Póliza No. ';
		$title_btn = 'Emitir';
		$read_new = 'disabled';
		$read_save = 'disabled';
		$sw = 2;
		break;
	case md5('i-edit'):
		$action = 'DE-issue-record.php';
		$title = 'Póliza No. ';
		$title_btn = 'Actualizar Datos';
		$read_edit = 'readonly';
		$sw = 3;
		break;
}

$sql = '';
switch($sw){
	case 1:
		$sql = 'select 
			sdc.id_cotizacion as idc,
			sdc.certificado_provisional as cp,
			sdc.cobertura,
			sdc.id_prcia,
			sdc.monto,
			sdc.moneda,
			sdc.plazo,
			sdc.tipo_plazo,
			scl.id_cliente,
			scl.nombre as cl_nombre,
			scl.paterno as cl_paterno,
			scl.materno as cl_materno,
			scl.ap_casada as cl_casada,
			scl.genero as cl_genero,
			scl.estado_civil as cl_estado_civil,
			scl.tipo_documento as cl_tipo_documento,
			scl.ci as cl_ci,
			scl.complemento as cl_complemento,
			scl.extension as cl_extension,
			scl.fecha_nacimiento as cl_fecha_nacimiento,
			scl.pais as cl_pais,
			scl.lugar_nacimiento as cl_lugar_nacimiento,
			scl.lugar_residencia as cl_lugar_residencia,
			scl.localidad as cl_localidad,
			scl.direccion as cl_direccion,
			scl.telefono_domicilio as cl_telefono_domicilio,
			scl.telefono_celular as cl_telefono_celular,
			scl.email as cl_email,
			scl.id_ocupacion as cl_ocupacion,
			scl.desc_ocupacion as cl_desc_ocupacion,
			scl.telefono_oficina as cl_telefono_oficina,
			scl.peso as cl_peso,
			scl.estatura as cl_estatura,
			scl.edad as cl_edad,
			sdd.porcentaje_credito as cl_porcentaje_credito,
			sdd.id_detalle,
			sdr.id_respuesta,
			sdr.respuesta as cl_respuesta,
			sdr.observacion as cl_observacion,
			sdc.id_pr_extra,
			sdc.modalidad
		from
			s_de_cot_cabecera as sdc
				inner join
			s_de_cot_detalle as sdd ON (sdd.id_cotizacion = sdc.id_cotizacion)
				inner join
			s_de_cot_cliente as scl ON (scl.id_cliente = sdd.id_cliente)
				inner join
			s_de_cot_respuesta as sdr ON (sdr.id_detalle = sdd.id_detalle)
				inner join 
			s_entidad_financiera as sef ON (sef.id_ef = sdc.id_ef)
		where
			sdc.id_cotizacion = "'.$idc.'"
				and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
				and sef.activado = true
		order by sdd.id_detalle asc
		;';
		break;
}

if($sw !== 1){
	$sql = 'select 
		sdc.id_emision as idc,
		sdc.id_cotizacion,
		sdc.no_emision,
		sdc.prefijo,
		sdc.certificado_provisional as cp,
		sdc.cobertura,
		sdc.id_prcia,
		sdc.monto_solicitado as monto,
		sdc.monto_deudor,
		sdc.monto_codeudor,
		sdc.cumulo_deudor,
		sdc.cumulo_codeudor,
		sdc.moneda,
		sdc.plazo,
		sdc.tipo_plazo,
		sdc.operacion,
		sdc.no_operacion,
		sdc.id_poliza,
		scl.id_cliente,
		scl.nombre as cl_nombre,
		scl.paterno as cl_paterno,
		scl.materno as cl_materno,
		scl.ap_casada as cl_casada,
		scl.genero as cl_genero,
		scl.estado_civil as cl_estado_civil,
		scl.tipo_documento as cl_tipo_documento,
		scl.ci as cl_ci,
		scl.complemento as cl_complemento,
		scl.extension as cl_extension,
		scl.fecha_nacimiento as cl_fecha_nacimiento,
		scl.pais as cl_pais,
		scl.lugar_nacimiento as cl_lugar_nacimiento,
		scl.lugar_residencia as cl_lugar_residencia,
		scl.localidad as cl_localidad,
		scl.direccion as cl_direccion,
		scl.telefono_domicilio as cl_telefono_domicilio,
		scl.telefono_celular as cl_telefono_celular,
		scl.email as cl_email,
		scl.id_ocupacion as cl_ocupacion,
		scl.desc_ocupacion as cl_desc_ocupacion,
		scl.telefono_oficina as cl_telefono_oficina,
		scl.peso as cl_peso,
		scl.estatura as cl_estatura,
		scl.edad as cl_edad,
		scl.avenida as cl_avenida,
		scl.no_domicilio as cl_nd,
		scl.direccion_laboral as cl_direccion_laboral,
		scl.mano as cl_mano,
		sdd.porcentaje_credito as cl_porcentaje_credito,
		sdd.id_detalle,
		sdr.id_respuesta,
		sdr.respuesta as cl_respuesta,
		sdr.observacion as cl_observacion,
		sdc.facultativo,
		sdc.motivo_facultativo,
		sdct.id_pr_extra,
		sdc.modalidad
	from
		s_de_em_cabecera as sdc
			inner join
		s_de_cot_cabecera as sdct ON (sdct.id_cotizacion = sdc.id_cotizacion)
			inner join
		s_de_em_detalle as sdd ON (sdd.id_emision = sdc.id_emision)
			inner join
		s_cliente as scl ON (scl.id_cliente = sdd.id_cliente)
			inner join
		s_de_em_respuesta as sdr ON (sdr.id_detalle = sdd.id_detalle)
			inner join 
		s_entidad_financiera as sef ON (sef.id_ef = sdc.id_ef)
	where
		sdc.id_emision = "'.$ide.'"
			and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
			and sef.activado = true
	order by sdd.id_detalle asc
	;';
}

$rs = $link->query($sql,MYSQLI_STORE_RESULT);
$nCl = $rs->num_rows;
if($nCl > 0 && $nCl <= $max_item){
	if($rs->data_seek(0) === TRUE){
		$row = $rs->fetch_array(MYSQLI_ASSOC);
		if ($sw !== 1) {
			$idc = $row['id_cotizacion'];
		}
	}
?>
<script type="text/javascript">
$(document).ready(function(e) {
    $("#dcr-type-mov").change(function(){
		var tm = $(this).prop('value');
		var sw = 0;
		var amount = $("#dcr-amount").prop('value');
		var currency = $("#dcr-currency").prop('value');
		var tcm = $('#tcm').prop('value');
		var mess = 'No tomar en cuenta <br> Solicitud Actual';
		var _idd = $("#dcr-amount-acc").prop('id');
		var _idc = $("#dcr-amount-acc-2").prop('id');
		
		if (currency === 'USD') {
			tcm = parseFloat(tcm);
			amount = amount * tcm;
		}
		
		if($("#"+_idd+" + .msg-form-lc").length)
			$("#"+_idd+" + .msg-form-lc").remove();
		
		if($("#"+_idc+" + .msg-form-lc").length)
			$("#"+_idc+" + .msg-form-lc").remove();
		
		$(".amount, .amount-2, .amount-type").show();
		$("#dcr-amount-de, #dcr-amount-cc").removeClass('required');
		$("#dcr-amount-de, #dcr-amount-cc").prop('value', '');
		$("#dcr-amount-de, #dcr-amount-cc").prop('readonly', true);
		$("#dcr-amount-acc, #dcr-amount-acc-2").prop('value', amount);
		$("#dcr-amount-acc, #dcr-amount-acc-2, .amount-mess-lc").hide();
		
		switch(tm){
			case 'PU':
				sw = 1;
				$("#dcr-amount-acc, #dcr-amount-acc-2").prop('value', amount);
				break;
			case 'AD':
				sw =2;
				$("#dcr-amount-de, #dcr-amount-cc").addClass('required');
				$("#dcr-amount-de, #dcr-amount-cc").prop('readonly', false);
				break;
			case 'LC':
				sw = 3;
				$("#dcr-amount-de, #dcr-amount-cc").prop('readonly', false);
				mess = 'Llenar solo en caso que el cliente tenga créditos adicionales, FUERA de la línea.';
				break;
		}
		
		$(".amount, .amount-2").html(amount);
		$(".amount-text").html(mess);
		
		if(sw !== 0){
			$("#dcr-amount-acc, #dcr-amount-acc-2").addClass('required');
			if(sw === 3){
				$(".amount-text").addClass('amount-lc');
				$("#dcr-amount-de, #dcr-amount-cc").prop('value', 0);
				$("#dcr-amount-acc, #dcr-amount-acc-2").prop('value', '');
				
				$(".amount, .amount-2, .amount-type").hide();
				$("#dcr-amount-acc, #dcr-amount-acc-2, .amount-mess-lc").show();
			}else{
				$(".amount-text").removeClass('amount-lc');
			}
			$(".amout-total, .amount-icon, .amount-text").show();
		}else{
			$(".amout-total, .amount-icon, .amount-text").hide();
		}
	});
	
	$("#dcr-amount-de, #dcr-amount-cc").keyup(function(e){
		var amount = parseFloat($("#dcr-amount").prop('value'));
		var amount_de = parseFloat($(this).prop('value'));
		var currency = $("#dcr-currency").prop('value');
		var tcm = $('#tcm').prop('value');
		var total = 0;
		if(currency === 'USD') {
			tcm = parseFloat(tcm);
			amount = amount * tcm;
		}
		
		var rel = parseInt($(this).attr('rel'));
		
		if(!isNaN(amount_de)){
			if(validarRealf(amount_de) === true){
				total = amount + amount_de;
				if($("#dcr-type-mov").prop('value') === 'AD'){
					if(rel === 1){
						$("#dcr-amount-acc").prop('value', total);
						$(".amount").html(total);
					}else if(rel === 2){
						$("#dcr-amount-acc-2").prop('value', total);
						$(".amount-2").html(total);
					}
				}
			}else{
				$(this).prop('value', '');
			}
		}else{
			$(this).prop('value', '');
			if(rel === 1){
				$("#dcr-amount-acc").prop('value', amount);
				$(".amount").html(amount);
			}else if(rel === 2){
				$("#dcr-amount-acc-2").prop('value', amount);
				$(".amount-2").html(amount);
			}
		}
	});
	
	$("#dcr-amount-acc, #dcr-amount-acc-2").keyup(function(e){
		var rel = parseInt($(this).attr('rel'));
		
		var amount = parseFloat($("#dcr-amount").prop('value'));
		var amount_de = 0;
		var text = '';
		if(rel === 1){
			amount_de = parseFloat($("#dcr-amount-de").prop('value'));
			text = 'deudor';
		}else if(rel === 2){
			amount_de = parseFloat($("#dcr-amount-cc").prop('value'));
			text = 'codeudor';
		}
		
		var amount_acc = parseFloat($(this).prop('value'));
		var _id = $(this).prop('id');
		var currency = $("#dcr-currency").prop('value');
		if(currency === 'USD')
			amount = amount * 7;
		
		if(!isNaN(amount_acc)){
			if(validarRealf(amount_acc) === true){
				if(amount_acc >= amount && amount_acc >= amount_de){
					if($("#"+_id+" + .msg-form-lc").length)
						$("#"+_id+" + .msg-form-lc").remove();
				}else{
					if(!$("#"+_id+" + .msg-form-lc").length)
						$("#"+_id+":last").after('<span class="msg-form-lc">El monto total acumulado debe ser mayor o igual al monto actual solicitado y al saldo '+text+' actual</span>');
				}
			}else{
				$(this).prop('value', '');
			}
		}else{
			$(this).prop('value', '');
		}
	});
	
	$("select.readonly option").not(":selected").attr("disabled", "disabled");
	
	$("input[type='text'].fbin, textarea.fbin").keyup(function(e){
		var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35);
		var _val = $(this).prop('value');
		
		if($.inArray(e.keyCode, arr_key) < 0 && $(this).hasClass('email') === false){
			$(this).prop('value',_val.toUpperCase());
		}
	});
});


function validarRealf(dat){
	var er_num=/^([0-9])*[.]?[0-9]*$/;
	if(dat.value != ""){
		if(!er_num.test(dat))
			return false;
		return true
	}
}
</script>
<h3 id="issue-title"><?=$title;?></h3>
<a href="certificate-detail.php?idc=<?=base64_encode($idc);?>&cia=<?=$_GET['cia'];?>&type=<?=base64_encode('PRINT');?>&pr=<?=base64_encode('DE');?>" class="fancybox fancybox.ajax btn-see-slip">Imprimir Slip Cotización</a>
<?php
if ($link->verifyModality($_SESSION['idEF'], 'DE') === false) {
?>
<a href="certificate-detail.php?idc=<?=base64_encode($idc);?>&cia=<?=$_GET['cia'];?>&type=<?=base64_encode('PRINT');?>&pr=<?=base64_encode('DE');?>&category=<?=base64_encode('PES');?>" class="fancybox fancybox.ajax btn-see-slip">Imprimir Slip Vida en Grupo</a>
<?php
}
?>

<form id="fde-issue" name="fde-issue" action="" method="post" class="form-quote form-customer">
<?php
$cont = 0;
$rsCl_1 = array();
$rsCl_2 = array();

$cr_coverage = 0;
$cr_product = 0;
$cr_modality = '';
$cr_amount = 0;
$cr_currency = '';
$cr_term = 0;
$cr_type_term = '';
$cr_type_mov = '';
$cr_opp = '';
$cr_policy = '';
$cr_amount_de = '';
$cr_amount_cc = '';
$cr_amount_acd = '';
$cr_amount_acc = '';
$idNE = '';

$swDE = false;
$swMo = false;
$disMo = '';
$readMo = '';
$FC = false;

if($rs->data_seek(0) === TRUE){
while($row = $rs->fetch_array(MYSQLI_ASSOC)){
	$cont += 1;
	if($cont === 1) {
		$rsCl_1 = json_decode($row['cl_respuesta'],TRUE);
	} elseif($cont === 2) {
		$rsCl_2 = json_decode($row['cl_respuesta'],TRUE);
	}
	
	$cr_coverage = $row['cobertura'];
	$cr_product = $row['id_prcia'];
	$cr_modality = $row['modalidad'];
	$cr_amount = $row['monto'];
	$cr_currency = $row['moneda'];
	$cr_term = $row['plazo'];
	$cr_type_term = $row['tipo_plazo'];
	if($sw !== 1){
		$idNE = $row['prefijo'] . '-' . $row['no_emision'];
		$cr_type_mov = $row['operacion'];
		$cr_opp = $row['no_operacion'];
		$cr_policy = $row['id_poliza'];
		$cr_amount_de = $row['monto_deudor'];
		$cr_amount_cc = $row['monto_codeudor'];
		$cr_amount_acd = $row['cumulo_deudor'];
		$cr_amount_acc = $row['cumulo_codeudor'];
		$mFC = $row['motivo_facultativo'];
		
		if($sw === 2 || $sw === 3){
			if((boolean)$row['facultativo'] === TRUE) {
				$FC = TRUE;
			}
		}
	} else {
		$cp = $row['cp'];
	}
	
	$cl_hand = '';	$cl_avc = '';	$cl_nd = '';	$cl_dir_office = '';
	if($sw === 2 || $sw === 3){
		$cl_hand = $row['cl_mano'];	$cl_avc = $row['cl_avenida'];	$cl_nd = $row['cl_nd'];
		$cl_dir_office = $row['cl_direccion_laboral'];
	}
	
	if(($cr_currency === 'BS' && $cr_amount > 35000) 
		|| ($cr_currency === 'USD' && $cr_amount > 5000)){
		$swDE = true;
	}
		
	if ($cr_modality !== null) {
		$swMo = true;
		$disMo = 'display: none;';
		$readMo = 'readonly';
	}
?>
	<h4>Titular <?=$cont;?></h4>
	<div class="form-col">
		<label>Nombres: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-name" name="dc-<?=$cont;?>-name" autocomplete="off" value="<?=$row['cl_nombre'];?>" class="required text fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Apellido Paterno: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-ln-patert" name="dc-<?=$cont;?>-ln-patern" autocomplete="off" value="<?=$row['cl_paterno'];?>" class="required text fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Apellido Materno: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-ln-matern" name="dc-<?=$cont;?>-ln-matern" autocomplete="off" value="<?=$row['cl_materno'];?>" class="not-required text fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Apellido de Casada: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-ln-married" name="dc-<?=$cont;?>-ln-married" autocomplete="off" value="<?=$row['cl_casada'];?>" class="not-required text fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Género: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-gender" name="dc-<?=$cont;?>-gender" class="required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
$arr_gender = $link->gender;
for($i = 0; $i < count($arr_gender); $i++){
	$gender = explode('|',$arr_gender[$i]);
	if($gender[0] === $row['cl_genero']) {
		echo '<option value="'.$gender[0].'" selected>'.$gender[1].'</option>';
	} else {
		echo '<option value="'.$gender[0].'">'.$gender[1].'</option>';
	}
}
?>
			</select>
		</div><br>
		
		<label>Estado Civil: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-status" name="dc-<?=$cont;?>-status" class="required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
$arr_status = $link->status;
for($i = 0; $i < count($arr_status); $i++){
	$status = explode('|',$arr_status[$i]);
	if($status[0] === $row['cl_estado_civil']) {
		echo '<option value="'.$status[0].'" selected>'.$status[1].'</option>';
	} else {
		echo '<option value="'.$status[0].'">'.$status[1].'</option>';
	}
}
?>
			</select>
		</div><br>
		
		<label>Tipo de Documento: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-type-doc" name="dc-<?=$cont;?>-type-doc" class="required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
$arr_type_doc = $link->typeDoc;
for($i = 0; $i < count($arr_type_doc); $i++){
	$type_doc = explode('|',$arr_type_doc[$i]);
	if($type_doc[0] === $row['cl_tipo_documento']) {
		echo '<option value="'.$type_doc[0].'" selected>'.$type_doc[1].'</option>';
	} else {
		echo '<option value="'.$type_doc[0].'">'.$type_doc[1].'</option>';
	}
}
?>
			</select>
		</div><br>
		
		<label>Documento de Identidad: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-doc-id" name="dc-<?=$cont;?>-doc-id" autocomplete="off" value="<?=$row['cl_ci'];?>" class="required dni fbin" <?=$read_new.$read_edit;?>>
		</div><br>
		
		<label>Complemento: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-comp" name="dc-<?=$cont;?>-comp" autocomplete="off" value="<?=$row['cl_complemento'];?>" class="not-required dni fbin" style="width:60px;" <?=$read_new;?>>
		</div><br>
		
		<label>Extensión: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-ext" name="dc-<?=$cont;?>-ext" class="required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
$rsDep = null;
if (($rsDep = $link->get_depto()) === FALSE) {
	$rsDep = null;
}

if ($rsDep->data_seek(0) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_ci'] === TRUE){
			if($rowDep['id_depto'] === $row['cl_extension']) {
				echo '<option value="'.$rowDep['id_depto'].'" selected>'.$rowDep['departamento'].'</option>';
			} else {
				echo '<option value="'.$rowDep['id_depto'].'">'.$rowDep['departamento'].'</option>';
			}
		}
	}
}
?>
			</select>
		</div><br>
		
		<label>Fecha de Nacimiento: <span>*</span></label>
		<div class="content-input">
        	<input type="hidden" id="dc-<?=$cont;?>-age" name="dc-<?=$cont;?>-age" value="<?=$row['cl_edad'];?>">
			<input type="text" id="dc-<?=$cont;?>-date-birth" name="dc-<?=$cont;?>-date-birth" autocomplete="off" value="<?=$row['cl_fecha_nacimiento'];?>" class="required fbin date-birth" <?=$read_new;?>>
		</div><br>
		
		<label>País: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-country" name="dc-<?=$cont;?>-country" autocomplete="off" value="<?=$row['cl_pais'];?>" class="required text fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Lugar de Nacimiento: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-place-birth" name="dc-<?=$cont;?>-place-birth" autocomplete="off" value="<?=$row['cl_lugar_nacimiento'];?>" class="required fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Lugar de Residencia: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-place-res" name="dc-<?=$cont;?>-place-res" class="required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
if ($rsDep->data_seek(0) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_dp'] === TRUE){
			if($rowDep['id_depto'] === $row['cl_lugar_residencia']) {
				echo '<option value="'.$rowDep['id_depto'].'" selected>'.$rowDep['departamento'].'</option>';
			} else {
				echo '<option value="'.$rowDep['id_depto'].'">'.$rowDep['departamento'].'</option>';
			}
		}
	}
}
?>
			</select>
		</div><br>
		
		<label>Localidad: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-locality" name="dc-<?=$cont;?>-locality" autocomplete="off" value="<?=$row['cl_localidad'];?>" class="required text-2 fbin" <?=$read_new;?>>
		</div><br>
        
        <label>Mano utilizada para escribir y/o firmar: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-hand" name="dc-<?=$cont;?>-hand" class="required fbin <?=$read_save;?>" <?=$read_save;?>>
            	<option value="">Seleccione...</option>
<?php
$arr_HA = $link->hand;
for($i = 0; $i < count($arr_HA); $i++){
	$HA = explode('|',$arr_HA[$i]);
	if($HA[0] === $cl_hand) {
		echo '<option value="'.$HA[0].'" selected>'.$HA[1].'</option>';
	} else {
		echo '<option value="'.$HA[0].'">'.$HA[1].'</option>';
	}
}
?>
            </select>
		</div><br>
	</div><!--
	--><div class="form-col">
		<label>Avenida o Calle: <span>*</span></label>
		<div class="content-input">
        	<select id="dc-<?=$cont;?>-avc" name="dc-<?=$cont;?>-avc" class="required fbin <?=$read_save;?>" <?=$read_save;?>>
            	<option value="">Seleccione...</option>
<?php
	$arr_AC = $link->avc;
	for($i = 0; $i < count($arr_AC); $i++){
		$AC = explode('|',$arr_AC[$i]);
		if($AC[0] === $cl_avc) {
			echo '<option value="'.$AC[0].'" selected>'.$AC[1].'</option>';
		} else {
			echo '<option value="'.$AC[0].'">'.$AC[1].'</option>';
		}
	}
?>
            </select>
		</div><br>
		
		<label>Dirección domicilio: <span>*</span></label><br>
		<textarea id="dc-<?=$cont;?>-address-home" name="dc-<?=$cont;?>-address-home" class="required fbin" <?=$read_new;?>><?=$row['cl_direccion'];?></textarea><br>
		
		<label>Número de domicilio: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-nhome" name="dc-<?=$cont;?>-nhome" autocomplete="off" value="<?=$cl_nd;?>" class="required number fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Teléfono 1: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-phone-1" name="dc-<?=$cont;?>-phone-1" autocomplete="off" value="<?=$row['cl_telefono_domicilio'];?>" class="required phone fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Teléfono 2: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-phone-2" name="dc-<?=$cont;?>-phone-2" autocomplete="off" value="<?=$row['cl_telefono_celular'];?>" class="not-required phone fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Email: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-email" name="dc-<?=$cont;?>-email" autocomplete="off" value="<?=$row['cl_email'];?>" class="not-required email fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Ocupación: <span>*</span></label>
		<div class="content-input">
			<select id="dc-<?=$cont;?>-occupation" name="dc-<?=$cont;?>-occupation" class="required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
if (($rsOcc = $link->get_occupation($_SESSION['idEF'])) !== FALSE) {
	while($rowOcc = $rsOcc->fetch_array(MYSQLI_ASSOC)){
		if($rowOcc['id_ocupacion'] === $row['cl_ocupacion']) {
			echo '<option value="'.base64_encode($rowOcc['id_ocupacion']).'" selected>'.$rowOcc['ocupacion'].'</option>';
		} else {
			echo '<option value="'.base64_encode($rowOcc['id_ocupacion']).'">'.$rowOcc['ocupacion'].'</option>';
		}
	}
}
?>
			</select>
		</div><br>
		
		<label style="width:auto;">Descripción Ocupación: <span>*</span></label><br>
		<textarea id="dc-<?=$cont;?>-desc-occ" name="dc-<?=$cont;?>-desc-occ" class="required fbin" <?=$read_new;?>><?=$row['cl_desc_ocupacion'];?></textarea><br>
		
		<label>Dirección laboral: <span>*</span></label><br>
		<textarea id="dc-<?=$cont;?>-address-work" name="dc-<?=$cont;?>-address-work" class="required fbin" <?=$read_save;?>><?=$cl_dir_office;?></textarea><br>
		
		<label>Teléfono oficina: </label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-phone-office" name="dc-<?=$cont;?>-phone-office" autocomplete="off" value="<?=$row['cl_telefono_oficina'];?>" class="not-required phone fbin" <?=$read_new;?>>
		</div><br>
		
		<label>Peso: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-weight" name="dc-<?=$cont;?>-weight" autocomplete="off" value="<?=$row['cl_peso'];?>" class="required wh fbin" <?=$read_new.$read_edit;?>>
		</div><br>
		
		<label>Estatura: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-height" name="dc-<?=$cont;?>-height" autocomplete="off" value="<?=$row['cl_estatura'];?>" class="required wh fbin" <?=$read_new.$read_edit;?>>
		</div><br>
		
		<label>Participación: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dc-<?=$cont;?>-share" name="dc-<?=$cont;?>-share" autocomplete="off" value="<?=(int)$row['cl_porcentaje_credito'];?>" class="required number fbin" <?=$read_new.$read_edit;?>>
		</div><br>
<?php
if($sw === 1){
	$row['id_cliente'] = uniqid('@S#1$2013-'.$cont.'-',true);
}
?>
		<input type="hidden" id="dc-<?=$cont;?>-idcl" name="dc-<?=$cont;?>-idcl" autocomplete="off" value="<?=base64_encode($row['id_cliente']);?>" class="required fbin" <?=$read_new;?>>
	</div><br>
<?php
if($cont === 1) {
	echo '<input type="hidden" id="dc-'.$cont.'-titular" name="dc-'.$cont.'-titular" value="DD">';
} elseif($cont === 2) {
	echo '<input type="hidden" id="dc-'.$cont.'-titular" name="dc-'.$cont.'-titular" value="CC">';
}

$sp_idb = '';	$sp_name = '';	$sp_patern = '';	$sp_matern = '';	$sp_relation = '';	$sp_ci = '';	$sp_ext = '';
$vg_idb = '';	$vg_name = '';	$vg_patern = '';	$vg_matern = '';	$vg_relation = '';	$vg_age = '';
$pr_idb = '';	$pr_name = '';	$pr_patern = '';	$pr_matern = '';	$pr_relation = '';	$pr_ci = '';	$pr_ext = '';
$co_idb = '';	$co_name = '';	$co_patern = '';	$co_matern = '';	$co_relation = '';	$co_ci = '';	$co_ext = '';
if($sw !== 1){
$sqlBN = 'select 
		max(if(sdb.cobertura = "SP", sdb.id_beneficiario, "")) as sp_id,
		max(if(sdb.cobertura = "SP", sdb.nombre, "")) as sp_nombre,
		max(if(sdb.cobertura = "SP", sdb.paterno, "")) as sp_paterno,
		max(if(sdb.cobertura = "SP", sdb.materno, "")) as sp_materno,
		max(if(sdb.cobertura = "SP", sdb.parentesco, "")) as sp_parentesco,
		max(if(sdb.cobertura = "SP", sdb.ci, "")) as sp_ci,
		max(if(sdb.cobertura = "SP", sdb.id_depto, "")) as sp_ext,
		max(if(sdb.cobertura = "VG", sdb.id_beneficiario, "")) as vg_id,
		max(if(sdb.cobertura = "VG", sdb.nombre, "")) as vg_nombre,
		max(if(sdb.cobertura = "VG", sdb.paterno, "")) as vg_paterno,
		max(if(sdb.cobertura = "VG", sdb.materno, "")) as vg_materno,
		max(if(sdb.cobertura = "VG", sdb.parentesco, "")) as vg_parentesco,
		max(if(sdb.cobertura = "VG", sdb.edad, "")) as vg_edad,
		max(if(sdb.cobertura = "PR", sdb.id_beneficiario, "")) as pr_id,
		max(if(sdb.cobertura = "PR", sdb.nombre, "")) as pr_nombre,
		max(if(sdb.cobertura = "PR", sdb.paterno, "")) as pr_paterno,
		max(if(sdb.cobertura = "PR", sdb.materno, "")) as pr_materno,
		max(if(sdb.cobertura = "PR", sdb.parentesco, "")) as pr_parentesco,
		max(if(sdb.cobertura = "PR", sdb.ci, "")) as pr_ci,
		max(if(sdb.cobertura = "PR", sdb.id_depto, "")) as pr_ext,
		max(if(sdb.cobertura = "CO", sdb.id_beneficiario, "")) as co_id,
		max(if(sdb.cobertura = "CO", sdb.nombre, "")) as co_nombre,
		max(if(sdb.cobertura = "CO", sdb.paterno, "")) as co_paterno,
		max(if(sdb.cobertura = "CO", sdb.materno, "")) as co_materno,
		max(if(sdb.cobertura = "CO", sdb.parentesco, "")) as co_parentesco,
		max(if(sdb.cobertura = "CO", sdb.ci, "")) as co_ci,
		max(if(sdb.cobertura = "CO", sdb.id_depto, "")) as co_ext
	from
		s_de_beneficiario as sdb
			inner join
		s_de_em_detalle as sdd ON (sdd.id_detalle = sdb.id_detalle)
	where
		sdb.id_detalle = "'.$row['id_detalle'].'"
	;';
	//echo $sqlBN;
	$rsBN = $link->query($sqlBN,MYSQLI_STORE_RESULT);
	if($rsBN->num_rows === 1){
		$rowBN = $rsBN->fetch_array(MYSQLI_ASSOC);
		$rsBN->free();
		$sp_name = $rowBN['sp_nombre'];
		$sp_patern = $rowBN['sp_paterno'];
		$sp_matern = $rowBN['sp_materno'];
		$sp_relation = $rowBN['sp_parentesco'];
		$sp_ci = $rowBN['sp_ci'];
		$sp_idb = $rowBN['sp_id'];
		
		if(empty($sp_ci) === FALSE) {
			$sp_ext = $rowBN['sp_ext'];
		}
		
		$vg_name = $rowBN['vg_nombre'];
		$vg_patern = $rowBN['vg_paterno'];
		$vg_matern = $rowBN['vg_materno'];
		$vg_relation = $rowBN['vg_parentesco'];
		$vg_age = $rowBN['vg_edad'];
		$vg_idb = $rowBN['vg_id'];
		
		$pr_name = $rowBN['pr_nombre'];
		$pr_patern = $rowBN['pr_paterno'];
		$pr_matern = $rowBN['pr_materno'];
		$pr_relation = $rowBN['pr_parentesco'];
		$pr_ci = $rowBN['pr_ci'];
		$pr_idb = $rowBN['pr_id'];
		if(empty($pr_ci) === FALSE) {
			$pr_ext = $rowBN['pr_ext'];
		}

		$co_name = $rowBN['co_nombre'];
		$co_patern = $rowBN['co_paterno'];
		$co_matern = $rowBN['co_materno'];
		$co_relation = $rowBN['co_parentesco'];
		$co_ci = $rowBN['co_ci'];
		$co_idb = $rowBN['co_id'];
		if(empty($co_ci) === FALSE) {
			$co_ext = $rowBN['co_ext'];
		}
	}
}
?>
    <div class="form-col">
		<h4>Beneficiario para la indemnización de Sepelio - Titular <?=$cont;?></h4>
		<label>Nombres: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dsp-<?=$cont;?>-name" name="dsp-<?=$cont;?>-name" autocomplete="off" value="<?=$sp_name;?>" class="required text fbin" <?=$read_save;?>>
		</div><br>		
		
		<label>Apellido Paterno: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dsp-<?=$cont;?>-ln-patert" name="dsp-<?=$cont;?>-ln-patern" autocomplete="off" value="<?=$sp_patern;?>" class="required text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Apellido Materno: </label>
		<div class="content-input">
			<input type="text" id="dsp-<?=$cont;?>-ln-matern" name="dsp-<?=$cont;?>-ln-matern" autocomplete="off" value="<?=$sp_matern;?>" class="not-required text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Parentesco: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dsp-<?=$cont;?>-relation" name="dsp-<?=$cont;?>-relation" autocomplete="off" value="<?=$sp_relation;?>" class="required text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Documento de Identidad: <span></span></label>
		<div class="content-input">
			<input type="text" id="dsp-<?=$cont;?>-doc-id" name="dsp-<?=$cont;?>-doc-id" autocomplete="off" value="<?=$sp_ci;?>" class="not-required dni fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Extensión: <span></span></label>
		<div class="content-input">
			<select id="dsp-<?=$cont;?>-ext" name="dsp-<?=$cont;?>-ext" class="not-required fbin" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
if (($rsDep->data_seek(0)) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_ci'] === TRUE){
			if($rowDep['id_depto'] === $sp_ext) {
				echo '<option value="'.$rowDep['id_depto'].'" selected>'.$rowDep['departamento'].'</option>';
			} else {
				echo '<option value="'.$rowDep['id_depto'].'">'.$rowDep['departamento'].'</option>';
			}
		}
	}
}
?>
			</select>
		</div><br>
<?php
if($sw !== 1) {
	echo '<input type="hidden" id="dsp-'.$cont.'-idb" name="dsp-'.$cont.'-idb" value="'.base64_encode($sp_idb).'">';
}
?>
	</div>
<?php
if($swMo === false){
?>
	<div class="form-col">
		<h4>Beneficiario para el Seguro de Vida en Grupo - Titular <?=$cont;?></h4>
		<label>Nombres: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dvg-<?=$cont;?>-name" name="dvg-<?=$cont;?>-name" autocomplete="off" value="<?=$vg_name;?>" class="required text fbin" <?=$read_save;?>>
		</div><br>		
		
		<label>Apellido Paterno: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dvg-<?=$cont;?>-ln-patert" name="dvg-<?=$cont;?>-ln-patern" autocomplete="off" value="<?=$vg_patern;?>" class="required text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Apellido Materno: </label>
		<div class="content-input">
			<input type="text" id="dvg-<?=$cont;?>-ln-matern" name="dvg-<?=$cont;?>-ln-matern" autocomplete="off" value="<?=$vg_matern;?>" class="not-required text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Parentesco: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dvg-<?=$cont;?>-relation" name="dvg-<?=$cont;?>-relation" autocomplete="off" value="<?=$vg_relation;?>" class="required text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Edad: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dvg-<?=$cont;?>-age" name="dvg-<?=$cont;?>-age" autocomplete="off" value="<?=$vg_age;?>" class="required number fbin" <?=$read_save;?>>
		</div><br>
<?php
if($sw !== 1) {
	echo '<input type="hidden" id="dvg-'.$cont.'-idb" name="dvg-'.$cont.'-idb" value="'.base64_encode($vg_idb).'">';
}
?>
	</div>
<?php
}else{
	//echo '-->';
}
?>
	<br>
<?php
		if($swMo === false){
?>
	<!--Beneficiarios Primario - Contingente-->
	<div class="form-col">
		<h4>Beneficiario Primario VG - Titular <?=$cont;?></h4>
		<label>Nombres: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dpr-<?=$cont;?>-name" name="dpr-<?=$cont;?>-name" autocomplete="off" value="<?=$pr_name;?>" class="required text fbin" <?=$read_save;?>>
		</div><br>		
		
		<label>Apellido Paterno: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dpr-<?=$cont;?>-ln-patert" name="dpr-<?=$cont;?>-ln-patern" autocomplete="off" value="<?=$pr_patern;?>" class="required text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Apellido Materno: </label>
		<div class="content-input">
			<input type="text" id="dpr-<?=$cont;?>-ln-matern" name="dpr-<?=$cont;?>-ln-matern" autocomplete="off" value="<?=$pr_matern;?>" class="not-required text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Parentesco: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dpr-<?=$cont;?>-relation" name="dpr-<?=$cont;?>-relation" autocomplete="off" value="<?=$pr_relation;?>" class="required text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Documento de Identidad: <span></span></label>
		<div class="content-input">
			<input type="text" id="dpr-<?=$cont;?>-doc-id" name="dpr-<?=$cont;?>-doc-id" autocomplete="off" value="<?=$pr_ci;?>" class="not-required dni fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Extensión: <span></span></label>
		<div class="content-input">
			<select id="dpr-<?=$cont;?>-ext" name="dpr-<?=$cont;?>-ext" class="not-required fbin" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
if (($rsDep->data_seek(0)) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_ci'] === TRUE){
			if($rowDep['id_depto'] === $pr_ext) {
				echo '<option value="'.$rowDep['id_depto'].'" selected>'.$rowDep['departamento'].'</option>';
			} else {
				echo '<option value="'.$rowDep['id_depto'].'">'.$rowDep['departamento'].'</option>';
			}
		}
	}
}
?>
			</select>
		</div><br>
<?php
if($sw !== 1) {
	echo '<input type="hidden" id="dpr-'.$cont.'-idb" name="dpr-'.$cont.'-idb" value="'.base64_encode($pr_idb).'">';
}
?>
	</div>
	<div class="form-col">
		<h4>Beneficiario Contingente VG - Titular <?=$cont;?></h4>
		<label>Nombres: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dco-<?=$cont;?>-name" name="dco-<?=$cont;?>-name" autocomplete="off" value="<?=$co_name;?>" class="required text fbin" <?=$read_save;?>>
		</div><br>		
		
		<label>Apellido Paterno: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dco-<?=$cont;?>-ln-patert" name="dco-<?=$cont;?>-ln-patern" autocomplete="off" value="<?=$co_patern;?>" class="required text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Apellido Materno: </label>
		<div class="content-input">
			<input type="text" id="dco-<?=$cont;?>-ln-matern" name="dco-<?=$cont;?>-ln-matern" autocomplete="off" value="<?=$co_matern;?>" class="not-required text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Parentesco: <span>*</span></label>
		<div class="content-input">
			<input type="text" id="dco-<?=$cont;?>-relation" name="dco-<?=$cont;?>-relation" autocomplete="off" value="<?=$co_relation;?>" class="required text fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Documento de Identidad: <span></span></label>
		<div class="content-input">
			<input type="text" id="dco-<?=$cont;?>-doc-id" name="dco-<?=$cont;?>-doc-id" autocomplete="off" value="<?=$co_ci;?>" class="not-required dni fbin" <?=$read_save;?>>
		</div><br>
		
		<label>Extensión: <span></span></label>
		<div class="content-input">
			<select id="dco-<?=$cont;?>-ext" name="dco-<?=$cont;?>-ext" class="not-required fbin" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
if (($rsDep->data_seek(0)) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_ci'] === TRUE){
			if($rowDep['id_depto'] === $co_ext) {
				echo '<option value="'.$rowDep['id_depto'].'" selected>'.$rowDep['departamento'].'</option>';
			} else {
				echo '<option value="'.$rowDep['id_depto'].'">'.$rowDep['departamento'].'</option>';
			}
		}
	}
}
?>
			</select>
		</div><br>
<?php
if($sw !== 1) {
	echo '<input type="hidden" id="dco-'.$cont.'-idb" name="dco-'.$cont.'-idb" value="'.base64_encode($co_idb).'">';
}
?>
	</div>
<?php
		}
	}
}

if($rs->data_seek(0) === TRUE){
	if (($rsQs = $link->get_question($_SESSION['idEF'])) !== FALSE) {
?>
	<hr>
	<h4>Resultado de las Preguntas</h4>
    <div class="question result-question">
		<span class="qs-no">&nbsp;</span>
		<p class="qs-title" style="text-align:center; font-weight:bold;">Preguntas</p>
		<div class="qs-option">
			Titular 1
		</div>
		<div class="qs-option">
			Titular 2
		</div>
	</div>
<?php
		$resp1 = '';	$resp2 = '';
		$required_1 = '';	$required_2 = '';
		$fac_1 = 1;		$fac_2 = 1;
		while($rowQs = $rsQs->fetch_array(MYSQLI_ASSOC)){
			if(count($rsCl_1) > 0){
				$respCl = explode('|',$rsCl_1[$rowQs['id_pregunta']]);
				if($rowQs['id_pregunta'] === $respCl[0]){
					if($respCl[1] == 1) {
						$resp1 = 'SI';
					} elseif($respCl[1] == 0) {
						$resp1 = 'NO';
					}
						
					if($respCl[1] != $rowQs['respuesta']){
						$required_1 = 'required';
						$fac_1 += 1;
					}
				}
			}
			
			if(count($rsCl_2) > 0){
				$respCl = explode('|',$rsCl_2[$rowQs['id_pregunta']]);
				if($rowQs['id_pregunta'] === $respCl[0]){
					if($respCl[1] == 1) {
						$resp2 = 'SI';
					} elseif($respCl[1] == 0) {
						$resp2 = 'NO';
					}
					
					if($respCl[1] != $rowQs['respuesta']){
						$required_2 = 'required';
						$fac_2 += 1;
					}
				}
			}
?>
	<div class="question result-question">
		<span class="qs-no"><?=$rowQs['orden'];?></span>
		<p class="qs-title"><?=$rowQs['pregunta'];?></p>
		<div class="qs-option"><?=$resp1;?></div>
		<div class="qs-option"><?=$resp2;?></div>
	</div>
<?php
		}
		
		if($rs->data_seek(0) === TRUE){
			$cont = 0;
			$required = '';
			$fac = 0;
			while($row = $rs->fetch_array(MYSQLI_ASSOC)){
				$cont += 1;
				if($cont === 1) {
					$required = $required_1;	$fac = $fac_1;
				} elseif($cont === 2) {
					$required = $required_2;	$fac = $fac_2;
				}
?>
	<label style="width:auto;">Aclaraciones Titular <?=$cont;?></label>
	<textarea id="dq-<?=$cont;?>-resp" name="dq-<?=$cont;?>-resp" style="width:600px; height:100px; margin:4px auto 18px auto;" class="fbin <?=$required;?>" <?=$read_save;?>><?=$row['cl_observacion'];?></textarea>
    <input type="hidden" id="dq-<?=$cont;?>-idd" name="dq-<?=$cont;?>-idd" value="<?=base64_encode($row['id_detalle']);?>">
    <input type="hidden" id="dq-<?=$cont;?>-idr" name="dq-<?=$cont;?>-idr" value="<?=base64_encode($row['id_respuesta']);?>">
    <input type="hidden" id="dq-<?=$cont;?>-fac" name="dq-<?=$cont;?>-fac" value="<?=base64_encode($fac);?>"><br>
<?php
			}
		}
	}else{
		exit();
	}
}
?>
	<hr>
	<h4>Datos del Crédito Solicitado</h4>
	<div class="form-col">
		<label>Tipo de Cobertura:</label>
		<div class="content-input">
        	<select id="dcr-coverage" name="dcr-coverage" class="not-required fbin <?=$read_new.$read_edit;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
$arr_coverage = array(0 => '2|Individual - Mancomuno');
for($i = 0; $i < count($arr_coverage); $i++){
	$coverage = explode('|',$arr_coverage[$i]);
	if($coverage[0] == $cr_coverage) {
		echo '<option value="'.$coverage[0].'" selected>'.$coverage[1].'</option>';
	} else {
		echo '<option value="'.$coverage[0].'">'.$coverage[1].'</option>';
	}
}
?>
			</select>
		</div><br>
		
		<label>Monto Actual Solicitado: <span>*</span></label>
		<div class="content-input">
        	<input type="hidden" id="dcr-amount-r" name="dcr-amount-r" value="<?=(int)$cr_amount;?>">
			<input type="text" id="dcr-amount" name="dcr-amount" autocomplete="off" value="<?=(int)$cr_amount;?>" class="required number fbin" <?=$read_new.$read_edit;?>>
		</div><br>
		
		<label>Moneda: <span>*</span></label>
		<div class="content-input">
        	<input type="hidden" id="dcr-currency-r" name="dcr-currency-r" value="<?=$cr_currency;?>">
        	<select id="dcr-currency" name="dcr-currency" class="required fbin  <?=$read_new.$read_edit;?>">
				<option value="">Seleccione...</option>
<?php
$arr_currency = $link->currency;
for($i = 0; $i < count($arr_currency); $i++){
	$currency = explode('|',$arr_currency[$i]);
	if($currency[0] === $cr_currency) {
		echo '<option value="'.$currency[0].'" selected>'.$currency[1].'</option>';
	} else {
		echo '<option value="'.$currency[0].'">'.$currency[1].'</option>';
	}
}
?>
			</select>
		</div><br>
		
		<label>Plazo del Crédito: <span>*</span></label>
		<div class="content-input" style="width:auto;">
			<input type="text" id="dcr-term" name="dcr-term" autocomplete="off" value="<?=$cr_term;?>" style="width:30px;" maxlength="" class="not-required number fbin" <?=$read_new;?>>
		</div>
		
		<label>&nbsp;</label>
		<div class="content-input">
			<select id="dcr-type-term" name="dcr-type-term" class="required fbin <?=$read_new;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
$arr_term = $link->typeTerm;
for($i = 0; $i < count($arr_term); $i++){
	$term = explode('|',$arr_term[$i]);
	if($term[0] === $cr_type_term) {
		echo '<option value="'.$term[0].'" selected>'.$term[1].'</option>';
	} else {
		echo '<option value="'.$term[0].'">'.$term[1].'</option>';
	}
}
?>
			</select>
		</div><br>
	</div><!--
	--><div class="form-col">
<?php
if($sw > 0){
	$sqlPr = 'select 
			spr.id_producto, spr.nombre
		from
			s_producto as spr
				inner join s_ef_compania as sec ON (sec.id_ef_cia = spr.id_ef_cia)
				inner join s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
		where
			spr.activado = true
				and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
				and sef.activado = true
		limit 0 , 1
		;';
	
	$sqlPr .= 'select 
			spc.id_prcia, spc.nombre, spc.id_producto, spr.nombre as pr_nombre
		from
			s_producto_cia as spc
				inner join
			s_producto as spr ON (spr.id_producto = spc.id_producto)
				inner join 
			s_ef_compania as sec ON (sec.id_ef_cia = spr.id_ef_cia)
				inner join 
			s_entidad_financiera as sef ON (sef.id_ef = sec.id_ef)
		where
			spr.activado = true
				and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
				and sef.activado = true
		order by id_prcia asc
		;';
	
	$swPr = FALSE;
	if($link->multi_query($sqlPr) === TRUE){
		do{
			if($swPr === TRUE) {
				echo ' <select id="prcia" name="prcia" class="required fbin '.$readMo.'" '.$read_save.'>
					<option value="">Seleccione...</option>';
			}
			if($rsPr = $link->store_result()){
				if($rsPr->num_rows === 1){
					$rowPr = $rsPr->fetch_array(MYSQLI_ASSOC);
					if($rowPr['nombre'] === 'PRODUCTO'){
						echo ' <label>Producto: <span>*</span></label>';
						$swPr = TRUE;
					}
				}else{
					while($rowPr = $rsPr->fetch_array(MYSQLI_ASSOC)){
						if($rowPr['id_prcia'] === $cr_product) {
							echo '<option value="'.$rowPr['id_prcia'].'" selected>'.$rowPr['nombre'].'</option>';
						} else {
							echo '<option value="'.$rowPr['id_prcia'].'">'.$rowPr['nombre'].'</option>';
						}
					}
				}
			}
		}while($link->next_result());
		if($swPr === TRUE) {
			echo '</select>';
		}
	}else{
		echo '<input type="hidden" id="prcia" name="prcia" value="'.base64_encode($cr_product).'">';
	}
}

if ($swMo === true) {
?>
		<input type="hidden" id="dcr-modality" name="dcr-modality" value="<?=base64_encode($cr_modality);?>">
<?php
	if ($sw === 1) {
?>
<script type="text/javascript">
$(document).ready(function(){
	$('#dcr-type-mov option[value="AD"]').prop('selected', true);
	$('#dcr-type-mov').trigger('change');
});
</script>
<?php
	}
}
?>
		<div style="<?=$disMo;?>">
		<label>Tipo de Movimiento: <span>*</span></label>
		<div class="content-input">
			<select id="dcr-type-mov" name="dcr-type-mov" class="required fbin" <?=$read_save.' '.$read_edit;?>>
				<option value="">Seleccione...</option>
<?php
$arr_mov = $link->moviment;
for($i = 0; $i < count($arr_mov); $i++){
	$mov = explode('|',$arr_mov[$i]);
	if($mov[0] === $cr_type_mov) {
		echo '<option value="'.$mov[0].'" selected>'.$mov[1].'</option>';
	} else {
		echo '<option value="'.$mov[0].'">'.$mov[1].'</option>';
	}
}
?>
			</select>
		</div>
		</div>

		<label>Número de Operación: </label>
		<div class="content-input" style="width:auto;">
			<input type="text" id="dcr-opp" name="dcr-opp" autocomplete="off" value="<?=$cr_opp;?>" class="not-required number fbin" <?=$read_save;?>>
		</div>
<?php
if ($swMo === false) {
?>
		<label>Número de Póliza: <span>*</span></label>
		<div class="content-input">
			<select id="dcr-policy" name="dcr-policy" class="required fbin" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
if (($rsPl = $link->get_policy($_SESSION['idEF'])) !== FALSE) {
	while($rowPl = $rsPl->fetch_array(MYSQLI_ASSOC)){
		if($rowPl['id_poliza'] == $cr_policy) {
			echo '<option value="'.base64_encode($rowPl['id_poliza']).'" selected>'.$rowPl['no_poliza'].'</option>';
		} else {
			echo '<option value="'.base64_encode($rowPl['id_poliza']).'">'.$rowPl['no_poliza'].'</option>';
		}
	}
}
?>
			</select>
		</div><br>
<?php
}

$opp_dis1 = '';
$opp_dis2 = '';
$opp_dis3 = '';
$opp_dis4 = '';
$opp_txt1 = 'No tomar en cuenta<br>Solicitud Actual';
$opp_class = '';
$opp_read = 'readonly';
if($sw === 3){
	$opp_dis1 = 'display: block;';
	$opp_dis2 = 'display: block;';	
	switch($cr_type_mov){
		case 'PU':
			$opp_dis4 = 'display: none;';
			break;
		case 'AD':
			$opp_dis4 = 'display: none;';	$opp_read = '';
			break;
		case 'LC':
			$opp_txt1 = 'Llenar solo en caso que el cliente tenga créditos adicionales, FUERA de la línea.';
			$opp_dis3 = 'display: none;';	$opp_class = 'amount-lc';	$opp_read = '';
			break;
	}
}elseif($sw === 2){
	$opp_dis2 = 'display: block;';
	$opp_dis3 = 'visibility:visible;';
	$opp_dis4 = 'display: none;';
	switch($cr_type_mov){
		case 'PU':
			
			break;
		case 'AD':
			
			break;
		case 'LC':
			
			break;
	}
}
?>
		<label>Saldo deudor actual del asegurado (Bs.) : </label>
		<div class="content-input" style="width:auto;">
			<input type="hidden" id="tcm" name="tcm" value="<?=$link->get_rate_exchange(true);?>" >
			<input type="text" id="dcr-amount-de" name="dcr-amount-de" autocomplete="off" value="<?=(float)$cr_amount_de;?>" class="not-required real fbin" <?=$opp_read.' '.$read_save;?> rel="1">
            <span class="amount-mess">
            	<div class="amount-icon" style=" <?=$opp_dis1;?> "></div>
            	<div class="amount-text <?=$opp_class;?>" style=" <?=$opp_dis1;?> "><?=$opp_txt1;?></div>
            </span>
		</div>
		<div class="amout-total" style=" <?=$opp_dis2;?> ">
			Monto Total Acumulado<br>
			<span class="amount" style=" <?=$opp_dis3;?> "><?=$cr_amount_acd?></span>
            <span class="amount-type" style=" <?=$opp_dis3;?> "> Bs.</span>
            <br>
            <input type="text" id="dcr-amount-acc" name="dcr-amount-acc" autocomplete="off" value="<?=$cr_amount_acd;?>" class="real fbin" style=" <?=$opp_dis4;?> " rel="1"><br>
            <span class="amount-mess-lc" style=" <?=$opp_dis4;?> ">Sumatoria del total de operaciones vigentes, incluyendo el Monto Actual Solicitado.</span>
		</div>
        
<?php
	if($nCl === $max_item){
?>
		<label>Saldo codeudor actual del asegurado (Bs.) : </label>
        <div class="content-input" style="width:auto;">
			<input type="text" id="dcr-amount-cc" name="dcr-amount-cc" autocomplete="off" value="<?=(float)$cr_amount_cc;?>" class="not-required real fbin" <?=$opp_read.' '.$read_save;?> rel="2">
            <span class="amount-mess">
            	<div class="amount-icon" style=" <?=$opp_dis1;?> "></div>
            	<div class="amount-text <?=$opp_class;?>" style=" <?=$opp_dis1;?> "><?=$opp_txt1;?></div>
            </span>
		</div>
        <div class="amout-total" style=" <?=$opp_dis2;?> ">
			Monto Total Acumulado<br>
			<span class="amount-2" style=" <?=$opp_dis3;?> "><?=$cr_amount_acc?></span>
            <span class="amount-type" style=" <?=$opp_dis3;?> "> Bs.</span>
            <br>
            <input type="text" id="dcr-amount-acc-2" name="dcr-amount-acc-2" autocomplete="off" value="<?=$cr_amount_acc;?>" class="real fbin" style=" <?=$opp_dis4;?> " rel="2"><br>
            <!--<span class="amount-mess-lc" style=" <?=$opp_dis4;?> ">Sumatoria del total de operaciones vigentes, incluyendo el Monto Actual Solicitado.</span>-->
		</div>
<?php
	}
?>
	</div>
    <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
	<input type="hidden" id="pr" name="pr" value="<?=$_GET['pr'];?>">
    <input type="hidden" id="flag" name="flag" value="<?=$_GET['flag'];?>">
    <input type="hidden" id="cia" name="cia" value="<?=$_GET['cia'];?>">
<?php
	if($sw === 1) {
		echo '<input type="hidden" id="cp" name="cp" value="'.base64_encode($cp).'">';
	}
	$target = '';
	if(isset($_GET['target'])){
		echo '<input type="hidden" id="target" name="target" value="'.$_GET['target'].'">';
		$target = '&target='.$_GET['target'];
	}

	if(isset($_GET['ide'])) {
		echo '<input type="hidden" id="de-ide" name="de-ide" value="'.base64_encode($ide).'" >';
	} elseif(isset($_GET['idc'])) {
		echo '<input type="hidden" id="de-idc" name="de-idc" value="'.base64_encode($idc).'" >';
	}
?>
	<div style="text-align:center;">
<?php
	if($sw === 2) {
		echo '<input type="button" id="dc-edit" name="dc-edit" value="Editar" class="btn-next btn-issue" > ';
	}
	
	// IMPLANTE
	$_IMP = $link->verify_implant($_SESSION['idEF'], 'DE');
	
	if($_IMP === TRUE) {
		if ($link->verify_agency_issuing($_SESSION['idUser'], $_SESSION['idEF'], 'DE') === TRUE && $sw === 2) {
			if($FC === TRUE && $sw === 2){
				if(!isset($_GET['target'])) {
					goto btnApproval;
				}
			} else {
				btnIssue: 
				echo '<input type="submit" id="dc-issue" name="dc-issue" value="'.$title_btn.'" class="btn-next btn-issue" > ';
			}
		} elseif ($sw === 2) {
			if (!isset($_GET['target'])) {
				echo '<a href="implant-send-approve.php?ide='.base64_encode($ide).'&pr='.base64_encode('DE').'" class="fancybox fancybox.ajax btn-issue">Solicitar aprobación del Intermediario</a> ';
			}
		} else {
			goto btnIssue;
			//echo '<input type="submit" id="dc-issue" name="dc-issue" value="'.$title_btn.'" class="btn-next btn-issue" > ';
		}
	} else {
		if($FC === TRUE && $sw === 2){
			if(!isset($_GET['target'])) {
				btnApproval:
				echo '<a href="company-approval.php?ide='.base64_encode($ide).'&pr='.base64_encode('DE').'" class="fancybox fancybox.ajax btn-issue">Solicitar aprobación de la Compañia</a> ';
			}
		} else{
			goto btnIssue;
			//echo '<input type="submit" id="dc-issue" name="dc-issue" value="'.$title_btn.'" class="btn-next btn-issue" > ';
		}
	}
	
	if($sw === 2) {
		echo '<input type="button" id="dc-save" name="dc-save" value="Guardar/Cerrar" class="btn-next btn-issue" >';
	}
?>
    </div>
    
    <div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(e) {
	$("#dc-save").click(function(e){
		e.preventDefault();
		location.href = 'index.php';
	});
	
	$("#dc-edit").click(function(e){
		e.preventDefault();
		location.href = 'de-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&ide=<?=base64_encode($ide);?>&flag=<?=md5('i-edit');?>&cia=<?=$_GET['cia'].$target;?>';
	});
<?php
switch($sw){
	case 1:
?>
	$("#fde-issue").validateForm({
		action: '<?=$action;?>',
		tm: true
	});
	$("#fde-issue").submit();
	
	get_change_amount();
<?php
		break;
	case 2:
?>
	$("#fde-issue").validateForm({
		action: '<?=$action;?>',
		tm: true,
		issue: true
	});
	$("#issue-title").append(' <?=$idNE;?>');
	
<?php
		break;
	case 3:
?>
	$(".date-birth").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});
	
	$(".date-birth").datepicker($.datepicker.regional[ "es" ]);
	
	$("#fde-issue").validateForm({
		action: '<?=$action;?>',
		tm: true
	});
	$("#issue-title").append(' <?=$idNE;?>');
	
	get_change_amount();
<?php
		break;
}

if($FC === TRUE && ($sw === 2 || $sw === 3)){
?>
	$("#issue-title:last").after('<div class="fac-mess"><strong>Nota:</strong> Se deshabilitó el boton "Emitir" por las siguientes razones: <br><?=$mFC;?><br><br><strong>Por tanto:</strong><br>Debe solicitar aprobación a la Compañía de Seguros. </div>');
<?php
}
?>
});

function get_change_amount(){
	/*$("#dcr-currency").change(function(){
		var amountR = $("#dcr-amount-r").prop('value');
		var amount = $("#dcr-amount").prop('value');
		
		if(amountR !== amount){
			$("#dcr-amount").prop('value', amountR);
		}
	});*/
	
	/*$("#dcr-amount").keyup(function(e){
		var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35);
		var amount = $(this).prop('value');
		var amountR = $("#dcr-amount-r").prop('value');
		var currency = $("#dcr-currency").prop('value');
		var currencyR = $("#dcr-currency-r").prop('value');
		
		var pr = '';
		if(arr_key.indexOf(e.keyCode) < 0){
			if(/^([0-9])*$/.test(amount)){
				switch(currencyR){
					case 'BS':
						if(amountR > 0 && amountR <= 35000){
							pr = 'VG';
						}else if(amountR > 35000){
							pr = 'DE'
						}
						break;
					case 'USD':
						if(amountR > 0 && amountR <= 5000){
							pr = 'VG';
						}else if(amountR > 5000){
							pr = 'DE'
						}
						break;
				}
				
				if(get_new_amount(pr, amount, amountR, currency, currencyR) === false){
					$(this).prop('value', amountR);
					alert('El Monto Actual Solicitado NO puede ser modificado');
				}
			}else{
				$(this).prop('value', amountR);
			}
		}
	});*/
}

function get_new_amount(pr, amount, amountR, currency, currencyR){
	var arr_limit = new Array();
	arr_limit[1] = 0;
	arr_limit[2] = 35000;
	arr_limit[3] = 5000;
	//arr_limit[4] = Number.MAX_VALUE;
	
	switch(pr){
		case 'VG':
			switch(currency){
				case 'BS':
					if(amount > arr_limit[1] && amount <= arr_limit[2])
						return true;
					else
						return false;
					break;
				case 'USD':
					if(amount > arr_limit[1] && amount <= arr_limit[3])
						return true;
					else
						return false;
					break;
			}
			break;
		case 'DE':
			switch(currency){
				case 'BS':
					if(amount > arr_limit[2])
						return true;
					else
						return false;
					break;
				case 'USD':
					if(amount > arr_limit[3])
						return true;
					else
						return false;
					break;
			}
			break;
	}
}
</script>
<?php
}else{
	echo 'No existen Clientes';
	exit();
}