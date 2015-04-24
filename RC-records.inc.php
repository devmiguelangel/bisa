<script type="text/javascript">
$(document).ready(function(e) {
	/*$(".date").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',
		yearRange: "c-100:c+100"
	});
	
	$(".date").datepicker($.datepicker.regional[ "es" ]);
	*/
    $(".f-records").submit(function(e){
		e.preventDefault();
		$(this).find(':submit').prop('disabled', true);
		
		var _data = $(this).serialize();
		$.ajax({
			url:'RC-result.inc.php',
			type:'GET',
			data:'frc=&'+_data,
			//dataType:"json",
			async:true,
			cache:false,
			beforeSend: function(){
				$(".result-search").hide();
				$(".result-loading").show();
			},
			complete: function(){
				$(".result-loading").hide();
				$(".result-search").show();
			},
			success: function(result){
				$(".result-search").html(result);
				$(".f-records :submit").prop('disabled', false);
			}
		});
		return false;
	});
});
</script>
<h3>Reporte de Siniestros</h3>
<div class="fac-records">
	<form class="f-records">
    	<label>N° de Siniestro: </label>
        <input type="text" id="frc-ns" name="frc-ns" value="" autocomplete="off">
		<input type="hidden" id="frc-user" name="frc-user" value="<?=$_SESSION['idUser'];?>" >
        <!--<label>Usuario: </label>
        <input type="text" id="frc-user" name="frc-user" value="" autocomplete="off">-->
        <br>
        <label style="width:auto;">Nombre del Cliente: </label>
        <input type="text" id="frc-name" name="frc-name" value="" autocomplete="off">
        
        <label style="width:auto;">C.I. del Cliente: </label>
        <input type="text" id="frc-dni" name="frc-dni" value="" autocomplete="off">
        
        <label style="width:auto;">Ramo: </label>
        <select id="frc-branch" name="frc-branch">
        	<option value="">Seleccione...</option>
<?php
$arrRm = array(
		0 => 'DE|Desgravamen', 
		1 => 'AU|Automotores', 
		2 => 'TRD|Todo Riesgo Domiciliario', 
		3 => 'TRM|Todo Riesgo Equipo Movil');
for($i = 0; $i < count($arrRm); $i++){
	$BRANCH = explode('|', $arrRm[$i]);
	echo '<option value="'.$BRANCH[0].'">'.$BRANCH[1].'</option>';
}
?>
        	
        </select>
        
        <!--
        <label style="width:auto;">Fecha: </label>
        <label style="width:auto;">desde: </label>
        <input type="text" id="frc-date-b" name="frc-date-b" value="" autocomplete="off" class="date" readonly>
        
        <label style="width:auto;">hasta: </label>
        <input type="text" id="frc-date-e" name="frc-date-e" value="" autocomplete="off" class="date" readonly>
        -->
        
        <input type="hidden" id="ms" name="ms" value="<?=$_GET['ms'];?>">
		<input type="hidden" id="page" name="page" value="<?=$_GET['page'];?>">
        <input type="hidden" id="idef" name="idef" value="<?=$_SESSION['idEF'];?>">
        <br>

        <div align="center">
        	<input type="submit" id="frc-search" name="frc-search" value="Buscar" class="fde-btn">
	        <input type="reset" id="frc-reset" name="frc-reset" value="Restablecer Campos" class="fde-btn">
        </div>
    </form>
    
    <div class="result-container">
    	<div class="result-loading"></div>
        <div class="result-search"></div>
    </div>
</div>