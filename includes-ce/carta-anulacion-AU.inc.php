<?php
function carta_anulacion_au($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
		
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
                  <td style="width:100%; text-align:right;">
                     La Paz, 12-06-2015

                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; font-weight:bold; text-align:left; font-size: 80%;">
                     Señor<br>
                     <?=$row['u_nombre'];?><br>
                     Presente.-<br><br>
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
                  <td style="width:100%; padding-bottom:4px; text-align:justify;">
                     Lorem ipsum dolor sit amet, consectetur elit. Viva mus lacus orci, convallis cursus rutrum cursus, facilisis convallis ante. Nunc non imperdiet purus. Lorem ipsum dolor sit amet, consectetur elit. Viva mus lacus orci, convallis cursus rutrum cursus, facilisis convallis ante. Nunc non imperdiet purus. .
                     <br><br>
                    Lorem ipsum dolor sit amet, consectetur elit. Viva mus lacus orci, convallis cursus rutrum cursus, facilisis convallis ante. Nunc non imperdiet purus. Lorem ipsum dolor sit amet, consectetur elit. Viva mus lacus orci, convallis cursus rutrum cursus, facilisis convallis ante. Nunc non imperdiet purus. 
                  </td>      
                </tr>
            </table>
            
            
            <div style="text-align:justify; margin-top:10px; margin-bottom:35px;">  
               Lorem ipsum dolor sit amet, consectetur elit. Viva mus lacus orci, convallis cursus rutrum cursus, facilisis convallis ante. Nunc non imperdiet purus. Lorem ipsum dolor sit amet, consectetur elit. Viva mus lacus orci, convallis cursus rutrum cursus, facilisis convallis ante. Nunc non imperdiet purus. Lorem ipsum dolor sit amet, consectetur elit. Viva mus lacus orci, convallis cursus rutrum cursus, facilisis convallis ante. Nunc non imperdiet purus. <br><br>
Lorem ipsum dolor sit amet, consectetur elit. Viva mus lacus orci, convallis cursus rutrum cursus, facilisis convallis ante. Nunc non imperdiet purus.Lorem ipsum dolor sit amet, consectetur elit. Viva mus lacus orci, convallis cursus rutrum cursus, facilisis convallis ante. Nunc non imperdiet purus. Lorem ipsum dolor sit amet, consectetur elit. Viva mus lacus orci, convallis cursus rutrum cursus, facilisis convallis ante. Nunc non imperdiet purus.  
            </div>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:20px;">
               <tr>
                <td style="width:14%; text-align:left; vertical-align:middle;">FIRMA USUARIO</td>
                <td style="width:30%; vertical-align:middle;">
                  <img src="files/<?=$row['u_firma']?>" width="150"/>
                </td>
                <td style="width:25%; vertical-align:middle;">FIRMA SUDAMERICANA (ESCANEADA)</td>
                                
                <td style="width:33%;">&nbsp;
                  
                </td> 
               </tr>
            </table>   	
        </div>            
        
        
      </div>
   </div>
   
<?php
	$html = ob_get_clean();
	return $html;
}
?>