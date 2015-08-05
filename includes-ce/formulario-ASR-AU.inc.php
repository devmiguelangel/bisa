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
	 $poliza = (91).''.plaza_au_asr($row['u_departamento']).''.$row['garantia'].''.str_pad($row['no_emision'],7,'0',STR_PAD_LEFT);	
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
                     Código APS: 109-910502-2007 12 311 2059<br>
                     R.A.: 799-14
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
                    <b>Póliza Nro.:</b>&nbsp;<?=$poliza;?><br>
                    <b>Lugar y Fecha:</b>&nbsp;<?=$row['u_departamento'].' '.date("d-m-Y", strtotime($fecha_em));?><br>
                    <b>Materia del Seguro Subrogada:</b>&nbsp;<?=$materia_seguro;?><br>
                    <b>Ubicación del Riesgo:</b><br>
                    <b>Vigencia del Seguro:</b>&nbsp;desde <?=date("d-m-Y", strtotime($row['fecha_iniv']));?> hasta <?=date("d-m-Y", strtotime($row['fecha_finv']));?><br>
                    <b>Vigencia de la Subrogación:</b>&nbsp;Durante la vigencia del crédito<br>
                    <b>Acreedor (Beneficiario de Subrogación):</b>&nbsp;<?=$row['ef_nombre'];?> 
                  </td>      
                </tr>
                <tr>
                  <td style="width:100%; padding-bottom:4px;">&nbsp;
                                                     
                  </td>
                </tr>   
                <tr>
                  <td style="width:100%; padding-bottom:4px; text-align:justify;">
                    Se deja constancia por el presente anexo, que a solicitud expresa de los tomadores y/o contratantes y/o asegurados, “EL ACREEDOR” será considerado como beneficiario hasta por el importe de su acreencia sin exceder la suma asegurada de la Póliza Nro. <?=$poliza;?>.
                    <br><br>
                                        
                    La Aseguradora solo estará obligada a pagar al Acreedor la suma equivalente al saldo adeudado por el Asegurado, y el excedente, si lo hubiera, será pagado al Asegurado.
                    <br><br>
                    Queda entendido y convenido qué, cuando los gravámenes aparezcan indicados en la póliza o se hubiera notificado a la Aseguradora por escrito la existencia de los mismos, ésta deberá comunicar a los Acreedores cualquier resolución destinada a rescindir, anular, modificar o suspender el contrato de segúro.
                    <br><br>
                    
                    La Aseguradora se obliga a notificar por escrito al Acreedor en caso que el original Tomador o Asegurado no pague – total o parcialmente – la prima correspondiente, otorgando siete (7) días calendarios adicionales de cobertura a partir de la notificación, a efectos de que el Acreedor pueda hacerse cargo del pago correspondiente de la prima adeudada.
                    <br><br>
                    En caso de renovación de la póliza y de no mediar solictud en contrario del Acreedor, la Aseguradora convieneque extenderá automáticamente el tenor de este anexo en la nueva póliza, aunque no medie solicitud en ese sentido 
                    <br><br>
                    El presente anexo será preeminente sobre cualquier cláusula, anexo, condiciones generales, particulares o especiales que se opongan a la misma, aun cuando sea de fecha posterior
                    <br><br><br><br><br><br>
                  </td>              
                </tr>
                <tr>
                  <td style="width:100%;">
                      <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:14%;">&nbsp;</td>
                           <td style="width:27%; border-bottom: 1px solid #333;">
                             <img src="<?=$url;?>img/firmas_bisa.png" height="90"/>
                           </td>
                           <td style="width:18%;">&nbsp;</td>
                           <td style="width:27%; border-bottom: 1px solid #333;">&nbsp;
                             
                           </td>
                           
                           <td style="width:14%;">&nbsp;</td>
                         </tr>
                      </table> 
                  </td> 
                </tr>
                
                <tr>
                  <td style="width:100%;">
                      <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:14%;">&nbsp;</td>
                           <td style="width:27%; text-align:center;">
                             Firma de la Cía. de Seguros
                           </td>
                           <td style="width:18%;">&nbsp;</td>
                           <td style="width:27%; text-align:center;">
                             Firma del Asegurado
                           </td>
                           <td style="width:14%;">&nbsp;</td>
                         </tr>
                      </table> 
                  </td> 
                </tr>
                
            </table>
            <br><br><br><br><br><br>
            <div style="font-size: 80%; text-align:center;">  
                 • Av. Arce Nº 2631, Edificio Multicine Piso 14 • Teléfono: (591-2) 217 7000 • Fax: (591-2) 214 1928 • La Paz – Bolivia.<br> 
• Autorizado por Resolución Administrativa Nº 158 del 7 de julio de 1999 de la Superintendencia de Pensiones Valores y Seguros.
            </div>
              	
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

function plaza_au_asr($sucursal){
  	switch ($sucursal) {
		case 'La Paz':
			return 1;
			break;
		case 'Santa Cruz':
			return 2;
			break;
		case 'Cochabamba':
			return 3;
			break;
		case 'Chuquisaca':
			return 4;
			break;
		case 'Tarija':
			return 5;
			break;
		case 'Oruro':
			return 6;
			break;
		case 'Potosí':
			return 7;
			break;
		case 'Beni':
			return 8;
			break;
		case 'Pando':
			return 9;
			break;
	}
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