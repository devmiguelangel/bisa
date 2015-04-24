<?php
require_once('sibas-db.class.php');
$link = new SibasDB();

$tokenSi = FALSE;
$idRc = '';
$flag = FALSE;
$roCl = 'required';
if(isset($_GET['rc'])){
	$tokenSi = TRUE;
	$idRc = $link->real_escape_string(trim(base64_decode($_GET['rc'])));
}

if(isset($_GET['flag']))
	if($_GET['flag'] === md5('rc-edit'))
		$flag = TRUE;

$readonly = '';
$display = '';
$btnText = 'Guardar Siniestro';
$title = 'Reportar Siniestro';
$k = 0;
$kk = '';
$sqlSi = '';
$arrSi = array('s-date-reg' => date('d/m/Y'), 
			's-id-client' => '', 
			's-dni' => '', 
			's-name' => '', 
			's-date-sinister' => '', 
			's-circumstance' => '', 
			's-id-user' => '', 
			's-user' => '', 
			's-email' => '', 
			's-subsidiary' => '', 
			's-agency' => '');

if($flag === TRUE)
	$btnText = 'Actualizar Siniestro';
			
if($tokenSi === TRUE){
	if($flag === FALSE)
		$readonly = 'readonly';
	
	$display = 'display: none;';
	
	$sqlSi = 'select 
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
			sdp.id_depto as s_sucursal,
		    sag.id_agencia as s_agencia
		from
			s_siniestro as ssi
				inner join
			s_usuario as su ON (su.id_usuario = ssi.denunciado_por)
				inner join
			s_departamento as sdp ON (sdp.id_depto = ssi.sucursal)
				left join
			s_agencia as sag ON (sag.id_agencia = ssi.agencia)
				inner join
			s_entidad_financiera as sef ON (sef.id_ef = ssi.id_ef)
		where
			ssi.id_siniestro = "'.$idRc.'"
				and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
				and sef.activado = true
		limit 0 , 1
		;';
	
	if(($rsSi = $link->query($sqlSi, MYSQLI_STORE_RESULT))){
		$rowSi = $rsSi->fetch_array(MYSQLI_ASSOC);
		$rsSi->free();
		
		$arrSi['s-date-reg'] = $rowSi['s_fecha_registro'];
		$arrSi['s-id-client'] = $rowSi['idCl'];
		if(empty($arrSi['s-id-client']) === TRUE) $roCl = '';
		$arrSi['s-dni'] = $rowSi['s_ci'];
		$arrSi['s-name'] = $rowSi['s_nombre'];
		$arrSi['s-date-sinister'] = $rowSi['s_fecha_siniestro'];
		$arrSi['s-circumstance'] = $rowSi['s_circunstancia'];
		$arrSi['s-id-user'] = $rowSi['s_usuario'];
		$arrSi['s-user'] = $rowSi['s_usuario_nombre'];
		$arrSi['s-email'] = $rowSi['s_usuario_email'];
		$arrSi['s-subsidiary'] = $rowSi['s_sucursal'];
		$arrSi['s-agency'] = $rowSi['s_agencia'];
		
		$title = 'Detalle de Siniestro N° '.$rowSi['s_no_siniestro'];
	}
}else{
	$sqlUs = 'SELECT su.id_usuario, su.nombre, su.email, su.id_depto, su.id_agencia 
			FROM s_usuario as su 
			WHERE su.id_usuario = "'.base64_decode($_SESSION['idUser']).'"
			LIMIT 0, 1 ;';
	if(($rsUs = $link->query($sqlUs,MYSQLI_STORE_RESULT))){
		$rowUs = $rsUs->fetch_array(MYSQLI_ASSOC);
		$rsUs->free();
		
		$arrSi['s-id-user'] = $rowUs['id_usuario'];
		$arrSi['s-user'] = $rowUs['nombre'];
		$arrSi['s-email'] = $rowUs['email'];
		$arrSi['s-subsidiary'] = $rowUs['id_depto'];
		$arrSi['s-agency'] = $rowUs['id_agencia'];
	}
}
?>
<script type="text/javascript">
$(document).ready(function(e) {
	$('input#rc-cl-exists').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
		increaseArea: '20%' // optional
	});
	
	$('input#rc-cl-exists').on('ifToggled', function(event){
		//alert(event.type + ' callback');
		$("#rc-npolicy").prop('value', '');
		$(".list-cl tbody").html('');
		if($("#rc-id-client + .msg-form").length)
			$("#rc-id-client + .msg-form").remove();
		if($("#rc-dni + .msg-form").length)
			$("#rc-dni + .msg-form").remove();
		if($("#rc-name + .msg-form").length)
			$("#rc-name + .msg-form").remove();
		$("#rc-dni").removeClass('error-text');
		$("#rc-name").removeClass('error-text');
		
		if($(this).is(':checked') === true){
			$("#rc-search").prop('readonly', true);
			$("#rc-dni").prop('readonly', false);		$("#rc-dni").addClass('required');
			$("#rc-name").prop('readonly', false);		$("#rc-name").addClass('required');
			$(".btn-add-del").fadeIn();
			$("#rc-id-client").removeClass('required');
			$("#rc-dni").focus();
			$("#td-mark").hide();
		}else{
			$("#rc-search").prop('readonly', false);
			$("#rc-dni").prop('readonly', true);		$("#rc-dni").removeClass('required');
			$("#rc-name").prop('readonly', true);		$("#rc-name").removeClass('required');
			$(".btn-add-del").fadeOut();
			$("#rc-id-client").addClass('required');
			$("#rc-search").focus();
			$("#td-mark").show();
		}
	});

	$("#rc-search").focus();
<?php
if($flag === TRUE || $tokenSi === FALSE){
?>
	$(".date-sinister").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});
	
	$(".date-sinister").datepicker($.datepicker.regional[ "es" ]);
<?php
}
?>
    $("#frc-report").validateForm({
		action: 'RC-report-record.php'
	});
	
	$("#rc-search").autocomplete({
		url: 'get-customer.php'
	});
	
	/*$("#rc-cl-exists").click(function(e){
		$("#rc-npolicy").prop('value', '');
		$(".list-cl tbody").html('');
		if($("#rc-id-client + .msg-form").length)
			$("#rc-id-client + .msg-form").remove();
		if($("#rc-dni + .msg-form").length)
			$("#rc-dni + .msg-form").remove();
		if($("#rc-name + .msg-form").length)
			$("#rc-name + .msg-form").remove();
		$("#rc-dni").removeClass('error-text');
		$("#rc-name").removeClass('error-text');
		
		if($(this).is(':checked') === true){
			$("#rc-search").prop('readonly', true);
			$("#rc-dni").prop('readonly', false);		$("#rc-dni").addClass('required');
			$("#rc-name").prop('readonly', false);		$("#rc-name").addClass('required');
			$(".btn-add-del").fadeIn();
			$("#rc-id-client").removeClass('required');
			$("#rc-dni").focus();
			$("#td-mark").hide();
		}else{
			$("#rc-search").prop('readonly', false);
			$("#rc-dni").prop('readonly', true);		$("#rc-dni").removeClass('required');
			$("#rc-name").prop('readonly', true);		$("#rc-name").removeClass('required');
			$(".btn-add-del").fadeOut();
			$("#rc-id-client").addClass('required');
			$("#rc-search").focus();
			$("#td-mark").show();
		}
	});*/
	
	$(".add-del").click(function(e){
		e.preventDefault();
		var rel = $(this).attr('rel');
		var _np = $("#rc-npolicy").prop('value');
		switch(rel){
			case 'add':
				$.getJSON("get-policy-autocomplete.php", {idCl: 0, np: _np}, function(result){
					$("#rc-npolicy").prop("value", result[0]);
					$(".list-cl tbody:last").append(result[1]);
				});
				break;
			case 'del':
				if(_np.length > 0){
					_np = _np.substring(0, _np.length - 2);
					$(".list-cl tbody tr:last").remove();
					$("#rc-npolicy").prop('value', _np);
				}
				break;
		}
	});
	
	$("#rc-edit").click(function(e){
		e.preventDefault();
		location.href = 'rc-report.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&rc=<?=base64_encode($idRc);?>&flag=<?=md5('rc-edit');?>';
	});
	
	$("select.readonly option").not(":selected").attr("disabled", "disabled");
});
</script>
<script type="text/javascript">
	$(document).ready(function(e) {
		$(".sn-mark").click(function(e){
			var idcb = "#"+this.id;
			var nr = $(idcb).prop("value");
			var np = $("#rc-npolicy").prop("value");
			alert(idcb);
			if($(idcb).is(":checked") === true){
				alert("OK");
			}else{
				alert("Err");
			}
		});
	});
</script>
<h3><?=$title;?></h3>
<form id="frc-report" name="frc-report" class="form-quote form-customer">
	<label>Fecha de Registro: <span>*</span></label>
    <div class="content-input">
        <input type="text" id="rc-date-reg" name="rc-date-reg" autocomplete="off" value="<?=$arrSi['s-date-reg'];?>" class="required fbin" readonly >
    </div><br>
    
    <div id="ctr-search" style=" <?=$display;?> ">
    	<label style="width:auto;">Nombre o C.I. del Cliente: <span>*</span></label>
        <div class="content-input" style="width:auto;">
            <input type="hidden" id="rc-id-client" name="rc-id-client" value="<?=base64_encode($arrSi['s-id-client']);?>" class="<?=$roCl;?>">
            <input type="text" id="rc-search" name="rc-search" autocomplete="off" value="" class="not-required fbin" style="width:350px;" >
        </div><br>
        
        <label style="width:auto; cursor: pointer; vertical-align:middle; font-size:95%;"><input type="checkbox" id="rc-cl-exists" name="rc-cl-exists" value="1"> Cliente no existe en el Sistema</label><br>
    </div>
    
    <label>C.I. Cliente: <span>*</span></label>
    <div class="content-input">
        <input type="text" id="rc-dni" name="rc-dni" autocomplete="off" value="<?=$arrSi['s-dni'];?>" class="dni fbin" readonly >
    </div>
    
    <label style="text-align:right;">Nombre Cliente: <span>*</span></label>
    <div class="content-input">
        <input type="text" id="rc-name" name="rc-name" autocomplete="off" value="<?=$arrSi['s-name'];?>" class="text fbin" readonly >
    </div><br>
    
    <label>Fecha del Siniestro: <span>*</span></label>
    <div class="content-input">
        <input type="text" id="rc-date-sinister" name="rc-date-sinister" autocomplete="off" value="<?=$arrSi['s-date-sinister'];?>" class="required fbin date-sinister" <?=$readonly;?> >
    </div><br>
    
    <label>Circunstancias: <span>*</span></label>
    <textarea id="rc-circumstance" name="rc-circumstance" class="not-required fbin" <?=$readonly;?> ><?=$arrSi['s-circumstance'];?></textarea><br>
    
    <label>Denunciado por: <span>*</span></label>
    <div class="content-input">
    	<input type="hidden" id="rc-denounced-id" name="rc-denounced-id" value="<?=base64_encode($arrSi['s-id-user']);?>">
        <input type="text" id="rc-denounced-name" name="rc-denounced-name" autocomplete="off" value="<?=$arrSi['s-user'];?>" class="required text fbin" readonly >
    </div><br>
    
	<label>Sucursal: <span>*</span></label>
    <div class="content-input">
    	<select id="rc-subsidiary" name="rc-subsidiary" class="required fbin <?=$readonly;?>">
        	<option value="">Seleccione...</option>
<?php
$rsSb = $link->get_depto();
if($rsSb->data_seek(0) === TRUE){
	while($rowSb = $rsSb->fetch_array(MYSQLI_ASSOC)){
		if((boolean)$rowSb['tipo_dp'] === TRUE){
			if($arrSi['s-subsidiary'] === $rowSb['id_depto'])
				echo '<option value="'.$rowSb['id_depto'].'" selected>'.$rowSb['departamento'].'</option>';
			else
				echo '<option value="'.$rowSb['id_depto'].'">'.$rowSb['departamento'].'</option>';
		}
	}
}
?>
		</select>
	</div>
    
    <label style="text-align:right;">Agencia: <span>*</span></label>
    <div class="content-input">
    	<select id="rc-agency" name="rc-agency" class="fbin <?=$readonly;?>">
        	<option value="">Seleccione...</option>
<?php
$sqlAg = 'SELECT sa.id_agencia, sa.agencia 
		FROM s_agencia as sa
			INNER JOIN s_entidad_financiera as sef ON (sef.id_ef = sa.id_ef)
		WHERE sa.id_agencia != "" 
			AND sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
		ORDER BY sa.id_agencia ASC ;';
if(($rsAg = $link->query($sqlAg,MYSQLI_STORE_RESULT))){
	if($rsAg->num_rows > 0){
		while($rowAg = $rsAg->fetch_array(MYSQLI_ASSOC)){
			if($arrSi['s-agency'] === $rowAg['id_agencia'])
				echo '<option value="'.base64_encode($rowAg['id_agencia']).'" selected>'.$rowAg['agencia'].'</option>';
			else
				echo '<option value="'.base64_encode($rowAg['id_agencia']).'">'.$rowAg['agencia'].'</option>';
		}
	}
}
?>
		</select>
	</div>
    <br><br>
	
    <h4 class="h4">
	    <table style="width:100%;">
            <tr>
                <td style="width:30%;">Pólizas vigentes en el banco</td>
                <td style="width:70%;">
                	<div class="btn-add-del" style="text-align:right;">
                        <a href="#" class="add-del" title="Adicionar" style="background-image:url(img/add-icon.png);" rel="add">Adicionar</a>
                        <a href="#" class="add-del" title="Eliminar" style="background-image:url(img/remove-icon.png);" rel="del">Eliminar</a>
                    </div>
                </td>
            </tr>
        </table>
	</h4>
    <div id="content-policy">
    	<table class="list-cl">
			<thead>
				<tr>
<?php
if($tokenSi === FALSE)	echo '<td style="width:8%;" id="td-mark">Marcar</td>';
?>
					<td style="width:10%;">No. Certificado</td>
					<td style="width:10%;">No. Póliza</td>
					<td style="width:17%;">Ramo</td>
					<td style="width:10%;">No. Operación</td>
					<td style="width:15%;">Plazo del Crédito</td>
					<td style="width:10%;">Fecha desembolso</td>
					<td style="width:20%;">Monto desembolsado</td>
				</tr>
			</thead>
            <tbody>
<?php
if($tokenSi === TRUE){
	$sqlSd = 'select 
			ssi.id_siniestro as idRc,
			ssd.id_detalle as idRd,
			ssd.token as d_token,
			ssd.id_emision as ide,
			ssd.no_emision as d_no_emision,
			ssd.no_poliza as d_no_poliza,
			ssd.producto as d_producto,
			ssd.no_operacion as d_no_operacion,
			ssd.plazo as d_plazo,
			ssd.tipo_plazo as d_tipo_plazo,
			ssd.fecha_desembolso as d_fecha_desembolso,
			ssd.monto_desembolso as d_monto_desembolso,
			ssd.moneda as d_moneda
		from
			s_siniestro_detalle as ssd
				inner join
			s_siniestro as ssi ON (ssi.id_siniestro = ssd.id_siniestro)
		where
			ssi.id_siniestro = "'.$idRc.'"
		order by ssd.id_detalle asc
		;';
	
	if(($rsSd = $link->query($sqlSd,MYSQLI_STORE_RESULT))){
		if($rsSd->num_rows > 0){
			$arr_Product = array(
					0 => 'DE|Desgravamen', 
					1 => 'AU|Automotores', 
					2 => 'TRD|Todo Riesgo Domiciliario', 
					3 => 'TRM|Ramos Técnicos',
					4 => 'CCB|Desgravamen', 
					5 => 'CCD|Desgravamen', 
					6 => 'CDB|Desgravamen', 
					7 => 'CDD|Desgravamen', 
					8 => 'VG|Desgravamen');
			$arr_Term = array(0 => 'Y|Años', 1 => 'M|Meses', 2 => 'W|Semanas', 3 => 'D|Días');
			$arr_Currency = array(0 => 'BS|Bolivianos', 1 => 'USD|Dolares');
			while($rowSd = $rsSd->fetch_array(MYSQLI_ASSOC)){
				$k = $rowSd['d_token'];
				$kk .= $rowSd['d_token'].'|';
?>
<tr>
    <td>
<?php
				if($flag === TRUE)
					echo '<input type="hidden" id="rc-'.$k.'-idd" name="rc-'.$k.'-idd" value="'.base64_encode($rowSd['idRd']).'">'
?>
    	<input type="hidden" id="rc-<?=$k;?>-ide" name="rc-<?=$k;?>-ide" value="<?=base64_encode($rowSd['ide']);?>">
        <input type="text" id="rc-<?=$k;?>-ncertified" name="rc-<?=$k;?>-ncertified" autocomplete="off" value="<?=$rowSd['d_no_emision'];?>" class="required fbin" <?=$readonly;?>>
    </td>
    <td>
        <input type="text" id="rc-<?=$k;?>-npolicy" name="rc-<?=$k;?>-npolicy" autocomplete="off" value="<?=$rowSd['d_no_poliza'];?>" class="required fbin" <?=$readonly;?>>
    </td>
    <td>
        <select id="rc-<?=$k;?>-product" name="rc-<?=$k;?>-product" class="required fbin">
<?php
				if($readonly === '')
					echo '<option value="">Seleccione...</option>';
				for($i = 0; $i < count($arr_Product); $i++){
					$PR = explode('|', $arr_Product[$i]);
					if($rowSd['d_producto'] === $PR[0])
						echo '<option value="'.$PR[0].'" selected>'.$PR[1].'</option>';
					elseif($readonly === '')
						echo '<option value="'.$PR[0].'">'.$PR[1].'</option>';
				}
?>
        </select>
    </td>
    <td>
        <input type="text" id="rc-<?=$k;?>-noperation" name="rc-<?=$k;?>-noperation" autocomplete="off" value="<?=$rowSd['d_no_operacion'];?>" class="not-required fbin" <?=$readonly;?>>
    </td>
    <td>
        <input type="text" id="rc-<?=$k;?>-term" name="rc-<?=$k;?>-term" autocomplete="off" value="<?=$rowSd['d_plazo'];?>" class="required number fbin" <?=$readonly;?>>
        <select id="rc-<?=$k;?>-term-type" name="rc-<?=$k;?>-term-type" class="required fbin">
<?php
				if($readonly === '')
					echo '<option value="">Seleccione...</option>';
				for($i = 0; $i < count($arr_Term); $i++){
					$TERM = explode('|', $arr_Term[$i]);
					if($rowSd['d_tipo_plazo'] === $TERM[0])
						echo '<option value="'.$TERM[0].'" selected>'.$TERM[1].'</option>';
					elseif($readonly === '')
						echo '<option value="'.$TERM[0].'">'.$TERM[1].'</option>';
				}
?>
        </select>
    </td>
    <td>
        <input type="text" id="rc-<?=$k;?>-date" name="rc-<?=$k;?>-date" autocomplete="off" value="<?=$rowSd['d_fecha_desembolso'];?>" class="required fbin date-sinister" <?=$readonly;?>>
    </td>
    <td>
        <input type="text" id="rc-<?=$k;?>-amount" name="rc-<?=$k;?>-amount" autocomplete="off" value="<?=(int)$rowSd['d_monto_desembolso'];?>" class="required number fbin" <?=$readonly;?>>
        <select id="rc-<?=$k;?>-amount-type" name="rc-<?=$k;?>-amount-type" class="required fbin">
<?php
				if($readonly === '')
					echo '<option value="">Seleccione...</option>';
				for($i = 0; $i < count($arr_Currency); $i++){
					$CURR = explode('|', $arr_Currency[$i]);
					if($rowSd['d_moneda'] === $CURR[0])
						echo '<option value="'.$CURR[0].'" selected>'.$CURR[1].'</option>';
					elseif($readonly === '')
						echo '<option value="'.$CURR[0].'">'.$CURR[1].'</option>';
				}
?>
        </select>
    </td>
</tr>
<?php
			}
		}
	}
}
?>
            </tbody>
		</table>
        <div id="content-script"></div>
    </div>
    
    <div style="text-align:center;">
    	<!--<input type="hidden" id="rc-npolicy" name="rc-npolicy" value="<?=$kk;?>" >-->
        <input type="hidden" id="rc-npolicy" name="rc-npolicy" value="<?=$kk;?>" class="required" >
        <input type="hidden" id="idef" name="idef" value="<?=$_SESSION['idEF'];?>" >
        <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>" >
        <input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>" >
<?php
if($tokenSi === TRUE){
	echo '<input type="hidden" id="idRc" name="idRc" value="'.base64_encode($idRc).'" >';
	if($flag === FALSE)
		echo '<input type="button" id="rc-edit" name="rc-edit" value="Editar Siniestro" class="btn-next btn-issue" >
		<a href="rc-send-sinister.php?rc='.base64_encode($idRc).'" class="fancybox fancybox.ajax btn-issue">Enviar y Salir</a>';
}
if($tokenSi === FALSE || $flag === TRUE){
	echo '<input type="submit" id="rc-report" name="rc-report" value="'.$btnText.'" class="btn-next btn-issue" >';
}
?>
    </div>
    <div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>