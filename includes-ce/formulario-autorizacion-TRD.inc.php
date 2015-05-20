<?php
function trd_formulario_autorizacion($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
		
	ob_start();
?>
  <div id="container-c" style="width: 785px; height: auto; 
    border: 0px solid #0081C2; padding: 5px;">
	  <div id="main-c" style="width: 775px; font-weight: normal; font-size: 15px; 
      font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php
     if($row['tipo_cliente']=='N'){
		 $cliente_nombre = $row['cl_nombre'].' '.$row['cl_paterno'].' '.$row['cl_materno'];
		 $cliente_nitci = $row['cl_ci'].$row['cl_complemento'].' '.$row['cl_extension'];
	 }elseif($row['tipo_cliente']=='J'){
		 $cliente_nombre = $row['cl_razon_social'];
		 $cliente_nitci = $row['cl_ci'];
	 }
?>
        <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                  <td style="width:100%; text-align:right;">
                     <img src="<?=$url;?>images/<?=$row['ef_logo'];?>"/> 
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
                     En razón que el CLIENTE ha decidido contratar de forma voluntaria una póliza de seguros de la empresa BISA SEGUROS Y REASEGUROS S.A., el CLIENTE instruye al Banco BISA S.A. a proporcionar su información con la que cuenta el Banco, a la Aseguradora referida y a la empresa Sudamericana Corredores de Seguros y Reaseguros, para la obtención de la póliza de seguros escogida por el propio CLIENTE.
                     <br><br>
                     Asimismo, autorizo a realizar el débito automático para el pago de las cuotas que se generen de esta póliza de la cuenta corriente/ahorro Nº.__________________ a nombre de ____________________
                     <br><br><br>
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:20%;">CLIENTE: </td>
                          <td style="width:50%; border-bottom: 1px solid #333;">&nbsp;
                             <?=$cliente_nombre;?>
                          </td>
                          <td style="width:30%;"></td>
                         </tr>
                         <tr><td colspan="3" style="width:100%;">&nbsp;</td></tr>
                         <tr> 
                          <td style="width:20%;">LUGAR Y FECHA: </td>
                          <td style="width:50%; border-bottom: 1px solid #333;">&nbsp;
                             <?=strtoupper(get_date_format_fat_trd($row['fecha_emision']));?>
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
            
        </div>    
        
      </div>
   </div> 
   
<?php
	$html = ob_get_clean();
	return $html;
}

function get_date_format_fat_trd($fecha){
	$date = date_create($fecha);
	
	$day = date_format($date, 'd');
	$month = date_format($date, 'F');
	$year = date_format($date, 'Y');
	
	return $day.' de '.get_month_es_fat_trd($month).' de '.$year;
}

function get_month_es_fat_trd($month){
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