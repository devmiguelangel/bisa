<?php

require 'sibas-db.class.php';

$observation = '';

if (isset($_GET['ide']) && isset($_GET['obs']) && isset($_GET['pr'])) {
	$link = new SibasDB();

	$ide	= $link->real_escape_string(trim(base64_decode($_GET['ide'])));
	$obs 	= (int)$link->real_escape_string(trim($_GET['obs']));
	$pr 	= $link->real_escape_string(trim(base64_decode($_GET['pr'])));
	
	$sql = 'select 
		sde.anulado,
		sde.motivo_anulado as annulment_mess,
		sde.request,
		sde.request_mess,
		sde.revert,
		sde.revert_mess 
	from
		s_' . $pr . '_em_cabecera as sde
	where
		sde.id_emision = "' . $ide . '"
	limit 0, 1
	;';

	if (($rs = $link->query($sql, MYSQLI_STORE_RESULT)) !== false) {
		if ($rs->num_rows === 1) {
			$row = $rs->fetch_array(MYSQLI_ASSOC);
			$rs->free();

			switch ($obs) {
			case 1:
				$observation = $row['annulment_mess'];
				break;
			case 2:
				$observation = $row['request_mess'];
				break;
			case 3:
				$observation = $row['revert_mess'];
				break;
			}
		}
	}
}

?>
<div>
	<?= $observation ;?>
</div>