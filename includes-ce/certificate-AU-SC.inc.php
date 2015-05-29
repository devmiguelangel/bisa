<?php
function au_sc_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '') {
	
	
	ob_start();
?>
  <div id="container-c" style="width: 785px; height: auto; 
    border: 0px solid #0081C2; padding: 5px;">
	  <div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; 
      font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php         
   $j = 1;
   $num_titulares=$rsDt->num_rows;
   	  	  			
   while($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)){
	   
	   if($row['tipo_cliente']==='empresa'){
		   $razon_social = $row['razon_social'];
		   $actividad = $row['actividad'];
		   $ejecutivo = $row['ejecutivo'];
		   $cargo = $row['cargo'];
		   $nit_ci = $row['nit'];
		   $cliente = $razon_social;
		   
		   $ap_paterno = '';
		   $ap_materno = '';
		   $nombre = '';
		   $fono_celular = '';
		   $fono_domicilio = $row['telefono_domicilio'];
		   
	   }elseif($row['tipo_cliente']==='titular'){
		   $ap_paterno = $row['paterno'];
		   $ap_materno = $row['materno'];
		   $nombre = $row['nombre'];
		   $fono_celular = $row['telefono_celular'];
		   $fono_domicilio = $row['telefono_domicilio'];
		   $nit_ci = $row['ci'];
		   $cliente = $row['nombre'].' '.$row['paterno'].' '.$row['materno'];
		   
		   $razon_social = '';
		   $actividad = '';
		   $ejecutivo = '';
		   $cargo = '';
	   }
	   
	   $data = $link->getTasaAu($row['idef'], $row['id_cotizacion']);
	   if (count($data) > 0) {
			foreach ($data as $key => $vh) {
				if ($vh['v_prima'] < $vh['v_prima_minima']) {
					$vh['v_prima'] = $vh['v_prima_minima'];
				}

			}
		}
		   
?>         
         <div style="width: 775px; border: 0px solid #FFFF00; text-align:center;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-family: Arial;">
                <tr>
                  <td style="width:20%;">&nbsp;</td>
                  <td style="width:60%;">
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                        <tr>
                          <td style="width:100%; font-size:70%; text-align:center; font-weight:bold;
                            border: 0px solid #FFFF00;">
                            FORMULARIO DE SOLICITUD DE SEGURO DE AUTOMOTOR<br>
                            PARA ENTIDADES FINANCIERAS
                          </td>
                        </tr>
                        <tr>
                          <td style="width:100%; font-size:60%; text-align:center; font-weight:bold;">
                            CODIGO APS No.: 109-910502-2007 12 311 3002<br>
                            R.A.  APS/DS/No. 395-2012
                          </td>
                        </tr>
                     </table>
                  </td>
                  <td style="width:20%; text-align:right;">
                     <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" height="60"/>
                  </td>
              </tr>
              <tr>
                <td style="width:100%; text-align:right; font-size:65%; padding-right:12px; padding-top:3px;" colspan="3">
                 Solicitud No <?=$row['no_cotizacion'];?>
                </td>
              </tr>
            </table>      
         </div>
         <br/>
         <div style="width: 775px; border: 0px solid #FFFF00;">
             <span style="font-weight:bold; font-size:80%;">
               INFORMACION GENERAL:
             </span>
             <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-bottom:5px;">
                <tr> 
                  <td style="width:100%; padding-bottom:4px;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                                  <tr>
                                    <td style="width:25%; border-top: 1px solid #333; border-left: 1px solid #333;
                                      border-bottom: 1px solid #333; background:#d8d8d8;">
                                      Nombre o Razón Social del Solicitante: </td>
                                    <td style="border: 1px solid #333; width:75%;">&nbsp;
                                        <?=$razon_social;?>
                                    </td>
                                  </tr>
                               </table>
                            </td>      
                          </tr>
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:17%; border: 1px solid #333; background:#d8d8d8;">
                                      Nombre Persona  Particular 
                                    </td>
                                    <td style="width:27%; border-top: 1px solid #333; 
                                      border-bottom: 1px solid #333; text-align:center;">
                                      <?=$ap_paterno;?>                                     
                                    </td>
                                    <td style="width:28%; border-top: 1px solid #333; border-bottom: 1px solid #333;
                                      border-left: 1px solid #333; text-align:center;">
                                      <?=$ap_materno;?>
                                    </td>
                                    <td style="width:28%; border-top: 1px solid #333; border-right: 1px solid #333;
                                      border-bottom: 1px solid #333; border-left: 1px solid #333; 
                                      text-align:center;">
                                      <?=$nombre;?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="width:17%;">
                                    </td>
                                    <td style="width:27%; text-align:center;">
                                     APELLIDO PATERNO
                                    </td>
                                    <td style="width:28%; text-align:center;">
                                     APELLIDO MATERNO
                                    </td>
                                    <td style="width:28%; text-align:center;">
                                     NOMBRES
                                    </td>
                                  </tr>
                               </table>                                  
                            </td>
                          </tr>
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                                  <tr>
                                    <td style="width:17%; border-top: 1px solid #333; border-left: 1px solid #333;
                                      border-bottom: 1px solid #333; background:#d8d8d8;">
                                      Domicilio Legal: </td>
                                    <td style="border: 1px solid #333; width:83%;">&nbsp;
                                        <?=$row['direccion_domicilio'];?>
                                    </td>
                                  </tr>
                               </table>
                            </td>      
                          </tr>
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                                  <tr>
                                    <td style="width:17%; border-top: 1px solid #333; border-left: 1px solid #333;
                                      border-bottom: 1px solid #333; background:#d8d8d8;">
                                      Dirección Laboral: </td>
                                    <td style="border: 1px solid #333; width:83%;">&nbsp;
                                        <?=$row['direccion_laboral'];?>
                                    </td>
                                  </tr>
                               </table>
                            </td>      
                          </tr>
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                                  <tr>
                                    <td style="width:11%; border: 1px solid #333; background:#d8d8d8;
                                      text-align:left;">
                                      NIT/C.I.: 
                                    </td>
                                    <td style="border-top: 1px solid #333; border-right: 1px solid #333; 
                                      border-bottom: 1px solid #333; width:39%;">&nbsp;
                                        <?=$nit_ci;?>
                                    </td>
                                    <td style="border-top: 1px solid #333; border-right: 1px solid #333; width:10%;
                                      border-bottom: 1px solid #333; background:#d8d8d8;">
                                      Email: 
                                    </td>
                                    <td style="border-top: 1px solid #333; border-right: 1px solid #333; width:40%;
                                      border-bottom: 1px solid #333;">&nbsp;
                                        <?=$row['email'];?>
                                    </td>
                                  </tr>
                               </table>
                            </td>      
                          </tr>
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:10%; border:1px solid #333; background:#d8d8d8;
                                      text-align:left;">
                                      Teléfono Dom: 
                                    </td>
                                    <td style="width:24%; border-top:1px solid #333; border-right:1px solid #333;
                                      border-bottom:1px solid #333;">&nbsp;
                                        <?=$fono_domicilio;?>
                                    </td>
                                    <td style="width:10%; border-top:1px solid #333; border-right:1px solid #333;
                                      border-bottom:1px solid #333; background:#d8d8d8;">
                                      Telefono Of. 
                                    </td>
                                    <td style="width:24%; border-top:1px solid #333; border-right:1px solid #333;
                                      border-bottom:1px solid #333;">&nbsp;
                                        <?=$row['telefono_oficina'];?>
                                    </td>
                                    <td style="width:8%; border-top:1px solid #333; border-right:1px solid #333;
                                      border-bottom:1px solid #333; background:#d8d8d8;">
                                      Celular: 
                                    </td>
                                    <td style="width:24%; border-top:1px solid #333; border-right:1px solid #333;
                                      border-bottom:1px solid #333;">&nbsp;
                                        <?=$fono_celular;?>
                                    </td> 
                                  </tr>
                                </table> 
                            </td>              
                          </tr>   
                       </table>
                     <span style="font-weight:bold;">
                      LLENAR SOLO EN CASO DE SER PERSONA JURIDICA
                     </span>
                     <table 
                          cellpadding="0" cellspacing="0" border="0" 
                          style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                          padding-bottom:5px;">    
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                                  <tr>
                                    <td style="width:20%; border-top: 1px solid #333; border-left: 1px solid #333;
                                      border-bottom: 1px solid #333; background:#d8d8d8;">
                                      Actividad y/o Giro del Negocio: </td>
                                    <td style="border: 1px solid #333; width:80%;">&nbsp;
                                        <?=$actividad;?>
                                    </td>
                                  </tr>
                               </table>
                            </td>
                          </tr>
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                                  <tr>
                                    <td style="width:20%; border-top: 1px solid #333; border-left: 1px solid #333;
                                      border-bottom: 1px solid #333; background:#d8d8d8;">
                                      Principal Ejecutivo: </td>
                                    <td style="border: 1px solid #333; width:80%;">&nbsp;
                                        <?=$ejecutivo;?>
                                    </td>
                                  </tr>
                               </table>
                            </td>      
                          </tr>
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                   <tr>
                                     <td style="width: 7%; border: 1px solid #333; background:#d8d8d8;">Cargo: </td>
                                     <td style="width: 65%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$cargo;?>
                                     </td>
                                     <td style="width: 8%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Teléfono: 
                                     </td>
                                     <td style="width: 20%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">
                                       <?=$row['telefono_oficina'];?>
                                     </td>
                                   </tr>
                                </table> 
                            </td> 
                          </tr>
                       </table>
                     <span style="font-weight:bold;">
                        DATOS DEL VEHICULO
                     </span>
                     <table 
                          cellpadding="0" cellspacing="0" border="0" 
                          style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                          padding-top:0px;">
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                  <td style="width:12%; border: 1px solid #333; background:#d8d8d8;">Tipo de Vehiculo: </td>
                                  <td style="width:34%; border-top: 1px solid #333; border-right: 1px solid #333;
                                     border-bottom: 1px solid #333;">&nbsp;
                                     <?=$rowDt['vehiculo'];?>
                                  </td>
                                  <td style="width:7%; border-top: 1px solid #333; border-right: 1px solid #333;
                                     border-bottom: 1px solid #333; background:#d8d8d8;">
                                     Marca:
                                  </td> 
                                  <td style="width:20%; border-top: 1px solid #333; border-right: 1px solid #333;
                                     border-bottom: 1px solid #333;">&nbsp;
                                     <?=$rowDt['marca'];?>
                                  </td> 
                                  <td style="width:7%; border-top: 1px solid #333; border-right: 1px solid #333;
                                     border-bottom: 1px solid #333; background:#d8d8d8;">
                                     Motor
                                  </td> 
                                  <td style="width:20%; border-top: 1px solid #333; border-right: 1px solid #333;
                                     border-bottom: 1px solid #333;">&nbsp;
                                     <?=$rowDt['motor'];?>
                                  </td>  
                                 </tr> 
                               </table>                                 
                            </td>
                          </tr>
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                   <tr>
                                    <td style="width:12%; border: 1px solid #333; background:#d8d8d8;">Traccion: </td>
                                    <td style="width:24%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$rowDt['traccion'];?>
                                    </td>
                                    <td style="width:7%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Color:
                                    </td> 
                                    <td style="width:20%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$rowDt['color'];?>
                                    </td> 
                                    <td style="width:7%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Modelo:
                                    </td> 
                                    <td style="width:30%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$rowDt['modelo'];?>
                                    </td>  
                                   </tr> 
                                </table>
                            </td>       
                          </tr>
                          <tr> 
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                   <tr>
                                    <td style="width:12%; border: 1px solid #333; background:#d8d8d8;">Placa: </td>
                                    <td style="width:24%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$rowDt['placa'];?>
                                    </td>
                                    <td style="width:8%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Cilindrada:
                                    </td> 
                                    <td style="width:19%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$rowDt['cilindrada'];?>
                                    </td> 
                                    <td style="width:7%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Chasis:
                                    </td> 
                                    <td style="width:30%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$rowDt['chasis'];?>
                                    </td>  
                                   </tr> 
                                </table>
                            </td>       
                          </tr>
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:11%; border: 1px solid #333; background:#d8d8d8;">
                                       Año Comercial: 
                                     </td>
                                    <td style="width:15%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                        <?=$rowDt['anio'];?>
                                    </td>
<?php
                               if((boolean)$row['garantia']===false){//no subrogado
?>                                    
                                    <td style="width:6%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                        Uso:
                                    </td>
                                    <td style="width:8%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">
                                        Particular
                                    </td>
                                    <td style="width:60%; text-align:left;">
                                        (Particular/Publico)
                                    </td>
<?php
							   }else{// subrogado
?>                                   
                                    <td style="width:74%;" colspan="3">
                                        &nbsp;
                                    </td>
<?php
							   }
?>                                    
                                  </tr>
                                </table>                                  
                            </td>
                          </tr>
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:13%; border: 1px solid #333; background:#d8d8d8;">
                                       Suma Asegurada: 
                                    </td>
                                    <td style="width:30%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                        <?=number_format($rowDt['valor_asegurado'], 2, '.', ',');?>
                                    </td>
                                    <td style="width:15%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                        Ciudad de Circulacion:
                                    </td>
                                    <td style="width:42%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                        <?=$rowDt['plaza'];?>
                                    </td>
                                  </tr>
                                </table>                                  
                            </td>
                          </tr>
                          <tr>
                            <td style="width:100%; padding-bottom:1px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:13%; border: 1px solid #333;
                                      text-align:left; font-weight:bold;">
                                      VIGENCIA: 
                                    </td>
                                    <td style="width:15%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Desde horas 12:01 PM 
                                    </td>
                                    <td style="width:14%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">&nbsp;
                                       
                                    </td>
                                    <td style="width:15%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; text-align:center;">
                                       <?=$row['ini_vigencia'];?>
                                    </td>
                                    <td style="width:15%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Hasta horas 12:01 PM 
                                    </td>
                                    <td style="width:14%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">&nbsp;
                                       
                                    </td>
                                    <td style="width:14%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; text-align:center;">
                                       <?=$row['fin_vigencia'];?>  
                                    </td>   
                                  </tr>
                                  <tr>
                                    <td style="width:13%;"></td>
                                    <td style="width:15%;"></td>
                                    <td style="width:14%;"></td>
                                    <td style="width:15%; text-align:center;">
                                       dd/mm/aa
                                    </td>
                                    <td style="width:15%;"></td>
                                    <td style="width:14%;"></td>
                                    <td style="width:14%; text-align:center;">
                                       dd/mm/aa
                                    </td>   
                                  </tr>
                                </table> 
                            </td>              
                          </tr>
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                   <tr>
                                     <td style="width:14%; border: 1px solid #333; background:#d8d8d8;">
                                       Período del Seguro: 
                                     </td>
                                     <td style="width:23%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">
                                       Anual con renovación automática
                                     </td>
                                     <td style="width:12%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Forma de Pago: 
                                     </td>
                                     <td style="width:20%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">&nbsp;
                                       <?=$row['forma_pago'];?>
                                     </td>
                                     <td style="width:8%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333; background:#d8d8d8;">
                                       Moneda: 
                                     </td>
                                     <td style="width:23%; border-top: 1px solid #333; border-right: 1px solid #333;
                                       border-bottom: 1px solid #333;">
                                        &nbsp;Dolar
                                     </td>
                                   </tr>
                                </table> 
                            </td> 
                          </tr>    
                       </table>
                     <br/>
                     <table 
                          cellpadding="0" cellspacing="0" border="0" 
                          style="width:100%; height:auto; font-size:100%; font-family:Arial; 
                          padding-top:0px; padding-bottom:6px;">
                          <tr>
                            <td style="width:100%; padding-bottom:4px;">
                               <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                   <tr>
                                      <td style="width:16%; border: 1px solid #333; background:#d8d8d8;">
                                         PRIMA TOTAL ANUAL: 
                                      </td>
                                      <td style="width:33%; border-top:1px solid #333; border-right:1px solid #333;
                                         border-bottom: 1px solid #333;">&nbsp;
                                         <?=number_format($vh['v_prima'], 2, '.', ',');?>
                                      </td>
                                      <td style="width:18%; border-top:1px solid #333; border-right:1px solid #333;
                                         border-bottom: 1px solid #333; background:#d8d8d8;">
                                         PRIMA TOTAL MENSUAL:
                                      </td>
                                      <td style="width:33%; border-top:1px solid #333; border-right:1px solid #333;
                                         border-bottom: 1px solid #333;">&nbsp;
                                         <?php 
											if($row['code_fpago']==='CR'){
											  echo number_format(($vh['v_prima']/12), 2, '.', ',');
											}
										  ?>
                                      </td>   
                                   </tr>
                               </table>
                            </td> 
                          </tr>
                       </table>
                     <span style="font-weight:bold;">
                       Requiere este Seguro subrogación de Derechos:   
                     </span>
                     <table 
                          cellpadding="0" cellspacing="0" border="0" 
                          style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                          padding-top:0px; padding-bottom:3px;">
                          <tr>
                            <td style="width:18%; border:1px solid #333; background:#d8d8d8;">
                               Detallar Nombres y montos:
                            </td>
                            <td style="width:82%; border-top:1px solid #333; border-right:1px solid #333;
                               border-bottom: 1px solid #333;">&nbsp;
                               
                            </td>
                          </tr>  
                       </table><br>
                     <table 
                        cellpadding="0" cellspacing="0" border="0" 
                        style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                        padding-top:0px; padding-bottom:3px;">
                        <tr>
                          <td style="width:100%; border-top:1px solid #333;" colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                          <td style="width:100%;" colspan="2">
                             COBERTURAS Y VALORES ASEGURADOS
                          </td>
                        </tr>  
                        <tr>
                          <td style="width:50%; font-size:100%; text-align: justify; padding-right:5px; 
                          border:0px solid #333;" valign="top">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Responsabilidad Civil Extracontractual </td>
                                   <td style="width:40%;">$us. 25.000,00</td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Responsabilidad Civil Consecuencial </td>
                                   <td style="width:40%;">$us. 3.000,00</td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Responsabilidad Civil a Ocupantes, p/ocupante:</td>
                                   <td style="width:40%;">$us. 3.000,00</td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Casco </td>
                                   <td style="width:40%;">$us. <?=number_format($rowDt['valor_asegurado'], 2, '.', ',');?></td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Accesorios</td>
                                   <td style="width:40%;">No Incluye</td>
                                 </tr>
                             </table>    
                          </td>
                          <td style="width:50%; font-size:100%; text-align: justify; padding:5px; 
                          border:0px solid #333;" valign="top">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Muerte Accidental, p/ocupante: </td>
                                   <td style="width:40%;">$us. 5.000,00</td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Invalidez Permanente, p/ocupante:</td>
                                   <td style="width:40%;">$us. 5.000,00</td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Gastos Médicos, p/ocupante:</td>
                                   <td style="width:40%;">$us. 1.000,00</td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;">Gastos de Sepelio, p/ocupante:</td>
                                   <td style="width:40%;">$us. 1.000,00</td>
                                 </tr>
                                 <tr>
                                   <td style="width:60%; font-weight:bold;"></td>
                                   <td style="width:40%;"></td>
                                 </tr>
                             </table>  
                          </td>
                        </tr>
                        <tr>
                          <td style="width:100%; border-bottom:1px solid #333;" colspan="2">&nbsp;</td>
                        </tr>
                    </table>         
                  </td>
                </tr>
             </table>
             <div style="font-size: 70%; text-align:center; margin-top:120px;">  
                • Av. Arce Nº 2631, Edificio Multicine Piso 14 • Teléfono: (591-2) 217 7000 • Fax: (591-2) 214 1928 • La Paz – Bolivia.<br> 
• Autorizado por Resolución Administrativa Nº 158 del 7 de julio de 1999 de la Superintendencia de Pensiones Valores y Seguros.   
             </div>        
         </div>
         
         <page><div style="page-break-before: always;">&nbsp;</div></page>
         
         <div style="width: 775px; border: 0px solid #FFFF00;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                <tr><td style="width:100%; text-align:right;" colspan="2">
                 <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" height="60"/> 
                </td></tr>
                <tr><td style="width:100%; font-weight:bold; text-align:left;" colspan="2">
                 COBERTURAS
                </td></tr>
                <tr>
                  <td style="width:50%; font-size:100%; text-align: justify; padding-right:5px; 
                  border:0px solid #333;" valign="top">
                    <b>Coberturas</b><br>
                    <b>Sección I</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Responsabilidad Civil Extracontractual</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Responsabilidad Civil Consecuencial</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                    </table>
                    <b>Sección II</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Perdida Total por Robo al 100%</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Perdida Total por Robo al 80%</td>
                          <td style="width:10%; text-align:right;">No Cubre</td>
                        </tr>
                    </table>
                    <b>Sección III</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Perdida Total por Accidente al 100%</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                    </table>
                    <b>Sección IV</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Daños Propios, VEH. LIVIANOS c/Franquicia Deducible</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">$us 50.- hasta $us. 50.000.- de valor de casco</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">y $us. 200.- con valor de casco mayor a $us. 50.000.-</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Daños Propios, VEH. PESADOS c/Franquicia Deducible</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">$us 150.- hasta $us. 50.000.- de valor de casco </td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">y $us. 300.- con valor de casco mayor a $us. 50.000.-</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                    </table>
                    <b>Sección V</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Conmoción Civil, Huelgas, Daño Malicioso, Sabotaje,</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Vandalismo y Terrorismo, VEH. LIVIANOS c/Franquicia</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Deducible $us 50.- hasta $us. 50.000.- de valor de</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">casco y $us. 200.- con valor de casco mayor</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">a $us 50.000.-</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Conmoción Civil, Huelgas, Daño Malicioso, Sabotaje,</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Vandalismo y Terrorismo, VEH. PESADOS c/Franquicia</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Deducible $us 150.- hasta $us. 50.000.- de valor de</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">casco y $us. 300.- con valor de casco mayor</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">a $us 50.000.-</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                    </table>
                    <b>Sección VI</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Robo Parcial al 80% </td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                    </table>
                    <b>Sección VII</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Muerte Accidental</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Invalidez Permanente (Total y Parcial)</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Gastos Médicos</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                    </table> 
                  </td>
                  
                  <td style="width:50%; font-size:100%; text-align: justify; padding-right:5px; 
                  border:0px solid #333;" valign="top">
                    <b>Coberturas Adicionales</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo de Ausencia de Control para el Seguro de Automotores para empresas		
		
</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo de Daños a Causa de Riesgos de la Naturaleza c/Franquicia deducible estipulada en la Sección IV y V</td>
                          <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Clausula de Circulación en Vías No Habilitadas para el Tránsito Vehicular</td>
                          <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo de Extraterritorialidad (Por la vigencia de la Poliza)</td>
                          <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo para Accesorio de vehículos</td>
                          <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo de Gastos de Sepelio para Accidentes </td>
                          <td style="width:10%; text-align:right;" valign="top"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">personales</td>
                          <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Clausula de Autoreemplazo (excluye motocicletas/quatracks y vehículos pesados)</td>
                          <td style="width:10%; text-align:right;" valign="top">Cubre</td>
                        </tr>
                    </table>
                    <b>Cláusulas y Anexos Adicionales</b>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Cláusula de Rehabilitación Automática de la suma Asegurada</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo para Restringir de Copia Legalizada</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Cláusula de Adelanto del 50% del Siniestro</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Cláusula de Elegibilidad de Ajustadores</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo para Vehículos con antigüedad  mayor</td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">a 15 años y para vehículos transformados</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo para Robo de Llantas, Partes, Equipos de Música y otras piezas</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo de Elegibilidad de Talleres</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Cláusula de Ampliación de Aviso de Siniestro a Diez Días</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo de Asistencia al Vehículo (excepto </td>
                          <td style="width:10%; text-align:right;"></td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;"> motocicletas/quadratracks y vehículos pesados)</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo de Beneficio de Asistencia Jurídica</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Cláusula de Rescisión de Contrato a Prorrata</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Cláusula de cobertura para Flete Aéreo ( hasta $us. 500.-)</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Anexo para Cubrir la Responsabilidad Civil a Ocupantes</td>
                          <td style="width:10%; text-align:right;">Cubre</td>
                        </tr>
                        <tr>
                          <td style="width:2%;">&nbsp;</td>
                          <td style="width:88%;">Cláusula de Valor Acordado</td>
                          <td style="width:10%; text-align:right;">No Cubre</td>
                        </tr>
                    </table>
                  </td>
                </tr>
             </table>
             <br/>
             <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:0px; padding-bottom:3px;">
                <tr>
                  <td style="width:100%; text-align:justify;">
                   <b>CONDICIONES ESPECIFICAS</b><br>
                    Este documento conjuntamente con los detalles presentados por el solicitante se constituirá en la Declaración hecha por el Asegurado para la contratación de la póliza y forma parte integrante de la misma.<br>
                    La cobertura de la presente solicitud correrá una vez  la Compañía Aseguradora efectúe el análisis del Riesgo y emita la Póliza correspondiente, de acuerdo a las declaraciones de la solicitud.<br>
                    El solicitante que suscribe será responsable de la exactitud de la información en la presente Solicitud y se compromete a efectuar el pago de las primas correspondientes, una vez emitida la Póliza.
                  </td>
                </tr>
            </table>
            <br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:0px;">
                <tr>
                  <td style="width:100%; border:1px solid #333; font-weight:bold;">
                     EXCLUSIONES: 
                  </td>
                </tr>  
            </table>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:5px;">
                <tr>
                  <td style="width:100%;">
                     <b>Exclusiones Adicionales a las Condiciones Generales</b><br>
                     La cobertura de Extraterritorialidad excluye el autoreemplazo (si es que cuenta con esta cobertura) y la asistencia jurídica. 
                  </td>
                </tr>  
            </table>
            <br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:0px; padding-bottom:0px;">
                <tr>
                  <td style="width:18%; border:1px solid #333; background:#d8d8d8; font-weight:bold;">
                     LUGAR Y FECHA: 
                  </td>
                  <td style="width:82%; border-top:1px solid #333; border-right:1px solid #333;
                     border-bottom: 1px solid #333;">&nbsp;
                     <?=$row['u_departamento'].' '.$row['fecha_creacion'];?>
                  </td>
                </tr>  
            </table>
            <br><br><br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:0px; padding-bottom:3px;">
                <tr>
                  <td style="width:45%; border-bottom:1px solid #333;">&nbsp;
                     
                  </td>
                  <td style="width:55%;">&nbsp;
                     
                  </td>
                </tr>
                <tr>
                  <td style="width:45%; text-align:center;">
                     FIRMA DEL CLIENTE
                  </td>
                  <td style="width:55%;">&nbsp;
                     
                  </td>
                </tr>  
            </table>
            <br>
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 80%; font-family: Arial; 
                padding-top:0px; padding-bottom:3px;">
                <tr>
                  <td style="width:16%; font-weight:bold; text-align:left;">
                     NOMBRE DEL CLIENTE: 
                  </td>
                  <td style="width:29%; border-bottom:1px solid #333;">&nbsp;
                     <?=$cliente;?>
                  </td>
                  <td style="width:55%;">&nbsp;</td>
                </tr>
                <tr><td colspan="3" style="width:100%;">&nbsp;</td></tr>
                <tr>
                  <td style="width:16%; font-weight:bold; text-align:left;">
                     No. CI: 
                  </td>
                  <td style="width:29%; border-bottom:1px solid #333;">&nbsp;
                     <?=$nit_ci;?>
                  </td>
                  <td style="width:55%;">&nbsp;</td>
                </tr>  
            </table>
            <div style="font-size: 70%; text-align:center; margin-top:120px;">  
                • Av. Arce Nº 2631, Edificio Multicine Piso 14 • Teléfono: (591-2) 217 7000 • Fax: (591-2) 214 1928 • La Paz – Bolivia.<br> 
• Autorizado por Resolución Administrativa Nº 158 del 7 de julio de 1999 de la Superintendencia de Pensiones Valores y Seguros.   
            </div>       
         </div>
      
<?php
         if($num_titulares <> $j)
		   echo "<page><div style='page-break-before: always;'>&nbsp;</div></page>";
	  $j++;
   }
?>      
         
      </div>
  </div>     
<?php
	$html = ob_get_clean();
	return $html;
}
?>