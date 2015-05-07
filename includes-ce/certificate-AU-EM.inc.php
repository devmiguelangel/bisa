<?php
function au_em_certificate($link, $row, $rsDt, $url, $implant, $fac, $reason = '', $type) {
		
	ob_start();
?>
  <div id="container-c" style="width: 785px; height: auto; 
    border: 0px solid #0081C2; padding: 5px;">
	  <div id="main-c" style="width: 775px; font-weight: normal; font-size: 12px; 
      font-family: Arial, Helvetica, sans-serif; color: #000000;">
<?php
     $j = 0;
     $num_titulares=$rsDt->num_rows;
			
     while($rowDt = $rsDt->fetch_array(MYSQLI_ASSOC)){
		 if($row['tipo_cliente']=='Juridico'){
			 $cliente_nombre = $row['cl_razon_social'];
			 $cliente_nitci = $row['ci'];
			 $cliente_direccion = $row['direccion_laboral'];
			 $cliente_fono = $row['telefono_oficina'];
		 }elseif($row['tipo_cliente']=='Natural'){
			 $cliente_nombre = $row['nombre'].' '.$row['paterno'].' '.$row['materno'];
			 $cliente_nitci = $row['ci'].$row['complemento'].' '.$row['extension'];
			 $cliente_direccion = $row['direccion'].' '.$row['no_domicilio'];
			 $cliente_fono = $row['telefono_domicilio'].' '.$row['telefono_celular'];
		 }
		 $j += 1;
		 if($row['no_copia']>0){
			 if($row['no_copia']>1) $text='COPIA'; else $text='ORIGINAL';
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
                            SEGUROS DE AUTOMOTORES INDIVIDUAL<br>
                            CONDICIONES PARTICULARES
                          </td>
                        </tr>
                        <tr>
                          <td style="width:100%; font-size:60%; text-align:center; font-weight:bold;">
                            CODIGO SPVS No.: 109-910502-2007 12 311<br>
                            R.A. 014/08
                          </td>
                        </tr>
                     </table>
                  </td>
                  <td style="width:20%; text-align:right;">
                     <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" height="60"/>
                  </td>
              </tr>
            </table>
            <div style="text-align:right; font-size:64%;"><?=$text;?></div>          
        </div>
        <div style="width: 775px; border: 0px solid #FFFF00;">
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 70%; font-family: Arial;">
                <tr> 
                  <td style="width:100%; border-bottom: 1px solid #333;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                          <td style="width:15%; text-align:left;">
                             Póliza No.: 
                          </td>
                          <td style="width:35%;">&nbsp;
                              AU-<?=$row['no_emision'];?>
                          </td>
                          <td style="width:15%; text-align:left;">
                             Nota de Cobranza.:   
                          </td>
                          <td style="width:35%;">&nbsp;
                              AU-<?=$row['no_emision'];?> 
                          </td>
                        </tr>
                        <tr>
                          <td style="width:15%; text-align:left;">
                             Lugar y Fecha: 
                          </td>
                          <td style="width:85%;" colspan="3">&nbsp;
                              <?=strtoupper($row['u_departamento']);?>, <?=strtoupper(get_date_format_au($row['fecha_emision']));?>
                          </td>
                        </tr>
                     </table>
                  </td>      
                </tr>
                <tr>
                  <td style="width:100%; border-bottom: 1px solid #333;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                          <td style="width:100%; text-align:left; font-weight:bold;" colspan="2">
                            Datos del Cliente
                          </td>
                        </tr>
                        <tr>
                          <td style="width:15%; text-align:left;">Pagador: </td>
                          <td style="width:85%;">&nbsp;
                               
                          </td>
                        </tr>
                        <tr>
                          <td style="width:100%; text-align:left;" colspan="2">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                <td style="width: 15%;">Asegurado: </td>
                                <td style="width: 45%;">&nbsp;<?=$cliente_nombre;?></td>
                                <td style="width: 15%;">Email: </td>
                                <td style="width: 25%;">&nbsp;<?=$row['email'];?></td>
                               </tr>
                             </table> 
                          </td>
                        </tr>
                        <tr>
                          <td style="width:15%; text-align:left;">Dirección: </td>
                          <td style="width:85%;">&nbsp;
                            <?=$cliente_direccion;?>  
                          </td>
                        </tr>
                        <tr>
                          <td style="width:100%; text-align:left;" colspan="2">
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                               <tr>
                                <td style="width: 15%;">NIT/CI: </td>
                                <td style="width: 45%;">&nbsp;<?=$cliente_nitci;?></td>
                                <td style="width: 15%;">Teléfono: </td>
                                <td style="width: 25%;">&nbsp;<?=$cliente_fono;?></td>
                               </tr>
                             </table> 
                          </td>
                        </tr>
                     </table>                                  
                  </td>
                </tr>   
                <tr>
                  <td style="width:100%; border-bottom: 1px solid #333;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                        <tr>
                         <td colspan="6" style="width:100%; text-align:left; font-weight:bold;">
                            Datos del Vehículo
                         </td>
                        </tr>
                        <tr>
                          <td style="width:19%; text-align:left; font-weight:bold;">Clase: </td>
                          <td style="width:25%;">&nbsp;
                              
                          </td>
                          <td style="width:12%; text-align:left; font-weight:bold;">Marca: </td>
                          <td style="width:15%;">&nbsp;
                              <?=$rowDt['marca'];?>
                          </td>
                          <td style="width:13%; text-align:left; font-weight:bold;">Tipo: </td>
                          <td style="width:16%;">&nbsp;
                              <?=$rowDt['tipo_vechiculo'];?> 
                          </td>   
                        </tr>
                        <tr>
                          <td style="width:19%; text-align:left; font-weight:bold;">Color: </td>
                          <td style="width:25%;">&nbsp;
                             <?=$rowDt['color'];?> 
                          </td>
                          <td style="width:12%; text-align:left; font-weight:bold;">Modelo: </td>
                          <td style="width:15%;">&nbsp;
                             <?=$rowDt['modelo']?> 
                          </td>
                          <td style="width:13%; text-align:left; font-weight:bold;">No. Chasis: </td>
                          <td style="width:16%;">&nbsp;
                             <?=$rowDt['chasis']?>  
                          </td>   
                        </tr>
                        <tr>
                          <td style="width:19%; text-align:left; font-weight:bold;">No. Motor: </td>
                          <td style="width:25%;">&nbsp;
                              <?=$rowDt['motor'];?>
                          </td>
                          <td style="width:12%; text-align:left; font-weight:bold;">Placa: </td>
                          <td style="width:15%;">&nbsp;
                              <?=$rowDt['placa']?>
                          </td>
                          <td style="width:13%; text-align:left;">&nbsp;</td>
                          <td style="width:16%;">&nbsp;
                               
                          </td>   
                        </tr>
                        <tr>
                          <td style="width:19%; text-align:left; font-weight:bold;">Caracteristicas especiales: </td>
                          <td style="width:25%;">&nbsp;
                              
                          </td>
                          <td style="width:12%; text-align:left; font-weight:bold;">Cap/Ton: </td>
                          <td style="width:15%;">&nbsp;
                             <?=$rowDt['cap_ton'];?> 
                          </td>
                          <td style="width:13%; text-align:left; font-weight:bold;">Uso:</td>
                          <td style="width:16%;">
                              PARTICULAR 
                          </td>   
                        </tr>
                     </table> 
                  </td>              
                </tr>
                <tr>
                   <td style="width:100%; font-weight:bold; text-align:left; border-bottom: 1px solid #333;">
                     Valores Asegurados y Número de Pasajeros
                   </td>
                </tr>
                <tr>
                  <td style="width:100%; border-bottom: 1px solid #333;">
                      <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                         <tr>
                           <td style="width:19%; text-align:left; font-weight:bold;">
                             Responsabilidad Civil Extracontractual: 
                           </td>
                           <td style="width:12%; text-align:right;">
                             $US           
                           </td>
                           <td style="width:12%; text-align:right;">20.000,00</td>
                           <td style="width:8%;">&nbsp;
                             
                           </td>
                           <td style="width:24%; text-align:left; font-weight:bold;">
                             No de Pasajeros: 
                           </td>
                           <td style="width:10%;">&nbsp;
                             
                           </td>
                           <td style="width:15%;">&nbsp;
                             
                           </td>
                         </tr>
                         <tr>
                           <td style="width:19%; text-align:left; font-weight:bold;">
                            Responsabilidad Civil Consecuencial: 
                           </td>
                           <td style="width:12%; text-align:right;">
                             $US           
                           </td>
                           <td style="width:12%; text-align:right;">3.000,00</td>
                           <td style="width:8%;">&nbsp;
                             
                           </td>
                           <td style="width:24%; text-align:left; font-weight:bold;">
                             Muerte Accidental:        
                           </td>
                           <td style="width:10%; text-align:right;">
                             $US
                           </td>
                           <td style="width:15%; text-align:center;">
                             5.000,00
                           </td>
                         </tr>
                         <tr>
                           <td style="width:19%; text-align:left; font-weight:bold;">
                            Casco 
                           </td>
                           <td style="width:12%; text-align:right;">
                             $US           
                           </td>
                           <td style="width:12%; text-align:right;">3.000,00</td>
                           <td style="width:8%;">&nbsp;
                             
                           </td>
                           <td style="width:24%; text-align:left; font-weight:bold;">
                             Invalidez Permanente (Total o Parcial):        
                           </td>
                           <td style="width:10%; text-align:right;">
                             $US
                           </td>
                           <td style="width:15%; text-align:center;">
                             5.000,00
                           </td>
                         </tr>
                         <tr>
                           <td style="width:19%; text-align:left; font-weight:bold;">
                            Accesorios 
                           </td>
                           <td style="width:12%; text-align:right;">
                                       
                           </td>
                           <td style="width:12%; text-align:right;">No Incluye</td>
                           <td style="width:8%;">&nbsp;
                             
                           </td>
                           <td style="width:24%; text-align:left; font-weight:bold;">
                             Gastos Médicos:        
                           </td>
                           <td style="width:10%; text-align:right;">
                             $US
                           </td>
                           <td style="width:15%; text-align:center;">
                             1.000,00
                           </td>
                         </tr>
                         <tr>
                           <td style="width:19%; text-align:left; font-weight:bold;">
                            Extraterritorialidad (días)
                           </td>
                           <td style="width:12%; text-align:right;">
                                       
                           </td>
                           <td style="width:12%; text-align:right;">365</td>
                           <td style="width:8%;">&nbsp;
                             
                           </td>
                           <td style="width:24%; text-align:left; font-weight:bold;">
                                   
                           </td>
                           <td style="width:10%; text-align:right;">
                             
                           </td>
                           <td style="width:15%; text-align:center;">
                             
                           </td>
                         </tr>
                      </table> 
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; border-bottom: 1px solid #333;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:80%;">
                         <tr><td style="width:100%; font-weight:bold; text-align:left;" colspan="2">
                           COBERTURAS
                          </td></tr>
                          <tr>
                            <td style="width:50%; font-size:100%; text-align: justify; padding-right:5px; 
                              border:0px solid #333;" valign="top">
                              <b>Coberturas</b><br>
                              <b>Item 1</b>
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:86%;">RESPONSABILIDAD CIVIL EXTRACONTRACTUAL</td>
                                    <td style="width:12%; text-align:right;">Cubre</td>
                                  </tr>
                              </table> 
                              <b>Item 2</b>
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:86%;">RESPONSABILIDAD CIVIL CONSECUENCIAL</td>
                                    <td style="width:12%; text-align:right;">Cubre</td>
                                  </tr>
                              </table>
                              <b>Item 3</b>
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">PERDIDA TOTAL POR ROBO AL 100%</td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">PERDIDA TOTAL POR ACCIDENTE AL 100%</td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">DAÑOS PROPIOS C/FRANQUICIA DEDUCIBLE DE $us. 50.- HASTA $us. 50.000 DE VALOR CASCO Y $us. 200 CON VALOR CASCO MAYOR A $us. 50.000.-</td>
                                    <td style="width:13%; text-align:right;" valign="top">cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">ROBO PARCIAL AL 80%</td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;"> CONMOCION CIVIL, HUELGAS, DAÑO MALICIOSO,   SABOTAJE, VANDALISMO Y TERRORISMO C/ FRANQUICIA DEDUCIBLE DE $us. 50.- HASTA $us. 50.000 DE VALOR CASCO Y $us. 200 CON VALOR CASCO MAYOR A $us. 50.000.-</td>
                                    <td style="width:13%; text-align:right;" valign="top">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">ANEXO PARA ACCESORIOS DE VEHICULOS</td>
                                    <td style="width:13%; text-align:right;">A solicitud</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">AIRBAGS</td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">CLAUSULA DE AUTOREEMPLAZO (EXCLUYE MOTOCICLETAS)</td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                              </table>
                              <b>Item 4</b>
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">MUERTE ACCIDENTAL</td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">INVALIDEZ PERMANENTE (TOTAL Y PARCIAL) </td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">GASTOS MEDICOS </td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                              </table>      
                            </td>
                            <td style="width:50%; font-size:100%; text-align: justify; padding-left:5px; 
                            border:0px solid #333;" valign="top">
                              <b>Coberturas Adicionales</b>
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">ANEXO DE ELEGIBILIDAD DE TALLERES</td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">ANEXO DE DAÑOS A CAUSA DE RIESGOS DE LA NATURALEZA C/ FRANQUICIA DEDUCIBLE DE $us. 50.- HASTA $us. 50.000 DE VALOR CASCO Y $us. 200 CON VALOR CASCO MAYOR A $us. 50.000.-</td>
                                    <td style="width:13%; text-align:right;" valign="top">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">CLAUSULA DE CIRCULACION EN VIAS NO AUTORIZADAS PARA EL TRANSITO VEHICULAR</td>
                                    <td style="width:13%; text-align:right;" valign="top">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">CLAUSULA DE AMPLIACION DE AVISO DE SINIESTRO A 10 DIAS</td>
                                    <td style="width:13%; text-align:right;" valign="top">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">ANEXO DE EXTRATERRITORIALIDAD (365 DIAS DEL AÑO)</td>
                                    <td style="width:13%; text-align:right;" valign="top">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">ASISTENCIA AL VEHICULO</td>
                                    <td style="width:13%; text-align:right;" valign="top">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">CLAUSULA DE RESCISION DE CONTRATO A PRORRATA</td>
                                    <td style="width:13%; text-align:right;" valign="top">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">ANEXO DE BENEFICIO DE ASISTENCIA JURIDICA</td>
                                    <td style="width:13%; text-align:right;" valign="top">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">SERVICIO DE ASISTENCIA VIAL REGIONAL</td>
                                    <td style="width:13%; text-align:right;" valign="top">Cubre</td>
                                  </tr>
                              </table> 
                              <b>Cláusulas Adicionales</b>
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;">
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">CLAUSULA DE REHABILITACION AUTOMATICA DE LA SUMA ASEGURADA</td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">ANEXO PARA RESTRINGIR EL REQUISITO DE PRESENTACION DE COPIA LEGALIZADA</td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">CLAUSULA DE ADELANTO DEL 50% EN CASO DE SINIESTRO</td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">CLAUSULA DE ELEGIBILIDAD DE AJUSTADORES</td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">ANEXO PARA ROBO DE LLANTAS, EQUIPOS DE MUSICA Y OTRAS PIEZAS</td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">ANEXO PARA VEHICULOS CON ANTIGÜEDAD MAYOR A 15 AÑOS Y PARA   VEHICULOS TRANSFORMADOS</td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                                  <tr>
                                    <td style="width:2%;">-</td>
                                    <td style="width:85%;">CLAUSULA DE COBERTURA PARA FLETE AEREO (HASTA $us. 500.-)</td>
                                    <td style="width:13%; text-align:right;">Cubre</td>
                                  </tr>
                              </table> 
                            </td>
                          </tr>
                     </table>
                  </td>     
                </tr> 
            </table>
            
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 70%; font-family: Arial;">
                <tr>
                  <td style="width:100%; text-align:left; font-weight:bold;">
                    Primas
                  </td>
                </tr>
                <tr> 
                  <td style="width:100%; border-bottom: 1px solid #333;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                          <td style="width:13%; text-align:left;">Al contacto: </td>
                          <td style="width:13%; font-weight:bold; text-align:left;">
                             $US
                          </td>
                          <td style="width:15%;">&nbsp;
                             
                          </td>
                          <td style="width:18%;">&nbsp;</td>
                          <td style="width:13%; text-align:left;">
                             Al Crédito:
                          </td>
                          <td style="width:13%; text-align:left; font-weight:bold;">
                             $US
                          </td>
                          <td style="width:15%;">&nbsp;
                             
                          </td>
                        </tr>
                     </table>
                  </td>      
                </tr>
                <tr>
                  <td style="width:100%; text-align:left; font-weight:bold;">
                      Forma de Pago                                 
                  </td>
                </tr>   
                <tr>
                  <td style="width:100%; border-bottom: 1px solid #333;">
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                          <td style="width:13%; text-align:left;">Al contacto: </td>
                          <td style="width:15%; font-weight:bold; text-align:left;">
                             Cuota Mensual
                          </td>
                          <td style="width:12%; text-align:left; font-weight:bold;">
                             $US
                          </td>
                          <td style="width:15%;">&nbsp;</td>
                          <td style="width:45%;">&nbsp;
                            
                          </td>
                        </tr>
                     </table> 
                  </td>              
                </tr>
                <tr>
                  <td style="width:100%; text-align:left; font-weight:bold;">
                    Vigencia:
                  </td>
                </tr>
                <tr>
                  <td style="width:100%; text-align:left; border-bottom: 1px solid #333;">
                     <?=$row['plazo_dias'];?>, A PARTIR DE LAS 12:01 P.M. HORAS DEL DIA <?=strtoupper(get_date_format_au($row['fecha_iniv']));?>, HASTA LA MISMA HORA DEL <?=strtoupper(get_date_format_au($row['fecha_finv']));?>. 
                  </td> 
                </tr>
                <tr>
                  <td style="width:100%; border-bottom: 1px solid #333;">
                     BENEFICIOS GRATUITOS:
                     <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:70%;">
                         <tr>
                          <td style="width:2%;">&bull;</td>
                          <td style="width:98%;">
                             LOS ACCESORIOS INSTALADOS AL MOMENTO DE ASEGURAR EL VEHICULO QUEDAN AUTOMATICAMENTE CUBIERTOS SIN COSTO ADICIONAL, HASTA LOS LIMITES ESTABLECIDOS EN EL ANEXO PARA ROBO DE LLANTAS, EQUIPOS DE MUSICA Y OTRAS PIEZAS. EN CASO QUE EL ACCESORIO SUPERE LOS LIMITES ESTABLECIDOS EL ASEGURADO PUEDE INCLUIRLO MEDIANTE ANEXO Y PAGO DE PRIMA ADICIONAL.
                          </td> 
                         </tr>
                         <tr>
                          <td style="width:2%;">&bull;</td>
                          <td style="width:98%;">
                             LOS ACCESORIOS QUE SE COLOQUEN CON POSTERIORIDAD DEBEN INCLUIRSE MEDIANTE ANEXO Y PAGO DE PRIMA ADICIONAL.
                          </td>
                         </tr>
                         <tr>
                          <td style="width:2%;">&bull;</td>
                          <td style="width:98%;">
                              EN CASO DE SINIESTRO BISA SEGUROS Y REASEGUROS LE PROPORCIONA UN SERVICIO DE ASISTENCIA JURIDICA SOLO EN TERRITORIO BOLIVIANO SEGUN LO ESTABLECISO EN EL ANEXO CORRESPONDIENTE.
                          </td>
                         </tr>
                         <tr>
                          <td style="width:2%;">&bull;</td>
                          <td style="width:98%;">
                               EN CASO DE ANULACION DE LA POLIZA, LA PRIMA SERA DEVUELTA A PRORRATA.(DESCONTANDO IMPUESTOS DE LEY).
                          </td>
                         </tr>
                         <tr>
                          <td style="width:2%;">&bull;</td>
                          <td style="width:98%;">
                               BISA SEGUROS Y REASEGUROS S.A., LE OTORGA ASISTENCIA VIAL REGIONAL A PARTIR DEL INGRESO DEL VEHÍCULO ASEGURADO A ARGENTINA, PERÚ, CHILE, PARAGUAY, URUGUAY Y BRASIL, LAS 24 HORAS DEL DIA Y LOS 365 DIAS DEL AÑO, CON SERVICIOS Y BENEFICIOS ESTABLECIDOS EN EL ANEXO DE SERVICIO DE ASISTENCIA VIAL REGIONAL.   EL PROVEEDOR DEL SERVICIO DE ASISTENCIA VIAL REGIONAL, ES RESPONSABLE DE LOS SERVICIOS PRESTADOS A LOS ASEGURADOS.
                          </td>
                         </tr> 
                      </table>
                  </td>     
                </tr> 
            </table> 
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial;">
                <tr>
                  <td style="width:100%; border-bottom: 1px solid #333;">
                    NOTAS ESPECIALES:
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:65%;">
                       <tr>
                        <td style="width:2%;">&bull;</td>
                        <td style="width:98%;">
                           EL ASEGURADO AUTORIZA A LA COMPAÑÍA DE SEGUROS A ENVIAR EL REPORTE A LA CENTRAL DE RIESGOS DEL MERCADO DE SEGUROS ACORDE A LAS NORMATIVAS REGLAMENTARIAS DE LA AUTORIDAD DE FISCALIZACION Y CONTROL DE PENSIONES Y SEGUROS - APS
                        </td> 
                       </tr>
                       <tr>
                        <td style="width:2%;">&bull;</td>
                        <td style="width:98%;">
                            LA COBERTURA DE ROBO PARCIAL AL 80% SE LIMITA A CUBRIR EL ROBO DE UNA LLANTA CON ARO AL AÑO, HASTA $us.700.- INCLUIDA LA LA LLANTA DE AUXILIO Y SUS ACCESORIOS. UN EQUIPO DE MUSICA Y ACCESORIOS HASTA $us. 350.- UNA VEZ AL AÑO, SE EXCLUYE EL ROBO DE RADIOS O EQUIPOS DE COMUNICACION, EQUIPOS SIMILARES Y SUS ACCESORIOS. <b>PARA LA CIUDAD DE SANTA CRUZ SE EXCLUYE EL ROBO DE LA LLANTA DE AUXILIO Y SUS ACCESORIOS.</b> 
                        </td>
                       </tr>
                       <tr>
                        <td style="width:2%;">&bull;</td>
                        <td style="width:98%;">
                            COBERTURA DE EXTRATERRITORIALIDAD: LA COMPAÑÍA LE OTORGA HASTA 60 DIAS ACUMULABLES CUBRIENDO EL VEHICULO ASEGURADO MIENTRAS SE ENCUENTRE FUERA DEL TERRITORIO DE BOLIVIA. EN CASO DE PERDIDA TOTAL POR ROBO LA COBERTURA QUEDA LIMITADA FUERA DEL PAIS AL 80%. LA EXTENSION DE COBERTURA TERRITORIAL COMPRENDE, DENTRO DE LOS ALCANCES Y CONDICIONES DE LA POLIZA TODAS LAS COBERTURAS SOLICITADAS Y AMPARADAS EN EL PRESENTE CONTRATO DE SEGURO Y HASTA LOS LIMITES SEÑALADOS. ESTA EXTENSION DE COBERTURA SE APLICA PARA LOS SIGUIENTES PAISES: ARGENTINA, BRASIL, CHILE, ECUADOR, PARAGUAY, PERU Y URUGUAY. PARA LA PROCEDENCIA DE LA PRESENTE COBERTURA SE DEBE DAR AVISO PREVIO A LA COMPAÑIA PARA LA EMISION DEL ANEXO CORRESPONDIENTE SIN COSTO PARA EL ASEGURADO. (ESTE ANEXO CUBRE LAS MISMAS CONDICIONES QUE LA POLIZA, A EXCEPCION DE LA ASISTENCIA AL VEHICULO)
                        </td>
                       </tr>
                       <tr>
                        <td style="width:2%;">&bull;</td>
                        <td style="width:98%;">
                            DESGASTE DE LLANTAS, ES RESPONSABILIDAD DEL ASEGURADO EL CORRECTO REEMPLAZO DE LAS LLANTAS  DEL VEHÍCULO ASEGURADO, YA QUE EN CASO DE  PRESENTARSE UN SINIESTRO, Y SE DETERMINE QUE EL  MAL ESTADO DE LAS LLANTAS (DESGASTE SUPERIOR AL TIEMPO DE VIDA ÚTIL DE LAS MISMAS) PROPICIÓ EL SINIESTRO, ESTE NO ESTARÁ CUBIERTO POR SER CONSIDERADO COMO UNA SITUACIÓN DE AGRAVACION DE RIESGO.
                        </td>
                       </tr>
                       <tr>
                        <td style="width:2%;">&bull;</td>
                        <td style="width:98%;">
                             EN CASOS DE ACCIDENTES DE TRANSITO EN LA CIUDAD DE SANTA CRUZ EL SISTEMA DE SEGURIDAD PASIVA (BOLSAS DE AIRE MAS ACCESORIOS) SE CUBRE HASTA $US.1.500.
                        </td>
                       </tr>
                       <tr>
                        <td style="width:2%;">&bull;</td>
                        <td style="width:98%;">
                             BISA SEGUROS Y REASEGUROS S.A., DENTRO DE TERRITORIO NACIONAL LE OTORGA ASISTENCIA AL VEHICULO  Y A LOS OCUPANTES DEL MISMO EN CASO DE MERGENCIA, LAS 24 HORAS DEL DIA Y LOS 365 DIAS DEL AÑO, DE ACUERDO A LOS SERVICIOS, CONDICIONES Y LÍMITES DESCRITOS EN EL ANEXO RESPECTIVO.
                        </td>
                       </tr>
                       <tr>
                        <td style="width:2%;">&bull;</td>
                        <td style="width:98%;">
                             AUTOREEMPLAZO SUJETO A TERMINOS Y CONDICIONES DE CLAUSULA ADJUNTA, EXCEPTO MOTOCICLETAS Y CAMIONES.
                        </td>
                       </tr>
                       <tr>
                        <td style="width:2%;">&bull;</td>
                        <td style="width:98%;">
                             LA COBERTURA DE ROBO PARCIAL AL 80% PARA MOTOCICLETAS QUEDA LIMITADA A 1 EVENTO DURANTE LA VIGENCIA.
                        </td>
                       </tr>
                       <tr>
                        <td style="width:2%;">&bull;</td>
                        <td style="width:98%;">
                             LA COBERTURA DE PERDIDA TOTAL POR ROBO PARA MOTOCICLETAS QUEDA LIMITADA AL 80%.
                        </td>
                       </tr>  
                   </table>
                  </td>
                </tr>    
            </table>     
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 75%; font-family: Arial;">
                <tr>
                  <td style="width:100%; border-bottom: 0px solid #333;">
                    OBSERVACIONES:<br>
                    El asegurado acepta tener conocimiento de todos los Condicionados, Claúsulas y Anexos de la presente póliza.
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:90%;">
                       <tr>
                        <td style="width:100%; text-align:left;">
                         Las presentes Condiciones Particulares establecen las coberturas contratadas por el Asegurado y prevalecen sobre las Condiciones Generales preimpresas adjuntas, debiendo aplicarse las ultimas en ausencia de disposición específica de las presentes Condiciones Particulares. Por lo tanto, las coberturas mencionadas como "No Cubre", no se encuentran aseguradas en la presente póliza. 
                        </td>
                       </tr> 
                   </table>
                  </td>
                </tr>    
            </table>
            <div style="'width: 100%; height: auto; margin: 0 0 5px 0;">
<?php
             $queryVar = 'set @anulado = "Polizas Anuladas: ";';
             if($link->query($queryVar,MYSQLI_STORE_RESULT)){
                 $canceled="select 
                                max(@anulado:=concat(@anulado, prefijo, '-', no_emision, ', ')) as cert_canceled
                            from
                                s_au_em_cabecera
                            where
                                anulado = 1
                                    and id_cotizacion = '".$row['id_cotizacion']."';";
                 if($resp = $link->query($canceled,MYSQLI_STORE_RESULT)){
                     $regis = $resp->fetch_array(MYSQLI_ASSOC);
                     echo '<span style="font-size:8px;">'.trim($regis['cert_canceled'],', ').'</span>';
                 }else{
                     echo "Error en la consulta "."\n ".$link->errno. ": " . $link->error;
                 }
             }else{
               echo "Error en la consulta "."\n ".$link->errno. ": " . $link->error;   
             }
?>
            </div>
            <div style="'width: 100%; height: auto; margin: 0 0 5px 0;">
<?php
			    if((boolean)$rowDt['facultativo']===true){
				   if((boolean)$rowDt['vh_aprobado']===true){
?>
                      <table border="0" cellpadding="1" cellspacing="0" style="width: 100%; font-size: 8px; font-weight: normal; font-family: Arial; margin: 2px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;">
                            <tr>
                                <td colspan="7" style="width:100%; text-align: center; font-weight: bold; background: #e57474; color: #FFFFFF;">Caso Facultativo</td>
                            </tr>
                            <tr>
                                
                                <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Aprobado</td>
                                <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa de Recargo</td>
                                <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Porcentaje de Recargo</td>
                                <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa Actual</td>
                                <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Tasa Final</td>
                                <td style="width:69%; text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">Observaciones</td>
                            </tr>
                            <tr>
                                
                                <td style="width:5%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=strtoupper($rowDt['vh_aprobado']);?></td>
                                <td style="width:5%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=strtoupper($rowDt['vh_tasa_recargo']);?></td>
                                <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$rowDt['vh_porcentaje_recargo'];?> %</td>
                                <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$rowDt['vh_tasa_actual'];?> %</td>
                                <td style="width:7%; text-align: center; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$rowDt['vh_tasa_final'];?> %</td>
                                <td style="width:69%; text-align: justify; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;"><?=$rowDt['motivo_facultativo'];?> |<br /><?=$rowDt['vh_observacion'];?></td>
                            </tr>
                       </table>
<?php
				   }else{	 
?> 
                      <table border="0" cellpadding="1" cellspacing="0" style="width: 100%; font-size: 9px; border-collapse: collapse; font-weight: normal; font-family: Arial; margin: 2px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;">         
                           <tr>
                            <td  style="text-align: center; font-weight: bold; background: #e57474; color: #FFFFFF;">
                              Caso Facultativo
                            </td>
                           </tr>
                           <tr>
                            <td style="text-align: center; font-weight: bold; border: 1px solid #dedede; background: #e57474;">
                              Observaciones
                            </td>
                           </tr>
                           <tr>
                            <td style="text-align: justify; background: #e78484; color: #FFFFFF; border: 1px solid #dedede;">
							  <?=$rowDt['motivo_facultativo'];?>
                            </td>
                           </tr>
                      </table>
<?php
				   }
				}
?>    
            </div>  
<?php
     if($type!=='MAIL' && (boolean)$row['emitir']===true && (boolean)$row['anulado']===false){
?>            
            <br>   
            <table 
                cellpadding="0" cellspacing="0" border="0" 
                style="width: 100%; height: auto; font-size: 65%; font-family: Arial;">
               <tr>
                <td style="width:30%;">&nbsp;</td>
                <td style="width:40%; text-align:center;">
                  BISA SEGUROS Y REASEGUROS S.A.
                </td>
                <td style="width:30%;">&nbsp;</td>
               </tr>
               <tr>
                <td style="width:30%;">&nbsp;</td>
                <td style="width:40%; border-bottom: 1px solid #333; text-align:center;">
                  <img src="<?=$url;?>img/firmas_bisa.png" height="115"/>
                </td>
                <td style="width:30%;">&nbsp;</td>
               </tr>
               <tr>
                <td style="width:30%;">&nbsp;</td>
                <td style="width:40%; text-align:center;">
                  FIRMAS AUTORIZADAS
                </td>
                <td style="width:30%;">&nbsp;</td>
               </tr>
            </table>
<?php
	 }
?>            
            
            <div style="font-size: 70%; text-align:center; margin-top:20px;">  
                • Av. Arce Nº 2631, Edificio Multicine Piso 14 • Teléfono: (591-2) 217 7000 • Fax: (591-2) 214 1928 • La Paz – Bolivia.<br> 
• Autorizado por Resolución Administrativa Nº 158 del 7 de julio de 1999 de la Superintendencia de Pensiones Valores y Seguros.     
            </div>
                	
        </div>            
<?php
        if($type!=='MAIL' && (boolean)$row['emitir']===true && (boolean)$row['anulado']===false){
?>        
            <page><div style="page-break-before: always;">&nbsp;</div></page>
            
            <div style="width: 775px; border: 0px solid #FFFF00;">
                <table 
                    cellpadding="0" cellspacing="0" border="0" 
                    style="width: 100%; height: auto; font-size: 75%; font-family: Arial;">
                    <tr>
                      <td style="width:50%; font-size:100%; text-align: justify; padding-right:5px; 
                      border:0px solid #333;" valign="top">
                        <div style="text-align: center; font-weight:bold;">
                           CLAUSULA DE AUTO REEMPLAZO<br>
                           Código ASFI: 109-910502-2007 12 311-2046<br>
                           R.A. 436/2010
                        </div>
                        <b>PÓLIZA Nro.:</b>&nbsp;<?=$row['no_emision'];?>	
                        <br><br>
                        <b>LUGAR Y FECHA:</b>&nbsp;<?=strtoupper(get_date_format_au($row['fecha_emision']));?>
                        <br><br>
                        De acuerdo a la prima adicional acordada, queda entendido y convenido mediante la presente Cláusula que en caso de siniestro y si la reparación del vehículo excede 10 días calendario, la Compañía proporcionará al Asegurado un vehículo compacto por un plazo máximo de 10 días calendario en exceso de los primeros 10 días calendario de reparación, siempre que se cumplan las siguientes condiciones:
                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;
                           padding-bottom:3px;">
                          <tr>
                            <td style="width:2%;" valign="top">&bull;</td>
                            <td style="width:98%;">Que el siniestro se encuentre cubierto por la póliza principal.</td>
                          </tr>
                          <tr>
                            <td style="width:2%;" valign="top">&bull;</td>
                            <td style="width:98%;">Que se cuente con todos los repuestos necesarios para iniciar la 
                            reparación.</td>
                          </tr>
                          <tr>
                            <td style="width:2%;" valign="top">&bull;</td>
                            <td style="width:98%;">Que no se haya concluido la reparación del vehículo asegurado.</td>
                          </tr>
                          <tr>
                            <td style="width:2%;" valign="top">&bull;</td>
                            <td style="width:98%;">El Asegurado tiene la obligación de cumplir con las obligaciones 
                            establecidas en el Contrato con la Empresa de Alquiler del vehículo otorgado. </td>
                          </tr>
                        </table>
                        Todos los demás términos y condiciones de la Póliza se mantienen sin variación alguna.
                        <table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                            padding-top:8px; padding-bottom:8px;">
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              BISA SEGUROS Y REASEGUROS S.A.
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; border-bottom: 1px solid #333;">
                              <img src="<?=$url;?>img/firmas_bisa.png" height="90"/>
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              FIRMAS AUTORIZADAS
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                        </table>
                        <div style="text-align: center; font-weight:bold;">
                           ANEXO PARA EL ROBO DE LLANTAS, PARTES, EQUIPOS DE MÚSICA Y OTRAS PIEZAS<br>
                           CÓDIGOAPS:109-910502-2007 12 311 2052<br>
                           R.A. 136/11<br>
                        </div>
                        <b>PÓLIZA Nro.:</b>&nbsp;<?=$row['no_emision'];?>	
                        <br><br>
                        <b>LUGAR Y FECHA:</b>&nbsp;<?=strtoupper(get_date_format_au($row['fecha_emision']));?>
                        <br><br>
                        Queda entendido y convenido mediante el presente Anexo, que no obstante lo estipulado en contrario
                        en la Póliza a que este Anexo se refiere, la responsabilidad de la Compañía por el robo de las 
                        piezas detalladas abajo, se limita de acuerdo a lo siguiente:
                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:100%;
                           padding-bottom:3px;">
                          <tr>
                            <td style="width:75%; font-weight:bold; text-align:center; border-left:1px solid #333;
                              border-top:1px solid #333; border-right:1px solid #333; border-bottom: 1px solid #333;">
                              Descripción de pieza cubierta:
                            </td>
                            <td style="width:25%; font-weight:bold; text-align:center;
                              border-top:1px solid #333; border-right:1px solid #333; border-bottom: 1px solid #333;">
                              Hasta un máximo como límite total anual:
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333; ">
                              Llanta con aro y/o accesorios
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom: 1px solid #333;">
                              $us. 700.00
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">
                              Llanta de auxilio con aro y/ accesorios
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;">
                              $us. 700.00
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">Equipo de música y/o sus accesorios como ser: amplificador, 
                              ecualizador, parlantes de todo tipo, CD, MP3, DVD, y otros (excluye el robo del control 
                              remoto)
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;" valign="top">
                              $us. 350.00
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">
                              Mascarilla desmontable del equipo de música
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;" valign="top">
                              Sin cobertura
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">
                              Equipo integrado de TV-pantalla-video-DVD
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;" valign="top">
                              Sin cobertura
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">
                              Sistema de navegación GPS
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;" valign="top">
                              Sin cobertura
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">
                              Juego de halógenos y/o rompenieblas (instalado como accesorio)
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;" valign="top">
                              Sin cobertura
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">
                              Deflectores de viento
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;" valign="top">
                              Sin cobertura
                            </td>
                          </tr>
                          <tr>
                            <td style="width:75%; border-left:1px solid #333; border-bottom: 1px solid #333; 
                              border-right:1px solid #333;">
                              Cola de pato
                            </td>
                            <td style="width:25%; text-align:center; border-right:1px solid #333;
                              border-bottom:1px solid #333;" valign="top">
                              Sin cobertura
                            </td>
                          </tr>
                        </table>
                        Los equipos o componentes que normalmente no forman parte de un vehículo automotor, como ser, pero
                        no limitado a: computadoras, equipos de radio comunicación, equipos científicos, equipos de 
                        servicio de cualquier naturaleza, teléfonos celulares, deberán ser expresamente declarados y 
                        valorados, sujetos al pago de una prima adicional, caso contrario, quedan expresamente excluidos 
                        de las coberturas de las pólizas.<br> 
    
                        Los equipos arriba detallados para tener cobertura, deberán encontrase sujetos o fijados al 
                        vehículo.<br>
                        Todos los demás términos y condiciones quedan sin alteración alguna.
                        <table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                            padding-top:8px; padding-bottom:8px;">
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              BISA SEGUROS Y REASEGUROS S.A.
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; border-bottom: 1px solid #333;">
                               <img src="<?=$url;?>img/firmas_bisa.png" height="90"/>
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              FIRMAS AUTORIZADAS
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                        </table>
    
                      </td>
                      <td style="width:50%; font-size:100%; text-align: justify; padding-left:5px; 
                        border:0px solid #333;" valign="top">
                        <div style="text-align: right; border:0px solid #FFFF00; margin-bottom:10px;">
                          <img src="<?=$url;?>images/<?=$row['logo_cia'];?>" height="60"/> 
                        </div>
                        <div style="text-align: center; font-weight:bold;">
                           ANEXO PARA VEHÍCULOS CON ANTIGÜEDAD MAYOR A 15 AÑOS Y PARA VEHÍCULOS TRANSFORMADOS 
                           (TRANSFORMERS)<br><br>
    
                           CÓDIGO SPVS No. 109-910502-2007 12 311 - 2015<br>
                           R.A. 014/08
                        </div>
                        <b>PÓLIZA Nro.:</b>&nbsp;<?=$row['no_emision'];?>	
                        <br><br>
                        <b>LUGAR Y FECHA:</b>&nbsp;<?=strtoupper(get_date_format_au($row['fecha_emision']));?>	
                        <br><br>
                        Se acuerda y establece mediante el presente Anexo, que en caso de siniestro que se encuentre 
                        cubierto por la póliza, laCompañía tendrá el derecho de reparar, reponer o indemnizar en dinero 
                        por los daños que se hubieran producido.
                        <br><br> 
                        Queda asimismo entendido y convenido que en caso de no encontrarse en el mercado local partes y/o 
                        piezas que fueran necesarias para reparación, la responsabilidad de obtener los repuestos será del
                        Asegurado, el cual además deberá obtener el consentimiento previo de Bisa Seguros y Reaseguros S. 
                        A. sobre el precio de los mismos.
                        <br><br> 
                        En ningún caso Bisa Seguros y Reaseguros S. A. pagará por la reparación o reposición de piezas un 
                        monto mayor al que tendría que pagar por la reparación o reposición de piezas de un vehículo 
                        similar, cuyos repuestos puedan encontrarse en el mercado.
                        <br><br>
                        La Compañía se reserva el derecho de reemplazar las piezas robadas o dañadas con piezas que no 
                        sean  originales o genuinas las mismas que podrán ser obtenidas en un mercado alternativo o 
                        seminuevas o que sean fabricadas localmente. 
                        <br><br>
                        Todos los demás términos y condiciones de la póliza quedan sin alteración alguna.
                        <table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                            padding-top:8px; padding-bottom:8px;">
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              BISA SEGUROS Y REASEGUROS S.A.
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; border-bottom: 1px solid #333;">
                              <img src="<?=$url;?>img/firmas_bisa.png" height="90"/>
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              FIRMAS AUTORIZADAS
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                        </table>
                        <div style="text-align: center; font-weight:bold;">
                           CLAUSULA DE COBERTURA PARA FLETE AÉREO
                           <br><br>
                           CÓDIGO SPVS No. 109-910502-2007 12 311 - 2017<br>
                           R.A. 014/08
                        </div>
                        <b>PÓLIZA Nro.:</b>&nbsp;<?=$row['no_emision'];?>	
                        <br><br>
                        <b>LUGAR Y FECHA:</b>&nbsp;<?=strtoupper(get_date_format_au($row['fecha_emision']));?>	
                        <br><br>
                        Queda entendido y convenido que, en adición a los términos, exclusiones, Cláusulas y Condiciones 
                        contenidos en la Póliza o a ella anexados, este seguro se extiende a cubrir los gastos 
                        adicionales por concepto de flete aéreo para la importación de partes o piezas, siempre y cuando 
                        dichos gastos se hayan generado en conexión con cualquier pérdida de o daño indemnizable a los 
                        objetos asegurados bajo esta Póliza.
                        <br><br>
                        Deducible:   20% de los gastos extras indemnizables, mínimo para cada evento.
                        <table 
                            cellpadding="0" cellspacing="0" border="0" 
                            style="width: 100%; height: auto; font-size: 100%; font-family: Arial; 
                            padding-top:8px; padding-bottom:8px;">
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              BISA SEGUROS Y REASEGUROS S.A.
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; border-bottom: 1px solid #333;">
                              <img src="<?=$url;?>img/firmas_bisa.png" height="90"/>
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                           <tr>
                            <td style="width:20%;">&nbsp;</td>
                            <td style="width:60%; text-align:center;">
                              FIRMAS AUTORIZADAS
                            </td>
                            <td style="width:20%;">&nbsp;</td>
                           </tr>
                        </table>
                      </td>
                    </tr>  
                </table>
                <div style="font-size: 70%; text-align:center; margin-top:90px;">  
                    • Av. Arce Nº 2631, Edificio Multicine Piso 14 • Teléfono: (591-2) 217 7000 • Fax: (591-2) 214 1928                • La Paz – Bolivia.<br> 
                    • Autorizado por Resolución Administrativa Nº 158 del 7 de julio de 1999 de la Superintendencia de 
                    Pensiones Valores y Seguros.     
                </div>     
            </div>
<?php
		}
	    if($num_titulares <> $j)
		   echo "<page><div style='page-break-before: always;'>&nbsp;</div></page>";
	   
	 
	 }
	if ($fac === TRUE) {
        $url .= 'index.php?ms='.md5('MS_AU').'&page='.md5('P_fac').'&ide='.base64_encode($row['id_emision']).'';
?>        
       <br/>
       <div style="width:500px; height:auto; padding:10px 15px; font-size:11px; font-weight:bold; text-align:left;">
          No. de Slip de Cotizaci&oacute;n: <?=$row['no_cotizacion'];?>
       </div><br>
       <div style="width:500px; height:auto; padding:10px 15px; border:1px solid #FF2D2D; background:#FF5E5E; color:#FFF; font-size:10px; font-weight:bold; text-align:justify;">
          Observaciones en la solicitud del seguro:<br><br><?=$reason;?>
       </div>
       <div style="width:500px; height:auto; padding:10px 15px; font-size:11px; font-weight:bold; text-align:left;">
          Para procesar la solicitud ingrese al siguiente link con sus credenciales de usuario:<br>
          <a href="<?=$url;?>" target="_blank">Procesar caso facultativo</a>
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

function get_date_format_au($fecha){
	$date = date_create($fecha);
	
	$day = date_format($date, 'd');
	$month = date_format($date, 'F');
	$year = date_format($date, 'Y');
	
	return $day.' de '.get_month_espanol($month).' de '.$year;
}

function get_month_espanol($month){
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