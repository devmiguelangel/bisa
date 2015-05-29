<?php
function trd_formulario_crt($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
		
	ob_start();
?>
  <div id="container-c" style="width: 785px; height: auto; border: 0px solid #0081C2; padding: 5px;">
	  <div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; 
        font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php
     if($row['tipo_cliente']=='N'){
		 $cliente_nombre = $row['cl_nombre'].' '.$row['cl_paterno'].' '.$row['cl_materno'];
		 $cliente_nitci = $row['cl_ci'].$row['cl_complemento'].' '.$row['cl_extension'];
	 }elseif($row['tipo_cliente']=='J'){
		 $cliente_nombre = $row['cl_razon_social'];
		 $cliente_nitci = $row['cl_ci'];
	 }
	 if($row['fecha_emision']!=='0000-00-00'){
	    $fecha_em = $row['fecha_emision'];
		$vec_f = explode('-',$fecha_em);
		$digi = substr($vec_f[0], -2);
		$mes = $vec_f[1];
	 }else{
	    /*$fecha = new DateTime();
        $fecha_em = $fecha->format('Y-m-d');
		$mes = $fecha->format('m');
		$anio = $fecha->format('Y');
		$digi = substr($anio, -2);*/
		$fecha_em = $row['fecha_creacion'];
		$vec_f = explode('-',$fecha_em);
		$digi = substr($vec_f[0], -2);
		$mes = $vec_f[1];
	 }
?>
        <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                  <td style="width:100%; text-align:right;">
                     La Paz, <?=get_date_format_crt_trd($fecha_em)?><br><br>
                     SUD/<?=$mes;?>/<?=$digi;?>

                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; font-weight:bold; text-align:left; font-size: 80%;">
                     Señor<br>
                     <?=$cliente_nombre;?><br>
                     Presente.-<br><br>
                     Ref.:	Póliza Multiriesgo N° <?=$row['no_emision'];?>
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
                     A tiempo de agradecerle(s) por la confianza depositada en SUDAMERICANA S.R.L., le(s) informamo(s) 
                     que cumpliendo con sus instrucciones, procedimos a solicitar a la compañía BISA SEGUROS S.A la 
                     emisión de la Póliza, de acuerdo a Términos y Condiciones previamente aprobados por Usted(es).
                     <br><br>
                     Remitimos la documentación original según detalle líneas abajo, de acuerdo a lo establecido en el
                     Art. 1013 Discrepancias en las Pólizas, del Código de Comercio, su contenido se considerará 
                     aceptado.
                  </td>      
                </tr>
            </table>
            
            <table 
              cellpadding="0" cellspacing="0" border="0" 
              style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
              <tr>
                  <td style="width: 50%; text-align: justify; padding: 5px; border-right: 1px solid #000;
                      border-top: 1px solid #000; border-left: 1px solid #000; border-bottom: 1px solid #000;"
                      valign="top">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                      <tr>
                        <td style="width:2%; font-weight:bold;" valign="top">1.&nbsp;</td>
                        <td style="width:98%;">
                           <b>PÓLIZA <?=$row['no_emision'];?></b>
                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                              <tr>
                                <td style="width:2%; font-weight:bold; padding-top:10px;" valign="top">&bull;</td>
                                <td style="width:98%; padding-top:10px;">
                                   <b>Condiciones Particulares que estipulan:</b>
                                   <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Compañía de Seguros
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Número de Póliza
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Ramo de Seguro
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Materia del Seguro
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Coberturas y Límites Asegurados
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Cláusulas Adicionales
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Condiciones Especiales
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Exclusiones Generales
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Prima
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Vigencia
                                        </td>
                                      </tr>
                                      <tr>
                                        <td style="width:2%; font-weight:bold;">o</td>
                                        <td style="width:98%;">
                                           	Forma de Pago
                                        </td>
                                      </tr>
                                   </table>      
                                </td>
                              </tr>
                              <tr>
                                <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                                <td style="width:98%;">
                                   <b>Condicionados Generales</b>
                                </td>
                              </tr>
                              <tr>
                                <td style="width:2%; font-weight:bold;">&bull;</td>
                                <td style="width:98%;">
                                   <b>Textos de Clausulas Adicionales y/o Anexos</b>
                                </td>
                              </tr>
                           </table> 
                        </td>       
                      </tr>
                      <tr>
                        <td style="width:2%; font-weight:bold; padding-top:10px;" valign="top">2.&nbsp;</td>
                        <td style="width:98%; text-align:justify; font-weight:bold; padding-top:10px;">
                           PAGOS DE PRIMAS<br><br>
                           de acuerdo a lo estipulado en el Art. 58 Inciso d) de la Ley de Seguros 1883, de no 
                           realizar el pago dentro de los plazos establecidos, la vigencia y cobertura de su Póliza 
                           quedan suspendidas, como consecuencia, si ocurriera un siniestro mientras las primas se 
                           encuentren impagas, el mismo no estará cubierto.
                        </td>
                      </tr>
                      <tr>
                        <td style="width:2%; font-weight:bold; padding-top:10px;" valign="top">3.&nbsp;</td>
                        <td style="width:98%; text-align:justify; padding-top:10px;">
                           <b>AVISO DE VENCIMIENTO:</b> 
                        </td>
                      </tr>
                      <tr>
                         <td style="width:2%; font-weight:bold; padding-top:10px;" valign="top">4.&nbsp;</td>
                         <td style="width:98%; padding-top:10px;">
                           <b>NOTA ACLARATORIA:</b>
                           <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                              <tr>
                                <td style="width:2%; font-weight:bold; padding-top:10px;" valign="top">&bull;</td>
                                <td style="width:98%; padding-top:10px;">
                                    Se aclara que esta póliza no se renovará posteriormente a la cancelación total de 
                                    la operación crediticia del asegurado con el contratante, de acuerdo al monto 
                                    subrogado y declarado en la póliza
                                </td>
                              </tr>
                              <tr>
                                <td style="width:2%; font-weight:bold;" valign="top">&bull;</td>
                                <td style="width:98%;">
                                    Se aclara que la vigencia de la póliza podrá terminar en forma anticipada, cuando 
                                    el Asegurado realice el pago anticipado del monto total de su operación crediticia
                                    adeudada al Contratante. Sin embargo, si la prima fue pagada al contado, la póliza
                                    se mantendrá vigente hasta su finalización.
                                </td>
                              </tr>
                           </table>
                         </td>
                       </tr>
                      <tr>
                        <td colspan="2" style="width:100%; font-weight:bold; text-align:justify;">
                           <u>En tal sentido al ser una póliza que no cuenta con renovación posterior al pago de la deuda 
                        con el contratante y siendo este el tomador de la póliza, Sudamericana no enviara aviso de 
                        vencimiento para la renovación o finalización de vigencia.</u>    
                        </td>
                      </tr>
                     </table>
                  </td>
                  <td style="width: 50%; text-align: justify; padding: 5px; border-top: 1px solid #000;
                     border-right: 1px solid #000; border-bottom: 1px solid #000;" valign="top">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                       <tr>
                         <td style="width:2%; font-weight:bold;" valign="top">5.&nbsp;</td>
                         <td style="width:98%;" valign="top">
                           <b>ASPECTOS RELEVANTES DE LA NORMA VIGENTE:  (CÓDIGO DE COMERCIO)</b><br><br>
                           <b>Art. 1000.- (OBLIGACIÓN DE MANTENER EL ESTADO DE RIESGO).</b> El asegurado está obligado
                            a mantener el estado del riesgo, en tal virtud, debe comunicar por escrito al asegurador 
                            las agravaciones substanciales del riesgo debidas a hechos propios, antes de su ejecución 
                            y los ajenos a su voluntad, dentro de los ocho días siguientes al momento en que los 
                            conozca. Si se omite la comunicación de estos hechos, cesan en lo futuro las obligaciones 
                            del asegurador, correspondiendo al mismo probar la agravación del riesgo. Comunicada la 
                            agravación del riesgo dentro de los términos previstos en este artículo, el asegurador 
                            puede rescindir el contrato o exigir el reajuste a que haya lugar en el importe de la 
                            prima, dentro de los quince días siguientes.<br><br>
                            
                            <b>Art. 1004.- (DOLO O MALA FE).</b> El dolo o mala fe del asegurado en la agravación del 
                            riesgo hace nulo el contrato de seguro en los términos del artículo 999. (Las 
                            declaraciones falsas o reticentes hechas con dolo o mala fe hacen nulo el contrato de 
                            seguro. En este caso el asegurado no tendrá derecho a la devolución de las primas pagadas)
                            <br><br>
                            Si hubiera necesidad de aclarar cualquier aspecto relacionado con esta documentación, nos 
                            ponemos a vuestra entera disposición y les reiteramos nuestras consideraciones más 
                            distinguidas.
                         </td> 
                       </tr>
                     </table>     
                  </td>   
              </tr>
            </table>
            <div style="font-size: 80%; text-align:justify; margin-top:10px; margin-bottom:35px;">  
               <b>Procedimientos en Caso de Siniestro:</b> El asegurado o beneficiario, tan pronto y a más tardar dentro de los tres (3) días calendario de tener conocimiento del siniestro, deben comunicar tal hecho al asegurador, salvo fuerza mayor o impedimento justificado, de acuerdo a lo que estipulan las condiciones de la póliza. El asegurador debe pronunciarse sobre el derecho del asegurado o beneficiario dentro de los 15 días calendario siguientes de recibida la información y evidencia requerida requeridas precedentemente, dicho plazo no corre termino hasta que se presente la totalidad de la información y evidencias requeridas de acuerdo a las condiciones de la póliza, el asegurador debe pagar a obligación dentro de los treinta (30) días siguientes.<br>
<b>Sudamericana S.R.L, se pone a su total disposición para aclarar cualquier aspecto adicional que necesite respecto a la documentación entregada, como para la atención y asesoramiento de siniestros que afecten las coberturas mencionadas en la presente nota, para lo cual ponemos a su disposición los numero de nuestras oficinas que se encuentran al pie de página.</b>
            </div>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:20px;">
               <tr>
                <td style="width:14%; text-align:left;">FIRMA CLIENTE</td>
                <td style="width:30%;">&nbsp;
                  
                </td>
                <td style="width:25%;">FIRMA SUDAMERICANA (ESCANEADA)</td>
                                
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

function get_date_format_crt_trd($fecha){
	$date = date_create($fecha);
	
	$day = date_format($date, 'd');
	$month = date_format($date, 'F');
	$year = date_format($date, 'Y');
	
	return get_month_es_crt_trd($month).' '.$day.' de '.$year;
}

function get_month_es_crt_trd($month){
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