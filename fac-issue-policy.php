<?php
header("Expires: Thu, 27 Mar 1980 23:59:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require __DIR__ . '/classes/Logs.php';
require __DIR__ . '/classes/Collections.php';
require 'sibas-db.class.php';
require 'classes/certificate-sibas.class.php';

if(isset($_POST['ide']) && isset($_POST['pr'])){
	session_start();
	
	$link = new SibasDB();
	
	$arrIs 	= array (0 => 'LA PÓLIZA NO PUEDE SER EMITIDA', 1 => '#');
	$record = 0;
	
	$ide 	= $link->real_escape_string(trim(base64_decode($_POST['ide'])));
	$pr 	= $link->real_escape_string(trim(base64_decode($_POST['pr'])));
	$typeCe = '';
	$url 	= 'index.php';
	
	if (($type = $link->verify_type_user($_SESSION['idUser'], $_SESSION['idEF'])) !== false) {
		if ($type['u_tipo_codigo'] === 'LOG') {
			$url = 'certificate-policy.php?ms=&page=&pr=' . base64_encode($pr) . '&ide=' . base64_encode($ide);
			$typeCe = 'MAIL';
		} elseif ($type['u_tipo_codigo'] === 'IMP') {
			$typeCe = 'ATCH';
		}
	}

	$sql = 'select 
		sae.id_emision as ide,
		sae.no_emision,
		sae.forma_pago, 
		sae.prima_total,
		sae.fecha_emision,
		sae.garantia
	from 
		s_' . strtolower($pr) . '_em_cabecera as sae
	where
		sae.id_emision = "' . $ide . '"
	limit 0, 1
	;';
	
	if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
		if ($rs->num_rows === 1) {
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();

			$record = $row['no_emision'];

			$collection = new Collection($link, $row, $pr);

			if ($collection->putPolicy($ide, $_SESSION['idEF'])) {
				$arr_host = array();
				if (($rowIm = $link->get_data_user($_SESSION['idUser'], $_SESSION['idEF'])) !== FALSE) {
					$arr_host['from'] = $rowIm['u_email'];
					$arr_host['fromName'] = $rowIm['u_nombre'];
				}

				$ce = new CertificateSibas($ide, NULL, NULL, $pr, $typeCe, 'CE', 1, 0, FALSE);
				$ce->host = $arr_host;
								
				if ($ce->Output()) {
					$arrIs[0] = 'LA PÓLIZA FUE EMITIDA CON EXITO !<br>Por favor espere...';
					$arrIs[1] = $url;
				} else {
					$arrIs[0] = 'LA PÓLIZA FUE EMITIDA CON EXITO';
				}

				$log_msg =  $pr . ' - Em. ' . $record . ' / Emision Fac.';

				$db = new Log($link);
				$db->postLog($_SESSION['idUser'], $log_msg);
			} else {
				$arrAU[2] = $collection->mess;
			}
		} else {
			$arrIs[0] = 'ERROR: LA PÓLIZA NO PUDO SER EMITIDA';
		}
	} else {
		$arrIs[0] = 'ERROR: LA PÓLIZA NO PUDO SER EMITIDA.';
	}
	
	echo json_encode($arrIs);
} else if(isset($_GET['ide']) && isset($_GET['pr'])) {
?>
<script type="text/javascript">
$(document).ready(function(e) {
	setTimeout(function() {
		var _data = $('#f-issue').serialize();
		
		sendApprove(_data);
	}, 2000);
});

function sendApprove(_data) {
	$.ajax({
		type:"POST",
		async:true,
		cache:false,
		url:"fac-issue-policy.php",
		data: _data,
		dataType: "json",
		beforeSend: function(){
			if($('.loading .loading-text').length) {
				$('.loading .loading-text').remove();
			}
		},
		complete: function(){
			$('.loading img').slideUp();
		},
		success: function(resp){
			$('.loading img:last').after('<span class="loading-text">'+resp[0]+'</span>');
			redirect(resp[1], 3);
		}
	});
	return false;
}
</script>
<div style="width:auto; height:auto; min-width:300px; padding:5px 5px; font-size:80%; text-align:center;">
	<form id="f-issue" name="f-issue">
    	<input type="hidden" id="ide" name="ide" value="<?=$_GET['ide'];?>">
	    <input type="hidden" id="pr" name="pr" value="<?=$_GET['pr'];?>">
    </form>
    <div class="loading">
        <img src="img/loading-01.gif" width="35" height="35" style="display:block;" />
    </div>
</div>
<?php
}
?>