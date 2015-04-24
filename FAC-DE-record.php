<?php
require('fac-de-email.class.php');

$arrDE = array(0 => 0, 1 => 'R', 2 => '');
if(isset($_GET['fp-ide']) && isset($_GET['fp-approved']) && isset($_GET['fp-rate']) && isset($_GET['fp-final-rate']) && isset($_GET['fp-state']) && isset($_GET['fp-observation']) && isset($_GET['ms']) && isset($_GET['page'])){
	$smail = new FACEmailDE();
	$link = $smail->cx;
	$swF = FALSE;
	$ide = $link->real_escape_string(trim(base64_decode($_GET['fp-ide'])));
	$user = $link->real_escape_string(trim(base64_decode($_GET['fp-user'])));
	$pr_approved = $link->real_escape_string(trim($_GET['fp-approved']));
	$pr_rate = $link->real_escape_string(trim($_GET['fp-rate']));
	$pr_percentage = $link->real_escape_string(trim($_GET['fp-percentage']));
	$pr_current_rate = $link->real_escape_string(trim($_GET['fp-current-rate']));
	$pr_final_rate = $link->real_escape_string(trim($_GET['fp-final-rate']));
	$pr_state = $link->real_escape_string(trim($_GET['fp-state']));
	$state = explode('|', $pr_state);
	$pr_observation = $link->real_escape_string(trim($_GET['fp-observation']));
	$pr_rate_curr = $link->real_escape_string(trim($_GET['fp-rate-curr']));
	$pr_email = $link->real_escape_string(trim($_GET['fp-email']));
	
	$_PR = 0;
	$_CR = 0;
	$_FR = 0;
	$_OB = '';
	$_TEXT = $pr_observation;
	$sqlF = '';	$sqlP = '';
	
	$patrones = array('@<script[^>]*?>.*?</script>@si',  	// Strip out javascript
			'@<colgroup[^>]*?>.*?</colgroup>@si',			// Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',				// Strip style tags properly
			'@<style[^>]*>.*</style>@siU',					// Strip style
			'@<![\s\S]*?--[ \t\n\r]*>@siU',					// Strip multi-line comments including CDATA,
			'@width:[^>].*;@siU',							// Strip width
			'@width="[^>].*"@siU',							// Strip width style
			'@height="[^>].*"@siU',							// Strip height
			'@class="[^>].*"@siU',							// Strip class
			'@border="[^>].*"@siU',							// Strip border
			'@font-family:[^>].*;@siU'						// Strip fonts
	);
	$sus = array('','','','','','width: 500px;','width="500"','','','','font-family: Helvetica, sans-serif, Arial;');
	$_OB = preg_replace($patrones,$sus,$_TEXT);
	
	switch($pr_approved){
		case 'SI':
			switch($pr_rate){
				case 'SI':
					$_PR = $pr_percentage;	$_CR = $pr_current_rate;	$_FR = $pr_final_rate;
					break;
				case 'NO':
					$_CR = $pr_rate_curr;	$_FR = $pr_rate_curr;
					break;
			}
			break;
		case 'NO':
			$_FR = $pr_rate_curr;
			break;
		case 'PE':
			switch($state[1]){
				case 'EM':
					$_OB = '';
					break;
				default:
					break;
			}
			break;
	}
	
	if($pr_approved !== 'PE'){
		$idF = uniqid('@S#1$2013',true);
	
		$sqlF = 'INSERT INTO s_de_facultativo 
			(`id_facultativo`, `id_emision`, `aprobado`, `tasa_recargo`, `porcentaje_recargo`, `tasa_actual`, `tasa_final`, `observacion`, `pro_usuario`, `fecha_creacion`, `recordatorio`, `fecha_recordatorio`) 
			VALUES 
			("'.$idF.'", "'.$ide.'", "'.$pr_approved.'", "'.$pr_rate.'", '.$_PR.', '.$_CR.', '.$_FR.', "'.$_OB.'", "'.$user.'", curdate(), 0, "");';
		
		if($link->query($sqlF) === TRUE)
			$swF = TRUE;
		else
			$swF = FALSE;
	}elseif($pr_approved === 'PE'){
		$sqlSearch = 'SELECT id_pendiente, id_emision, COUNT(id_emision) as token
				FROM s_de_pendiente
			WHERE id_emision = "'.$ide.'" ;';
		
		if(($rsSearch = $link->query($sqlSearch,MYSQLI_STORE_RESULT))){
			$rowSearch = $rsSearch->fetch_array(MYSQLI_ASSOC);
			if((int)$rowSearch['token'] === 0){
				$idP = uniqid('@S#1$2013',true);
				$sqlP = 'INSERT INTO s_de_pendiente 
					(`id_pendiente`, `id_emision`, `id_estado`, `observacion`, `pro_usuario`, `fecha_creacion`, `respuesta`, `obs_respuesta`, `fecha_respuesta`, `id_cm`)
					VALUES
					("'.$idP.'", "'.$ide.'", '.$state[0].', "'.$_OB.'", "'.$user.'", curdate(), 0, "", "", 1) ;';
			}elseif((int)$rowSearch['token'] > 0){
				$sqlP = 'UPDATE s_de_pendiente 
					SET `id_estado` = '.$state[0].', `observacion` = "'.$_OB.'", `pro_usuario` = "'.$user.'", 
						`fecha_creacion` = curdate(), `respuesta` = FALSE
					WHERE 
						id_pendiente = "'.$rowSearch['id_pendiente'].'" 
							AND id_emision = "'.$rowSearch['id_emision'].'" ;';
			}
		}
		
		if($link->query($sqlP) === TRUE){
			$swF = TRUE;
		}else
			$swF = FALSE;
	}
	
	if($swF === TRUE){
		$sqlm = 'UPDATE s_de_em_cabecera
			SET leido = FALSE 
			WHERE id_emision = "'.$ide.'" ;';
		
		if($link->query($sqlm) === TRUE){
			$arrDE[0] = 1;
			$arrDE[1] = 'index.php?ms='.$_GET['ms'].'&page='.$_GET['page'].'&fwd='.md5('forwardFAC');
			$arrDE[2] = 'El caso facultativo se proceso correctamente';
			if($smail->send_mail_fac($ide, $pr_email) === TRUE){
				$arrDE[2] .= '<br>y se envió el Correo Electronico';
			}else{
				$arrDE[2] .= '<br>pero no se envió el Correo Electronico';
			}
		}else
			$arrDE[2] = 'El caso facultativo se proceso correctamente pero no se marco como leído';
	}else
		$arrDE[2] = 'No se pudo procesar el caso facultativo';
	
	echo json_encode($arrDE);
}else{
	echo json_encode($arrDE);
}
?>