<?php
if(($rs_FORMS = $link->get_home_forms($_SESSION['idEF'])) !== false){
	if($rs_FORMS->num_rows > 0){
		$product = '';

		while ($row_FORMS = $rs_FORMS->fetch_array(MYSQLI_ASSOC)) {
			if ($product !== $row_FORMS['f_producto']) {
				$product = $row_FORMS['f_producto'];
?>
<br>
<h3>Formularios - <?=$row_FORMS['f_producto_text'];?></h3>
<?php
			}
?>
<a href="file_form/<?=$row_FORMS['f_archivo'];?>" target="_blank" class="list-forms"><?=$row_FORMS['f_titulo'];?></a>
<?php
		}
	}else{
		echo 'No existen formularios';
	}
}
?>