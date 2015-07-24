<?php

require_once 'sibas-db.class.php';

$link = new SibasDB();
$idc = $link->real_escape_string(trim(base64_decode($_GET['idc'])));

$sqlCia = 'select 
	sac.id_cotizacion as idc,
	sef.id_ef as idef,
	scia.id_compania as idcia,
	scia.nombre as cia_nombre,
	scia.logo as cia_logo,
	sac.garantia as c_garantia,
	sac.plazo as c_plazo,
	sac.tipo_plazo as c_tipo_plazo,
	sac.forma_pago as c_forma_pago
from
	s_au_cot_cabecera as sac
		inner join
	s_entidad_financiera as sef ON (sef.id_ef = sac.id_ef)
		inner join
	s_ef_compania as sec ON (sec.id_ef = sef.id_ef)
		inner join
	s_compania as scia ON (scia.id_compania = sec.id_compania)
where
	sac.id_cotizacion = "' . $idc . '"
		and sef.id_ef = "' . base64_decode($_SESSION['idEF']) . '"
		and sef.activado = true
		and sec.producto = "AU"
		and scia.activado = true
order by scia.id_compania asc
;';

if (($rsCia = $link->query($sqlCia, MYSQLI_STORE_RESULT)) !== false) {
	if ($rsCia->num_rows > 0) {
		$year = 0;
        $type = base64_encode('PRINT');
        $pr = base64_encode('AU');
        $category = base64_encode('CP');
        $data = array();

		while ($rowCia = $rsCia->fetch_array(MYSQLI_ASSOC)) {
			$year = $link->get_year_final($rowCia['c_plazo'], $rowCia['c_tipo_plazo']);
			$primaT = 0;
			$tasaT = 0;

			$data = $link->getTasaAu($rowCia['idef'], $rowCia['idc']);

			if (count($data) > 0) {
				foreach ($data as $key => $vh) {
					if ($vh['v_prima'] < $vh['v_prima_minima']) {
						$vh['v_prima'] = $vh['v_prima_minima'];
					}

					$primaT += $vh['v_prima'];
				}
			}
?>
<h3>Seguro de Automotores - Tenemos las siguientes ofertas</h3>
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
			<span class="value">USD <?=number_format($primaT,2,'.',',');?> </span><br>
            <?= $link->methodPayment[$rowCia['c_forma_pago']] ;?>
		</span>

		<a href="certificate-detail.php?idc=<?=base64_encode($idc);?>&cia=<?=
			base64_encode($rowCia['idcia']);?>&pr=<?=
			base64_encode('AU');?>&type=<?=base64_encode('PRINT');?>" 
			class="fancybox fancybox.ajax btn-see-slip">
			Ver Solicitud
		</a>
		<?php if ($token): ?>
		<a href="au-quote.php?ms=<?=$_GET['ms'];?>&page=<?=$_GET['page'];?>&pr=<?=
			base64_encode('AU|04');?>&idc=<?=$_GET['idc'];?>&flag=<?=
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