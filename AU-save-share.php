<?php
require_once('sibas-db.class.php');
$link = new SibasDB();
$idc = $link->real_escape_string(trim(base64_decode($_GET['idc'])));
$cia = $link->real_escape_string(trim(base64_decode($_GET['cia'])));
$cp = false;
$cpLnk = $cpSql = '';
$url = 'au-quote.php?ms=' . $_GET['ms']
    . '&page=' . $_GET['page'] . '&pr=' . base64_encode('AU|05')
    . '&idc=' . base64_encode($idc)
    . '&flag=' . md5('i-new') . '&cia=' . base64_encode($cia);

$data = $link->getTasaAu(base64_decode($_SESSION['idEF']), $idc);

if (count($data) > 0) {
	$prima_total = 0;

	foreach ($data as $key => $vh) {
		if ($vh['v_prima'] < $vh['v_prima_minima']) {
			$vh['v_prima'] = $vh['v_prima_minima'];
		}

		$prima_total += $vh['v_prima'];

		$sql = 'update s_au_cot_detalle 
		set tasa = "' . $vh['v_tasa'] . '",
			prima = "' . $vh['v_prima'] . '"
		where
			id_vehiculo = "' . $vh['idvh'] . '"
		;';

		if ($link->query($sql) === false) {
			echo 'No se pudo registrar la prima';
		}
	}

	$sql = 'update s_au_cot_cabecera
    set prima_total = ' . $prima_total . '
    where id_cotizacion = "' . $idc . '"
        and id_ef = "' . base64_decode($_SESSION['idEF']) . '" ;';

	if ($link->query($sql)) {

	}
}

?>
<script type="text/javascript">
$(document).ready(function(e) {
	redirect('<?= $url; ?>', 0);
});
</script>