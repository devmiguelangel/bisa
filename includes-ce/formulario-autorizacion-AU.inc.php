<?php
function au_formulario_autorizacion($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
		
	ob_start();
?>
  <div id="container-c" style="width: 785px; height: auto; 
    border: 0px solid #0081C2; padding: 5px;">
	  <div id="main-c" style="width: 775px; font-weight: normal; font-size: 15px; 
      font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php
     if($row['tipo_cliente']=='N'){
		 $cliente_nombre = $row['nombre'].' '.$row['paterno'].' '.$row['materno'];
		 $cliente_nitci = $row['ci'].$row['complemento'].' '.$row['extension'];
	 }elseif($row['tipo_cliente']=='J'){
		 $cliente_nombre = $row['cl_razon_social'];
		 $cliente_nitci = $row['ci'];
	 }
	 $data_count = json_decode($row['nro_cuenta_tomador'],true);
	 
	 if($row['fecha_emision']!=='0000-00-00'){
		 $fecha_em = $row['fecha_emision'];
	 }else{
		 $fecha_em = $row['fecha_creacion'];
		 /*$fecha = new DateTime();
         $fecha_em = $fecha->format('Y-m-d');*/
	 }
?>
        <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                  <td style="width:100%; text-align:right;">
                     <img src="<?=$url;?>images/<?=$row['logo_ef'];?>"/> 
                  </td> 
                </tr>
            </table>     
        </div>
        <br>
        <br>
        <div style="width: 775px; border: 0px solid #FFFF00;">
			
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:4px; padding-bottom:10px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px; font-weight:bold; text-align:center;">
                     <u>FORMULARIO DE AUTORIZACIÓN</u>
                  </td>      
                </tr> 
            </table>
            <br><br><br><br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:4px; padding-bottom:3px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px; text-align:justify;">
                     En razón que el CLIENTE ha decidido contratar de forma voluntaria una póliza de seguros de la empresa BISA SEGUROS Y REASEGUROS S.A., el CLIENTE instruye al Banco BISA S.A. a proporcionar su información con la que cuenta el Banco, a la Aseguradora referida y a la empresa Sudamericana S.R.L. Corredores de Seguros y Reaseguros, para la obtención de la póliza de seguros escogida por el propio CLIENTE.
                     <br><br>
                     Asimismo, autorizo a realizar el débito automático para el pago de las cuotas que se generen de esta póliza de la cuenta corriente/ahorro Nº.&nbsp;<?=$data_count['numero'];?>&nbsp; a nombre de &nbsp;<?=$row['tomador_nombre'];?>&nbsp;
                     <br><br><br>
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:20%;">CLIENTE: </td>
                          <td style="width:50%; border-bottom: 0px solid #333;">&nbsp;
                             <?=$cliente_nombre;?>
                          </td>
                          <td style="width:30%;"></td>
                         </tr>
                         <tr><td colspan="3" style="width:100%;">&nbsp;</td></tr>
                         <tr> 
                          <td style="width:20%;">LUGAR Y FECHA: </td>
                          <td style="width:50%; border-bottom: 0px solid #333;">&nbsp;
                             <?=strtoupper(get_date_format_fat_au($fecha_em));?>
                          </td>
                          <td style="width:30%;"></td>  
                         </tr> 
                     </table>
                  </td>      
                </tr>
            </table>
            <br><br><br><br><br><br><br><br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
               <tr>
                <td style="width:25%;"></td>
                <td style="width:50%; border-bottom: 1px solid #333;">&nbsp;
                  
                </td>
                <td style="width:25%;"></td> 
               </tr>
               <tr>
                <td style="width:25%;"></td>
                <td style="width:50%; text-align:center; font-weight:bold;">
                  FIRMA DEL CLIENTE
                </td>
                <td style="width:25%;"></td> 
               </tr>  
            </table>
            
            <br><br><br><br><br><br>
            <div style="font-size: 80%; text-align:center;">  
                 • Av. Arce Nº 2631, Edificio Multicine Piso 14 • Teléfono: (591-2) 217 7000 • Fax: (591-2) 214 1928 • La Paz – Bolivia.<br> 
• Autorizado por Resolución Administrativa Nº 158 del 7 de julio de 1999 de la Superintendencia de Pensiones Valores y Seguros.
            </div>
              
        </div>    
        
      </div>
   </div> 
   
<?php
	$html = ob_get_clean();
	return $html;
}

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

?>