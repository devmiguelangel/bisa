<?php

require_once 'sibas-db.class.php';
require 'classes/BisaWs.php';

$link = new SibasDB();
$ide = 0;
$idc = 0;
if(isset($_GET['ide'])) {
	$ide = $link->real_escape_string(base64_decode($_GET['ide']));
} elseif (isset($_GET['idc'])) {
	$idc = $link->real_escape_string(base64_decode($_GET['idc']));
}

$max_item = 0;
if (($rowTR = $link->get_max_amount_optional($_SESSION['idEF'], 'TRD')) !== FALSE) {
	$max_item = (int)$rowTR['max_item'];
}

$ws_db = $link->checkWebService($_SESSION['idEF'], 'TRD');

$cp = false;

$flag = $_GET['flag'];
$action = '';

$read_new = '';
$read_save = '';
$read_edit = '';
$title = '';
$title_btn = '';
$link_save = 'index.php';
$token_issue = true;

$sw = 0;
$swMo = false;

switch($flag){
	case md5('i-new'):
		$action = 'TRD-issue-record.php';
		$title = 'Emisión de Póliza de Seguro de Todo Riesgo Domiciliario';
		$title_btn = 'Guardar';
		
		$read_new = 'readonly';
		$sw = 1;
		break;
	case md5('i-read'):
		$action = 'TRD-policy-record.php';
		$title = 'Póliza No. TRD - ';
		$title_btn = 'Emitir';
		$read_new = 'disabled';
		$read_save = 'disabled';
		$sw = 2;
		break;
	case md5('i-edit'):
		$action = 'TRD-issue-record.php';
		$title = 'Póliza No. TRD - ';
		$title_btn = 'Actualizar Datos';
		$read_edit = 'readonly';
		$sw = 3;
		break;
}

$sql = '';
switch($sw){
	case 1:
		$sql = 'select 
		    strc.id_cotizacion as idc,
		    sef.id_ef as idef,
		    sef.nombre as ef_nombre,
		    strc.certificado_provisional as cp,
		    strc.garantia as c_garantia,
		    strc.ini_vigencia as c_ini_vigencia,
		    strc.fin_vigencia as c_fin_vigencia,
		    strc.forma_pago as c_forma_pago,
		    strc.plazo as c_plazo,
		    strc.tipo_plazo as c_tipo_plazo,
		    strc.prima_total as c_prima_total,
		    scl.tipo as cl_tipo_cliente,
		    scl.codigo_bb as cl_code,
		    scl.id_cliente as idcl,
		    scl.ci as cl_dni,
		    scl.extension as cl_extension,
		    scl.razon_social as cl_razon_social,
		    scl.nombre as cl_nombre,
		    scl.paterno as cl_paterno,
		    scl.materno as cl_materno,
		    scl.ap_casada as cl_ap_casada,
		    scl.fecha_nacimiento as cl_fecha_nacimiento,
		    scl.complemento as cl_complemento,
		    scl.genero as cl_genero,
		    scl.direccion_domicilio as cl_direccion_domicilio,
		    scl.direccion_laboral as cl_direccion_laboral,
		    scl.telefono_domicilio as cl_tel_domicilio,
		    scl.telefono_celular as cl_tel_celular,
		    scl.telefono_oficina as cl_tel_oficina,
		    scl.email as cl_email,
		    scl.pais as cl_pais,
			scl.estado_civil as cl_estado_civil,
		    "" as cl_lugar_residencia,
		    "" as cl_localidad,
		    "" as cl_ocupacion,
		    scl.desc_ocupacion as cl_desc_ocupacion,
		    scl.cargo as cl_cargo,
			scl.ingreso_mensual as cl_ingreso_mensual,
			scl.actividad as cl_actividad,
			scl.ejecutivo as cl_ejecutivo,
		    scl.data_jur,
		    "" as cl_adjunto,
		    "" as cl_cuenta,
		    strd.id_inmueble as idpr,
		    strd.tipo_in as pr_tipo,
		    strd.uso as pr_uso,
		    strd.uso_otro as pr_uso_otro,
		    strd.estado as pr_estado,
		    strd.departamento as pr_departamento,
		    strd.zona as pr_zona,
		    strd.localidad as pr_localidad,
		    strd.direccion as pr_direccion,
		    strd.modalidad as pr_modalidad,
		    strd.valor_asegurado as pr_valor_asegurado,
		    strd.valor_contenido as pr_valor_contenido,
		    strd.tasa as pr_tasa,
			strd.prima as pr_prima,
		    "" as pr_adjunto
		from
		    s_trd_cot_cabecera as strc
		        inner join
		    s_trd_cot_cliente as scl ON (scl.id_cliente = strc.id_cliente)
		        inner join
		    s_trd_cot_detalle as strd ON (strd.id_cotizacion = strc.id_cotizacion)
		        inner join
		    s_entidad_financiera as sef ON (sef.id_ef = strc.id_ef)
		where
		    strc.id_cotizacion = "' . $idc . '"
		        and sef.id_ef = "' . base64_decode($_SESSION['idEF']) . '"
		        and sef.activado = true
		order by strd.id_inmueble asc
		;';
		break;
}

if($sw !== 1){
	$sql = 'select 
	    stre.id_emision as ide,
	    stre.id_cotizacion as idc,
	    sef.id_ef as idef,
	    sef.nombre as ef_nombre,
	    stre.certificado_provisional as cp,
	    stre.garantia as c_garantia,
	    stre.no_emision,
	    stre.ini_vigencia as c_ini_vigencia,
	    stre.fin_vigencia as c_fin_vigencia,
	    stre.plazo as c_plazo,
	    stre.tipo_plazo as c_tipo_plazo,
	    stre.forma_pago as c_forma_pago,
	    stre.prima_total as c_prima_total,
	    stre.operacion as c_no_operacion,
	    stre.id_poliza as c_poliza,
	    stre.facultativo as c_facultativo,
	    stre.motivo_facultativo as c_motivo_facultativo,
	    scl.tipo as cl_tipo_cliente,
	    scl.codigo_bb as cl_code,
	    scl.id_cliente as idcl,
	    scl.ci as cl_dni,
	    scl.extension as cl_extension,
	    scl.razon_social as cl_razon_social,
	    scl.nombre as cl_nombre,
	    scl.paterno as cl_paterno,
	    scl.materno as cl_materno,
	    scl.ap_casada as cl_ap_casada,
	    scl.fecha_nacimiento as cl_fecha_nacimiento,
	    scl.pais as cl_pais,
		scl.estado_civil as cl_estado_civil,
	    scl.complemento as cl_complemento,
	    scl.genero as cl_genero,
	    scl.telefono_domicilio as cl_tel_domicilio,
	    scl.telefono_celular as cl_tel_celular,
	    scl.telefono_oficina as cl_tel_oficina,
	    scl.email as cl_email,
	    scl.avenida as cl_avc,
	    scl.direccion as cl_direccion_domicilio,
	    scl.no_domicilio as cl_no_domicilio,
	    scl.lugar_residencia as cl_lugar_residencia,
	    scl.localidad as cl_localidad,
	    scl.id_ocupacion as cl_ocupacion,
	    scl.desc_ocupacion as cl_desc_ocupacion,
	    scl.direccion_laboral as cl_direccion_laboral,
	    scl.cargo as cl_cargo,
	    scl.ingreso_mensual as cl_ingreso_mensual,
		scl.actividad as cl_actividad,
		scl.ejecutivo as cl_ejecutivo,
	    scl.data_jur,
	    stre.cuenta as cl_cuenta,
	    scl.ci_archivo as cl_adjunto,
	    stre.tomador_codigo as cl_tomador_code,
	    stre.tomador_nombre as cl_tomador_nombre,
		stre.tomador_ci_nit as cl_tomador_dni,
	    strd.id_inmueble as idpr,
	    strd.tipo_in as pr_tipo,
	    strd.uso as pr_uso,
	    strd.uso_otro as pr_uso_otro,
	    strd.estado as pr_estado,
	    strd.departamento as pr_departamento,
	    strd.zona as pr_zona,
	    strd.localidad as pr_localidad,
	    strd.direccion as pr_direccion,
	    strd.modalidad as pr_modalidad,
	    strd.valor_asegurado as pr_valor_asegurado,
	    strd.valor_contenido as pr_valor_contenido,
	    "" as pr_adjunto,
	    strd.tasa as pr_tasa,
    	strd.prima as pr_prima,
    	stre.aprobado,
    	strf.aprobado as f_aprobado,
    	stre.factura_nombre,
		stre.factura_nit
	from
	    s_trd_em_cabecera as stre
	        inner join
	    s_cliente as scl ON (scl.id_cliente = stre.id_cliente)
	        inner join
	    s_trd_em_detalle as strd ON (strd.id_emision = stre.id_emision)
	   		left join
	   	s_trd_facultativo as strf ON (strf.id_emision = stre.id_emision)
	        inner join
	    s_entidad_financiera as sef ON (sef.id_ef = stre.id_ef)
	where
	    stre.id_emision = "'.$ide.'"
	        and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
	        and sef.activado = true
	order by strd.id_inmueble asc
	;';
}
// echo $sql;
$rs = $link->query($sql,MYSQLI_STORE_RESULT);
$nPr = $rs->num_rows;
if($nPr > 0 && $nPr <= $max_item){
	if($sw !== 1){
		if($rs->data_seek(0) === TRUE){
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$idc = $row['idc'];
		}
	}
?>
<script type="text/javascript">
$(document).ready(function(e) {
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
<a href="certificate-detail.php?idc=<?=base64_encode($idc);?>&cia=<?=
	$_GET['cia'];?>&type=<?=base64_encode('PRINT');?>&pr=<?=
	base64_encode('TRD');?>" class="fancybox fancybox.ajax btn-see-slip">
	Ver Solicitud
</a>

<form id="fde-issue" name="fde-issue" action="" method="post" 
	class="form-quote form-customer" enctype="multipart/form-data">
<?php
$cont = 0;

$TASA = 0;
$PRIMA = 0;
$YEAR_FINAL = 0;

$cr_amount = 0;
$cr_term = 0;
$cr_type_term = $cr_method_payment = $cr_opp = $cr_policy = '';
$bill_name = $bill_nit = $taken_name = $taken_nit = $taken_code = '';
$data = array();

$display_nat = $display_jur = 'display: block;';
$read_nat = $read_jur = 'not-required';

$idNE = '';

$FC = FALSE;

if($rs->data_seek(0) === TRUE){
	$row = $rs->fetch_array(MYSQLI_ASSOC);

	$cr_term = $row['c_plazo'];
	$cr_type_term = $row['c_tipo_plazo'];
	$cr_method_payment = $row['c_forma_pago'];
	
	$cl_type_client = (int)$row['cl_tipo_cliente'];
	$cl_code = $row['cl_code'];

	$accounts = array();
	$req = array(
		'codigoCliente' => $cl_code,
	);

	if ($ws_db && $sw === 1) {
		$ws = new BisaWs($link, 'AD', $req);
		$ws->getDataAccount();
		$accounts = $ws->data;
	}
	
	if($cl_type_client === 0) { 
		$display_jur = 'display: none;';
		$read_nat = 'required';
		$row['type_company'] = '';
		$row['registration_number'] = '';
		$row['license_number'] = '';
		$row['number_vifpe'] = '';
		$row['antiquity'] = '';
		$row['ex_ci'] = '';
		$row['ex_ext'] = '';
		$row['ex_birth'] = '';
		$row['ex_profession'] = '';
	} elseif($cl_type_client === 1) { 
		$display_nat = 'display: none;'; 
		$read_jur = 'required';

		$data = json_decode($row['data_jur'], true);
		if (count($data) === 9) {
			$row['type_company'] 		= $data['type_company'];
			$row['registration_number'] = $data['registration_number'];
			$row['license_number'] 		= $data['license_number'];
			$row['number_vifpe'] 		= $data['number_vifpe'];
			$row['antiquity'] 			= $data['antiquity'];
			$row['ex_ci']				= $data['executive_ci'];
			$row['ex_ext']				= $data['executive_ext'];
			$row['ex_birth']			= $data['executive_birth'];
			$row['ex_profession']		= $data['executive_profession'];
		}
	}
	
	if ($sw !== 1) {
		$idNE = $row['no_emision'];
		
		$cr_opp = $row['c_no_operacion'];
		$cr_policy = $row['c_poliza'];
		$mFC = $row['c_motivo_facultativo'];
		
		if ($sw === 2 || $sw === 3) {
			if((boolean)$row['c_facultativo'] === TRUE) {
				$FC = TRUE;
			}
		}

		$taken_code = $row['cl_tomador_code'];
		$taken_name = $row['cl_tomador_nombre'];
		$taken_nit 	= $row['cl_tomador_dni'];
		$bill_name 	= $row['factura_nombre'];
		$bill_nit 	= $row['factura_nit'];

		$aux_account = json_decode($row['cl_cuenta'], true);
		if (is_array($aux_account)) {
			$row['cl_cuenta'] = $aux_account['numero'] . ' / ' 
				. $aux_account['moneda'] . ' / ' . $aux_account['tipo'];
		}
	} else {
		if ($cl_type_client === 0) {
			$taken_name = $row['cl_nombre'] . ' ' . $row['cl_paterno'] . ' ' . $row['cl_materno'];
		} else {
			$taken_name = $row['cl_razon_social'];
		}
		
		$taken_code = $cl_code;
		$bill_name 	= $taken_name;
		$bill_nit 	= $taken_nit = $row['cl_dni'];
	}
	
	$YEAR_FINAL = $link->get_year_final($row['c_plazo'], $row['c_tipo_plazo']);

	if ((boolean)$row['c_garantia'] && $FC === false && $sw > 1) {
		$link_save = 'certificate-policy.php?ms=' . $_GET['ms'] 
			. '&page=' . $_GET['page'] . '&pr=' . base64_encode('TRD') 
			. '&ide=' . base64_encode($row['ide']);
	}
?>
	<h4>Datos del Prestatario</h4>
<?php
if($sw > 1){
	echo '<input type="hidden" id="dc-idcl" name="dc-idcl" value="' 
		. base64_encode($row['idcl']) . '" class="required">';
}
?>
    <input type="hidden" id="dc-type-client" name="dc-type-client" 
    	value="<?=base64_encode($cl_type_client);?>">
    <input type="hidden" id="dc-code" name="dc-code" 
    	value="<?=base64_encode($cl_code);?>">
    <input type="hidden" id="tcs" value="<?= $row['cl_tipo_cliente'] ;?>">
    
    <!-- NATURAL -->
    <div id="form-person" style=" <?=$display_nat;?> ">
    	<div class="form-col">
            <label>Nombres: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-name" name="dc-name" 
                	autocomplete="off" value="<?=$row['cl_nombre'];?>" 
                	class="<?=$read_nat;?> text fbin field-person" <?=$read_new;?>>
            </div><br>
            
            <label>Apellido Paterno: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-ln-patern" name="dc-ln-patern" 
                	autocomplete="off" value="<?=$row['cl_paterno'];?>" 
                	class="<?=$read_nat;?> text fbin field-person" <?=$read_new;?>>
            </div><br>
            
            <label>Apellido Materno: </label>
            <div class="content-input">
                <input type="text" id="dc-ln-matern" name="dc-ln-matern" 
                	autocomplete="off" value="<?=$row['cl_materno'];?>" 
                	class="text fbin" <?=$read_new;?>>
            </div><br>
            
            <label>Documento de Identidad: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-doc-id" name="dc-doc-id" 
                	autocomplete="off" value="<?=$row['cl_dni'];?>" 
                	class="<?=$read_nat;?> dni fbin field-person" <?=$read_new.$read_edit;?>>
            </div><br>
            
            <label>Complemento: </label>
            <div class="content-input">
                <input type="text" id="dc-comp" name="dc-comp" 
                	autocomplete="off" value="<?=$row['cl_complemento'];?>" 
                	class="not-required dni fbin" style="width:60px;" <?=$read_new;?>>
            </div><br>
            
            <label>Extensión: <span>*</span></label>
            <div class="content-input">
                <select id="dc-ext" name="dc-ext" class="<?=$read_nat;?> fbin 
                	field-person <?=$read_new.$read_edit;?>" <?=$read_new;?> >
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
                <input type="text" id="dc-date-birth" name="dc-date-birth" 
                	autocomplete="off" value="<?=$row['cl_fecha_nacimiento'];?>" 
                	class="<?=$read_nat;?> fbin date field-person" readonly 
                	style="cursor:pointer;" <?=$read_new;?>>
            </div><br>

            <label>País: <span>*</span></label>
			<div class="content-input">
				<input type="text" id="dc-country" name="dc-country" 
					autocomplete="off" value="<?=$row['cl_pais'];?>" 
					class="<?=$read_nat;?> text fbin field-person" <?= $read_new ;?>>
			</div><br>
			
			<label>Estado Civil: <span>*</span></label>
			<div class="content-input">
				<select id="dc-status" name="dc-status" 
					class="<?=$read_nat;?> fbin field-person <?=$read_new . ' ' . $read_edit;?>" <?= $read_new ;?>>
	            	<option value="">Seleccione...</option>
	            	<?php foreach ($link->status as $key => $value): $selected = ''; ?>
	            		<?php if ($value[0] === $row['cl_estado_civil']): $selected = 'selected'; ?>
	            		<?php endif ?>
	            	<option value="<?= $value[0] ;?>" <?= $selected ;?>><?= $value[1] ;?></option>
	            	<?php endforeach ?>
				</select>
			</div><br>
            
            <label>Dirección domicilio: <span>*</span></label><br>
            <textarea id="dc-address-home" name="dc-address-home" 
            	class="<?=$read_nat;?> fbin" <?=$read_new . ' ' 
            	. $read_save;?>><?=$row['cl_direccion_domicilio'];?></textarea><br>
        </div><!--
        --><div class="form-col">
            <label>Dirección laboral: <span></span></label><br>
            <textarea id="dc-address-work" name="dc-address-work" 
            	class="fbin" <?=$read_new . ' ' 
            	. $read_save;?>><?=$row['cl_direccion_laboral'];?></textarea><br>

            <label>Ocupación: <span>*</span></label><br>
			<textarea id="dc-desc-occ" name="dc-desc-occ" 
				class="<?= $read_nat ;?> fbin field-person" 
				<?= $read_new ;?>><?= $row['cl_desc_ocupacion'] ;?></textarea><br>

			<label>Cargo: <span>*</span></label><br>
			<div class="content-input" style="width: 350px;">
				<input type="text" id="dc-position" name="dc-position" 
					autocomplete="off" value="<?=$row['cl_cargo'];?>" 
					class="<?= $read_nat ;?> field-person fbin" 
					<?= $read_new ;?> style="width: 350px;">
			</div><br>

			<label>Ingreso Mensual: <span>*</span></label>
			<div class="content-input">
				<select id="dc-monthly-income" name="dc-monthly-income" 
					class="<?=$read_nat;?> fbin field-person <?=$read_new;?>" <?= $read_new ;?>>
	            	<option value="">Seleccione...</option>
	            	<?php foreach ($link->monthly_income['N'] as $key => $value): $selected = ''; ?>
	            		<?php if ($key === (int)$row['cl_ingreso_mensual']): $selected = 'selected'; ?>
	            		<?php endif ?>
	            	<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
	            	<?php endforeach ?>
				</select>
			</div><br>
            
            <label>Teléfono de domicilio: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-phone-1" name="dc-phone-1" 
                	autocomplete="off" value="<?=$row['cl_tel_domicilio'];?>" 
                	class="<?=$read_nat;?> phone fbin" <?=$read_new;?>>
            </div><br>

            <label>Teléfono oficina: </label>
            <div class="content-input">
                <input type="text" id="dc-phone-office" name="dc-phone-office" 
                	autocomplete="off" value="<?=$row['cl_tel_oficina'];?>" 
                	class="not-required phone fbin" <?=$read_new . ' ' . $read_save;?>>
            </div><br>
            
            <label>Teléfono celular: </label>
            <div class="content-input">
                <input type="text" id="dc-phone-2" name="dc-phone-2" 
                	autocomplete="off" value="<?=$row['cl_tel_celular'];?>" 
                	class="not-required phone fbin" <?=$read_new;?>>
            </div><br>
            
            <label>Email: </label>
			<div class="content-input">
				<input type="text" id="dc-email" name="dc-email" 
					autocomplete="off" value="<?=$row['cl_email'];?>" 
					class="not-required email fbin" <?=$read_new;?>>
			</div><br>

			<label>Número de Cuenta: <span>*</span></label>
			<div class="content-input">
				<?php if ($ws_db && $sw === 1): ?>
				<select id="dc-account-nat" name="dc-account-nat" 
					class="<?=$read_nat;?> fbin field-person <?=$read_edit;?>" <?= $read_save ;?>>
	            	<option value="">Seleccione...</option>
	            	<?php foreach ($accounts as $key => $account): $selected = ''; ?>
	            		<?php if ($account['numero'] === (int)$row['cl_cuenta']): $selected = 'selected'; ?>
	            		<?php endif ?>
	            	<option value='<?= $account['account'] ;?>' <?= $selected ;?>>
	            		<?= $account['numero'] . ' / ' . $account['moneda'] . ' / ' . $account['tipo'] ;?>
	            	</option>
	            	<?php endforeach ?>
				</select>
				<?php else: ?>
				<input type="text" id="dc-account-nat" name="dc-account-nat" 
					autocomplete="off" value="<?=$row['cl_cuenta'];?>" 
					class="<?=$read_nat;?> fbin" <?=$read_save . ' ' . $read_edit;?> >
				<?php endif ?>
			</div><br>
        </div><br>
    </div>
    
    <!-- JURIDICO -->
    <div id="form-company" style=" <?=$display_jur;?> ">
    	<div class="form-col">
            <label style="width:auto;">Nombre o Razón Social: <span>*</span></label><br>
            <div class="content-input">
                <textarea id="dc-company-name" name="dc-company-name" 
                	class="<?=$read_jur;?> fbin field-company" <?=$read_new;?>><?=
                	$row['cl_razon_social'];?></textarea><br>
            </div><br>
            
            <label>NIT: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-nit" name="dc-nit" autocomplete="off" 
                	value="<?=$row['cl_dni'];?>" class="<?=$read_jur;?> dni fbin field-company" 
                	<?=$read_new.$read_edit;?>>
            </div><br>
            
            <label>Departamento: <span>*</span></label>
            <div class="content-input">
                <select id="dc-depto" name="dc-depto" class="<?=$read_jur;?> fbin 
                	field-company <?=$read_new.$read_edit;?>" <?=$read_save;?>>
                    <option value="">Seleccione...</option>
<?php
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

            <label style="width: auto;">Tipo de Sociedad Comercial: <span>*</span></label><br>
            <div class="content-input">
				<input type="text" id="dc-type-company" name="dc-type-company" 
					autocomplete="off" value="<?=$row['type_company'];?>" 
					class="<?= $read_jur ;?> field-company text-2 fbin" <?= $read_new ;?>>
			</div><br>

            <label style="width: auto;">No. de Registro en Fundempresa: <span>*</span></label><br>
            <div class="content-input">
				<input type="text" id="dc-registration-number" name="dc-registration-number" 
					autocomplete="off" value="<?=$row['registration_number'];?>" 
					class="<?= $read_jur ;?> field-company text-2 fbin" <?= $read_new ;?>>
			</div><br>

			<label style="width: auto;">No. de Licencia de Funcionamiento GAM: <span>*</span></label><br>
            <div class="content-input">
				<input type="text" id="dc-license-number" name="dc-license-number" 
					autocomplete="off" value="<?=$row['license_number'];?>" 
					class="<?= $read_jur ;?> field-company text-2 fbin" <?= $read_new ;?>>
			</div><br>

			<label style="width: auto;">
				No. de Registro del VIFPE (Solo para Org. sin fines de lucro): <span></span>
			</label><br>
            <div class="content-input">
				<input type="text" id="dc-number-vifpe" name="dc-number-vifpe" 
					autocomplete="off" value="<?=$row['number_vifpe'];?>" 
					class="not-required text-2 fbin" <?= $read_new ;?>>
			</div><br>

			<label style="width: auto;">Actividad y/o Giro del Negocio: <span>*</span></label><br>
			<div class="content-input">
				<textarea id="dc-activity" name="dc-activity" 
					class="<?= $read_jur ;?> fbin field-company" 
						<?= $read_new ;?>><?= $row['cl_actividad'] ;?></textarea><br>
			</div><br>

			<label style="width: auto;">Antigüedad de la Persona Juridica: <span>*</span></label><br>
            <div class="content-input">
				<input type="text" id="dc-antiquity" name="dc-antiquity" 
					autocomplete="off" value="<?=$row['antiquity'];?>" 
					class="<?= $read_jur ;?> field-company text-2 fbin" <?= $read_new ;?>>
			</div><br>
        </div><!--
        --><div class="form-col">
            <label>Dirección domicilio: <span></span></label><br>
			<textarea id="dc-company-address-home" name="dc-company-address-home" 
				class="not-required fbin" <?=$read_new . ' ' . $read_save;?>><?=
					$row['cl_direccion_domicilio'];?></textarea><br>

			<label>Dirección laboral: <span>*</span></label><br>
			<textarea id="dc-company-address-work" name="dc-company-address-work" 
				class="<?=$read_jur;?> fbin" 
				<?=$read_save;?>><?= $row['cl_direccion_laboral'] ;?></textarea><br>

			<label>Principal Ejecutivo: <span>*</span></label><br>
			<div class="content-input" style="width: 350px;">
				<input type="text" id="dc-executive" name="dc-executive" 
					autocomplete="off" value="<?=$row['cl_ejecutivo'];?>" 
					class="<?= $read_jur ;?> field-company text fbin" <?= $read_new ;?> style="width: 350px;">
			</div><br>

			<label>No. de Documento de Identidad: <span>*</span></label>
            <div class="content-input" style="width: auto;">
                <input type="text" id="dc-ex-ci" name="dc-ex-ci" autocomplete="off" 
                	value="<?=$row['ex_ci'];?>" class="<?=$read_jur;?> dni fbin field-company"
                	<?= $read_new ;?> style="width: 110px;">
            </div>
            <div class="content-input" style="width: auto;">
                <input type="text" id="dc-ex-ext" name="dc-ex-ext" autocomplete="off" 
                	value="<?=$row['ex_ext'];?>" class="not-required dni fbin field-company"
                	<?= $read_new ;?> style="width: 45px;">
            </div><br>

            <label>Fecha de Nacimiento: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-ex-birth" name="dc-ex-birth" 
                	autocomplete="off" value="<?=$row['ex_birth'];?>" 
                	class="<?=$read_jur;?> fbin date field-company" 
                	readonly style="cursor:pointer;" <?= $read_new ;?>>
            </div>
            <br>

            <label>Profesión: <span>*</span></label><br>
			<div class="content-input" style="width: 350px;">
				<input type="text" id="dc-ex-profession" name="dc-ex-profession" 
					autocomplete="off" value="<?=$row['ex_profession'];?>" 
					class="<?= $read_jur ;?> field-company text fbin" 
					style="width: 350px;" <?= $read_new ;?>>
			</div><br>

			<label>Cargo: <span>*</span></label><br>
			<div class="content-input" style="width: 350px;">
				<input type="text" id="dc-position2" name="dc-position2" 
					autocomplete="off" value="<?=$row['cl_cargo'];?>" 
					class="<?= $read_jur ;?> field-company fbin" 
					<?= $read_new ;?> style="width: 350px;">
			</div><br>

			<label>Ingreso Mensual: <span>*</span></label>
			<div class="content-input">
				<select id="dc-monthly-income2" name="dc-monthly-income2" 
					class="<?=$read_jur;?> fbin field-company <?= $read_new ;?>" <?= $read_new ;?>>
	            	<option value="">Seleccione...</option>
	            	<?php foreach ($link->monthly_income['J'] as $key => $value): $selected = ''; ?>
	            		<?php if ($key === (int)$row['cl_ingreso_mensual']): $selected = 'selected'; ?>
	            		<?php endif ?>
	            	<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
	            	<?php endforeach ?>
				</select>
			</div><br>

			<label>Teléfono oficina: </label>
            <div class="content-input">
                <input type="text" id="dc-company-phone-office" name="dc-company-phone-office" 
                	autocomplete="off" value="<?=$row['cl_tel_oficina'];?>" 
                	class="not-required phone  fbin" <?=$read_new;?>>
            </div><br>
                
			<label>Email: </label>
            <div class="content-input">
                 <input type="text" id="dc-company-email" name="dc-company-email" 
                 	autocomplete="off" value="<?=$row['cl_email'];?>" 
                 	class="not-required email fbin" <?=$read_new;?>>
            </div><br>

            <label>Número de Cuenta: <span>*</span></label>
			<div class="content-input">
				<?php if ($ws_db && $sw === 1): ?>
				<select id="dc-account-jur" name="dc-account-jur" 
					class="<?=$read_jur;?> fbin field-person <?=$read_edit;?>" <?= $read_save ;?>>
	            	<option value="">Seleccione...</option>
	            	<?php foreach ($accounts as $key => $account): $selected = ''; ?>
	            		<?php if ($account['numero'] === (int)$row['cl_cuenta']): $selected = 'selected'; ?>
	            		<?php endif ?>
	            	<option value='<?= $account['account'] ;?>' <?= $selected ;?>>
	            		<?= $account['numero'] . ' / ' . $account['moneda'] . ' / ' . $account['tipo'] ;?>
	            	</option>
	            	<?php endforeach ?>
				</select>
				<?php else: ?>
				<input type="text" id="dc-account-jur" name="dc-account-jur" 
					autocomplete="off" value="<?=$row['cl_cuenta'];?>" 
					class="<?=$read_jur;?> fbin" <?=$read_save . ' ' . $read_edit;?> >
				<?php endif ?>
			</div><br>
        </div>
    </div>

    <div class="form-col">
		<h4>Datos del Tomador</h4>

    	<label style="width: auto; font-size: 90%;">
            <input class="check various fancybox.ajax" type="checkbox" id="taken-flag" 
            	name="taken-flag" value="1" <?=$read_save;?>>
			&nbsp;&nbsp;El Tomador de la Póliza no es igual al Asegurado
		</label>

		<div class="taken">
			Documento de Identidad:
			<input type="text" id="dsc-dni" autocomplete="off" 
				value="" class="text fbin" style="width: 75px;">
			<input type="text" id="dsc-ext" autocomplete="off" 
				value="" class="text fbin" style="width: 25px;">
			<input type="button" id="dsc-sc" value="Buscar Ciente" class="btn-search-cs">
			<div class="taken__result"></div>
		</div>
		
		<label>Nombre: <span>*</span></label><br>
    	<div class="content-input">
    		<input type="hidden" name="taken-code" id="taken-code" value="<?=$taken_code;?>">
            <textarea id="taken-name" name="taken-name" class="required fbin" 
            	<?=$read_save . $read_edit;?>><?=trim($taken_name);?></textarea><br>
        </div><br>

        <label>CI/NIT: <span>*</span></label>
        <div class="content-input">
            <input type="text" id="taken-nit" name="taken-nit" autocomplete="off" value="<?=$taken_nit;?>" 
            	class="required dni fbin field-company" <?=$read_save . $read_edit;?>>
        </div><br>
    </div><!--
    --><div class="form-col">
    	<h4>Datos de Facturación</h4>
		<label>Facturar a: <span>*</span></label><br>
        <div class="content-input">
            <textarea id="bl-name" name="bl-name" class="required fbin" 
            	<?=$read_save . $read_edit;?>><?=trim($bill_name);?></textarea><br>
        </div><br>
    	
    	<label>NIT: <span>*</span></label>
        <div class="content-input">
            <input type="text" id="bl-nit" name="bl-nit" autocomplete="off" value="<?=$bill_nit;?>" 
            	class="required dni fbin field-company" <?=$read_save . $read_edit;?>>
        </div><br>
    </div>
<?php
}

?>
	<hr>
        
    <h4>Datos del Inmueble</h4>
<?php
if($rs->data_seek(0) === TRUE){
	$k = 0;
?>
	<table class="list-cl list-vh">
<?php
	while($rowPr = $rs->fetch_array(MYSQLI_ASSOC)){
		$k += 1;
?>
        <tr class="title-vh" valign="top">
			<td style="width:20%;">Departamento</td>
			<td style="width:30%;">Zona</td>
			<td style="width:20%;">Ciudad/Localidad</td>
			<td style="width:30%;" colspan="2">Dirección</td>
        </tr>
        <tr valign="top">
            <td>
<?php
if($sw > 1){
	echo '<input type="hidden" id="dp-' . $k . '-idpr" name="dp-' . $k 
		. '-idpr" value="' . base64_encode($rowPr['idpr']) . '" class="required">';
}
?>
            	<select id="dp-<?=$k;?>-depto" name="dp-<?=$k;?>-depto" 
            		class="required fbin <?= $read_new . ' ' . $read_edit ;?>" <?=$read_save;?>>
            		<option value="">Seleccione...</option>
<?php
if(($rsDp = $link->get_depto()) !== FALSE){
	while($rowDp = $rsDp->fetch_array(MYSQLI_ASSOC)){
		if ((boolean)$rowDp['tipo_dp'] === TRUE) {
			if($rowDp['id_depto'] === $rowPr['pr_departamento']) {
				echo '<option value="' . base64_encode($rowDp['id_depto']) . '" selected>' 
					. $rowDp['departamento'] . '</option>';
			} else {
				echo '<option value="' . base64_encode($rowDp['id_depto']) . '">' 
					. $rowDp['departamento'] . '</option>';
			}
		}
	}
}
?>
				</select>
            </td>
            <td>
            	<textarea id="dp-<?=$k;?>-zone" name="dp-<?=$k;?>-zone" 
            		class="required fbin" <?=$read_new . ' ' . $read_edit . ' ' 
            		. $read_save;?>><?=$rowPr['pr_zona'];?></textarea>
            </td>
            <td>
            	<input type="text" id="dp-<?=$k;?>-locality" name="dp-<?=$k;?>-locality" 
            		autocomplete="off" value="<?=$rowPr['pr_localidad'];?>" 
            		class="required text-2 fbin " <?= $read_new . ' ' . $read_edit . ' '.$read_save;?>>
            </td>
            <td colspan="2">
            	<textarea id="dp-<?=$k;?>-address" name="dp-<?=$k;?>-address" 
            		class="required fbin" <?=$read_new . ' ' . $read_edit . ' ' 
            		. $read_save;?>><?=$rowPr['pr_direccion'];?></textarea>
            </td>
        </tr>
        <tr class="title-vh" valign="top">
			<td >Tipo</td>
			<td >Uso</td>
			<td style="width: 15%;">Valor Asegurado</td>
            <td style="width: 15%;">Valor Muebles y/o contenido</td>
            <td style="width: 15%;">Prima</td>
        </tr>
        <tr valign="top">
        	<td>
        		<select id="dp-<?=$k;?>-type" name="dp-<?=$k;?>-type" 
        			class="required fbin <?= $read_new . ' ' . $read_edit ;?>" <?=$read_save;?>>
	            	<option value="">Seleccione...</option>
	            	<?php foreach ($link->typeProperty as $key => $value): $selected = ''; ?>
	            		<?php if ($rowPr['pr_tipo'] === $key): $selected = 'selected'; ?>
	            		<?php endif ?>
						<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
	            	<?php endforeach ?>
	            </select>
        	</td>
        	<td>
            	<select id="dp-<?=$k;?>-use" name="dp-<?=$k;?>-use" 
            		class="required fbin dp-use <?= $read_new . ' ' . $read_edit ;?>" <?=$read_save;?> data-rel="<?=$k;?>">
            		<option value="">Seleccione...</option>
            		<?php foreach ($link->useProperty as $key => $value): $selected = ''; ?>
            			<?php if ($rowPr['pr_uso'] === $key): $selected = 'selected'; ?>
	            		<?php endif ?>
						<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
            		<?php endforeach ?>
				</select><br />
            </td>
            <td>
            	<span class="value"><?=number_format($rowPr['pr_valor_asegurado'], 2, '.', ',');?> USD.</span>
            </td>
            <td>
            	<span class="value"><?=number_format($rowPr['pr_valor_contenido'], 2, '.', ',');?> USD.</span>
            </td>
            <td>
<?php
	$TASA = $rowPr['pr_tasa'];
	$PRIMA = $rowPr['pr_prima'];
?>
				<span class="value value-premium"><?=number_format($PRIMA, 2, '.', ',');?> USD.</span>
				<input type="hidden" id="dp-<?=$k;?>-value-insured" name="dp-<?=$k;?>-value-insured" 
					value="<?=base64_encode($rowPr['pr_valor_asegurado']);?>" class="required">
				<input type="hidden" id="dp-<?=$k;?>-value-content" name="dp-<?=$k;?>-value-content" 
					value="<?=base64_encode($rowPr['pr_valor_contenido']);?>" class="required">
				<input type="hidden" id="dp-<?=$k;?>-rate" name="dp-<?=$k;?>-rate" 
					value="<?=base64_encode($TASA);?>" class="required">
				<input type="hidden" id="dp-<?=$k;?>-premium" name="dp-<?=$k;?>-premium" 
					value="<?=base64_encode($PRIMA);?>" class="required">
            </td>
        </tr>
<?php
	}
?>
	 </table>
<?php
}
?>
	<br>
	<h4>Datos del Crédito Solicitado</h4>
	<div class="form-col">
    	<input type="hidden" id="nPr" name="nPr" value="<?=base64_encode($nPr);?>">
        <input type="hidden" id="di-warranty" name="di-warranty" value="<?=base64_encode($row['c_garantia']);?>">
    	<label>Inicio de Vigencia: <span>*</span></label>
        <div class="content-input">
            <input type="text" id="di-date-inception-1" name="di-date-inception-1" 
            	autocomplete="off" value="<?=date('d/m/Y', strtotime($row['c_ini_vigencia']));?>" 
            	class="required fbin" readonly style="cursor:pointer;" <?=$read_new.$read_edit;?>>
            <input type="hidden" id="di-date-inception" name="di-date-inception" 
            	value="<?=base64_encode($row['c_ini_vigencia']);?>">
            <input type="hidden" id="di-end-inception" name="di-end-inception" 
            	value="<?=base64_encode($row['c_fin_vigencia']);?>">
        </div><br>

        <?php if ((boolean)$row['c_garantia']): ?>
			<label>Plazo: <span>*</span></label>
			<div class="content-input" style="width: auto;">
				<input type="text" id="di-term" name="di-term" autocomplete="off" 
					value="<?=$row['c_plazo'];?>" style="width:30px;" maxlength="" 
					class="required number fbin" <?=$read_save . ' ' . $read_edit;?>>
				<select id="di-type-term" name="di-type-term" 
					class="required fbin <?= $read_edit ;?>" <?=$read_save;?> style="width: 132px;">
					<option value="">Seleccione...</option>
					<?php foreach ($link->typeTerm as $key => $value): $selected = ''; ?>
						<?php if ($key === $row['c_tipo_plazo']): $selected = 'selected'; ?>
						<?php endif ?>
						<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
					<?php endforeach ?>
				</select>
        	</div>
			<br>
        <?php endif ?>
        
		<label>Modalidad de Pago: <span>*</span></label>
		<div class="content-input">
			<select id="di-method-payment" name="di-method-payment" 
				class="required fbin <?=$read_new . $read_edit;?>" <?=$read_save;?>>
				<option value="">Seleccione...</option>
				<?php foreach ($link->methodPayment as $key => $value): $selected = ''; ?>
					<?php if ($key === $cr_method_payment): $selected = 'selected'; ?>
					<?php endif ?>
					<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
				<?php endforeach ?>
			</select>
		</div><br>
	</div><!--
	--><div class="form-col">
<?php
if ($swMo === false) {
?>
		<label>Número de Póliza: <span>*</span></label>
		<div class="content-input">
			<select id="di-policy" name="di-policy" class="required fbin" <?=$read_save;?>>
				<option value="">Seleccione...</option>
<?php
if (($rsPl = $link->get_policy($_SESSION['idEF'], 'TRD')) !== FALSE) {
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

if ((boolean)$row['c_garantia'] && $user_type === 'PA' && $ws_db && $sw === 3) {
	$req = array(
		'codigoCliente' => $taken_code,
	);

	$ws2 = new BisaWs($link, 'WD', $req);
	$ws2->getDataOperation();

	if (empty($cr_opp)) {
		$token_issue = false;
	}
?>
		<label>Operaciones y Garantías: </label>
		<div class="content-input" style="width:auto;">
			<select id="di-opp" name="di-opp" 
				class="fbin field-person " <?= $read_save ;?>>
            	<?php foreach ($ws2->data as $key => $opp): ?>
            	<option value='<?= $opp['opperation'] ;?>' >
            		<?= 'Op. ' . $opp['operacion'] . ' / Gar. ' . $opp['garantia'] 
            			. ' / Monto. ' . $opp['monto'] . ' / Moneda. ' . $opp['moneda'] ;?>
            	</option>
            	<?php endforeach ?>
			</select>
		</div>
<?php
} elseif (empty($cr_opp) === false) {
	$opp = $row['c_no_operacion'] = json_decode($cr_opp, true);
	if (is_array($opp)) {
		$row['c_no_operacion'] = 'Op. ' . $opp['operacion'] . ' / Gar. ' . $opp['garantia'] 
			. ' / ' . $opp['monto'] . ' / ' . $opp['moneda'];
	}
?>
		<label>Operaciones y Garantías: </label>
		<input type="text" id="di-opp" name="di-opp" 
			autocomplete="off" value="<?=$row['c_no_operacion'];?>" 
			class="fbin" <?=$read_save . ' ' . $read_edit;?> >

<?php
} elseif ($ws_db && $sw === 2 && empty($cr_opp)) {
	$token_issue = false;
}
?>
	</div>
    
    <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
	<input type="hidden" id="pr" name="pr" value="<?=$_GET['pr'];?>">
    <input type="hidden" id="flag" name="flag" value="<?=$_GET['flag'];?>">
    <input type="hidden" id="cia" name="cia" value="<?=$_GET['cia'];?>">
    <input type="hidden" id="idef" name="idef" value="<?=$_SESSION['idEF'];?>">
<?php
	if($sw === 1) {
		echo '<input type="hidden" id="cp" name="cp" value="'.base64_encode($row['cp']).'">';
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
	$_IMP = $link->verify_implant($_SESSION['idEF'], 'TRD');
	
	if($_IMP === TRUE) {
		if ($link->verify_agency_issuing($_SESSION['idUser'], $_SESSION['idEF'], 'TRD') === TRUE && $sw === 2) {
			if($FC === TRUE && $sw === 2){
				if(!isset($_GET['target'])) {
					goto btnApproval;
				}
			} else {
				btnIssue: 
				echo '<input type="submit" id="dc-issue" name="dc-issue" value="' 
					. $title_btn . '" class="btn-next btn-issue" > ';
			}
		} elseif ($sw === 2) {
			if(!isset($_GET['target'])) {
				echo '<a href="implant-send-approve.php?ide='.base64_encode($ide).'&pr='.base64_encode('TRD').'" class="fancybox fancybox.ajax btn-issue">Solicitar aprobación del Intermediario</a> ';
			}
		} else {
			goto btnIssue;
			//echo '<input type="submit" id="dc-issue" name="dc-issue" value="'.$title_btn.'" class="btn-next btn-issue" > ';
		}
	} else {
		if($FC === TRUE && $sw === 2 && is_null($row['f_aprobado'])){
			if(!isset($_GET['target'])) {
				btnApproval:
				echo '<a href="company-approval.php?ide=' . base64_encode($ide) 
					. '&pr=' . base64_encode('TRD') . '" 
					class="fancybox fancybox.ajax btn-issue">
					Solicitar aprobación de la Compañia</a> ';
			}
		} else{
			if ($sw === 1) {
				goto btnIssue;
			} elseif ((boolean)$row['c_garantia'] === false) {
				goto btnIssue;
			} elseif ($token_issue && $user_type === 'PA') {
				goto btnIssue;
			} elseif ($user_type === 'PA' && empty($cr_opp) && $sw === 3) {
				goto btnIssue;
			} elseif ($user_type === 'LOG' && $sw === 3) {
				goto btnIssue;
			}
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
	$('.check').iCheck({
		checkboxClass: 'icheckbox_flat-red',
		radioClass: 'iradio_flat-red'
	});

	$('#taken-flag').on('ifChanged', function(e) {
		var payment = $('#di-method-payment').prop('value');
		
		if (payment.length > 0) {
			if ($(this).is(':checked')) {
				$('.taken').slideDown();
			} else {
				$('.taken').slideUp();
			}
		}
	});

	$("#dc-save").click(function(e){
		e.preventDefault();
		location.href = '<?= $link_save ;?>';
	});
	
	$("#dc-edit").click(function(e){
		e.preventDefault();
		location.href = 'trd-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&ide=<?=base64_encode($ide);?>&flag=<?=md5('i-edit');?>&cia=<?=$_GET['cia'].$target;?>';
	});

	$('#dsc-sc').click(function(e) {
		var dni 	= $('#dsc-dni').prop('value');
		var ext 	= $('#dsc-ext').prop('value');

		if (dni.length > 0 && ext.length > 0) {
			$.ajax({
				url: 'data_client.php',
				type: 'GET',
				data: 'op=C&dni=' + dni + '&ext=' + ext,
				dataType: 'json',
				async: true,
				cache: false,
				beforeSend: function(){
					$('.taken__result').html('Espere...');
				},
				complete: function(){
				},
				success: function(result){
					$('.taken__result').html('');

					if (result['token'] === true) {
						$.each(result['data']['clients'], function(index, value) {
							$('.taken__result').append('<a href="" tittle="Codigo de Cliente" \
								class="code-cl" data-code="' + value['codigoCliente'] + '" \
								data-name="' + value['full_name'] + '" \
								data-nit="' + value['nroDocumento'] + value['ext'] + '">' + value['codigoCliente'] + ' - \
								' + value['full_name'] + ' - ' + value['nroDocumento'] + value['ext'] + ' </a><br>');
						});

						setDataClient();
					} else {
						$('.taken__result').html(result['mess']);
					}
				}
			});
		}
	});

	function setDataClient () {
		var type = $('#tcs').prop('value');

		$('.code-cl').click(function(e) {
			e.preventDefault();
			var code = $(this).attr('data-code');
			var name = $(this).attr('data-name');
			var nit = $(this).attr('data-nit');
			
			$('#taken-code').prop('value', code);
			$('#taken-name').prop('value', name);
			$('#taken-nit').prop('value', nit);

			$.getJSON('data_client.php', {
				op: 'A',
				code: code
			}).done(function (result) {
				var field = 'dc-account-';

				switch(type) {
				case '0':
					field += 'nat';
					break;
				case '1':
					field += 'jur';
					break;
				}

				$('#' + field + ' option[value!=""]').remove();
				$.each(result['data']['accounts'], function(index, value) {
					$('#' + field + '').append($('<option>', {
					    value: 	value['account'],
					    text:	value['numero'] + ' / ' + value['moneda'] + ' / ' + value['tipo']
					}));
				});
			});
		});
	}
	
<?php
switch($sw){
	case 1:
?>
	$("#fde-issue").validateForm({
		action: '<?=$action;?>',
		tm: true
	});
	
	$("#fde-issue").submit();
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
	$(".date").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});
	
	$(".date").datepicker($.datepicker.regional[ "es" ]);
	
	$("#fde-issue").validateForm({
		action: '<?=$action;?>',
		tm: true
	});
	$("#issue-title").append(' <?=$idNE;?>');
<?php
		break;
}

if($FC === TRUE && ($sw === 2 || $sw === 3)){
?>
	$("#issue-title:last").after('\
		<div class="fac-mess">\
			<strong>Nota:</strong> Se deshabilitó el boton "Emitir" por las siguientes razones: <br><?=$link->real_escape_string($mFC);?><br><br><strong>Por tanto:</strong><br>Debe solicitar aprobación a la Compañía de Seguros.</div>');
<?php
}
?>
});
</script>
<?php
}else{
	echo 'No existen Clientes';
	exit();
}