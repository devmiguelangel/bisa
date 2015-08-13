<?php

require_once 'sibas-db.class.php';
$link = new SibasDB();

$max_item = $max_amount = 0;
if (($rowTR = $link->get_max_amount_optional($_SESSION['idEF'], 'TRD')) !== FALSE) {
	$max_item = (int)$rowTR['max_item'];
	$max_amount = (int)$rowTR['max_monto'];
}

$swPr = false;

$dp_type = $dp_use = $dp_use_other = $dp_state = $dp_depto = $dp_zone = 
$dp_locality = $dp_address = $dp_modality = $dp_value_insured = $dp_value_content = '';

$title_btn = 'Agregar Inmueble';

$cp = false;

if(isset($_GET['idPr'])){
	$swPr = true;
	$title_btn = 'Actualizar datos';
	
	$sqlUp = 'select 
	    strd.id_inmueble as idPr,
	    strd.departamento as pr_departamento,
	    strd.zona as pr_zona,
	    strd.localidad as pr_localidad,
	    strd.direccion as pr_direccion,
	    strd.tipo_in as pr_tipo,
	    strd.uso as pr_uso,
	    strd.uso_otro as pr_uso_otro,
	    strd.estado as pr_estado,
	    strd.modalidad as pr_modalidad,
	    strd.valor_asegurado as pr_valor_asegurado,
	    strd.valor_contenido as pr_valor_contenido
	from
	    s_trd_cot_detalle as strd
	        inner join
	    s_trd_cot_cabecera as strc ON (strc.id_cotizacion = strd.id_cotizacion)
	        inner join
	    s_entidad_financiera as sef ON (sef.id_ef = strc.id_ef)
	where
	    strc.id_cotizacion = "'.base64_decode($_GET['idc']).'"
	        and strd.id_inmueble = "'.base64_decode($_GET['idPr']).'"
	        and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
	        and sef.activado = true
	;';
	
	$rsUp = $link->query($sqlUp);
	
	if($rsUp->num_rows === 1){
		$rowUp = $rsUp->fetch_array(MYSQLI_ASSOC);
		$rsUp->free();
		
		$dp_type = $rowUp['pr_tipo'];
		$dp_use = $rowUp['pr_uso'];
		$dp_use_other = $rowUp['pr_uso_otro'];
		$dp_state = $rowUp['pr_estado'];
		$dp_depto = $rowUp['pr_departamento'];
		$dp_zone = $rowUp['pr_zona'];
		$dp_locality = $rowUp['pr_localidad'];
		$dp_address = $rowUp['pr_direccion'];
		$dp_modality = $rowUp['pr_modalidad'];
		$dp_value_insured = (int)$rowUp['pr_valor_asegurado'];
		$dp_value_content = (int)$rowUp['pr_valor_contenido'];
	}
}
?>
<h3>Datos del Inmueble</h3>
<?php
$nPr = 0;
if($swPr === false && isset($_GET['idc'])){
	$sqlPr = 'select 
	    strd.id_inmueble as idPr,
	    strd.tipo_in as pr_tipo,
	    strd.uso as pr_uso,
	    strd.estado as pr_estado,
	    sdep.departamento as pr_departamento,
	    strd.zona as pr_zona,
	    strd.localidad as pr_localidad,
	    strd.direccion as pr_direccion,
	    strd.valor_asegurado as pr_valor_asegurado,
	    strd.valor_contenido as pr_valor_contenido,
	    strd.facultativo as pr_facultativo
	from
	    s_trd_cot_detalle as strd
	        inner join
	    s_trd_cot_cabecera as strc ON (strc.id_cotizacion = strd.id_cotizacion)
	        left join
	    s_departamento as sdep ON (sdep.id_depto = strd.departamento)
	        inner join
	    s_entidad_financiera as sef ON (sef.id_ef = strc.id_ef)
	where
	    strc.id_cotizacion = "' . base64_decode($_GET['idc']) . '"
	        and sef.id_ef = "' . base64_decode($_SESSION['idEF']) . '"
	        and sef.activado = true
	order by strd.id_inmueble asc
	;';
	//echo $sqlPr;
	$rsPr = $link->query($sqlPr,MYSQLI_STORE_RESULT);
	$nPr = $rsPr->num_rows;
	if($nPr < $max_item){
		
	}
}
?>

<form id="ftr-property" name="ftr-property" action="" method="post" class="form-quote form-customer">
<?php
if($swPr === FALSE){
	if($nPr > 0){
?>
		<table class="list-cl">
			<thead>
				<tr>
					<td style="width:3%;"></td>
					<td style="width:8%;">Tipo</td>
					<td style="width:10%;">Uso</td>
					<td style="width:8%;">Departamento</td>
					<td style="width:15%;">Zona</td>
					<td style="width:10%;">Ciudad/Localidad</td>
					<td style="width:15%;">Dirección</td>
                    <td style="width:9%;">Valor Asegurado USD.</td>
                    <td style="width:9%;">Valor Muebles y/o contenido USD.</td>
					<td style="width:6%;"></td>
                    <td style="width:6%;"></td>
				</tr>
			</thead>
			<tbody>
<?php
		$cont = 1;
		while($rowPr = $rsPr->fetch_array(MYSQLI_ASSOC)){
			$bgFac = '';
			if((boolean)$rowPr['pr_facultativo']) {
				$bgFac = 'background:#FFE6D9;';
			}
?>
				<tr style=" <?=$bgFac;?> ">
					<td style="font-weight:bold;"><?=$cont;?></td>
					<td><?= $link->typeProperty[$rowPr['pr_tipo']] ;?></td>
					<td><?= $link->useProperty[$rowPr['pr_uso']] ;?></td>
					<td><?=$rowPr['pr_departamento'];?></td>
					<td><?=$rowPr['pr_zona'];?></td>
					<td><?=$rowPr['pr_localidad'];?></td>
					<td><?=$rowPr['pr_direccion'];?></td>
                    <td>
                    	<span class="value">
                    		<?=number_format($rowPr['pr_valor_asegurado'], 2, '.', ',');?> USD.
                		</span>
            		</td>
            		<td>
                    	<span class="value">
                    		<?=number_format($rowPr['pr_valor_contenido'], 2, '.', ',');?> USD.
                		</span>
            		</td>
					<td>
						<a href="trd-quote.php?ms=<?=$_GET['ms'];?>&page=<?=
							$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=
							$_GET['idc'];?>&idPr=<?=base64_encode($rowPr['idPr']);?>" 
							title="Editar Información">
							<img src="img/edit-inf-icon.png" width="40" height="40" 
								alt="Editar Información" title="Editar Información"></a></td>
                    <td>
                    	<a href="trd-remove-property.php?ms=<?=$_GET['ms'];?>&page=<?=
                    		$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=
                    		$_GET['idc'];?>&idPr=<?=base64_encode($rowPr['idPr']);?>" 
                    		title="Eliminar Inmueble" class="fancybox fancybox.ajax">
                    		<img src="img/delete-icon.png" width="40" height="40" 
                    			alt="Eliminar Inmueble" title="Eliminar Vehículo"></a></td>
				</tr>
<?php
			$cont += 1;
		}

		$rsPr->free();
?>
			</tbody>
		</table>
		
		<input type="button" id="dp-next" name="dp-next" value="Continuar" class="btn-next" >
		<hr>
<?php
	}
}
if($nPr < $max_item || $swPr === true){
?>
	<div class="accordion">
		<h5>Datos complementarios de Garantía</h5>
       	<div style="text-align: center;">
       		<label style="width: auto;">Cod. de Cliente: </label>
       		<input type="text" style="width: 100px;" id="cod_cl" autocomplete="off">
       		<label style="width: auto;">No. de Folio: </label>
       		<input type="text" style="width: 100px;" id="no_cf" data-pr="T">
       		<button class="btn-issue add-inf" style="width: 150px; margin: 0 5px; background: #23abea;">Buscar</button>

       		<div>
       			<img src="img/loading-04.GIF" class="dw-loading">
				<div class="dw-mess" style="width: 50%; margin: 0 auto;"></div>
			</div>
       	</div>
	</div>
	<br>

    <div class="form-col">
        <label>Departamento: <span>*</span></label>
        <div class="content-input">
            <select id="dp-depto" name="dp-depto" class="required fbin">
                <option value="">Seleccione...</option>
                <?php
                if(($rsDp = $link->get_depto()) !== FALSE){
                    while($rowDp = $rsDp->fetch_array(MYSQLI_ASSOC)){
                        if ((boolean)$rowDp['tipo_dp'] === TRUE) {
                            if($rowDp['id_depto'] === $dp_depto) {
                                echo '<option value="'.base64_encode($rowDp['id_depto']).'" selected>'.$rowDp['departamento'].'</option>';
                            } else {
                                echo '<option value="'.base64_encode($rowDp['id_depto']).'">'.$rowDp['departamento'].'</option>';
                            }
                        }
                    }
                }
                ?>
            </select>
        </div><br>

        <label>Zona: <span>*</span></label><br />
        <textarea id="dp-zone" name="dp-zone" class="required fbin"><?=$dp_zone;?></textarea><br>

        <label>Localidad: <span>*</span></label>
        <div class="content-input">
            <input type="text" id="dp-locality" name="dp-locality" autocomplete="off" value="<?=$dp_locality;?>" class="required text-2 fbin">
        </div><br>
    </div><!--
--><div class="form-col">
		<label>Tipo: <span>*</span></label>
		<div class="content-input">
			<select id="dp-type" name="dp-type" class="required fbin">
            	<option value="">Seleccione...</option>
            	<?php foreach ($link->typeProperty as $key => $value): $selected = ''; ?>
            		<?php if ($dp_type === $key): $selected = 'selected'; ?>
            		<?php endif ?>
            		<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
            	<?php endforeach ?>
            </select>
		</div><br>

		<label>Dirección: <span>*</span></label>
        <textarea id="dp-address" name="dp-address" class="required fbin"><?=$dp_address;?></textarea><br>

        <label>Uso: <span>*</span></label>
        <div class="content-input">
            <select id="dp-use" name="dp-use" class="required fbin">
                <option value="">Seleccione...</option>
                <?php foreach ($link->useProperty as $key => $value): $selected = ''; ?>
            		<?php if ($dp_use === $key): $selected = 'selected'; ?>
            		<?php endif ?>
            		<option value="<?= $key ;?>" <?= $selected ;?>><?= $value ;?></option>
            	<?php endforeach ?>
            </select>
        </div><br>

        <label>Valor Asegurado (USD): <span>*</span></label>
        <?php
        $display_value = 'display: none;';
        if(($dp_value_insured + $dp_value_content) > $max_amount) {
            $display_value = 'display: block;';
        }
        ?>
        <div class="content-input">
            <input type="text" id="dp-value-insured" name="dp-value-insured" 
            	autocomplete="off" value="<?=$dp_value_insured;?>" 
            	class="required number fbin value_insured">
        </div><br>

        <label>Valor Muebles y/o contenido (USD): <span>*</span></label>
		<div class="content-input">
            <input type="text" id="dp-value-content" name="dp-value-content" 
            	autocomplete="off" value="<?=$dp_value_content;?>" 
            	class="required number fbin value_insured">
        </div><br>

        <div id="mess-amount" class="au-mess"
             style=" <?=$display_value;?> ">Inmuebles cuyo valor excedan los 
             	<?=$max_amount;?> USD o el valor de los muebles mas contenido 
             	supera el 20% del valor del inmueble, requieren aprobación de 
             	la Compañia de Seguros</div>
    </div>
<?php
?>
	<br>
	
	<input type="hidden" id="max-amount" name="max-amount" value="<?=$max_amount;?>">
    <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
	<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
	<input type="hidden" id="pr" name="pr" value="<?=base64_encode('TRD|01');?>">
<?php
if (isset($_GET['idc'])) {
?>
	<input type="hidden" id="dp-idc" name="dp-idc" value="<?=$_GET['idc'];?>" >
<?php
}
?>
	<input type="hidden" id="dp-token" name="dp-token" value="<?=base64_encode('dp-OK');?>" >
    <input type="hidden" id="idef" name="idef" value="<?=$_SESSION['idEF'];?>" >
	
    <div style="text-align:center">
    	<input type="submit" id="dp-property" name="dp-property" value="<?=$title_btn;?>" class="btn-next btn-issue" >
<?php
	if($swPr === TRUE){
		echo '<input type="button" id="dp-cancel" name="dp-cancel" value="Cancelar" class="btn-next btn-issue" >
			<input type="hidden" id="dp-idPr" name="dp-idPr" value="'.$_GET['idPr'].'" >';
	}
?>
    </div>
<?php
}
?>	
	<div class="loading">
		<img src="img/loading-01.gif" width="35" height="35" />
	</div>
</form>

<script type="text/javascript">
$(document).ready(function(e) {
	$("#ftr-property").validateForm({
		action: 'TRD-property-record.php'
	});
	
	$("input[type='text'].fbin, textarea.fbin").keyup(function(e){
		var arr_key = new Array(37, 39, 8, 16, 32, 18, 17, 46, 36, 35, 186);
		var _val = $(this).prop('value');
		
		if($.inArray(e.keyCode, arr_key) < 0 && $(this).hasClass('email') === false){
			$(this).prop('value',_val.toUpperCase());
		}
	});
	
	$("#dp-use").change(function(e){
		var use = $(this).prop('value');
		if(use === 'OTH'){
			$("#dp-use-other").removeClass('not-required').addClass('required');
			$("#dp-use-other").prop("readonly", false).focus();
		}else{
			$("#dp-use-other").removeClass('required').addClass('not-required');
			$("#dp-use-other").prop("readonly", true);
			$("#dp-use-other").prop("value", '');
		}
	});

	$(".value_insured").keyup(function(e){
		var amount = parseInt($('#dp-value-insured').prop('value'));
		var amount_content = parseInt($('#dp-value-content').prop('value'));
		var max_amount = parseInt($("#max-amount").prop('value'));
		
		if (isNaN(amount)) {
			amount = 0;
			$('#dp-value-insured').prop('value', '');
		}

		if (isNaN(amount_content)) {
			amount_content = 0;
			$('#dp-value-content').prop('value', '');
		}

		if (amount_content > (amount * 20 / 100)) {
			$('#ftr-property :submit').prop('disabled', true);
		} else {
			$('#ftr-property :submit').prop('disabled', false);
		}

		if(((amount + amount_content) > max_amount)) {
			$("#mess-amount").fadeIn();
		} else {
			$("#mess-amount").fadeOut();
		}
	});
	
<?php
if (isset($_GET['idc'])) {
?>
	$("#dp-cancel").click(function(e){
		e.preventDefault();
		location.href = 'trd-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=$_GET['pr'];?>&idc=<?=$_GET['idc'];?>';
	});
	
	$("#dp-next").click(function(e){
		e.preventDefault();
		location.href = 'trd-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=base64_encode('TRD|02');?>&idc=<?=$_GET['idc']?>';
	});
<?php
}
?>
	var icons = {
      header: "ui-icon-circle-arrow-e",
      activeHeader: "ui-icon-circle-arrow-s"
    };
	
	$(".accordion" ).accordion({
		collapsible: true,
		icons: icons,
		heightStyle: "content",
		active: 6
	});

	data_warranty();
});
</script>