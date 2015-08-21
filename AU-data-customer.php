<script type="text/javascript">
$(document).ready(function(e) {
	$("#fau-customer").validateForm({
		action: 'AU-customer-record.php'
	});
	
	$(".date").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});
	
	$(".date").datepicker($.datepicker.regional[ "es" ]);
	
	$('input').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
		increaseArea: '20%' // optional
	});

	$('input').on('ifClicked', function(e){
		var type = $(this).prop('value');

		if (type == 1) {
			$('#di-method-payment option[value="CR"]').prop('selected', true);
			$('#di-method-payment option:not(:selected)').prop('disabled', true);
			$('#di-method-payment').trigger('change');
		} else {
			$('#di-method-payment option:not(:selected)').prop('disabled', false);
		}
	});
	
	$("input[type='text'].fbin, textarea.fbin").keyup(function(e){
		var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35, 186);
		var _val = $(this).prop('value');
		
		if($.inArray(e.keyCode, arr_key) < 0 && $(this).hasClass('email') === false){
			$(this).prop('value',_val.toUpperCase());
		}
	});
	
	$("#dc-type-client").change(function(e){
		var type = $(this).prop('value');
		if(type !== ''){
			$("#fau-sc").slideDown();
			$("#form-person, #form-company").hide();
			switch(type){
			case 'NAT':
				$("#dsc-type-client").prop('value', 'NAT');
				$("#form-person").slideDown();
				$("#form-person").find('.field-person')
					.removeClass('not-required')
					.addClass('required');
				$("#form-company").find('.field-company')
					.removeClass('required')
					.addClass('not-required');

				$("#form-company").find('input[type="text"], textarea')
					.prop('value', '');
				
				$('#dsc-ext option:eq(0)').prop('selected', true);
				break;
			case 'JUR':
				$("#dsc-type-client").prop('value', 'JUR');
				$("#form-company").slideDown();
				$("#form-company").find('.field-company')
					.removeClass('not-required')
					.addClass('required');
				$("#form-person").find('.field-person')
					.removeClass('required')
					.addClass('not-required');

				$("#form-person").find('input[type="text"], textarea')
					.prop('value', '');

				$('#dsc-ext option[value="NIT"]').prop('selected', true);
				break;
			}
		}else{
			$("#form-person, #form-company").hide();
			$("#fau-sc").slideUp();
			$("#dsc-type-client").prop('value', '');
		}
	});

	$("#dc-ext option").attr("disabled", "disabled");
	
});
</script>
<?php
require_once 'sibas-db.class.php';
require 'classes/BisaWs.php';

$link = new SibasDB();

$swCl = FALSE;

$dc_code = '';
$dc_name = '';
$dc_company_name = '';
$dc_lnpatern = '';
$dc_lnmatern = '';
$dc_lnmarried = '';
$dc_doc_id = '';
$dc_nit = '';
$dc_comp = '';
$dc_ext = '';
$dc_depto = '';
$dc_birth = '';
$dc_country = '';
$dc_status = '';
$dc_address_home = '';
$dc_address_work = '';
$dc_desc_occ = '';
$dc_monthly_income = '';
$dc_phone_1 = '';
$dc_phone_2 = '';
$dc_email = '';
$dc_company_email = '';
$dc_phone_office = '';
$dc_activity = '';
$dc_executive = '';
$dc_ex_ci = '';
$dc_ex_ext = '';
$dc_ex_birth = '';
$dc_ex_profession = '';
$dc_position = '';
$dc_type_company = '';
$dc_registration_number = '';
$dc_license_number = '';
$dc_number_vifpe = '';
$dc_antiquity = '';
$data = array();


$title_btn = 'Registrar Cliente ';
$err_search = '';

$display_fsc = $display_nat = $display_jur = 'display: none;';
$require_nat = $require_jur = 'not-required';
$_TYPE_CLIENT = '';

if (isset($_POST['dsc-dni']) && isset($_POST['dsc-ext']) && isset($_POST['dsc-type-client'])) {
	$dni = $link->real_escape_string(trim($_POST['dsc-dni']));
	$ext = $link->real_escape_string(trim($_POST['dsc-ext']));
	$type_client = $link->real_escape_string(trim($_POST['dsc-type-client']));
	$_TYPE_CLIENT = $type_client;

	if ($type_client === 'NAT') {
		$type_client = 0;
		$display_nat = 'display: block;';
		$require_nat = 'required';
	} elseif ($type_client === 'JUR') {
		$type_client = 1;
		$display_jur = 'display: block;';
		$require_jur = 'required';
	}
	
	$display_fsc = 'display: block;';

	if ($link->checkWebService($_SESSION['idEF'], 'AU')) {
		$req = array(
			'tipoCliente' 	=> '',
			'nroDocumento' 	=> $dni,
			'sigla' 		=> $ext,
		);

		if ($type_client === 1) {
			$req['tipoCliente'] = 'N';
		} elseif ($type_client === 0) {
			$req['tipoCliente'] = 'P';
		}

		$ws = new BisaWs($link, 'CD', $req);

		if ($ws->getDataCustomer()) {
			$dc_code = $ws->data['codigoCliente'];
			
			if ($type_client === 0) {
				$dc_name 		= $ws->data['primerNombre'] 
					. ' ' . $ws->data['segundoNombre'];
				$dc_lnpatern 	= $ws->data['apPaterno'];
				$dc_lnmatern	= $ws->data['apMaterno'];
				$dc_lnmarried	= $ws->data['apCasada'];
				$dc_doc_id		= $ws->data['nroDocumento'];
				$dc_ext			= $ws->data['sigla'];
				$dc_country		= $ws->data['nacionalidad'];
				$dc_birth		= $ws->data['fecNacimiento'];
				$dc_status		= $ws->data['estCivil'];
				$dc_desc_occ	= $ws->data['profesion'] 
					. ' ' . $ws->data['actividad'];
				$dc_address_home = $ws->data['ciudad'] 
					. ' ' . $ws->data['zona']
					. ' ' . $ws->data['calle']
					. ' ' . $ws->data['numero']
					. ' ' . $ws->data['nomEdificio']
					. ' ' . $ws->data['nroDepto'];
				$dc_phone_1 = $ws->data['telefono'];
				$dc_phone_2 = $ws->data['celular'];
				$dc_email = $ws->data['correo'];
				$dc_address_work = $ws->data['ciudadTrab']
					. ' ' . $ws->data['zonaTrabajo']
					. ' ' . $ws->data['empCalle']
					. ' ' . $ws->data['empNumero']
					. ' ' . $ws->data['empNomEdif']
					. ' ' . $ws->data['empNroPiso']
					. ' ' . $ws->data['empTrabajo'];
				$dc_phone_office = $ws->data['telefonOfic'];
				$dc_position = $ws->data['Cargo'];
			} elseif ($type_client === 1) {
				$dc_company_name	= $ws->data['primerNombre'];
				$dc_nit				= $ws->data['nroDocumento'];
				$dc_activity		= $ws->data['actividad'];
				$dc_address_work	= $ws->data['ciudad']
					. ' ' . $ws->data['zona']
					. ' ' . $ws->data['calle']
					. ' ' . $ws->data['numero']
					. ' ' . $ws->data['nomEdificio']
					. ' ' . $ws->data['nroDepto'];
				$dc_phone_office 	= $ws->data['telefono'];
				$dc_company_email	= $ws->data['correo'];
			}
		} else {
			$err_search = $ws->err_mess;
		}
	} else {
		$sqlSc = 'select 
			scl.id_cliente,
			scl.tipo as cl_tipo,
			scl.codigo_bb as cl_code,
			scl.razon_social as cl_razon_social,
			scl.nombre as cl_nombre,
			scl.paterno as cl_paterno,
			scl.materno as cl_materno,
			scl.ap_casada as cl_ap_casada,
			scl.ci as cl_dni,
			scl.complemento as cl_complemento,
			scl.extension as cl_extension,
			scl.fecha_nacimiento as cl_fecha_nacimiento,
			scl.pais as cl_pais,
			scl.estado_civil as cl_estado_civil,
			scl.direccion_domicilio as cl_direccion_domicilio,
			scl.direccion_laboral as cl_direccion_laboral,
			scl.desc_ocupacion as cl_desc_ocupacion,
			scl.ingreso_mensual as cl_ingreso_mensual,
			scl.actividad as cl_actividad,
			scl.ejecutivo as cl_ejecutivo,
			scl.cargo as cl_cargo,
			scl.telefono_domicilio as cl_tel_domicilio,
			scl.telefono_celular as cl_tel_celular,
			scl.telefono_oficina as cl_tel_oficina,
			scl.email as cl_email,
			scl.genero as cl_genero,
			scl.data_jur
		from
			s_au_cot_cliente as scl
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = scl.id_ef)
		where
			scl.ci = "' . $dni . '"
				and scl.tipo = ' . $type_client . '
				and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
				and sef.activado = true
		limit 0 , 1
		;';
		//echo $sqlSc;

		if(($rsSc = $link->query($sqlSc,MYSQLI_STORE_RESULT))){
			if($rsSc->num_rows === 1){
				$rowSc = $rsSc->fetch_array(MYSQLI_ASSOC);
				$rsSc->free();
				
				$dc_code = $rowSc['cl_code'];
				$dc_company_name = $rowSc['cl_razon_social'];
				$dc_name = $rowSc['cl_nombre'];
				$dc_lnpatern = $rowSc['cl_paterno'];
				$dc_lnmatern = $rowSc['cl_materno'];
				$dc_lnmarried = $rowSc['cl_ap_casada'];
				$dc_nit = $dc_doc_id = $rowSc['cl_dni'];
				$dc_comp = $rowSc['cl_complemento'];
				$dc_depto = $dc_ext = $rowSc['cl_extension'];
				$dc_birth = $rowSc['cl_fecha_nacimiento'];
				$dc_country = $rowSc['cl_pais'];
				$dc_status = $rowSc['cl_estado_civil'];
				$dc_address_home = $rowSc['cl_direccion_domicilio'];
				$dc_address_work = $rowSc['cl_direccion_laboral'];
				$dc_desc_occ = $rowSc['cl_desc_ocupacion'];
				$dc_monthly_income = (int)$rowSc['cl_ingreso_mensual'];
				$dc_activity = $rowSc['cl_actividad'];
				$dc_executive = $rowSc['cl_ejecutivo'];
				$dc_position = $rowSc['cl_cargo'];
				$dc_phone_1 = $rowSc['cl_tel_domicilio'];
				$dc_phone_2 = $rowSc['cl_tel_celular'];
				$dc_company_email = $dc_email = $rowSc['cl_email'];
				$dc_phone_office = $rowSc['cl_tel_oficina'];
				
				$dc_type = (int)$rowSc['cl_tipo'];
				if($dc_type === 1) {
					$dc_doc_id = $dc_ext = $dc_email = '';
					$data = json_decode($rowSc['data_jur'], true);
					if (count($data) === 9) {
						$dc_type_company 		= $data['type_company'];
						$dc_registration_number = $data['registration_number'];
						$dc_license_number 		= $data['license_number'];
						$dc_number_vifpe 		= $data['number_vifpe'];
						$dc_antiquity 			= $data['antiquity'];
						$dc_ex_ci				= $data['executive_ci'];
						$dc_ex_ext				= $data['executive_ext'];
						$dc_ex_birth			= $data['executive_birth'];
						$dc_ex_profession		= $data['executive_profession'];
					}
				} elseif ($dc_type === 0) {
					$dc_nit = $dc_depto = $dc_company_email = '';
				}
			}else{
				$err_search = 'El Cliente no Existe !';
			}
		}else{
			$err_search = 'El Cliente no Existe';
		}
	}
}

$rsDep = null;
if(($rsDep = $link->get_depto()) === FALSE) {
	$rsDep = null;
}
?>

<h3>Datos del Cliente</h3>
<div style="text-align:center;">
	<form class="form-quote" style="text-align: left; font-size: 70%;">
		<div style="margin: 7px 5px; font-size: 90%; font-weight: bold;">
			Los campos marcados con <span style="color: #FF4A4A;">(*)</span> son obligatorios.
		</div>
	</form>
	
	<form id="fau-sc" name="fau-sc" action="" method="post" class="form-quote" style=" <?=$display_fsc;?> font-size: 70%;">
		<label style="width: auto;">Documento de Identidad: <span>*</span></label>
        <div class="content-input" style="width:auto;">
            <input type="text" id="dsc-dni" name="dsc-dni" autocomplete="off" 
            	value="" style="width:100px;" class="required text fbin">
        </div>

        <label style="width: auto;">Extensión: <span>*</span></label>
        <div class="content-input" style="width:auto;">
        	<select id="dsc-ext" name="dsc-ext" style="width: 100px;" class="required text fbin">
        		<option value="">Seleccione...</option>
				<?php if ($rsDep->data_seek(0)): ?>
					<?php while ($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)): $px_code = ''; ?>
						<?php if ((boolean)$rowDep['tipo_ci'] && (boolean)$rowDep['tipo_dp']): $px_code = 'C'; ?>
						<?php endif ?>
						<option value="<?= $px_code . $rowDep['codigo'] ;?>"><?= $rowDep['departamento'] ;?></option>
					<?php endwhile ?>
				<option value="NIT">Persona Jurídica</option>
        		<?php endif ?>        		
        	</select>
        </div>
        <input type="hidden" id="dsc-type-client" name="dsc-type-client" value="<?=$_TYPE_CLIENT;?>">
        <input type="submit" id="dsc-sc" name="dsc-sc" value="Buscar Cliente" class="btn-search-cs">
		<div class="mess-err-sc"><?=$err_search;?></div>
    </form>
</div>

<form id="fau-customer" name="fau-customer" action="" method="post" class="form-quote form-customer">
    <div style="text-align:center;">
    	<label style="text-align:right;">Tipo de Cliente: <span>*</span></label>
            <div class="content-input">
            <select id="dc-type-client" name="dc-type-client" class="required fbin">
                <option value="">Seleccione...</option>
<?php
$arr_type_client = $link->typeClient;
for($i = 0; $i < count($arr_type_client); $i++){
	$tc = explode('|', $arr_type_client[$i]);
	if($_TYPE_CLIENT === $tc[0]) {
		echo '<option value="'.$tc[0].'" selected>'.$tc[1].'</option>';
	} else {
		echo '<option value="'.$tc[0].'">'.$tc[1].'</option>';
	}
}
?>
            </select>
        </div><br>
    </div><br>

    <input type="hidden" id="dc-code" name="dc-code" value="<?= base64_encode($dc_code) ;?>">
    
    <div id="form-person" style=" <?=$display_nat;?> ">
    	<div class="form-col">
            <label>Nombres: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-name" name="dc-name" 
                	autocomplete="off" value="<?=$dc_name;?>" 
                	class="<?=$require_nat;?> text fbin field-person">
            </div><br>
            
            <label>Apellido Paterno: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-ln-patern" name="dc-ln-patern" 
                autocomplete="off" value="<?=$dc_lnpatern;?>" 
                class="<?=$require_nat;?> text fbin field-person">
            </div><br>
            
            <label>Apellido Materno: </label>
            <div class="content-input">
                <input type="text" id="dc-ln-matern" name="dc-ln-matern" 
                	autocomplete="off" value="<?=$dc_lnmatern;?>" 
                	class="not-required text fbin">
            </div><br>
            
            <label>Documento de Identidad: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-doc-id" name="dc-doc-id" 
                	autocomplete="off" value="<?=$dc_doc_id;?>" 
                	class="<?=$require_nat;?> dni fbin field-person" readonly>
            </div><br>
            
            <label>Complemento: </label>
            <div class="content-input">
                <input type="text" id="dc-comp" name="dc-comp" 
                	autocomplete="off" value="<?=$dc_comp;?>" 
                	class="not-required dni fbin" style="width:60px;">
            </div><br>
            
            <label>Extensión: <span>*</span></label>
            <div class="content-input">
                <select id="dc-ext" name="dc-ext" class="<?=$require_nat;?> fbin field-person">
                    <option value="">Seleccione...</option>
<?php
if ($rsDep->data_seek(0) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_ci'] === TRUE){
			if($rowDep['id_depto'] === $dc_ext) {
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
                	autocomplete="off" value="<?=$dc_birth;?>" 
                	class="<?=$require_nat;?> fbin date field-person" 
                	readonly style="cursor:pointer;">
            </div><br>

            <label>País: <span>*</span></label>
			<div class="content-input">
				<input type="text" id="dc-country" name="dc-country" 
					autocomplete="off" value="<?=$dc_country;?>" 
					class="<?=$require_nat;?> text fbin field-person">
			</div><br>

			<label>Estado Civil: <span>*</span></label>
			<div class="content-input">
				<select id="dc-status" name="dc-status" 
					class="<?=$require_nat;?> fbin field-person">
	            	<option value="">Seleccione...</option>
	            	<?php foreach ($link->status as $key => $value): $selected = ''; ?>
	            		<?php if ($value[0] === $dc_status): $selected = 'selected'; ?>
	            		<?php endif ?>
	            	<option value="<?= $value[0] ;?>" <?= $selected ;?>><?= $value[1] ;?></option>
	            	<?php endforeach ?>
				</select>
			</div><br>

			<label>Dirección domicilio: <span>*</span></label><br>
			<textarea id="dc-address-home" name="dc-address-home" 
				class="fbin <?= $require_nat ;?> field-person"><?= $dc_address_home ;?></textarea><br>

        </div><!--
        --><div class="form-col">
        	<label>Dirección Laboral: <span></span></label><br>
			<textarea id="dc-address-work" name="dc-address-work" 
				class="not-required fbin"><?= $dc_address_work ;?></textarea><br>

			<label>Ocupación: <span>*</span></label><br>
			<textarea id="dc-desc-occ" name="dc-desc-occ" 
				class="<?= $require_nat ;?> fbin field-person"><?= $dc_desc_occ ;?></textarea><br>

			<label>Cargo: <span>*</span></label><br>
			<div class="content-input" style="width: 350px;">
				<input type="text" id="dc-position" name="dc-position" 
					autocomplete="off" value="<?=$dc_position;?>" 
					class="<?= $require_nat ;?> field-person fbin" style="width: 350px;">
			</div><br>

			<label>Ingreso Mensual: <span>*</span></label>
			<div class="content-input">
				<select id="dc-monthly-income" name="dc-monthly-income" 
					class="<?=$require_nat;?> fbin field-person">
	            	<option value="">Seleccione...</option>
	            	<?php foreach ($link->monthly_income['N'] as $key => $value): $selected = ''; ?>
	            		<?php if ($key === $dc_monthly_income): $selected = 'selected'; ?>
	            		<?php endif ?>
	            	<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
	            	<?php endforeach ?>
				</select>
			</div><br>

            <label>Teléfono de domicilio: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-phone-1" name="dc-phone-1" 
                	autocomplete="off" value="<?=$dc_phone_1;?>" 
                	class="<?=$require_nat;?> phone fbin field-person">
            </div><br>

            <label>Teléfono de oficina: <span></span></label>
            <div class="content-input">
                <input type="text" id="dc-phone-office" name="dc-phone-office" 
                	autocomplete="off" value="<?=$dc_phone_office;?>" 
                	class="not-required phone fbin">
            </div><br>
            
            <label>Teléfono celular: </label>
            <div class="content-input">
                <input type="text" id="dc-phone-2" name="dc-phone-2" 
                	autocomplete="off" value="<?=$dc_phone_2;?>" 
                	class="not-required phone fbin">
            </div><br>
            
            <label>Email: </label>
            <div class="content-input">
                <input type="text" id="dc-email" name="dc-email" 
                	autocomplete="off" value="<?=$dc_email;?>" 
                	class="not-required email fbin">
            </div><br>
        </div><br>
    </div>
    
    <div id="form-company" style=" <?=$display_jur;?> ">
    	<div class="form-col">
            <label style="width:auto;">Nombre o Razón Social: <span>*</span></label><br>
            <div class="content-input">
                <textarea id="dc-company-name" name="dc-company-name" 
                	class="<?=$require_jur;?> fbin field-company"><?=$dc_company_name;?></textarea><br>
            </div><br>
            
            <label>NIT: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-nit" name="dc-nit" autocomplete="off" readonly
                	value="<?=$dc_nit;?>" class="<?=$require_jur;?> dni fbin field-company">
            </div><br>
            
            <label>Departamento: <span>*</span></label>
            <div class="content-input">
                <select id="dc-depto" name="dc-depto" class="<?=$require_jur;?> fbin field-company">
                    <option value="">Seleccione...</option>
<?php
if ($rsDep->data_seek(0) === TRUE) {
	while($rowDep = $rsDep->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowDep['tipo_ci'] === TRUE){
			if($rowDep['id_depto'] === $dc_depto) {
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
					autocomplete="off" value="<?=$dc_type_company;?>" 
					class="<?= $require_jur ;?> field-company text-2 fbin">
			</div><br>

            <label style="width: auto;">No. de Registro en Fundempresa: <span>*</span></label><br>
            <div class="content-input">
				<input type="text" id="dc-registration-number" name="dc-registration-number" 
					autocomplete="off" value="<?=$dc_registration_number;?>" 
					class="<?= $require_jur ;?> field-company text-2 fbin">
			</div><br>

			<label style="width: auto;">No. de Licencia de Funcionamiento GAM: <span>*</span></label><br>
            <div class="content-input">
				<input type="text" id="dc-license-number" name="dc-license-number" 
					autocomplete="off" value="<?=$dc_license_number;?>" 
					class="<?= $require_jur ;?> field-company text-2 fbin">
			</div><br>

			<label style="width: auto;">
				No. de Registro del VIFPE (Solo para Org. sin fines de lucro): <span></span>
			</label><br>
            <div class="content-input">
				<input type="text" id="dc-number-vifpe" name="dc-number-vifpe" 
					autocomplete="off" value="<?=$dc_number_vifpe;?>" 
					class="not-required text-2 fbin">
			</div><br>

			<label style="width: auto;">Actividad y/o Giro del Negocio: <span>*</span></label><br>
			<div class="content-input">
				<textarea id="dc-activity" name="dc-activity" 
					class="<?= $require_jur ;?> fbin field-company"><?= $dc_activity ;?></textarea><br>
			</div><br>

			<label style="width: auto;">Antigüedad de la Persona Juridica: <span>*</span></label><br>
            <div class="content-input">
				<input type="text" id="dc-antiquity" name="dc-antiquity" 
					autocomplete="off" value="<?=$dc_antiquity;?>" 
					class="<?= $require_jur ;?> field-company text-2 fbin">
			</div><br>
        </div><!--
        --><div class="form-col">
        	<label>Dirección domicilio: <span></span></label><br>
            <div class="content-input">
                <textarea id="dc-address-home2" name="dc-address-home2" 
					class="not-required fbin"><?= $dc_address_home ;?></textarea>
            </div><br>
			
			<label>Dirección Laboral: <span>*</span></label><br>
			<div class="content-input">
				<textarea id="dc-address-work2" name="dc-address-work2" 
					class="<?= $require_jur ;?> fbin field-company"><?= $dc_address_work ;?></textarea><br>
			</div><br>

			<label>Representante Legal: <span>*</span></label><br>
			<div class="content-input" style="width: 350px;">
				<input type="text" id="dc-executive" name="dc-executive" 
					autocomplete="off" value="<?=$dc_executive;?>" 
					class="<?= $require_jur ;?> field-company text fbin" style="width: 350px;">
			</div><br>

			<label>No. de Documento de Identidad: <span>*</span></label>
            <div class="content-input" style="width: auto;">
                <input type="text" id="dc-ex-ci" name="dc-ex-ci" autocomplete="off" 
                	value="<?=$dc_ex_ci;?>" class="<?=$require_jur;?> dni fbin field-company"
                	style="width: 110px;">
            </div>
            <div class="content-input" style="width: auto;">
                <input type="text" id="dc-ex-ext" name="dc-ex-ext" autocomplete="off" 
                	value="<?=$dc_ex_ext;?>" class="not-required dni fbin field-company"
                	style="width: 45px;">
            </div><br>

            <label>Fecha de Nacimiento: <span>*</span></label>
            <div class="content-input">
                <input type="text" id="dc-ex-birth" name="dc-ex-birth" 
                	autocomplete="off" value="<?=$dc_ex_birth;?>" 
                	class="<?=$require_jur;?> fbin date field-company" 
                	readonly style="cursor:pointer;">
            </div><br>

            <label>Profesión: <span>*</span></label><br>
			<div class="content-input" style="width: 350px;">
				<input type="text" id="dc-ex-profession" name="dc-ex-profession" 
					autocomplete="off" value="<?=$dc_ex_profession;?>" 
					class="<?= $require_jur ;?> field-company text fbin" style="width: 350px;">
			</div><br>

			<label>Cargo: <span>*</span></label><br>
			<div class="content-input" style="width: 350px;">
				<input type="text" id="dc-position2" name="dc-position2" 
					autocomplete="off" value="<?=$dc_position;?>" 
					class="<?= $require_jur ;?> field-company fbin" style="width: 350px;">
			</div><br>

			<label>Ingreso Mensual: <span>*</span></label>
			<div class="content-input">
				<select id="dc-monthly-income2" name="dc-monthly-income2" 
					class="<?=$require_jur;?> fbin field-company">
	            	<option value="">Seleccione...</option>
	            	<?php foreach ($link->monthly_income['J'] as $key => $value): $selected = ''; ?>
	            		<?php if ($key === $dc_monthly_income): $selected = 'selected'; ?>
	            		<?php endif ?>
	            	<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
	            	<?php endforeach ?>
				</select>
			</div><br>

        	<label>Teléfono: </label>
			<div class="content-input">
				<input type="text" id="dc-phone-office2" name="dc-phone-office2" 
					autocomplete="off" value="<?=$dc_phone_office;?>" 
					class="not-required phone fbin">
			</div><br>
            
            <label>Email: </label>
            <div class="content-input">
                <input type="text" id="dc-company-email" name="dc-company-email" 
                	autocomplete="off" value="<?=$dc_company_email;?>" 
                	class="not-required email fbin">
            </div><br>
        </div><br>
    </div>
    
    <h2>Datos del Seguro Solicitado</h2>
    <label>Tipo de Emisión: <span>*</span></label>
	<div class="content-input" style="width:auto;">
		<label class="check" style="width:auto;">
        	<input type="radio" id="di-warranty-s" name="di-warranty" 
        		value="1">&nbsp;&nbsp;Subrogada</label>
		<label class="check" style="width:auto;">
        	<input type="radio" id="di-warranty-n" name="di-warranty" 
        		value="0" checked>&nbsp;&nbsp;Voluntaria</label><br>
	</div><br>
    
    <label>Modalidad de Pago: <span>*</span></label>
	<div class="content-input" style="width:150px;">
		<select id="di-method-payment" name="di-method-payment" class="required fbin" style="width:133px;">
			<option value="">Seleccione...</option>
		<?php foreach ($link->methodPayment as $key => $value): ?>
			<option value="<?= $key ;?>"><?= $value ;?></option>
		<?php endforeach ?>
		</select>
	</div><br>
	
    <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
	<input type="hidden" id="pr" name="pr" value="<?=base64_encode('AU|03');?>">
	<input type="hidden" id="dc-idc" name="dc-idc" value="<?=$_GET['idc'];?>" >
	<input type="hidden" id="dc-token" name="dc-token" value="<?=base64_encode('dc-OK');?>" >
    <input type="hidden" id="id-ef" name="id-ef" value="<?=$_SESSION['idEF'];?>" >
	
	<input type="submit" id="dc-customer" name="dc-customer" value="<?=$title_btn;?>" class="btn-next" >

	<div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>
<hr>