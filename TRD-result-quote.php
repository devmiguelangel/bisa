<?php

require_once 'sibas-db.class.php';

$link = new SibasDB();
$idc = $link->real_escape_string(trim(base64_decode($_GET['idc'])));

$sqlCia = 'select 
    strc.id_cotizacion as idc,
    sef.id_ef as idef,
    scia.id_compania as idcia,
    scia.nombre as cia_nombre,
    scia.logo as cia_logo,
    strc.garantia as c_garantia,
    strc.plazo as c_plazo,
    strc.tipo_plazo as c_tipo_plazo,
    strc.forma_pago as c_forma_pago
from
    s_trd_cot_cabecera as strc
        inner join
    s_entidad_financiera as sef ON (sef.id_ef = strc.id_ef)
        inner join
    s_ef_compania as sec ON (sec.id_ef = sef.id_ef)
        inner join
    s_compania as scia ON (scia.id_compania = sec.id_compania)
where
    strc.id_cotizacion = "'.$idc.'"
        and sef.id_ef = "'.base64_decode($_SESSION['idEF']).'"
        and sef.activado = true
        and scia.activado = true
        and sec.producto = "TRD"
order by scia.id_compania asc
;';

if(($rsCia = $link->query($sqlCia, MYSQLI_STORE_RESULT)) !== false){
	if($rsCia->num_rows > 0){
		$year = 0;
        $type = base64_encode('PRINT');
        $pr = base64_encode('TRD');
        $category = base64_encode('CP');

        $data = $link->getTasaTrd($idc);

		while($rowCia = $rsCia->fetch_array(MYSQLI_ASSOC)){
			$year = $link->get_year_final($rowCia['c_plazo'], $rowCia['c_tipo_plazo']);
			$primaT = 0;
			$tasaT = 0;

			if (count($data) > 0) {
				foreach ($data as $key => $tr) {
					$primaT += $tr['tr_prima'];
				}
			}
?>
<h3>Seguro de Todo Riesgo Domiciliario - Tenemos las siguientes ofertas</h3>
<h4>Escoge el plan que mas te convenga</h4>
<section style="text-align:center;">
	<div class="result-quote">
		<div class="rq-img">
			<img src="images/<?=$rowCia['cia_logo'];?>" 
				alt="<?=$rowCia['cia_nombre'];?>" 
				title="<?=$rowCia['cia_nombre'];?>">
		</div>
		<span class="rq-tasa">
			Prima: <br>
			<span class="value">USD <?= number_format($primaT, 2, '.', ',') ;?> </span><br>
			<?= $link->methodPayment[$rowCia['c_forma_pago']] ;?>
		</span>
		<a href="certificate-detail.php?idc=<?=base64_encode($idc);?>&cia=<?=
			base64_encode($rowCia['idcia']);?>&type=<?=base64_encode('PRINT');?>&pr=<?=
			base64_encode('TRD');?>" class="fancybox fancybox.ajax btn-see-slip">
			Ver Solicitud
		</a>
		<?php if ($token): ?>
		<a href="trd-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=
			base64_encode('TRD|04');?>&idc=<?=$_GET['idc'];?>&flag=<?=
			md5('i-new');?>&cia=<?=base64_encode($rowCia['idcia']);?>" 
			class="btn-send">Emitir</a>
		<?php endif ?>
	</div>
</section>
<?php
		}
		
		$rsCia->free();
	} else {
		echo 'No se puede obtener las Compañias |';
	}
} else {
	echo 'No se puede obtener las Compañias';
}
?>