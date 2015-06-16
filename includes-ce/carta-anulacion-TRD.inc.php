<?php
function carta_anulacion_trd($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	if($row['fecha_emision']!=='0000-00-00'){
	    $fecha_em = $row['fecha_emision'];
		$vec_f = explode('-',$fecha_em);
		//$digi = substr($vec_f[0], -2);
		$anio = $vec_f[0];
		$mes = $vec_f[1];
	 }else{
		$fecha_em = $row['fecha_creacion'];
		$vec_f = explode('-',$fecha_em);
		//$digi = substr($vec_f[0], -2);
		$anio = $vec_f[0];
		$mes = $vec_f[1];
	 }
	 if($row['tipo_cliente']=='J'){
		 $cliente_nombre = $row['cl_razon_social'];
		 $cliente_nitci = $row['cl_ci'];
	 }elseif($row['tipo_cliente']=='N'){
		 $cliente_nombre = $row['cl_nombre'].' '.$row['cl_paterno'].' '.$row['cl_materno'];
		 $cliente_nitci = $row['cl_ci'].$row['cl_complemento'].' '.$row['cl_extension'];
	 }
	 $poliza = (92).''.plaza($row['u_depto']).''.$row['garantia'].''.str_pad($row['no_emision'],7,'0',STR_PAD_LEFT);		
     $correlativo = str_pad($row['no_emision'],3,'0',STR_PAD_LEFT);
	 $prefijo = prefijo($row['u_depto']);
	ob_start();
?>
  <div id="container-c" style="width: 785px; height: auto; border: 0px solid #0081C2; padding: 5px;">
	  <div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; 
        font-family: Arial, Helvetica, sans-serif; color: #000000;">

        <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                  <td style="width:100%; text-align:right; height:100px;">&nbsp;
                     
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; text-align:left; font-size: 100%;">
                     La Paz, <?=get_date_format_ca($fecha_em)?><br>
                     <?=$prefijo;?>-<?=$correlativo;?>/<?=$anio;?><br><br><br><br>
                     Señores<br>
                     <b>BISA SEGUROS Y REASEGUROS S.A.</b><br>
                     <u>Presente.-</u>
                  </td> 
                </tr>
            </table>     
        </div>
        <br>
        <br>
        <div style="width: 775px; border: 0px solid #FFFF00;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                padding-top:4px; padding-bottom:3px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px; text-align:justify;">
                     <b>Ref.:</b>	Solicitud de Anulación de la Póliza N° <?=$poliza?> 
                  </td>      
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                  <td>
<?php
                  if((boolean)$row['garantia']===true){
?>                  
                     De nuestra consideración:<br><br>

                     Mediante la presente solicitamos y autorizamos se proceda a la anulación del Seguro subrogado a favor del Banco Bisa N° <?=$poliza;?>, perteneciente al Cliente <?=$cliente_nombre;?> por motivo de <?= $row['request_mess'] ;?>.
                       <br><br> 
                     Agradeciendo su atención a la presente, nos despedimos atentamente.
<?php
				  }else{
?>					
	                 De mi consideración:<br><br>

                     Mediante la presente, Yo, <?=$cliente_nombre;?> solicito se proceda a la anulación del Seguro N° <?=$poliza;?>, por motivo de <?= $row['request_mess'] ;?>.<br><br>
                    
                     Agradeciendo su atención a la presente, me despido atentamente.

<?php    				  
				  }
?>                     
                  </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
<?php
              if((boolean)$row['garantia']===true){
?>                
                <tr><td style="text-align:center; font-weight:bold;">BANCO BISA S.A.</td></tr>
<?php
			  }
?>                
            </table>
            <br><br><br><br>
<?php
           if((boolean)$row['garantia']===true){
?>              
                <table 
                    cellpadding="0" cellspacing="0" border="0" 
                    style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                    padding-top:20px;">
                   <tr>
                    <td style="width:30%;"></td>
                    <td style="width:40%; text-align:center;"><img src="files/<?=$row['u_firma'];?>" width="150"/></td>
                    <td style="width:30%;"></td>
                   </tr>
                   <tr>
                    <td style="width:30%;"></td>
                    <td style="width:40%; text-align:center; font-weight:bold;"><?=$row['u_nombre'];?></td>
                    <td style="width:30%;"></td>
                   </tr>
                </table>   	
<?php
		   }else{
?>			
			   <table 
                    cellpadding="0" cellspacing="0" border="0" 
                    style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                    padding-top:20px;">
                   <tr>
                    <td style="width:30%;"></td>
                    <td style="width:40%; text-align:center;"><?=$cliente_nombre;?></td>
                    <td style="width:30%;"></td>
                   </tr>
                   <tr>
                    <td style="width:30%;"></td>
                    <td style="width:40%; text-align:center; font-weight:bold;"><?=$cliente_nitci;?></td>
                    <td style="width:30%;"></td>
                   </tr>
                </table>   	
<?php			 
	       }
?>                
        </div>            
        
        
      </div>
   </div>
   
<?php
	$html = ob_get_clean();
	return $html;
}
?>