<?php
function trd_formulario_uif_N($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
		
	ob_start();
?>
  <div id="container-c" style="width: 785px; height: auto; 
    border: 0px solid #0081C2; padding: 5px;">
	  <div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; 
      font-family: Arial, Helvetica, sans-serif; color: #000000; border-left: 1px solid #000;
      border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;">
<?php
       $ingreso_mensual = $link->monthly_income[$row['tipo_cliente']];
	   $estadoc = $link->status;
	   	   	   
       $vec = explode('-',$row['fecha_emision']);
	   $dia = $vec[2];
	   $mes = $vec[1];
	   $anio = $vec[0];
	   $sucursal = $row['u_depto'];
	   $cliente = $row['cl_nombre'].' '.$row['cl_paterno'].' '.$row['cl_materno'];
	   $ci = $row['cl_ci'];
	   $extension = $row['cl_extension'];
	   $pais = $row['cl_pais'];
	   $direccion = $row['cl_direccion'];
	   $vec_fn = explode('-',$row['cl_fecha_nacimiento']);
	   $dia_fn = $vec_fn[2];
	   $mes_fn = $vec_fn[1];
	   $anio_fn = $vec_fn[0];
	   $prof_ocupacion = $row['cl_desc_ocupacion'];
	   $lugar_trabajo = $row['cl_direccion_laboral'];
	   $cargo = $row['cl_cargo'];
	   
?>
        <div style="width: 775px; border: 0px solid #000; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial; font-size:80%;">
                <tr>
                  <td style="width:60%; text-align:left;">
                     <img src="<?=$url;?>img/logo-sud.jpg" width="200"/> 
                  </td>
                  <td style="width:40%; padding-right:10px;" align="right">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                          <td style="width:40%; text-align:center; border-top: 0px solid #000;
                           border-left: 1px solid #000; border-bottom: 1px solid #000; height:25px;
                           background:#d8d8d8; vertical-align:middle;">Sucursal</td>
                          <td style="width:20%; text-align:center; border-top: 0px solid #000;
                           border-left: 1px solid #000; border-bottom: 1px solid #000; height:25px;
                           background:#d8d8d8; vertical-align:middle;">Día</td>
                          <td style="width:20%; text-align:center; border-top: 0px solid #000;
                           border-left: 1px solid #000; border-bottom: 1px solid #000; height:25px;
                           background:#d8d8d8; vertical-align:middle;">Mes</td>
                          <td style="width:20%; text-align:center; border-top: 0px solid #000;
                           border-left: 1px solid #000; border-bottom: 1px solid #000; height:25px;
                           background:#d8d8d8; vertical-align:middle; border-right: 1px solid #000;">Año</td>
                        </tr>
                        <tr>
                          <td style="width:40%; text-align:center; border-left: 1px solid #000;
                           border-bottom: 1px solid #000; height:25px;"><?=$sucursal;?></td>
                          <td style="width:20%; text-align:center; border-left: 1px solid #000;
                           border-bottom: 1px solid #000; height:25px;"><?=$dia;?></td>
                          <td style="width:20%; text-align:center; border-left: 1px solid #000;
                           border-bottom: 1px solid #000; height:25px;"><?=$mes;?></td>
                          <td style="width:20%; text-align:center; border-left: 1px solid #000;
                           border-bottom: 1px solid #000; height:25px; 
                           border-right: 1px solid #000;"><?=$anio;?></td>
                        </tr>
                     </table>
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; font-weight:bold; text-align:center; padding-top:15px;" colspan="2">
                     FORMULARIO DE IDENTIFICACION DE CLIENTE Y BENEFICIARIO ECONOMICO  - PERSONAS NATURALES / PRIMAS MENORES A $US. 5,000 <br> Politica Conzoca su Cliente ART 26 D.S.24771
                  </td> 
                </tr>
            </table>     
        </div>
        
        <div style="width: 775px; border: 0px solid #FFFF00;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 65%; font-family: Arial; 
                padding-top:4px; padding-bottom:3px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px; height:25px; text-align:left;
                    border-top: 1px solid #000; border-bottom: 1px solid #000; background:#d8d8d8;
                    vertical-align:middle;" colspan="3">
                     DATOS PERSONALES Y LABORALES
                  </td>      
                </tr>
                <tr>
                  <td style="width:52%; border-bottom: 1px solid #000; height:25px; text-align:left;
                   vertical-align:middle;">
                     <b>NOMBRES Y APELLIDOS:</b>&nbsp;<?=$cliente;?>                                 
                  </td>
                  <td style="width:28%; border-bottom: 1px solid #000; height:25px; text-align:left;
                    border-left: 1px solid #000; vertical-align:middle;">
                    <b>N° DOC. DE IDENTIDAD:</b>&nbsp;<?=$ci;?>                                  
                  </td>
                  <td style="width:20%; border-bottom: 1px solid #000; height:25px; text-align:left;
                    border-left: 1px solid #000; vertical-align:middle;">
                    <b>EXTENSION:</b>&nbsp;<?=$extension;?>                                 
                  </td>
                </tr>
                <tr>
                  <td style="width:52%; border-bottom: 1px solid #000; height:25px; text-align:left;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                          <td style="width:73%; text-align:left; height:25px;
                          vertical-align:middle;"><b>NACIONALIDAD:</b>&nbsp;<?=$pais;?></td>
                          <td style="width:27%; text-align:left; border-left: 1px solid #000; height:25px;
                          vertical-align:middle;">
                             <b>FECHA DE NACIMIENTO:</b> 
                          </td> 
                         </tr> 
                     </table>                                 
                  </td>
                  <td style="width:28%; border-bottom: 1px solid #000; height:25px; text-align:left;
                    border-left: 1px solid #000;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:9%; height:25px; text-align:center;
                           background:#d8d8d8; vertical-align:middle;">DIA </td>
                           <td style="width:31%; height:25px; border-left: 1px solid #000;
                             text-align:center;"><?=$dia_fn;?></td>
                           <td style="width:9%; height:25px; border-left: 1px solid #000;
                             text-align:center; background:#d8d8d8; vertical-align:middle;">MES </td>
                           <td style="width:40%; height:25px; border-left: 1px solid #000;
                             text-align:center;"><?=$mes_fn;?></td>
                           <td style="width:11%; height:25px; border-left: 1px solid #000;
                            text-align:center; background:#d8d8d8; vertical-align:middle;">AÑO</td>
                         </tr>
                      </table>                        
                  </td>
                  <td style="width:20%; border-bottom: 1px solid #000; height:25px; text-align:center;
                    border-left: 1px solid #000;">
                      <?=$anio_fn;?>                              
                  </td>
                </tr>
                <tr>
                  <td style="width:100%; height:25px; border-bottom: 1px solid #000;" colspan="3">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:8%; height:25px; text-align:left; 
                             background:#d8d8d8; vertical-align:middle;">
                             <b>ESTADO CIVIL</b>
                           </td>
<?php
                        foreach ($estadoc as $key => $value) {
?>                           
                           <td style="width:12%; height:25px; border-left: 1px solid #000; 
                              vertical-align:middle;">
                              <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                 font-size:100%; border: 0px solid #000;">
                                 <tr>
                                    <td style="width:30%; padding-left:8px;">
                                      <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                         text-align:center; vertical-align:middle;">
<?php
                                          if ($value[0] === $row['cl_estado_civil']) {
												echo 'X';
										  }else {
												echo '&nbsp;';
										  } 
?>
                                       </div> 
                                    </td>
                                    <td style="width:70%; text-align:center;">
                                        <?=strtoupper($value[1]);?>
                                    </td>
                                 </tr>
                              </table>    
                           </td>
<?php
						}
?>                           
                           <!--
                           <td style="width:12%; height:25px; border-left: 1px solid #000;
                              vertical-align:middle;">
                              <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                 font-size:100%; border: 0px solid #000;">
                                 <tr>
                                    <td style="width:30%; padding-left:8px;">
                                      <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                         text-align:center;">
                                        
                                       </div> 
                                    </td>
                                    <td style="width:70%; text-align:center;">
                                        CASADO(A)
                                    </td>
                                 </tr>
                              </table>   
                           </td>
                           <td style="width:12%; height:25px; border-left: 1px solid #000;
                              vertical-align:middle;">
                              <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                 font-size:100%; border: 0px solid #000;">
                                 <tr>
                                    <td style="width:30%; padding-left:8px;">
                                      <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                         text-align:center;">
                                        
                                       </div> 
                                    </td>
                                    <td style="width:70%; text-align:center;">
                                        UNION LIBRE
                                    </td>
                                 </tr>
                              </table> 
                           </td>
                           <td style="width:12%; height:25px; border-left: 1px solid #000; 
                              vertical-align:middle;">
                              <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                 font-size:100%; border: 0px solid #000;">
                                 <tr>
                                    <td style="width:30%; padding-left:8px;">
                                      <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                         text-align:center;">
                                        
                                       </div> 
                                    </td>
                                    <td style="width:70%; text-align:center;">
                                        DIVORCIADO(A)
                                    </td>
                                 </tr>
                              </table> 
                           </td>
                           <td style="width:10%; height:25px; border-left: 1px solid #000;
                              vertical-align:middle;">
                              <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                 font-size:100%; border: 0px solid #000;">
                                 <tr>
                                    <td style="width:30%; padding-left:8px;">
                                      <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                         text-align:center;">
                                        
                                       </div> 
                                    </td>
                                    <td style="width:70%; text-align:center;">
                                        VIUDO(A)
                                    </td>
                                 </tr>
                              </table> 
                           </td>
                           -->
                           <td style="width:32%; height:25px; border-left: 1px solid #000; text-align:left;">
                              <b>DIRECCION DOMICILIO:</b>&nbsp;<?=$direccion;?>
                           </td>
                         </tr>
                      </table> 
                  </td>
                </tr>
                <tr>
                  <td style="width:100%; height:25px; border-bottom: 1px solid #000;" colspan="3">
                      <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:43%; text-align:left; height:25px; vertical-align:middle;">
                            <b>PROFESION/ OCUPACION:</b>&nbsp;<?=$prof_ocupacion;?>
                          </td>
                          <td style="width:32%; text-align:left; height:25px; border-left: 1px solid #000;
                             vertical-align:middle;">
                            <b>LUGAR DE TRABAJO:</b>&nbsp;<?=$lugar_trabajo;?>    
                          </td>
                          <td style="width:25%; text-align:left; height:25px; border-left: 1px solid #000;
                            vertical-align:middle;">
                            <b>CARGO:</b>&nbsp;<?=$cargo;?>
                          </td>
                        </tr>
                      </table> 
                  </td>
                </tr>
                <tr>
                  <td style="width:100%; height:25px; border-bottom: 1px solid #000;" colspan="3">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:12.5%; height:25px; text-align:left; 
                           background:#d8d8d8; vertical-align:middle;"><b>INGRESOS MENSUALES</b></td>
<?php
                   if(is_array($ingreso_mensual)){       
                        foreach ($ingreso_mensual as $key => $value) {

?>  
                           <td style="width:12.5%; height:25px; border-left: 1px solid #000;
                              vertical-align:middle;">
                              <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                 font-size:100%; border: 0px solid #000;">
                                 <tr>
                                    <td style="width:30%; padding-left:8px;">
                                      <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                         text-align:center; vertical-align:middle;">
<?php
                                         if($row['ingreso_mensual']==$key){
			                                echo 'X';					
									     }else{
										    echo '';	 
									     }
?>                                       
                                       </div> 
                                    </td>
                                    <td style="width:70%; text-align:center;">
                                        <?=$value;?>
                                    </td>
                                 </tr>
                              </table>    
                           </td>
<?php
						}
				   }
?>                           
                           <!--
                           <td style="width:12.5%; height:25px; border-left: 1px solid #000;
                              vertical-align:middle;">
                              <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                 font-size:100%; border: 0px solid #000;">
                                 <tr>
                                    <td style="width:30%; padding-left:8px;">
                                      <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                         text-align:center;">
                                        &nbsp;
                                       </div> 
                                    </td>
                                    <td style="width:70%; text-align:center;">
                                        De Bs. 2,001 a Bs. 4,000
                                    </td>
                                 </tr>
                              </table>   
                           </td>
                           <td style="width:12.5%; height:25px; border-left: 1px solid #000;
                              vertical-align:middle;">
                              <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                 font-size:100%; border: 0px solid #000;">
                                 <tr>
                                    <td style="width:30%; padding-left:8px;">
                                      <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                         text-align:center;">
                                        &nbsp;
                                       </div> 
                                    </td>
                                    <td style="width:70%; text-align:center;">
                                        De Bs. 4,001 a Bs. 8,000
                                    </td>
                                 </tr>
                              </table> 
                           </td>
                           <td style="width:12.5%; height:25px; border-left: 1px solid #000; 
                              vertical-align:middle;">
                              <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                 font-size:100%; border: 0px solid #000;">
                                 <tr>
                                    <td style="width:30%; padding-left:8px;">
                                      <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                         text-align:center;">
                                        &nbsp;
                                       </div> 
                                    </td>
                                    <td style="width:70%; text-align:center;">
                                        De Bs. 8,001 a Bs. 12,000
                                    </td>
                                 </tr>
                              </table> 
                           </td>
                           <td style="width:12.5%; height:25px; border-left: 1px solid #000;
                              vertical-align:middle;">
                              <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                 font-size:100%; border: 0px solid #000;">
                                 <tr>
                                    <td style="width:30%; padding-left:8px;">
                                      <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                         text-align:center;">
                                        &nbsp;
                                       </div> 
                                    </td>
                                    <td style="width:70%; text-align:center;">
                                        De Bs. 12,001 a Bs. 15,000
                                    </td>
                                 </tr>
                              </table> 
                           </td>
                           <td style="width:12.5%; height:25px; border-left: 1px solid #000;
                              vertical-align:middle;">
                              <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                 font-size:100%; border: 0px solid #000;">
                                 <tr>
                                    <td style="width:30%; padding-left:8px;">
                                      <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                         text-align:center;">
                                        &nbsp;
                                       </div> 
                                    </td>
                                    <td style="width:70%; text-align:center;">
                                        De Bs. 15,001 a Bs. 20,000
                                    </td>
                                 </tr>
                              </table> 
                           </td>
                           <td style="width:12.5%; height:25px; border-left: 1px solid #000;
                              vertical-align:middle;">
                              <table cellspacing="0" cellpadding="0" border="0" style="width:100%;
                                 font-size:100%; border: 0px solid #000;">
                                 <tr>
                                    <td style="width:30%; padding-left:8px;">
                                      <div style="width: 15px; height: 12px; border: 1px solid #000; 
                                         text-align:center;">
                                        &nbsp;
                                       </div> 
                                    </td>
                                    <td style="width:70%; text-align:center;">
                                        De 20,000 en adelante 
                                    </td>
                                 </tr>
                              </table> 
                           </td>
                           -->
                         </tr>
                      </table> 
                  </td>
                </tr>    
            </table>
            
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                margin-top:100px;">
               <tr>
                <td style="width:10%;"></td>
                <td style="width:80%; border-bottom: 1px solid #000;">&nbsp;</td>
                <td style="width:10%;"></td>  
               </tr>
               <tr>
                <td style="width:10%;"></td>
                <td style="width:80%; text-align:center; font-weight:bold;">
                 Firma del Declarante (Cliente)<br>
                 * El presente formulario tiene carácter de delaración jurada, firmo en conformidad de los datos contenidos en el presente documento 
                </td>
                <td style="width:10%;"></td>  
               </tr>
            </table>
            
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial; 
                margin-top:100px;">
               <tr>
                <td style="width:10%;"></td>
                <td style="width:80%; border-bottom: 1px solid #000;">&nbsp;</td>
                <td style="width:10%;"></td>  
               </tr>
               <tr>
                <td style="width:10%;"></td>
                <td style="width:80%; text-align:center; font-weight:bold;">
                 Firma y Sello del Funcionario de Sudamericana que recibe el Formulario 
                </td>
                <td style="width:10%;"></td>  
               </tr>
               <tr><td style="width:100%; height:150px;" colspan="3"></td></tr>
            </table>      	
        </div>            
 
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