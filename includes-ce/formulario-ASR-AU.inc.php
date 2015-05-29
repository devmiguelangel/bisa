<?php
function au_formulario_asr($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
		
	ob_start();
?>
  <div id="container-c" style="width: 785px; height: auto; 
    border: 0px solid #0081C2; padding: 5px;">
	  <div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; 
      font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php
     if($row['tipo_cliente']=='N'){
		 $cliente_nombre = $row['nombre'].' '.$row['paterno'].' '.$row['materno'];
		 $cliente_nitci = $row['ci'].$row['complemento'].' '.$row['extension'];
	 }elseif($row['tipo_cliente']=='J'){
		 $cliente_nombre = $row['cl_razon_social'];
		 $cliente_nitci = $row['ci'];
	 }
	 if($row['fecha_emision']!=='0000-00-00'){
		$fecha_em = $row['fecha_emision'];  
	 }else{
		$fecha_em = $row['fecha_creacion']; 
	 }
	 while($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)){ 
	    $materia_seguro = $rowDt['tipo_vechiculo'].' '.$rowDt['marca'].' '.$rowDt['modelo'].' '.$rowDt['placa'];
?>
        <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                  <td style="width:25%; text-align:left;">&nbsp;
                       
                  </td>
                  <td style="width:50%; font-weight:bold; text-align:center; font-size: 85%;">
                     ANEXO DE SUBROGACIÓN DE DERECHOS PARA ACREEDORES<br><br>
                     Código APS: XXX-XXXXXX-XXXXXXXXXXXXX<br>
                     R.A. XXX/XXXX
                  </td>
                  <td style="width:25%; text-align:right;">
                  
                  </td> 
                </tr>
            </table>     
        </div>
        <br/>
        
        <div style="width: 775px; border: 0px solid #FFFF00;"> 
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:4px; padding-bottom:3px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px;">
                    <b>Asegurado:</b>&nbsp;<?=$cliente_nombre;?><br>
                    <b>Póliza Nro.:</b>&nbsp;<?=$row['no_emision'];?><br>
                    <b>Materia del Seguro Subrogada:</b>&nbsp;<?=$materia_seguro;?><br>
                    <b>Ubicación del Riesgo:</b><br>
                    <b>Vigencia del Seguro:</b>&nbsp;desde <?=$row['fecha_iniv'];?> hasta <?=$row['fecha_finv'];?><br>
                    <b>Vigencia de la Subrogación:</b>&nbsp;Durante la vigencia del crédito<br>
                    <b>Acreedor (Beneficiario de Subrogación):</b>&nbsp;<?=$row['ef_nombre'];?><br>
                    <b>Lugar y Fecha:</b>&nbsp;<?=$row['u_departamento'].' '.$fecha_em;?> 
                  </td>      
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">&nbsp;
                                                     
                  </td>
                </tr>   
                <tr>
                  <td style="width:100%; padding-bottom:4px; text-align:justify;">
                    Se deja constancia por el presente anexo, que a solicitud expresa de los tomadores y/o contratantes y/o asegurados, “EL ACREEDOR” será considerado como beneficiario hasta por el importe de su acreencia sin exceder la suma asegurada de la Póliza Nro. <?=$row['no_emision'];?>.
                    <br><br>
                    En consecuencia, el Asegurado no podrá ejercitar sus derechos, sino por intermedio del Acreedor.
                    <br><br>
                    
                    La Aseguradora solo estará obligada a pagar al Acreedor la suma equivalente al saldo adeudado por el Asegurado, y el excedente, si lo hubiera, será pagado al Asegurado.
                    <br><br>
                    Queda entendido y convenido por la Aseguradora, que ninguna modificación en las condiciones de la presente póliza, sean estas generales, particulares o especiales, y que afecten los intereses del Acreedor,  serán introducidas sin el previo consentimiento escrito del Acreedor. Para este efecto,  toda modificación solicitada debe ser acompañada de la correspondiente aprobación escrita por parte del Acreedor.
                    <br><br>
                    Consecuentemente se considera como no inserta o no puesta cualquier modificación que no haya sido expresamente autorizada por el Acreedor.
                    <br><br>
                    La Aseguradora se obliga a notificar por escrito al Acreedor en caso que el original Tomador o Asegurado no pague – total o parcialmente – la prima correspondiente, otorgando siete (7) días calendarios adicionales de cobertura a partir de la notificación, a efectos de que el Acreedor pueda hacerse cargo del pago correspondiente de la prima adeudada.
                    <br><br>
                    En caso de renovación de la póliza y de no mediar solicitud en contrario del Acreedor, la Aseguradora conviene que extenderá automáticamente el tenor de esta cláusula en la nueva póliza, aunque no medie solicitud en ese sentido.
                    <br><br>
                    La presente cláusula será preeminente sobre cualquier cláusula, anexo, condiciones generales, particulares o especiales que se opongan a la misma, aun cuando sea de  fecha posterior.
                    <br><br><br><br><br><br>
                  </td>              
                </tr>
                <tr>
                  <td style="width:100%;">
                      <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:16%;">&nbsp;</td>
                           <td style="width:25%; border-bottom: 1px solid #333;">
                             <img src="<?=$url;?>img/firmas_bisa.png" height="90"/>
                           </td>
                           <td style="width:17%;">&nbsp;</td>
                           <td style="width:25%; border-bottom: 1px solid #333;">&nbsp;
                             
                           </td>
                           
                           <td style="width:16%;">&nbsp;</td>
                         </tr>
                      </table> 
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%;">
                      <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:16%;">&nbsp;</td>
                           <td style="width:25%; text-align:center;">
                             Firma de la Cía. de Seguros
                           </td>
                           <td style="width:17%;">&nbsp;</td>
                           <td style="width:25%; text-align:center;">
                             Firma del Asegurado
                           </td>
                           <td style="width:16%;">&nbsp;</td>
                         </tr>
                      </table> 
                  </td> 
                </tr>
            </table>
              	
        </div>            
<?php
	 }
?>        
      </div>
   </div>
   
<?php
	$html = ob_get_clean();
	return $html;
}
/*
function get_date_format_fat_au($fecha){
	$date = date_create($fecha);
	
	$day = date_format($date, 'd');
	$month = date_format($date, 'F');
	$year = date_format($date, 'Y');
	
	return $day.' de '.get_month_es_fat_au($month).' de '.$year;
}

function get_month_es_fat_au($month){
	switch ($month) {
		case 'January':
			return 'Enero';
			break;
		case 'February':
			return 'Febrero';
			break;
		case 'March':
			return 'Marzo';
			break;
		case 'April':
			return 'Abril';
			break;
		case 'May':
			return 'Mayo';
			break;
		case 'June':
			return 'Junio';
			break;
		case 'July':
			return 'Julio';
			break;
		case 'August':
			return 'Agosto';
			break;
		case 'September':
			return 'Septiembre';
			break;
		case 'October':
			return 'Octubre';
			break;
		case 'November':
			return 'Noviembre';
			break;
		case 'December':
			return 'Diciembre';
			break;
	}
}
*/
?>