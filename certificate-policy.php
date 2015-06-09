<?php
$product = '';
if(isset($_GET['pr']) && isset($_GET['ide'])){
	$product = base64_decode($_GET['pr']);
} else {
	echo '<meta http-equiv="refresh" content="0;url=index.php" >';
}

include('header.inc.php');
?>
<div id="content-main">
	<section id="main">
<?php
$ide = trim(base64_decode($_GET['ide']));
$idc = NULL;
$cp = false;
$type = base64_encode('PRINT');
$category = NULL;
$pr = base64_encode($product);

$titleSlip = 'Formulario de Solicitud';
$titleSlip2 = '';
$titleCert = 'Póliza';
$titleCert2 = '';
$titleCert3 = 'Formulario de Autorización';
$titleCert4 = 'Formulario UIF';
$titleCert5 = 'Anexo de Subrogación';
$titleCert6 = 'Carta Sudamericana';

if($token === TRUE){
	$sqlIs = '';
	switch($product){
		case 'DE':
			$titleSlip2 = 'Slip Vida en Grupo';
			$titleCert = 'Póliza Desgravamen';
			$titleCert2 = 'Póliza Vida en Grupo';
			
			$sqlIs = 'select 
					sde.id_emision as ide,
					sdc.id_cotizacion as idc, 
					sde.prefijo,
					sde.no_emision,
					sde.id_compania,
					sde.certificado_provisional as cp,
					sde.modalidad
				from s_de_em_cabecera as sde
					inner join s_de_cot_cabecera as sdc on (sdc.id_cotizacion = sde.id_cotizacion)
				where sde.id_emision = "'.$ide.'"
			;';
			break;
		case 'AU':
			$sqlIs = 'select 
					sae.id_emision as ide,
					sac.id_cotizacion as idc, 
					sae.prefijo,
					sae.no_emision,
					sae.id_compania,
					sae.certificado_provisional as cp,
					sae.garantia,
					sae.emitir
				from s_au_em_cabecera as sae
					inner join s_au_cot_cabecera as sac on (sac.id_cotizacion = sae.id_cotizacion)
				where sae.id_emision = "'.$ide.'"
			;';
			break;
		case 'TRD':
			$sqlIs = 'select 
					stre.id_emision as ide,
					strc.id_cotizacion as idc, 
					stre.prefijo,
					stre.no_emision,
					stre.id_compania,
					stre.certificado_provisional as cp,
					stre.garantia,
					stre.emitir
				from s_trd_em_cabecera as stre
					inner join s_trd_cot_cabecera as strc on (strc.id_cotizacion = stre.id_cotizacion)
				where stre.id_emision = "'.$ide.'"
			;';
			break;
		case 'TRM':
			$sqlIs = 'select 
					stre.id_emision as ide,
					strc.id_cotizacion as idc, 
					stre.prefijo,
					stre.no_emision,
					stre.id_compania,
					stre.certificado_provisional as cp
				from s_trm_em_cabecera as stre
					inner join s_trm_cot_cabecera as strc on (strc.id_cotizacion = stre.id_cotizacion)
				where stre.id_emision = "'.$ide.'"
			;';
			break;
	}
	
	$rsIs = $link->query($sqlIs, MYSQLI_STORE_RESULT);
	if($rsIs->num_rows === 1){
		$rowIs = $rsIs->fetch_array(MYSQLI_ASSOC);
		$ide = base64_encode($rowIs['ide']);
		$idc = base64_encode($rowIs['idc']);
		$cp = (boolean)$rowIs['cp'];
		
		if ($cp === true) {
			$category = base64_encode('CP');
			$titleCert = 'Póliza Provisional';
		} else {
			$category = base64_encode('CE');
			if ($product === 'DE') {
				$titleCert = 'Póliza Desgravamen';
			}
		}
?>
<h3 id="issue-title">Póliza <?=$rowIs['prefijo'] . '-' . $rowIs['no_emision'];?></h3>

<a href="certificate-detail.php?idc=<?=$idc;?>&cia=<?=
	base64_encode($rowIs['id_compania']);?>&type=<?=$type;?>&pr=<?=$pr;?>" 
	class="fancybox fancybox.ajax view-detail">Ver <?=$titleSlip;?></a>

<a href="certificate-detail.php?ide=<?=$ide;?>&type=<?=$type;?>&pr=<?=
	$pr;?>&category=<?=$category;?>" 
	class="fancybox fancybox.ajax view-detail">Ver <?=$titleCert;?></a>

<a href="certificate-detail.php?ide=<?=$ide;?>&type=<?=$type;?>&pr=<?=
	$pr;?>&category=<?=base64_encode('FAT');?>" 
	class="fancybox fancybox.ajax view-detail">Ver <?=$titleCert3;?></a>

<a href="certificate-detail.php?ide=<?=$ide;?>&type=<?=$type;?>&pr=<?=
	$pr;?>&category=<?=base64_encode('UIF');?>" 
	class="fancybox fancybox.ajax view-detail">Ver <?=$titleCert4;?></a>

<?php if ((boolean)$rowIs['garantia']): ?>
<a href="certificate-detail.php?ide=<?=$ide;?>&type=<?=$type;?>&pr=<?=
	$pr;?>&category=<?=base64_encode('ASR');?>" 
	class="fancybox fancybox.ajax view-detail">Ver <?=$titleCert5;?></a>
<?php endif ?>

<a href="certificate-detail.php?ide=<?=$ide;?>&type=<?=$type;?>&pr=<?=
	$pr;?>&category=<?=base64_encode('CRT');?>" 
	class="fancybox fancybox.ajax view-detail">Ver <?=$titleCert6;?></a>

<?php if ((boolean)$rowIs['garantia'] && !(boolean)$rowIs['emitir']): ?>
	<div class="fac-mess" style="width: 40%; text-align: center; font-size: 80%;">
		Es obligatorio imprimir todos los documentos y hacerlos firmar con el cliente para su posterior entrega
    </div>
    <div class="fac-mess" style="width: 40%; text-align: center; font-size: 80%;">
		Póliza emitida pendiente de vinculación de garantía
	</div>
<?php elseif ((boolean)$rowIs['garantia'] && (boolean)$rowIs['emitir']): ?>
	<div class="fac-mess" style="width: 40%; text-align: center; font-size: 80%; 
		border: 1px solid #096; background: #afffaa; color: #494949; font-weight: bold;">
		Póliza se vinculó correctamente
    </div>
<?php endif ?>
<?php
	} else {
		echo 'Usted no puede visualizar los Cetificados';
	}
} else {
	include('index-content.inc.php');
}
?>
	</section>
</div>	
<?php
include('footer.inc.php');
?>