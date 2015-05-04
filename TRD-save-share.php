<?php

require_once('sibas-db.class.php');

$link = new SibasDB();
$idc = $link->real_escape_string(trim(base64_decode($_GET['idc'])));
$cia = $link->real_escape_string(trim(base64_decode($_GET['cia'])));
$cp = false;

$url = 'trd-quote.php?ms=' . $_GET['ms']
    . '&page=' . $_GET['page'] . '&pr=' . base64_encode('TRD|05')
    . '&idc=' . base64_encode($idc)
    . '&flag='.md5('i-new') . '&cia=' . base64_encode($cia);

$data = $link->getTasaTrd($idc);

if (count($data) > 0) {
	$prima_total = 0;

	foreach ($data as $key => $tr) {
		$prima_total += $tr['tr_prima'];

		$sql = 'update s_trd_cot_detalle 
		set tasa = "' . $tr['tr_tasa'] . '",
			prima = "' . $tr['tr_prima'] . '"
		where
			id_inmueble = "' . $tr['id_tr'] . '"
		;';

		if ($link->query($sql) === false) {
			echo 'No se pudo registrar la prima';
		}
	}

	$sql = 'update s_trd_cot_cabecera
    set prima_total = ' . $prima_total . '
    where id_cotizacion = "' . $idc . '"
        and id_ef = "' . base64_decode($_SESSION['idEF']) . '" ;';

	if ($link->query($sql)) {

	}
}

?>
<script type="text/javascript">
$(document).ready(function(e) {
	redirect('<?=$url;?>', 0);
});
</script>